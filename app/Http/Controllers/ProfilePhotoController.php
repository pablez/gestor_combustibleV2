<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoController
{
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // max 5MB
        ]);

        $user = Auth::user();

        $path = $request->file('photo')->store('profile-photos', ['disk' => config('filesystems.default')]);

        // Optionally delete old photo
        if ($user->profile_photo_path) {
            Storage::disk(config('filesystems.default'))->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = $path;
        $user->save();

        return response()->json(['url' => Storage::disk(config('filesystems.default'))->url($path)]);
    }
}
