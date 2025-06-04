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
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    {{-- <link rel="manifest" href="/site.webmanifest"> --}}

    <title>{{ config('app.name', 'Kanboard') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Service Worker pour le mode hors ligne -->
    {{-- <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script> --}}
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
        <header class="bg-white dark:bg-gray-800 shadow fixed top-0 left-0 right-0 z-40">
            @include('layouts.navigation')
        </header>

        <main class="flex bg-gray-50 dark:bg-gray-900 flex-1">
            {{ $slot }}

            <x-modal-create-tableau></x-modal-create-tableau>
            <x-modal-add-member></x-modal-add-member>

            <div id="notification-modal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
                    <div class="flex items-center">
                        <i class="fas fa-bell text-blue-500 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white" id="notification-title"></h4>
                            <p class="text-gray-600 dark:text-gray-400" id="notification-message"></p>
                        </div>
                    </div>
                    <button onclick="closeNotification()"
                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Fermer
                    </button>
                </div>
            </div>
        </main>

        <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
    </div>

    @stack('scripts')

    <script>
        function showNotification(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-green-500' :
                type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';

            toast.className =
                `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center transform transition-transform duration-300 translate-x-full`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-3"></i>
                <div>
                    <div class="font-semibold">${title}</div>
                    <div class="text-sm opacity-90">${message}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        }

        function closeNotification() {
            document.getElementById('notification-modal').classList.add('hidden');
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
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
    </script>
</body>

</html>
