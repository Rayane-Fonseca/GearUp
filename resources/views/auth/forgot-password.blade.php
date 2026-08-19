<x-guest-layout>
    <div class="min-h-screen w-full flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Recuperar senha</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Esqueceu sua senha? Sem problemas. Informe seu e-mail corporativo para enviarmos um link de redefinição.
                </p>
            </div>

            <!-- Status do envio do e-mail -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">E-mail corporativo</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-800">
                        Voltar ao login
                    </a>

                    <button type="submit" class="py-2.5 px-5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition shadow-md">
                        Enviar link
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>