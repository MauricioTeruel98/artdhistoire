<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Muestra los datos del perfil
    public function show()
    {
        $user = Auth::user();
        $activeSubscriptions = $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('categories')
            ->get();

        return view('auth.profile', compact('user', 'activeSubscriptions'));
    }

    // Actualiza los datos del perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'avatar' => 'nullable|image',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->settings = $request->input('settings');

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            // Guardar el nuevo avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Contraseña actualizada correctamente.');
    }
}
