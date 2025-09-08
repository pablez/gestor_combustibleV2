# Flujos Detallados del Sistema - Gobernación de Cochabamba

## 1. Flujo Detallado de Registro de Usuario con WhatsApp

### 1.1 Generación de Código de Registro

**Actor Principal**: Admin_General, Admin_Secretaria, o Supervisor

**Precondiciones**:
- Usuario debe estar autenticado con rol apropiado
- Sistema debe tener acceso a API de WhatsApp

**Flujo Principal**:

1. **Acceso al Módulo de Códigos**
   ```
   Dashboard → Gestión de Usuarios → Códigos de Registro → Nuevo Código
   ```

2. **Formulario de Generación**
   ```
   Campos requeridos:
   - Vigencia del código (fecha/hora)
   - Observaciones (opcional)
   - Notificación WhatsApp (checkbox habilitado por defecto)
   ```

3. **Generación Automática**
   ```
   Sistema genera código único formato: GC[AAAA][NNNNNN][AA]
   Ejemplo: GC2025123456AB
   
   Donde:
   - GC: Prefijo fijo (Gobernación Cochabamba)
   - AAAA: Año actual
   - NNNNNN: Número aleatorio de 6 dígitos
   - AA: Dos letras aleatorias
   ```

4. **Almacenamiento en Base de Datos**
   ```sql
   INSERT INTO codigos_registro (
     codigo, 
     vigente_hasta, 
     id_usuario_creador,
     created_at
   ) VALUES (
     'GC2025123456AB',
     '2025-09-12 23:59:59',
     [id_usuario_logueado],
     NOW()
   );
   ```

5. **Presentación al Usuario**
   ```
   Modal con:
   - Código generado (destacado)
   - Fecha de vigencia
   - Link para compartir
   - Botón "Copiar código"
   - Botón "Enviar por WhatsApp"
   ```

### 1.2 Pre-registro del Usuario Nuevo

**Actor Principal**: Usuario nuevo (ciudadano/funcionario)

**Punto de Entrada**: URL pública de registro

**Flujo Principal**:

1. **Acceso a Formulario de Registro**
   ```
   URL: https://combustible.gobernacion.gob.bo/registro
   ```

2. **Formulario de Pre-registro**
   ```html
   <form id="pre-registro">
     <h2>Registro de Usuario - Gobernación de Cochabamba</h2>
     
     <!-- Datos Personales -->
     <fieldset>
       <legend>Datos Personales</legend>
       <input type="text" name="nombre" placeholder="Nombre completo" required>
       <input type="text" name="username" placeholder="Nombre de usuario" required>
       <input type="email" name="email" placeholder="Correo electrónico">
       <input type="tel" name="telefono" placeholder="Número de teléfono" required>
     </fieldset>
     
     <!-- Datos de Registro -->
     <fieldset>
       <legend>Código de Registro</legend>
       <input type="text" name="codigo_registro" placeholder="Código proporcionado" required>
       <small>Solicite este código a su supervisor o administrador</small>
     </fieldset>
     
     <!-- Datos Organizacionales -->
     <fieldset>
       <legend>Información Organizacional</legend>
       <select name="id_unidad_organizacional" required>
         <option value="">Seleccione su unidad organizacional</option>
         <!-- Opciones cargadas dinámicamente -->
       </select>
       <select name="rol_solicitado" required>
         <option value="">Seleccione el rol solicitado</option>
         <option value="Conductor">Conductor</option>
         <option value="Operator">Operador</option>
         <option value="Supervisor">Supervisor</option>
       </select>
     </fieldset>
     
     <!-- Observaciones -->
     <fieldset>
       <legend>Información Adicional</legend>
       <textarea name="observaciones_solicitud" placeholder="Información adicional o justificación"></textarea>
     </fieldset>
     
     <button type="submit">Enviar Solicitud de Registro</button>
   </form>
   ```

3. **Validaciones Frontend (JavaScript)**
   ```javascript
   // Validación en tiempo real del código
   document.getElementById('codigo_registro').addEventListener('blur', async function() {
     const codigo = this.value;
     if (codigo.length >= 10) {
       const response = await fetch(`/api/validar-codigo/${codigo}`);
       const result = await response.json();
       
       if (!result.valido) {
         mostrarError('Código inválido o expirado');
         this.classList.add('error');
       } else {
         this.classList.add('success');
         mostrarExito('Código válido');
       }
     }
   });
   ```

4. **Validaciones Backend (Laravel)**
   ```php
   // Validación del código de registro
   public function validarCodigo(Request $request)
   {
     $codigo = CodigoRegistro::where('codigo', $request->codigo)
       ->where('vigente_hasta', '>', now())
       ->where('usado', false)
       ->first();
       
     if (!$codigo) {
       return response()->json([
         'valido' => false,
         'mensaje' => 'Código inválido o expirado'
       ], 400);
     }
     
     return response()->json([
       'valido' => true,
       'vigente_hasta' => $codigo->vigente_hasta,
       'creador' => $codigo->usuario_creador->nombre
     ]);
   }
   ```

