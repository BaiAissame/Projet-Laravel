@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 dark:from-gray-900 dark:via-blue-900/20 dark:to-purple-900/20">

    <!-- Hero Section -->
    <div class="relative overflow-hidden py-20 lg:py-32">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-pink-500/10"></div>
        <div class="absolute top-0 left-0 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-20 lg:pt-32 lg:pb-32">
            <div class="text-center">
                <!-- Titre principal -->
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 dark:text-white mb-6 lg:mb-12 animate-fade-in">
                    Gérez vos projets avec
                    <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        Kanboard
                    </span>
                </h1>
                
                <!-- Sous-titre -->
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-8 lg:mb-16 max-w-4xl mx-auto animate-fade-in-delay">
                    La plateforme de gestion de projet moderne qui transforme vos idées en résultats. 
                    Organisez, collaborez et accomplissez plus que jamais.
                </p>

                <!-- Boutons CTA -->
                <div class="flex flex-col sm:flex-row gap-6 lg:gap-8 justify-center items-center mb-20 lg:mb-32 animate-fade-in-delay-2">
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                        <i class="fas fa-rocket mr-3"></i>
                        Commencer gratuitement
                    </a>
                    <a href="#features" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl text-gray-700 dark:text-gray-300 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white dark:hover:bg-gray-800 transition-all duration-200 shadow-lg hover:shadow-xl border border-gray-200 dark:border-gray-700 flex items-center">
                        <i class="fas fa-play mr-3"></i>
                        Voir la démo
                    </a>
            </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-20 max-w-4xl mx-auto animate-fade-in-delay-3">
                    <div class="text-center">
                        <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">10K+</div>
                        <div class="text-gray-600 dark:text-gray-400">Projets créés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">50K+</div>
                        <div class="text-gray-600 dark:text-gray-400">Tâches complétées</div>
                </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold bg-gradient-to-r from-pink-600 to-red-600 bg-clip-text text-transparent">99%</div>
                        <div class="text-gray-600 dark:text-gray-400">Satisfaction client</div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Features Section -->
    <div id="features" class="py-32 lg:py-48 bg-white/50 dark:bg-gray-900/50 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 lg:mb-32">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4 lg:mb-8">
                    Pourquoi choisir Kanboard ?
                </h2>
                <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                    Découvrez les fonctionnalités qui font de Kanboard la solution idéale pour votre équipe
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
                <!-- Feature 1 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-columns text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Tableaux Kanban Intuitifs</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Organisez vos tâches en colonnes visuelles. Glissez-déposez pour une gestion fluide et intuitive de vos projets.
                    </p>
                    </div>

                <!-- Feature 2 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Collaboration en Temps Réel</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Travaillez en équipe avec des mises à jour instantanées. Partagez des commentaires et assignez des tâches facilement.
                    </p>
            </div>

                <!-- Feature 3 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Suivi des Progrès</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Visualisez l'avancement de vos projets avec des graphiques et des métriques détaillées pour optimiser votre productivité.
                    </p>
                    </div>

                <!-- Feature 4 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Responsive Design</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Accédez à vos projets depuis n'importe quel appareil. Interface optimisée pour mobile, tablette et desktop.
                    </p>
                    </div>

                <!-- Feature 5 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Sécurité Avancée</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Vos données sont protégées avec les dernières technologies de sécurité. Sauvegarde automatique et chiffrement.
                    </p>
                    </div>

                <!-- Feature 6 -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 border border-white/20 dark:border-gray-700/50">
                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bolt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Performance Optimisée</h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Interface ultra-rapide avec des temps de chargement optimisés. Profitez d'une expérience fluide et réactive.
                    </p>
                </div>
            </div>
        </div>
                    </div>

    <!-- CTA Section -->
    <div class="py-32 lg:py-48 bg-gradient-to-r from-blue-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                            <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 lg:mb-8">
                Prêt à transformer votre productivité ?
            </h2>
            <p class="text-xl lg:text-2xl text-blue-100 mb-8 lg:mb-12">
                Rejoignez des milliers d'équipes qui utilisent déjà Kanboard pour accomplir plus.
            </p>
            <div class="flex flex-col sm:flex-row gap-6 lg:gap-8 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-rocket mr-3"></i>
                    Commencer maintenant
                </a>
                <a href="{{ route('login') }}" class="bg-transparent text-white border-2 border-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-3"></i>
                    Se connecter
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo et description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('logo-kanboard.png') }}" alt="kanboard-logo" class="h-8 w-auto mr-3">
                        <span class="text-xl font-bold">Kanboard</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        La plateforme de gestion de projet moderne qui transforme vos idées en résultats. 
                        Organisez, collaborez et accomplissez plus que jamais.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Produit</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Intégrations</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Statut</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} Kanboard - Tous droits réservés
                </p>
            </div>
        </div>
    </footer>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-delay {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-delay-2 {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-delay-3 {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-fade-in-delay {
    animation: fade-in-delay 1s ease-out 0.2s both;
}

.animate-fade-in-delay-2 {
    animation: fade-in-delay-2 1s ease-out 0.4s both;
}

.animate-fade-in-delay-3 {
    animation: fade-in-delay-3 1s ease-out 0.6s both;
}
</style>

<script>
// Smooth scroll pour les ancres
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection