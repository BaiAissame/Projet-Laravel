<section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl h-screen shadow-xl border-r border-white/20 dark:border-gray-700/50 w-64 min-w-64 text-gray-700 dark:text-gray-300">
    <div class="p-6">
        <!-- En-tête de la navigation -->
        <div class="mb-8">
            <h2 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Navigation</h2>
        </div>

        <!-- Menu principal -->
        <ul class="space-y-2">
            <li>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-table text-white"></i>
                    </div>
                    <span class="font-medium">Tableaux</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <span class="font-medium">Membres</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-cog text-white"></i>
                    </div>
                    <span class="font-medium">Paramètres</span>
                </a>
            </li>
        </ul>

        <!-- Séparateur -->
        <div class="my-8 border-t border-gray-200 dark:border-gray-700"></div>

        <!-- Vos tableaux -->
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                <i class="fas fa-folder mr-2 text-blue-500"></i>
                Vos tableaux
            </h3>
            
            @if (isset($data) && count($data) > 0)
                <ul class="space-y-2">
                    @foreach ($data as $projet)
                        <li>
                            <a href="{{ route('projet.show', $projet) }}" 
                               class="flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <i class="fas fa-project-diagram text-white text-xs"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium truncate">{{ $projet->name }}</p>
                                    @if($projet->statut)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $projet->statut }}</p>
                                    @endif
                                </div>
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-folder-plus text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Aucun tableau</p>
                    <a href="{{ route('projet.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Créer un tableau
                    </a>
                </div>
            @endif
        </div>

        <!-- Statistiques rapides -->
        @if (isset($data) && count($data) > 0)
            <div class="mt-8 p-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-3">Statistiques</h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Tableaux actifs</span>
                        <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ count($data) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Tâches totales</span>
                        <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $totalTasks ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
