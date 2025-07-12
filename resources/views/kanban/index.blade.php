<x-app-layout>
    <div class="w-full h-screen flex flex-col">
        <!-- Header moderne du projet -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-xl border-b border-white/20 dark:border-gray-700/50 p-6 fixed top-20 left-0 right-0 z-30">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-project-diagram text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projet->nom }}</h1>
                            @if($projet->statut)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-800 dark:text-blue-200">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ $projet->statut }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Boutons de vue -->
                    <div class="flex bg-gray-100/80 dark:bg-gray-800/80 rounded-xl p-1 backdrop-blur-sm">
                        <button onclick="switchView('kanban')"
                                class="px-4 py-2 rounded-lg text-sm font-medium view-btn active transition-all duration-200"
                                data-view="kanban">
                            <i class="fas fa-columns mr-2"></i> Kanban
                        </button>
                        <button onclick="switchView('list')"
                                class="px-4 py-2 rounded-lg text-sm font-medium view-btn transition-all duration-200"
                                data-view="list">
                            <i class="fas fa-list mr-2"></i> Liste
                        </button>
                        <button onclick="switchView('calendar')"
                                class="px-4 py-2 rounded-lg text-sm font-medium view-btn transition-all duration-200"
                                data-view="calendar">
                            <i class="fas fa-calendar mr-2"></i> Calendrier
                        </button>
                    </div>

                    <!-- Boutons d'action -->
                    <button onclick="openCreateTaskModal()"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center">
                        <i class="fas fa-plus mr-2"></i> Nouvelle tâche
                    </button>

                    <button onclick="openAddMemberModal()"
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="flex-1 pt-32 pb-8 px-8 overflow-hidden">
            <!-- Vue Kanban -->
            <div id="kanban-view" class="view-content h-full">
                <div class="h-full">
                    <div class="flex gap-6 h-full overflow-x-auto pb-6" id="kanban-board">
                        @foreach($colonnes as $colonne)
                            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 min-w-80 max-w-80 flex flex-col"
                                 data-colonne-id="{{ $colonne->id }}">
                                <!-- En-tête de colonne -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-columns text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 dark:text-white">
                                                {{ $colonne->nom }}
                                            </h3>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $colonne->tasks->count() }} tâche(s)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="addTask('{{ $colonne->id }}')"
                                                class="w-8 h-8 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg flex items-center justify-center transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                        <button onclick="editColumn('{{ $colonne->id }}')"
                                                class="w-8 h-8 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg flex items-center justify-center transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                                            <i class="fas fa-edit text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Zone de tâches -->
                                <div class="flex-1 space-y-4 droppable-zone overflow-y-auto" data-colonne="{{ $colonne->id }}">
                                    @foreach($colonne->tasks->sortBy('ordre') as $task)
                                        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700 draggable-task cursor-grab hover:shadow-xl transition-all duration-200 transform hover:scale-105"
                                             data-task-id="{{ $task->id }}"
                                             onclick="openTaskModal('{{ $task->id }}')">

                                            <!-- En-tête de la tâche -->
                                            <div class="flex items-start justify-between mb-3">
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-5 flex-1">
                                                    {{ $task->titre }}
                                                </h4>
                                                @if($task->priorite)
                                                    <span class="priority-badge priority-{{ $task->priorite }} ml-2">
                                                        @switch($task->priorite)
                                                            @case('haute')
                                                                <div class="w-6 h-6 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-exclamation text-white text-xs"></i>
                                                                </div>
                                                                @break
                                                            @case('moyenne')
                                                                <div class="w-6 h-6 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                                                                </div>
                                                                @break
                                                            @case('basse')
                                                                <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                                    <i class="fas fa-minus text-white text-xs"></i>
                                                                </div>
                                                                @break
                                                        @endswitch
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Description -->
                                            @if($task->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-xs mb-4 line-clamp-2 leading-relaxed">
                                                    {{ Str::limit($task->description, 80) }}
                                                </p>
                                            @endif

                                            <!-- Catégorie -->
                                            @if($task->categorie)
                                                <div class="mb-4">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-800 dark:text-blue-200">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $task->categorie }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Pied de carte -->
                                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                                <!-- Assignés -->
                                                <div class="flex -space-x-2">
                                                    @foreach($task->assignes->take(3) as $assigne)
                                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center text-white text-xs font-bold" 
                                                             title="{{ $assigne->name }}">
                                                            {{ strtoupper(substr($assigne->name, 0, 1)) }}
                                                        </div>
                                                    @endforeach
                                                    @if($task->assignes->count() > 3)
                                                        <div class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-400">
                                                            +{{ $task->assignes->count() - 3 }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Date limite -->
                                                @if($task->date_limite)
                                                    <span class="text-xs {{ $task->date_limite < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($task->date_limite)->format('d/m') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Bouton ajouter tâche -->
                                <button onclick="addTask('{{ $colonne->id }}')"
                                        class="w-full mt-4 p-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 flex items-center justify-center">
                                    <i class="fas fa-plus mr-2"></i> Ajouter une tâche
                                </button>
                            </div>
                        @endforeach

                        <!-- Colonne pour ajouter une nouvelle colonne -->
                        <div class="bg-gray-100/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 min-w-80 max-w-80 flex flex-col items-center justify-center hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                            <button onclick="addColumn()"
                                    class="w-full h-full flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 min-h-32">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-plus text-white text-2xl"></i>
                                </div>
                                <span class="font-medium">Ajouter une colonne</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue Liste -->
            <div id="list-view" class="view-content hidden h-full">
                @include('projets.partials.list-view', ['tasks' => $tasks])
            </div>

            <!-- Vue Calendrier -->
            <div id="calendar-view" class="view-content hidden h-full">
                @include('projets.partials.calendar-view', ['tasks' => $tasks])
            </div>
        </div>
    </div>

    @include('projets.partials.task-modal')
    @include('projets.partials.column-modal')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let draggedTask = null;
        let currentView = 'kanban';

        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            initializeViewButtons();
        });

        function initializeDragAndDrop() {
            const columns = document.querySelectorAll('.droppable-zone');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 300,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onStart: function(evt) {
                        draggedTask = evt.item;
                        evt.item.style.transform = 'rotate(5deg) scale(1.05)';
                    },
                    onEnd: function(evt) {
                        evt.item.style.transform = '';
                        const taskId = evt.item.dataset.taskId;
                        const newColumnId = evt.to.dataset.colonne;
                        const newPosition = evt.newIndex;

                        // Mise à jour en base de données
                        updateTaskPosition(taskId, newColumnId, newPosition);
                    }
                });
            });
        }

        function initializeViewButtons() {
            const viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    viewButtons.forEach(b => {
                        b.classList.remove('active', 'bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
                        b.classList.add('text-gray-600', 'dark:text-gray-400');
                    });
                    this.classList.add('active', 'bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
                    this.classList.remove('text-gray-600', 'dark:text-gray-400');
                });
            });
        }

        function switchView(view) {
            currentView = view;

            document.querySelectorAll('.view-content').forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(view + '-view').classList.remove('hidden');

            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
                btn.classList.add('text-gray-600', 'dark:text-gray-400');
            });

            const activeBtn = document.querySelector(`[data-view="${view}"]`);
            activeBtn.classList.add('active', 'bg-white', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
            activeBtn.classList.remove('text-gray-600', 'dark:text-gray-400');
        }

        function updateTaskPosition(taskId, columnId, position) {
            fetch(`/api/tasks/${taskId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    colonne_id: columnId,
                    position: position
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Succès', 'Tâche déplacée avec succès', 'success');
                } else {
                    showNotification('Erreur', 'Erreur lors du déplacement', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur', 'Erreur lors du déplacement', 'error');
            });
        }

        function openTaskModal(taskId) {
            fetch(`/api/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    populateTaskModal(task);
                    document.getElementById('task-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur', 'Impossible de charger la tâche', 'error');
                });
        }

        function addTask(columnId) {
            // Logique pour ajouter une tâche
            console.log('Ajouter une tâche à la colonne:', columnId);
        }

        function editColumn(columnId) {
            // Logique pour éditer une colonne
            console.log('Éditer la colonne:', columnId);
        }

        function addColumn() {
            // Logique pour ajouter une colonne
            console.log('Ajouter une colonne');
        }

        function openCreateTaskModal() {
            // Logique pour ouvrir le modal de création de tâche
            console.log('Ouvrir modal création tâche');
        }

        function openAddMemberModal() {
            // Logique pour ouvrir le modal d'ajout de membre
            console.log('Ouvrir modal ajout membre');
        }
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.5;
            transform: rotate(5deg) scale(1.05);
        }
        
        .sortable-chosen {
            transform: rotate(5deg) scale(1.05);
        }
        
        .sortable-drag {
            transform: rotate(5deg) scale(1.05);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Scrollbar personnalisée */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.8);
        }

        /* Animation des cartes */
        .draggable-task {
            transition: all 0.3s ease;
        }

        .draggable-task:hover {
            transform: translateY(-2px);
        }
    </style>
    @endpush
</x-app-layout>