### 1.3 Procesamiento de la Solicitud

**Flujo de Validación y Almacenamiento**:

1. **Validación Completa**
   ```php
   public function procesarSolicitud(Request $request)
   {
     // Validaciones
     $validated = $request->validate([
       'nombre' => 'required|string|max:100',
       'username' => 'required|string|max:50|unique:usuarios,username',
       'email' => 'nullable|email|unique:usuarios,email',
       'telefono' => 'required|string|max:20',
       'codigo_registro' => 'required|exists:codigos_registro,codigo',
       'id_unidad_organizacional' => 'required|exists:unidades_organizacionales,id_unidad_organizacional',
       'rol_solicitado' => 'required|in:Conductor,Operator,Supervisor'
     ]);
     
     // Verificar que el código esté vigente
     $codigo = CodigoRegistro::where('codigo', $validated['codigo_registro'])
       ->where('vigente_hasta', '>', now())
       ->where('usado', false)
       ->firstOrFail();
   }
   ```

2. **Creación de Solicitud**
   ```php
   DB::transaction(function() use ($validated, $codigo) {
     // Crear solicitud de aprobación
     $solicitud = SolicitudAprobacionUsuario::create([
       'id_creador' => $codigo->id_usuario_creador,
       'id_codigo_registro' => $codigo->id_codigo_registro,
       'username_solicitado' => $validated['username'],
       'nombre_solicitado' => $validated['nombre'],
       'email_solicitado' => $validated['email'],
       'telefono_solicitado' => $validated['telefono'],
       'tipo_solicitud' => 'nuevo_usuario',
       'estado_solicitud' => 'pendiente',
       'rol_solicitado' => $validated['rol_solicitado'],
       'observaciones_solicitud' => $validated['observaciones_solicitud'] ?? null
     ]);
     
     // Marcar código como usado
     $codigo->update([
       'usado' => true,
       'fecha_uso' => now()
     ]);
   });
   ```

### 1.4 Envío de Notificación WhatsApp

**Implementación del Servicio WhatsApp**:

1. **Servicio de WhatsApp**
   ```php
   // app/Services/WhatsAppService.php
   class WhatsAppService
   {
     protected $apiUrl;
     protected $token;
     
     public function __construct()
     {
       $this->apiUrl = config('services.whatsapp.api_url');
       $this->token = config('services.whatsapp.token');
     }
     
     public function enviarNotificacionSolicitudUsuario($solicitud)
     {
       $codigo = $solicitud->codigoRegistro;
       $creador = $codigo->usuarioCreador;
       
       // Construir mensaje
       $mensaje = $this->construirMensajeSolicitud($solicitud);
       
       // Construir URL de aprobación
       $urlAprobacion = route('aprobar-usuario', [
         'token' => $this->generarTokenSeguro($solicitud->id),
         'solicitud' => $solicitud->id
       ]);
       
       return $this->enviarMensaje($creador->telefono, $mensaje, $urlAprobacion);
     }
     
     private function construirMensajeSolicitud($solicitud)
     {
       return "🔔 *NUEVA SOLICITUD DE USUARIO*\n\n" .
              "📋 *Datos del Solicitante:*\n" .
              "👤 Nombre: {$solicitud->nombre_solicitado}\n" .
              "🆔 Usuario: {$solicitud->username_solicitado}\n" .
              "📧 Email: {$solicitud->email_solicitado}\n" .
              "📱 Teléfono: {$solicitud->telefono_solicitado}\n" .
              "🏢 Unidad: {$solicitud->unidadOrganizacional->nombre_unidad}\n" .
              "👔 Rol solicitado: {$solicitud->rol_solicitado}\n\n" .
              "🔑 Código usado: {$solicitud->codigoRegistro->codigo}\n" .
              "📅 Fecha solicitud: " . $solicitud->created_at->format('d/m/Y H:i') . "\n\n" .
              "⚡ *Acción requerida:* Aprobar o rechazar solicitud\n\n" .
              "🔗 Para procesar la solicitud, haga clic en el enlace que llegará en el siguiente mensaje.";
     }
   }
   ```

