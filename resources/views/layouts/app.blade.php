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

    <title>{{ config('app.name', 'Kanboard') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">
        <header class="bg-white dark:bg-gray-800 shadow fixed top-0 left-0 right-0 z-40">
            @include('layouts.navigation')
        </header>

        <main class="flex bg-gray-50 dark:bg-gray-900 flex-1 pt-16" id="app">
            @yield('content')
            {{ $slot ?? '' }}

            <!-- Composants modaux -->
            @if(View::exists('components.modal-create-tableau'))
                <x-modal-create-tableau />
            @else
                @include('components.modal-create-tableau')
            @endif

            @if(View::exists('components.modal-add-member'))
                <x-modal-add-member />
            @else
                @include('components.modal-add-member')
            @endif

            <!-- Modal de notification -->
            <div id="notification-modal" class="modal fade" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notification-title">Notification</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bell text-primary me-3"></i>
                                <p id="notification-message" class="mb-0"></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Toast container -->
        <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')

    <script>
        // Configuration du thème
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

        // Fonction pour afficher les notifications toast
        function showNotification(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();
            
            const bgClass = type === 'success' ? 'bg-success' :
                          type === 'error' ? 'bg-danger' :
                          type === 'warning' ? 'bg-warning' : 'bg-primary';

            const iconClass = type === 'success' ? 'fa-check-circle' :
                           type === 'error' ? 'fa-times-circle' :
                           type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle';

            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas ${iconClass} me-2"></i>
                            <strong>${title}</strong>
                            <div class="small">${message}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 5000
            });
            
            toast.show();
            
            // Supprimer l'élément du DOM après fermeture
            toastElement.addEventListener('hidden.bs.toast', function () {
                this.remove();
            });
        }

        // Fonction pour afficher le modal de notification
        function showNotificationModal(title, message) {
            document.getElementById('notification-title').textContent = title;
            document.getElementById('notification-message').textContent = message;
            
            const modal = new bootstrap.Modal(document.getElementById('notification-modal'));
            modal.show();
        }

        // Fonction pour fermer le modal de notification
        function closeNotification() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('notification-modal'));
            if (modal) {
                modal.hide();
            }
        }

        // Fonction pour ouvrir le modal de création de tableau
        function openCreateTableauModal() {
            const modal = new bootstrap.Modal(document.getElementById('modal-create-tableau'));
            modal.show();
        }

        // Fonction pour ouvrir le modal d'ajout de membre
        function openAddMemberModal() {
            const modal = new bootstrap.Modal(document.getElementById('modal-add-member'));
            modal.show();
        }
    </script>
</body>

</html>