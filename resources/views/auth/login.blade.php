<x-guest-layout>
    <div class="min-h-screen w-full grid grid-cols-1 md:grid-cols-2">
        <!-- Coluna da Esquerda -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-indigo-900 to-blue-950 p-8 md:p-12 text-white flex flex-col justify-between">
            
            <!-- Logo e Nome -->
            <div class="relative z-10 flex items-center gap-2">
                <div class="w-8 h-8 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow p-1">
                    <img src="{{ asset('images/Logo.png') }}" alt="GearUp Logo" class="w-full h-full object-contain">
                </div>
                <span class="font-bold text-2xl tracking-wide">GearUp</span>
            </div>

            <!-- Textos Principais -->
            <div class="relative z-10 my-auto py-12 space-y-6">
                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">Plataforma corporativa de aprendizagem para equipes de TI</h1>
                <p class="text-sm text-blue-200">Capacite sua equipe com trilhas de aprendizagem e cursos práticos atualizados.</p>
                <ul class="space-y-3 text-xs text-blue-100 font-medium">
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Cursos organizados por área de atuação
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Trilhas personalizadas para sua equipe
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Certificados e acompanhamento de progresso
                    </li>
                </ul>
            </div>

            <!-- Footer / Copyright -->
            <span class="relative z-10 text-xs text-blue-300 font-medium">© {{ date('Y') }} GearUp. Todos os direitos reservados.</span>

            <!-- Imagem de Fundo na Parte Inferior -->
            <img src="{{ asset('images/fundo.svg') }}" alt="" class="absolute bottom-0 right-0 w-64 md:w-80 opacity-14 pointer-events-none select-none">
        </div>

        <!-- Coluna da Direita (Login) -->
        <div class="bg-white p-8 md:p-16 flex flex-col justify-center items-center">
            <div class="w-full max-w-md space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Bem-vindo de volta</h2>
                    <p class="text-xs text-gray-500 mt-1">Acesse sua conta corporativa para continuar.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">E-mail corporativo</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-xs font-semibold text-gray-700">Senha</label>
                            @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-blue-600 hover:underline" href="{{ route('password.request') }}">
                                Esqueceu a senha?
                            </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-200 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ms-2 text-xs text-gray-600">Lembrar da minha conta</span>
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 shadow-md transition">
                            Entrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>