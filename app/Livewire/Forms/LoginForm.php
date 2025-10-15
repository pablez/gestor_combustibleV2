<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        // Verificar si el usuario está activo
        $user = Auth::user();
        if (!$user->activo) {
            Auth::logout();
            
            // Verificar si tiene solicitud pendiente
            $solicitudPendiente = \App\Models\SolicitudAprobacionUsuario::where('id_usuario', $user->id)
                ->where('estado_solicitud', 'pendiente')
                ->where('tipo_solicitud', 'nuevo_usuario')
                ->exists();
            
            if ($solicitudPendiente) {
                throw ValidationException::withMessages([
                    'form.email' => 'Su cuenta está pendiente de aprobación por parte del administrador. Recibirá un correo cuando sea activada.',
                ]);
            } else {
                throw ValidationException::withMessages([
                    'form.email' => 'Su cuenta ha sido desactivada. Contacte al administrador para más información.',
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Handle form submit from the Livewire view.
     */
    public function login(): void
    {
        // Validate attributes defined with #[Validate]
        $this->validate();

        // Attempt authentication
        $this->authenticate();

        // Regenerate session to prevent fixation
        session()->regenerate();

        // Note: Redirect is handled in the Volt component, not here
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
