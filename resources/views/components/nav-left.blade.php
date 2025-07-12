<section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl h-screen shadow-xl border-r border-white/20 dark:border-gray-700/50 w-64 min-w-64 text-gray-700 dark:text-gray-300 fixed left-0 top-20 z-40">
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
                @if(isset($projet))
                    <a href="{{ route('projet.members.index', $projet) }}" class="flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <span class="font-medium">Membres</span>
                    </a>
                @else
                    <button onclick="openProjectSelector()" class="w-full flex items-center px-4 py-3 rounded-xl text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-users text-gray-400"></i>
                        </div>
                        <span class="font-medium">Membres</span>
                    </button>
                @endif
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
                    <button onclick="openCreateProjectModal()" 
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Créer un tableau
                    </button>
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

    <!-- Modal de sélection de projet pour les membres -->
    <div id="projectSelectorModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <!-- En-tête de la modal -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-users mr-3 text-blue-500"></i>
                            Sélectionner un projet
                        </h3>
                        <button onclick="closeProjectSelector()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Choisissez un projet pour accéder à la gestion des membres</p>
                </div>

                <!-- Contenu de la modal -->
                <div class="p-6">
                    @if (isset($data) && count($data) > 0)
                        <div class="space-y-3">
                            @foreach ($data as $projet)
                                <button onclick="selectProjectForMembers('{{ route('projet.members.index', $projet) }}')" 
                                        class="w-full flex items-center p-4 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                        <i class="fas fa-project-diagram text-white"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <h4 class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                            {{ $projet->name }}
                                        </h4>
                                        @if($projet->statut)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $projet->statut }}</p>
                                        @endif
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-500 transition-colors duration-200"></i>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-folder-plus text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Aucun projet disponible</p>
                            <button onclick="openCreateProjectModal()" 
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>
                                Créer un projet
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Script pour la modal -->
    <script>
        function openProjectSelector() {
            const modal = document.getElementById('projectSelectorModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeProjectSelector() {
            const modal = document.getElementById('projectSelectorModal');
            const content = document.getElementById('modalContent');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function selectProjectForMembers(url) {
            window.location.href = url;
        }

        // Fermer la modal en cliquant à l'extérieur
        document.getElementById('projectSelectorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProjectSelector();
            }
        });

        // Fermer la modal avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProjectSelector();
            }
        });
    </script>
</section>
