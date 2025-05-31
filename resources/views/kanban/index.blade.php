<x-app-layout>
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 p-4 fixed top-16 left-0 right-0 z-30">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projet->nom }}</h1>
                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                        {{ $projet->statut }}
                    </span>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button onclick="switchView('kanban')"
                                class="px-3 py-1 rounded text-sm font-medium view-btn active"
                                data-view="kanban">
                            <i class="fas fa-columns mr-1"></i> Kanban
                        </button>
                        <button onclick="switchView('list')"
                                class="px-3 py-1 rounded text-sm font-medium view-btn"
                                data-view="list">
                            <i class="fas fa-list mr-1"></i> Liste
                        </button>
                        <button onclick="switchView('calendar')"
                                class="px-3 py-1 rounded text-sm font-medium view-btn"
                                data-view="calendar">
                            <i class="fas fa-calendar mr-1"></i> Calendrier
                        </button>
                    </div>

                    <button onclick="openCreateTaskModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i> Nouvelle tâche
                    </button>

                    <button onclick="openAddMemberModal()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-24">
            <div id="kanban-view" class="view-content">
                <div class="p-4">
                    <div class="flex gap-6 overflow-x-auto pb-4" id="kanban-board">
                        @foreach($colonnes as $colonne)
                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 min-w-80 max-w-80"
                                 data-colonne-id="{{ $colonne->id }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $colonne->nom }}
                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            ({{ $colonne->tasks->count() }})
                                        </span>
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="addTask('{{ $colonne->id }}')"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button onclick="editColumn('{{ $colonne->id }}')"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-3 droppable-zone" data-colonne="{{ $colonne->id }}">
                                    @foreach($colonne->tasks->sortBy('ordre') as $task)
                                        <div class="bg-white dark:bg-gray-700 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-600 draggable-task cursor-grab"
                                             data-task-id="{{ $task->id }}"
                                            onclick="openTaskModal('{{ $task->id }}')">

                                            <div class="flex items-start justify-between mb-2">
                                                <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-5">
                                                    {{ $task->titre }}
                                                </h4>
                                                @if($task->priorite)
                                                    <span class="priority-badge priority-{{ $task->priorite }} ml-2">
                                                        @switch($task->priorite)
                                                            @case('haute')
                                                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                                                @break
                                                            @case('moyenne')
                                                                <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                                                @break
                                                            @case('basse')
                                                                <i class="fas fa-minus-circle text-green-500"></i>
                                                                @break
                                                        @endswitch
                                                    </span>
                                                @endif
                                            </div>

                                            @if($task->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-xs mb-3 line-clamp-2">
                                                    {{ Str::limit($task->description, 80) }}
                                                </p>
                                            @endif

                                            @if($task->categorie)
                                                <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs mb-2">
                                                    {{ $task->categorie }}
                                                </span>
                                            @endif

                                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100 dark:border-gray-600">
                                                <div class="flex -space-x-1">
                                                    @foreach($task->assignes->take(3) as $assigne)
                                                        <img src="{{ $assigne->avatar ?? '/default-avatar.png' }}"
                                                             alt="{{ $assigne->name }}"
                                                             class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-700"
                                                             title="{{ $assigne->name }}">
                                                    @endforeach
                                                    @if($task->assignes->count() > 3)
                                                        <span class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700 flex items-center justify-center text-xs">
                                                            +{{ $task->assignes->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($task->date_limite)
                                                    <span class="text-xs {{ $task->date_limite < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($task->date_limite)->format('d/m') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button onclick="addTask('{{ $colonne->id }}')"
                                        class="w-full mt-3 p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Ajouter une tâche
                                </button>
                            </div>
                        @endforeach

                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 min-w-80 max-w-80">
                            <button onclick="addColumn()"
                                    class="w-full h-full flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-gray-400 dark:hover:border-gray-500 transition-colors min-h-32">
                                <i class="fas fa-plus text-2xl mb-2"></i>
                                <span>Ajouter une colonne</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="list-view" class="view-content hidden">
                @include('projets.partials.list-view', ['tasks' => $tasks])
            </div>

            <div id="calendar-view" class="view-content hidden">
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
        });

        function initializeDragAndDrop() {
            const columns = document.querySelectorAll('.droppable-zone');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onStart: function(evt) {
                        draggedTask = evt.item;
                    },
                    onEnd: function(evt) {
                        const taskId = evt.item.dataset.taskId;
                        const newColumnId = evt.to.dataset.colonne;
                        const newPosition = evt.newIndex;

                        // Mise à jour en base de données
                        updateTaskPosition(taskId, newColumnId, newPosition);
                    }
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
                btn.classList.remove('active', 'bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white');
                btn.classList.add('text-gray-600', 'dark:text-gray-400');
            });

            const activeBtn = document.querySelector(`[data-view="${view}"]`);
            activeBtn.classList.add('active', 'bg-white', 'dark:bg-gray-600', 'text-gray-900', 'dark:text-white');
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
                });
        }

        function openCreateTaskModal(columnId = null) {
            clearTaskModal();
            if (columnId) {
                document.getElementById('task-column-id').value = columnId;
            }
            document.getElementById('task-modal').classList.remove('hidden');
        }

        function addTask(columnId) {
            openCreateTaskModal(columnId);
        }

        function addColumn() {
            document.getElementById('column-modal').classList.remove('hidden');
        }

        function editColumn(columnId) {
            // Charger les détails de la colonne et ouvrir la modal
            fetch(`/api/columns/${columnId}`)
                .then(response => response.json())
                .then(column => {
                    populateColumnModal(column);
                    document.getElementById('column-modal').classList.remove('hidden');
                });
        }

        const style = document.createElement('style');
        style.textContent = `
            .sortable-ghost {
                opacity: 0.5;
                background: #f3f4f6;
            }

            .sortable-chosen {
                cursor: grabbing !important;
            }

            .sortable-drag {
                transform: rotate(5deg);
            }

            .draggable-task:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transition: all 0.2s ease;
            }

            .view-btn.active {
                background: white;
                color: #1f2937;
            }

            .dark .view-btn.active {
                background: #4b5563;
                color: white;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    </script>
    @endpush
</x-app-layout>