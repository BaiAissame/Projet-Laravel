<x-app-layout>
    {{-- Navigation latérale --}}
    <x-nav-left :data="$userProjects" :projet="$projet" />

    <div class="flex-1 flex flex-col custom-padding-members">
        <!-- Header moderne responsive -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-xl border-b border-white/20 dark:border-gray-700/50 p-3 sm:p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-3 sm:space-x-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 sm:mr-4">
                            <i class="fas fa-users text-white text-sm sm:text-lg lg:text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white">Membres du projet</h1>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 truncate max-w-xs sm:max-w-none">{{ $projet->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('projet.show', $projet) }}" 
                       class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 py-2 sm:px-4 sm:py-2 lg:px-6 lg:py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2 text-xs sm:text-sm"></i> 
                        <span class="hidden sm:inline">Retour au projet</span>
                        <span class="sm:hidden">Retour</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu principal responsive -->
        <div class="flex-1 p-3 sm:p-4 lg:p-8 overflow-auto">
            <div class="max-w-7xl mx-auto">
                <!-- Statistiques responsive -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-4 sm:p-5 lg:p-6 rounded-xl sm:rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $members->count() + 1 }}</p>
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm lg:text-base font-medium">Total membres</p>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-4 sm:p-5 lg:p-6 rounded-xl sm:rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-1">1</p>
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm lg:text-base font-medium">Créateur</p>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-crown text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-4 sm:p-5 lg:p-6 rounded-xl sm:rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $members->count() }}</p>
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm lg:text-base font-medium">Membres ajoutés</p>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-plus text-white text-lg sm:text-xl lg:text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Créateur du projet responsive -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-4 sm:p-5 lg:p-6 mb-6 lg:mb-8">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-crown mr-2 sm:mr-3 text-yellow-500 text-sm sm:text-base lg:text-lg"></i>
                            <span class="hidden sm:inline">Créateur du projet</span>
                            <span class="sm:hidden">Créateur</span>
                        </h2>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 p-3 sm:p-4 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-xl">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-full flex items-center justify-center text-white text-lg sm:text-xl font-bold mx-auto sm:mx-0">
                            {{ strtoupper(substr($creator->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-white">{{ $creator->name }}</h3>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 break-all sm:break-normal">{{ $creator->email }}</p>
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-200 mt-2">
                                <i class="fas fa-crown mr-1"></i>
                                Créateur
                            </span>
                        </div>
                        <div class="text-center sm:text-right">
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Membre depuis</p>
                            <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">{{ $projet->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Membres du projet responsive -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-xl sm:rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-4 sm:p-5 lg:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-users mr-2 sm:mr-3 text-blue-500 text-sm sm:text-base lg:text-lg"></i>
                            <span class="hidden sm:inline">Membres du projet</span>
                            <span class="sm:hidden">Membres</span>
                        </h2>
                        
                        @if($isCreator)
                            <!-- Formulaire d'ajout de membre responsive -->
                            <form action="{{ route('projet.members.add', ['projet' => $projet->slug]) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                                @csrf
                                <div class="relative flex-1 sm:flex-initial">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                    </div>
                                    <input type="email" 
                                           name="email" 
                                           placeholder="membre@example.com" 
                                           required
                                           class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 sm:min-w-64 text-sm sm:text-base"
                                           style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
                                </div>
                                <button type="submit" 
                                        class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-sm sm:text-base">
                                    <i class="fas fa-user-plus mr-1 sm:mr-2 text-sm"></i> 
                                    <span class="hidden sm:inline">Ajouter</span>
                                    <span class="sm:hidden">Ajouter membre</span>
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($members->count() > 0)
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($members as $member)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-all duration-200 space-y-3 sm:space-y-0">
                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">{{ $member->name }}</h3>
                                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 break-all sm:break-normal">{{ $member->email }}</p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-800 dark:text-blue-200 mt-1">
                                                <i class="fas fa-user mr-1"></i>
                                                Membre
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between sm:justify-end space-x-3 sm:space-x-4">
                                        <div class="text-left sm:text-right">
                                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Ajouté le</p>
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $member->pivot->created_at ? $member->pivot->created_at->format('d/m/Y') : 'N/A' }}
                                            </p>
                                        </div>
                                        
                                        @if($isCreator)
                                            <form action="{{ route('projet.members.remove', ['projet' => $projet, 'user' => $member]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir retirer {{ $member->name }} du projet ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg flex items-center justify-center transition-all duration-200 transform hover:scale-110 shadow-lg flex-shrink-0"
                                                        title="Retirer le membre">
                                                    <i class="fas fa-user-minus text-xs sm:text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 sm:py-12">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                                <i class="fas fa-users text-2xl sm:text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun membre ajouté</h3>
                            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-4 sm:mb-6 px-4">Ce projet n'a pas encore de membres supplémentaires.</p>
                            
                            @if($isCreator)
                                <!-- Formulaire d'ajout de membre sans membres -->
                                <form action="{{ route('projet.members.add', ['projet' => $projet->slug]) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center space-y-3 sm:space-y-0 sm:space-x-3 max-w-md mx-auto">
                                    @csrf
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 sm:pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="email" 
                                               name="email" 
                                               placeholder="membre@example.com" 
                                               required
                                               class="w-full pl-10 sm:pl-12 pr-3 sm:pr-4 py-2 sm:py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-sm sm:text-base"
                                               style="background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
                                    </div>
                                    <button type="submit" 
                                            class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center text-sm sm:text-base whitespace-nowrap">
                                        <i class="fas fa-user-plus mr-1 sm:mr-2 text-sm"></i> 
                                        <span class="hidden sm:inline">Ajouter le premier membre</span>
                                        <span class="sm:hidden">Ajouter membre</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Messages de session responsive -->
                @if (session('success'))
                    <div class="mt-4 sm:mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-3 sm:p-4">
                        <div class="flex items-start sm:items-center space-x-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Succès</h3>
                                <p class="text-sm text-green-700 dark:text-green-300 mt-1">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 sm:mt-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 sm:p-4">
                        <div class="flex items-start sm:items-center space-x-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm sm:text-base"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Erreur</h3>
                                <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Animation d'entrée responsive
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.grid > div, .bg-white\\/80');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</x-app-layout>