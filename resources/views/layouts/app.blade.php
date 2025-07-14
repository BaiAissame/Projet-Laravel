<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Kanboard - Votre application de gestion de projet avec tableaux Kanban">
    <meta name="keywords" content="kanban, gestion projet, tâches, collaboration, productivité">
    <meta name="author" content="Kanboard Team">

    <meta property="og:title" content="{{ config('app.name', 'Kanboard') }}">
    <meta property="og:description" content="Votre application de gestion de projet avec tableaux Kanban">
    <meta property="og:type" content="website">

    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <title>{{ config('app.name', 'Kanboard') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Header moderne avec effet de verre -->
        <header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-white/20 dark:border-gray-700/50 shadow-lg fixed top-0 left-0 right-0 z-50">
            @include('layouts.navigation')
        </header>

     <main class="flex bg-gray-50 dark:bg-gray-900 flex-1 pt-16" id="app">
            @yield('content')
            {{ $slot ?? '' }}

            <x-modal-create-tableau></x-modal-create-tableau>
            <x-modal-add-member></x-modal-add-member>

            <!-- Modal de notification moderne -->
            <div id="notification-modal"
                class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="notification-content">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-bell text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-xl text-gray-900 dark:text-white" id="notification-title"></h4>
                            <p class="text-gray-600 dark:text-gray-400 mt-1" id="notification-message"></p>
                        </div>
                    </div>
                    <button onclick="closeNotification()"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                        Fermer
                    </button>
                </div>
            </div>
        </main>

        <!-- Container de notifications modernes -->
        <div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-3"></div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

    <script>
        function showNotification(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const gradients = {
                success: 'from-emerald-500 to-green-600',
                error: 'from-red-500 to-pink-600',
                warning: 'from-amber-500 to-orange-600',
                info: 'from-blue-500 to-purple-600'
            };

            const icons = {
                success: 'fa-check-circle',
                error: 'fa-times-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            };

            toast.className = `bg-gradient-to-r ${gradients[type]} text-white p-6 rounded-2xl shadow-2xl flex items-center transform transition-all duration-500 translate-x-full opacity-0 backdrop-blur-sm`;
            toast.innerHTML = `
                <div class="flex items-center flex-1">
                    <i class="fas ${icons[type]} text-2xl mr-4"></i>
                    <div>
                        <div class="font-bold text-lg">${title}</div>
                        <div class="text-sm opacity-90">${message}</div>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            `;

            container.appendChild(toast);

            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);

            // Animation de sortie
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 500);
            }, 5000);
        }

        function closeNotification() {
            const modal = document.getElementById('notification-modal');
            const content = document.getElementById('notification-content');
            
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                content.classList.remove('scale-95', 'opacity-0');
            }, 300);
        }

        // Gestion du thème sombre
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.theme) {
                if (e.matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });

        // Animation des modals
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('[id$="-modal"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    const content = modal.querySelector('div');
                    if (content) {
                        content.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            content.classList.remove('scale-95', 'opacity-0');
                        }, 100);
                    }
                }
            });
        });
    </script>
</body>

</html>