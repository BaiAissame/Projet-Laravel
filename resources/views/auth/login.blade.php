@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-blue-900/20 dark:to-purple-900/20 flex flex-col">
    <!-- Header -->
    <div class="flex-shrink-0 p-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-center">
                <a href="{{ route('dashboard') }}" class="flex items-center group">
                    <div class="relative">
                        <img src="{{ asset('logo-kanboard.png') }}" alt="kanboard-logo" class="h-16 w-auto object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <span class="ml-4 text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Kanboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl">
                    <i class="fas fa-sign-in-alt text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                    Connexion
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Accédez à votre espace de travail Kanboard</p>
            </div>

            <!-- Formulaire -->
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 dark:border-gray-700/50 p-8">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 dark:text-gray-300 font-medium" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <x-text-input id="email" type="email" name="email" required autofocus 
                                class="w-full pl-12 pr-4 py-4 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                placeholder="votre@email.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Mot de passe -->
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 dark:text-gray-300 font-medium" />
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <x-text-input id="password" type="password" name="password" required 
                                class="w-full pl-12 pr-12 py-4 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" 
                                placeholder="••••••••" />
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" id="password-toggle"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Se souvenir de moi') }}</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                            {{ __('Mot de passe oublié?') }}
                        </a>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-3"></i>
                        {{ __('Se connecter') }}
                    </button>

                    <!-- Lien d'inscription -->
                    <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-gray-600 dark:text-gray-400">
                            Pas encore de compte? 
                            <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-medium transition-colors">
                                Créer un compte
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Messages d'erreur -->
            @if ($errors->any())
                <div class="mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Erreur de connexion</h3>
                            <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <footer class="flex-shrink-0 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl border-t border-white/20 dark:border-gray-700/50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    © {{ date('Y') }} Kanboard - Tous droits réservés
                </p>
                <div class="mt-4 flex justify-center space-x-6">
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        Conditions d'utilisation
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                        Support
                    </a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
    }
}

// Animation d'entrée
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const header = document.querySelector('.text-center');
    
    form.style.opacity = '0';
    form.style.transform = 'translateY(20px)';
    header.style.opacity = '0';
    header.style.transform = 'translateY(-20px)';
    
    setTimeout(() => {
        header.style.transition = 'all 0.6s ease';
        header.style.opacity = '1';
        header.style.transform = 'translateY(0)';
        
        setTimeout(() => {
            form.style.transition = 'all 0.6s ease';
            form.style.opacity = '1';
            form.style.transform = 'translateY(0)';
        }, 200);
    }, 100);
});
</script>
@endsection