2. **Envío del Mensaje**
   ```php
   private function enviarMensaje($telefono, $mensaje, $urlAprobacion = null)
   {
     try {
       // Primer mensaje con información
       $response1 = Http::withToken($this->token)
         ->post($this->apiUrl . '/messages', [
           'to' => $this->formatearTelefono($telefono),
           'type' => 'text',
           'text' => ['body' => $mensaje]
         ]);
       
       // Segundo mensaje con enlace (si existe)
       if ($urlAprobacion) {
         $mensajeEnlace = "🔗 *ENLACE DE APROBACIÓN*\n\n" .
                         "Haga clic en el siguiente enlace para procesar la solicitud:\n\n" .
                         $urlAprobacion . "\n\n" .
                         "⚠️ Este enlace es válido por 24 horas.";
         
         Http::withToken($this->token)
           ->post($this->apiUrl . '/messages', [
             'to' => $this->formatearTelefono($telefono),
             'type' => 'text',
             'text' => ['body' => $mensajeEnlace]
           ]);
       }
       
       return $response1->successful();
       
     } catch (Exception $e) {
       Log::error('Error enviando WhatsApp: ' . $e->getMessage());
       return false;
     }
   }
   
   private function formatearTelefono($telefono)
   {
     // Formatear para Bolivia (+591)
     $telefono = preg_replace('/[^0-9]/', '', $telefono);
     
     if (strlen($telefono) === 8) {
       return '591' . $telefono; // Agregar código de país
     }
     
     return $telefono;
   }
   ```

### 1.5 Procesamiento de Aprobación vía WhatsApp

**URL de Aprobación Segura**:

1. **Generación de Token Seguro**
   ```php
   private function generarTokenSeguro($solicitudId)
   {
     $payload = [
       'solicitud_id' => $solicitudId,
       'timestamp' => time(),
       'expires_at' => time() + (24 * 60 * 60) // 24 horas
     ];
     
     return Crypt::encrypt($payload);
   }
   ```

2. **Ruta de Aprobación**
   ```php
   // routes/web.php
   Route::get('/aprobar-usuario/{token}/{solicitud}', [AprobacionController::class, 'mostrarFormulario'])
     ->name('aprobar-usuario');
   
   Route::post('/aprobar-usuario/{token}/{solicitud}', [AprobacionController::class, 'procesarAprobacion'])
     ->name('procesar-aprobacion');
   ```

3. **Controlador de Aprobación**
   ```php
   class AprobacionController extends Controller
   {
     public function mostrarFormulario($token, $solicitudId)
     {
       try {
         // Validar token
         $payload = Crypt::decrypt($token);
         
         if ($payload['expires_at'] < time()) {
           return view('errors.token-expirado');
         }
         
         if ($payload['solicitud_id'] != $solicitudId) {
           return abort(403);
         }
         
         // Obtener solicitud
         $solicitud = SolicitudAprobacionUsuario::with([
           'codigoRegistro.usuarioCreador',
           'unidadOrganizacional'
         ])->findOrFail($solicitudId);
         
         if ($solicitud->estado_solicitud !== 'pendiente') {
           return view('aprobacion.ya-procesada', compact('solicitud'));
         }
         
         return view('aprobacion.formulario', compact('solicitud', 'token'));
         
       } catch (Exception $e) {
         return view('errors.token-invalido');
       }
     }
   }
   ```

4. **Vista de Aprobación**
   ```blade
   {{-- resources/views/aprobacion/formulario.blade.php --}}
   @extends('layouts.public')
   
   @section('content')
   <div class="container mx-auto px-4 py-8">
     <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
       <div class="bg-blue-600 text-white p-6 rounded-t-lg">
         <h1 class="text-2xl font-bold">📋 Aprobación de Usuario</h1>
         <p class="mt-2">Gobernación de Cochabamba - Sistema de Combustible</p>
       </div>
       
       <div class="p-6">
         <div class="mb-6 p-4 bg-gray-50 rounded-lg">
           <h3 class="font-semibold text-lg mb-3">👤 Datos del Solicitante</h3>
           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
             <div>
               <strong>Nombre:</strong> {{ $solicitud->nombre_solicitado }}
             </div>
             <div>
               <strong>Usuario:</strong> {{ $solicitud->username_solicitado }}
             </div>
             <div>
               <strong>Email:</strong> {{ $solicitud->email_solicitado ?? 'No proporcionado' }}
             </div>
             <div>
               <strong>Teléfono:</strong> {{ $solicitud->telefono_solicitado }}
             </div>
             <div>
               <strong>Unidad:</strong> {{ $solicitud->unidadOrganizacional->nombre_unidad }}
             </div>
             <div>
               <strong>Rol solicitado:</strong> {{ $solicitud->rol_solicitado }}
             </div>
           </div>
           
           @if($solicitud->observaciones_solicitud)
           <div class="mt-4">
             <strong>Observaciones del solicitante:</strong>
             <p class="mt-2 text-gray-700">{{ $solicitud->observaciones_solicitud }}</p>
           </div>
           @endif
         </div>
         
         <form action="{{ route('procesar-aprobacion', [$token, $solicitud->id]) }}" method="POST">
           @csrf
           
           <div class="mb-4">
             <label class="block text-sm font-medium text-gray-700 mb-2">
               👔 Rol a asignar (puede modificar el rol solicitado)
             </label>
             <select name="rol_final" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
               <option value="Conductor" {{ $solicitud->rol_solicitado === 'Conductor' ? 'selected' : '' }}>
                 🚗 Conductor
               </option>
               <option value="Operator" {{ $solicitud->rol_solicitado === 'Operator' ? 'selected' : '' }}>
                 ⚙️ Operador
               </option>
               <option value="Supervisor" {{ $solicitud->rol_solicitado === 'Supervisor' ? 'selected' : '' }}>
                 👨‍💼 Supervisor
               </option>
             </select>
           </div>
           
           <div class="mb-6">
             <label class="block text-sm font-medium text-gray-700 mb-2">
               📝 Observaciones de aprobación
             </label>
             <textarea name="observaciones_aprobacion" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2" 
                       rows="3" 
                       placeholder="Observaciones sobre la aprobación (opcional)"></textarea>
           </div>
           
           <div class="flex space-x-4">
             <button type="submit" 
                     name="accion" 
                     value="aprobar" 
                     class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg">
               ✅ Aprobar Usuario
             </button>
             
             <button type="submit" 
                     name="accion" 
                     value="rechazar" 
                     class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg">
               ❌ Rechazar Solicitud
             </button>
           </div>
         </form>
       </div>
     </div>
   </div>
   @endsection
   ```

