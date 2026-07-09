<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Update the profile (name, bio, avatar) of the authenticated user.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Введите имя.',
            'name.max' => 'Имя не должно превышать 255 символов.',
            'bio.max' => 'Описание не должно превышать 500 символов.',
            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Поддерживаются форматы: JPG, PNG, WEBP.',
            'avatar.max' => 'Максимальный размер файла — 2 МБ.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('status', 'Профиль обновлён ✅');
    }
}
