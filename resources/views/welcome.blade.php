<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'GearUp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                @layer theme {
                    :root, :host {
                        --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                    }
                }
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] font-sans flex flex-col items-center justify-between min-h-screen p-6 lg:p-8">
        
        <!-- Header / Navigation -->
        <header class="w-full lg:max-w-5xl max-w-[335px] text-sm mb-6">
            <nav class="flex items-center justify-between gap-4">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/Logo.png') }}" alt="GearUp Logo" class="h-9 w-auto object-contain transition-transform group-hover:scale-105">
                    <span class="font-bold text-xl text-[#1b1b18] dark:text-[#EDEDEC] tracking-wide">GearUp</span>
                </a>

                <!-- Auth Navigation -->
                <div class="flex items-center gap-3">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-2 text-sm font-medium dark:text-[#EDEDEC] border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-md transition-all"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-4 py-2 text-sm font-medium dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-md transition-all"
                        >
                            Entrar
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-2 text-sm font-medium dark:text-[#EDEDEC] border border-[#19140035] hover:border-[#1915014a] text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-md transition-all"
                            >
                                Criar Conta
                            </a>
                        @endif
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Main Content Hero Card -->
        <div class="flex items-center justify-center w-full my-auto">
            <main class="w-full lg:max-w-5xl max-w-[335px] grid grid-cols-1 lg:grid-cols-2 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-2xl overflow-hidden bg-white dark:bg-[#161615]">
                
                <!-- Left Content Area -->
                <div class="flex flex-col justify-between p-8 lg:p-14">
                    <div>
                        <!-- Logo & Badge Header -->
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('images/Logo.png') }}" alt="GearUp Logo" class="h-10 w-auto object-contain">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#1b1b18]/5 dark:bg-white/10 text-[#1b1b18] dark:text-[#EDEDEC]">
                                Plataforma Inteligente
                            </span>
                        </div>

                        <h1 class="text-3xl lg:text-4xl font-bold tracking-tight text-[#1b1b18] dark:text-white mb-4">
                            Acelere seu fluxo de trabalho com o <span class="text-[#f53003] dark:text-[#FF4433]">GearUp</span>
                        </h1>
                        
                        <p class="text-sm lg:text-base leading-relaxed text-[#706f6c] dark:text-[#A1A09A] mb-8">
                            A solução integrada com automação e inteligência para otimizar suas tarefas diárias. Conecte sua equipe e potencialize seus resultados.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 bg-[#1b1b18] text-white dark:bg-white dark:text-[#1b1b18] hover:bg-[#1b1b18]/90 dark:hover:bg-white/90 rounded-lg font-medium text-sm transition-all shadow-sm">
                                Acessar Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-6 py-3 bg-[#1b1b18] text-white dark:bg-white dark:text-[#1b1b18] hover:bg-[#1b1b18]/90 dark:hover:bg-white/90 rounded-lg font-medium text-sm transition-all shadow-sm">
                                Começar Agora
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-6 py-3 border border-[#19140035] dark:border-[#3E3E3A] hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg font-medium text-sm transition-all">
                                Fazer Login
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Robot Illustration Area -->
                <div class="relative bg-[#f7f7f6] dark:bg-[#0e0e0e] border-t lg:border-t-0 lg:border-l border-[#e3e3e0] dark:border-[#3E3E3A] flex items-center justify-center p-8 min-h-[320px] lg:min-h-full">
                    <!-- Glow effect backdrop -->
                    <div class="absolute w-48 h-48 bg-[#f53003]/10 dark:bg-[#FF4433]/15 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Robot Image -->
                    <img 
                        src="{{ asset('images/robo.png') }}" 
                        alt="Robô Assistente GearUp" 
                        class="relative z-10 max-h-[320px] lg:max-h-[380px] w-auto object-contain transition-transform duration-500 hover:scale-105"
                    >
                </div>

            </main>
        </div>

        <!-- Footer -->
        <footer class="w-full text-center text-xs text-[#706f6c] dark:text-[#A1A09A] mt-6">
            &copy; {{ date('Y') }} GearUp. Todos os direitos reservados.
        </footer>

    </body>
</html>