### 1.6 Procesamiento Final de la Aprobación

```php
public function procesarAprobacion(Request $request, $token, $solicitudId)
{
  // Validar token (mismo proceso que antes)
  // ...
  
  $validated = $request->validate([
    'accion' => 'required|in:aprobar,rechazar',
    'rol_final' => 'required_if:accion,aprobar|in:Conductor,Operator,Supervisor',
    'observaciones_aprobacion' => 'nullable|string|max:1000'
  ]);
  
  DB::transaction(function() use ($validated, $solicitud) {
    if ($validated['accion'] === 'aprobar') {
      // Crear usuario
      $password = Str::random(12);
      
      $usuario = Usuario::create([
        'username' => $solicitud->username_solicitado,
        'password' => Hash::make($password),
        'nombre' => $solicitud->nombre_solicitado,
        'email' => $solicitud->email_solicitado,
        'telefono' => $solicitud->telefono_solicitado,
        'rol' => $validated['rol_final'],
        'id_unidad_organizacional' => $solicitud->id_unidad_organizacional,
        'activo' => true
      ]);
      
      // Actualizar solicitud
      $solicitud->update([
        'id_usuario' => $usuario->id_usuario,
        'estado_solicitud' => 'aprobado',
        'observaciones_aprobacion' => $validated['observaciones_aprobacion'],
        'fecha_aprobacion' => now(),
        'id_usuario_aprobador' => auth()->id() ?? null
      ]);
      
      // Enviar credenciales por WhatsApp/Email
      $this->enviarCredenciales($usuario, $password);
      
    } else {
      // Rechazar solicitud
      $solicitud->update([
        'estado_solicitud' => 'rechazado',
        'observaciones_aprobacion' => $validated['observaciones_aprobacion'],
        'fecha_aprobacion' => now(),
        'id_usuario_aprobador' => auth()->id() ?? null
      ]);
    }
  });
  
  return view('aprobacion.resultado', [
    'accion' => $validated['accion'],
    'solicitud' => $solicitud
  ]);
}
```

## 2. Configuración de WhatsApp API

### 2.1 Variables de Entorno

```env
# WhatsApp Configuration
WHATSAPP_API_URL=https://api.whatsapp.com/send
WHATSAPP_API_TOKEN=tu_token_aqui
WHATSAPP_PHONE_COUNTRY_CODE=591
WHATSAPP_BUSINESS_NUMBER=59177123456
```

### 2.2 Configuración en config/services.php

```php
'whatsapp' => [
  'api_url' => env('WHATSAPP_API_URL'),
  'token' => env('WHATSAPP_API_TOKEN'),
  'country_code' => env('WHATSAPP_PHONE_COUNTRY_CODE', '591'),
  'business_number' => env('WHATSAPP_BUSINESS_NUMBER'),
],
```

## 3. Consideraciones de Seguridad

### 3.1 Validación de Tokens
- Tokens con expiración de 24 horas
- Encriptación con clave de aplicación Laravel
- Validación de timestamp en cada acceso

### 3.2 Rate Limiting
```php
// En RouteServiceProvider
RateLimiter::for('whatsapp-approval', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

### 3.3 Logs de Auditoría
Todos los accesos a URLs de aprobación quedan registrados en la tabla `registro_auditoria`.

Este flujo garantiza un proceso seguro, trazable y eficiente para el registro de usuarios en el sistema de gestión de combustible de la Gobernación de Cochabamba.
