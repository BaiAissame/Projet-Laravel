<section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-xl border-r border-white/20 dark:border-gray-700/50 w-64 min-w-64 text-gray-700 dark:text-gray-300 fixed left-0 top-16 sm:top-20 z-10 h-[calc(100vh-4rem)] sm:h-[calc(100vh-5rem)] overflow-y-auto">
    <div class="p-3 sm:p-6">
        <!-- En-tête de la navigation -->
        <div class="mb-4 sm:mb-8">
            <h2 class="text-base sm:text-lg font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Navigation</h2>
        </div>

        <!-- Menu principal -->
        <ul class="space-y-1 sm:space-y-2">

            @if(isset($projet))
            <a href="{{ route('projet.members.index', $projet) }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-2 sm:mr-3 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-users text-white text-sm sm:text-base"></i>
                </div>
                <span class="font-medium text-sm sm:text-base">Membres</span>
            </a>
            @else
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'select-project-modal' }))" class="w-full flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center mr-2 sm:mr-3 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-users text-gray-400 text-sm sm:text-base"></i>
                </div>
                <span class="font-medium text-sm sm:text-base">Membres</span>
            </button>
            @endif
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mr-2 sm:mr-3 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-cog text-white text-sm sm:text-base"></i>
                    </div>
                    <span class="font-medium text-sm sm:text-base">Paramètres</span>
                </a>
            </li>
        </ul>

        <!-- Séparateur -->
        <div class="my-4 sm:my-8 border-t border-gray-200 dark:border-gray-700"></div>

        <!-- Vos tableaux -->
        <div class="mb-4 sm:mb-6">
            <h3 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white mb-2 sm:mb-4 flex items-center">
                <i class="fas fa-folder mr-2 text-blue-500 text-xs sm:text-sm"></i>
                Vos tableaux
            </h3>

            @if (isset($data) && count($data) > 0)
            <ul class="space-y-1 sm:space-y-2 max-h-48 sm:max-h-64 overflow-y-auto">
                @foreach ($data as $projet)
                <li>
                    <a href="{{ route('projet.show', $projet) }}"
                        class="flex items-center px-3 sm:px-4 py-2 sm:py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-2 sm:mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-project-diagram text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate text-xs sm:text-sm">{{ $projet->name }}</p>
                            @if($projet->statut)
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate hidden sm:block">{{ $projet->statut }}</p>
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
            <div class="text-center py-4 sm:py-8">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-4">
                    <i class="fas fa-folder-plus text-lg sm:text-2xl text-gray-400"></i>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-2 sm:mb-4">Aucun tableau</p>
                <button onclick="openCreateProjectModal()"
                    class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 text-xs sm:text-sm font-medium">
                    <i class="fas fa-plus mr-1 sm:mr-2 text-xs"></i>
                    Créer un tableau
                </button>
            </div>
            @endif
        </div>

        <!-- Statistiques rapides -->
        @if (isset($data) && count($data) > 0)
        <div class="mt-4 sm:mt-8 p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl">
            <h4 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Statistiques</h4>
            <div class="space-y-1 sm:space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Tableaux actifs</span>
                    <span class="text-xs sm:text-sm font-bold text-blue-600 dark:text-blue-400">{{ count($data) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-600 dark:text-gray-400">Tâches totales</span>
                    <span class="text-xs sm:text-sm font-bold text-purple-600 dark:text-purple-400">{{ $totalTasks ?? 0 }}</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal de sélection de projet pour gestion des membres -->
    <x-modal name="select-project-modal" :show="false" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Sélectionner un projet</h2>
            @if(isset($data) && count($data) > 0)
                <ul class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($data as $projet)
                        <li>
                            <a href="{{ route('projet.members.index', $projet) }}"
                               class="block px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-800 transition text-gray-900 dark:text-white font-medium">
                                <span class="truncate">{{ $projet->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 dark:text-gray-400">Aucun projet disponible.</p>
            @endif
            <div class="mt-6 text-right">
                <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'select-project-modal' }))" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-700 transition">Fermer</button>
            </div>
        </div>
    </x-modal>
</section>