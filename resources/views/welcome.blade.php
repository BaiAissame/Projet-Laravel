@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col dark:bg-gray-900">
    <!-- Contenu principal -->
    <div class="flex-1 grid md:grid-cols-2 gap-12 items-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Section présentation -->
        <div class="space-y-8">
            <div class="space-y-4">
                <h1 class="text-5xl font-bold text-gray-900 dark:text-white animate-slide-in">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-blue-600">
                        Kanboard
                    </span>
                </h1>
                <p class="text-2xl text-gray-600 dark:text-gray-300 font-light">
                    La solution ultime pour votre productivité d'équipe
                </p>
            </div>

            <!-- Points forts -->
            <div class="space-y-6">
                <div class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 transition-all hover:shadow-md dark:hover:shadow-gray-700">
                    <svg class="w-8 h-8 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Gestion visuelle</h3>
                        <p class="text-gray-600 dark:text-gray-300">Organisez vos tâches en tableau Kanban intuitif</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/50 transition-all hover:shadow-md dark:hover:shadow-gray-700">
                    <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Collaboration en temps réel</h3>
                        <p class="text-gray-600 dark:text-gray-300">Travaillez en équipe avec des mises à jour instantanées</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire avec tabs Alpine -->
        <div x-data="{ activeTab: 'login' }" class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg dark:shadow-gray-700 max-w-md w-full mx-auto">
            <!-- Navigation tabs -->
            <div class="flex rounded-lg bg-gray-100 dark:bg-gray-700 p-1 mb-6">
                <button 
                    @click="activeTab = 'login'"
                    :class="{ 'bg-white dark:bg-gray-600 shadow-sm': activeTab === 'login' }"
                    class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 dark:text-gray-300"
                >
                    Connexion
                </button>
                <button 
                    @click="activeTab = 'register'"
                    :class="{ 'bg-white dark:bg-gray-600 shadow-sm': activeTab === 'register' }"
                    class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 dark:text-gray-300"
                >
                    Inscription
                </button>
            </div>

            <!-- Formulaire connexion -->
            <div x-show="activeTab === 'login'" x-transition:enter.opacity.duration.300ms>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- Email -->
                    <div class="space-y-1">
                        <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300"/>
                        <x-text-input id="email" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="email" name="email" required autofocus />
                    </div>

                    <!-- Mot de passe -->
                    <div class="mt-4 space-y-1">
                        <x-input-label for="password" :value="__('Mot de passe')" class="dark:text-gray-300"/>
                        <x-text-input id="password" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password" required />
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Se souvenir</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:underline dark:text-primary-400">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <x-primary-button class="w-full mt-4">
                        Se connecter
                    </x-primary-button>
                </form>
            </div>

            <!-- Formulaire inscription -->
            <div x-show="activeTab === 'register'" x-transition:enter.opacity.duration.300ms>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <!-- Nom -->
                    <div class="space-y-1">
                        <x-input-label for="name" :value="__('Nom')" class="dark:text-gray-300"/>
                        <x-text-input id="name" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="name" required />
                    </div>

                    <!-- Email -->
                    <div class="mt-4 space-y-1">
                        <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300"/>
                        <x-text-input id="email" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="email" name="email" required />
                    </div>

                    <!-- Mot de passe -->
                    <div class="mt-4 space-y-1">
                        <x-input-label for="password" :value="__('Mot de passe')" class="dark:text-gray-300"/>
                        <x-text-input id="password" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password" required />
                    </div>

                    <!-- Confirmation -->
                    <div class="mt-4 space-y-1">
                        <x-input-label for="password_confirmation" :value="__('Confirmer')" class="dark:text-gray-300"/>
                        <x-text-input id="password_confirmation" class="w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password_confirmation" required />
                    </div>

                    <x-primary-button class="w-full mt-6">
                        Créer un compte
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-600 dark:text-gray-400">
                <p class="text-sm">
                    © {{ date('Y') }} Kanboard - Tous droits réservés
                </p>
                <div class="mt-2 flex justify-center space-x-4">
                    <a href="#" class="text-sm hover:text-gray-900 dark:hover:text-gray-200">Conditions d'utilisation</a>
                    <a href="#" class="text-sm hover:text-gray-900 dark:hover:text-gray-200">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection