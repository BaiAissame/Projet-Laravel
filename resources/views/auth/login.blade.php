@extends('layouts.guest')

@section('content')
<div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="flex-grow flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md space-y-8 mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Connexion</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Accédez à votre espace de travail</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
                @csrf

                <!-- Email -->
                <div class="space-y-1 mb-4">
                    <x-input-label for="email" :value="__('Email')" class="dark:text-white" />
                    <x-text-input id="email" type="email" name="email" required autofocus 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <!-- Mot de passe -->
                <div class="space-y-1 mb-6">
                    <x-input-label for="password" :value="__('Mot de passe')" class="dark:text-white" />
                    <x-text-input id="password" type="password" name="password" required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <x-primary-button class="w-full py-3 text-lg">
                    {{ __('Se connecter') }}
                </x-primary-button>

                <div class="mt-4 text-center">
                    <a href="{{ route('register') }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                        Créer un compte
                    </a>
                </div>
            </form>
        </div>
    </div>

    
    <footer class="w-full bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8">
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
