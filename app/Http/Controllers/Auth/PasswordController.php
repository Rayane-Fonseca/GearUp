<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                // Valida diretamente contra o hash do banco de dados
                function ($attribute, $value, $fail) use ($request) {
                    if (Hash::check($value, $request->user()->password)) {
                        $fail('A nova senha não pode ser igual à senha atual.');
                    }
                },
                Password::min(8)->numbers()->symbols(),
                'confirmed',
            ],
        ], [
            'current_password.required'         => 'Informe a sua senha atual para continuar.',
            'current_password.current_password' => 'A senha atual informada está incorreta.',
            'password.required'                 => 'Informe a nova senha.',
            'password.min'                      => 'A nova senha precisa ter no mínimo 8 caracteres.',
            'password.numbers'                  => 'A nova senha precisa conter pelo menos um número.',
            'password.symbols'                  => 'A nova senha precisa conter pelo menos um caractere especial (!@#$...).',
            'password.confirmed'                => 'A confirmação de senha não coincide com a nova senha.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}