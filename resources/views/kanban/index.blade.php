<x-app-layout>
    <x-nav-left :data="$projets" :projet="$projet"></x-nav-left>
    <div class="flex-1 flex flex-col h-screen ml-64">
        <!-- Header moderne du projet -->
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-xl border-b border-white/20 dark:border-gray-700/50 p-6 z-30">
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
        <div class="flex-1 overflow-hidden">
            <!-- Vue Kanban -->
            <div id="kanban-view" class="view-content h-full p-6 pl-8">
                <div class="h-full">
                    <div class="flex gap-6 h-full overflow-x-auto pb-6" id="kanban-board">
                        @foreach($colonnes as $colonne)
                            <div class="kanban-column bg-white/80 dark:bg-gray-800/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 min-w-80 max-w-80 flex flex-col relative {{ $colonne->color ? 'border-' . $colonne->color . '-400 dark:border-' . $colonne->color . '-500 bg-' . $colonne->color . '-50/50 dark:bg-' . $colonne->color . '-900/20' : '' }}"
                                 data-colonne-id="{{ $colonne->id }}" data-color="{{ $colonne->color }}">
                                <!-- En-tête de colonne -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center flex-1">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                            <i class="fas fa-columns text-white"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">
                                        {{ $colonne->nom }}
                                            </h3>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $colonne->tasks->count() }} tâche(s)
                                        </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <!-- Bouton ajouter tâche rapide -->
                                        <button onclick="quickAddTask('{{ $colonne->id }}')"
                                                class="w-8 h-8 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>

                                        <!-- Menu d'options -->
                                        <div class="relative">
                                            <button class="column-menu-btn w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200" data-column-id="{{ $colonne->id }}">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="column-menu hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700">
                                                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium color-btn" data-column-id="{{ $colonne->id }}">
                                                    <i class="fas fa-palette mr-2"></i>Changer la couleur
                                                </button>
                                                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium" onclick="editColumnName('{{ $colonne->id }}')">
                                                    <i class="fas fa-edit mr-2"></i>Renommer
                                                </button>
                                                <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                                <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 font-semibold delete-btn" data-column-id="{{ $colonne->id }}">
                                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                        </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Zone de tâches -->
                                <div class="flex-1 space-y-3 droppable-zone overflow-y-auto" data-colonne="{{ $colonne->id }}">
                                    @foreach($colonne->tasks->sortBy('ordre') as $task)
                                        <div class="task-card bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700 draggable-task cursor-grab hover:shadow-xl transition-all duration-200 transform hover:scale-105 group"
                                             data-task-id="{{ $task->id }}"
                                            onclick="openTaskModal('{{ $task->id }}')">

                                            <!-- En-tête de la tâche -->
                                            <div class="flex items-start justify-between mb-3">
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-5 flex-1 pr-2">
                                                    {{ $task->titre }}
                                                </h4>
                                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                @if($task->priorite)
                                                        <span class="priority-badge priority-{{ $task->priorite }}">
                                                        @switch($task->priorite)
                                                            @case('élevée')
                                                                    <div class="w-5 h-5 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                                                        <i class="fas fa-exclamation text-white text-xs"></i>
                                                                    </div>
                                                                @break
                                                            @case('moyenne')
                                                                    <div class="w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center">
                                                                        <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                                                                    </div>
                                                                @break
                                                            @case('basse')
                                                                    <div class="w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                                        <i class="fas fa-minus text-white text-xs"></i>
                                                                    </div>
                                                                @break
                                                        @endswitch
                                                    </span>
                                                @endif
                                                    <button onclick="event.stopPropagation(); quickEditTask('{{ $task->id }}')"
                                                            class="w-5 h-5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            @if($task->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">
                                                    {{ Str::limit($task->description, 80) }}
                                                </p>
                                            @endif

                                            <!-- Tags et métadonnées -->
                                            <div class="flex items-center justify-between mb-3">
                                            @if($task->categorie)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-800 dark:text-blue-200">
                                                        <i class="fas fa-tag mr-1"></i>
                                                    {{ $task->categorie }}
                                                </span>
                                            @endif

                                                @if($task->date_limite)
                                                    <span class="text-xs {{ $task->date_limite < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }} flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($task->date_limite)->format('d/m') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Pied de carte -->
                                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                                <!-- Assignés -->
                                                <div class="flex -space-x-2">
                                                    @foreach($task->assignes->take(3) as $assigne)
                                                        <div class="w-7 h-7 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center text-white text-xs font-bold"
                                                             title="{{ $assigne->name }}">
                                                            {{ strtoupper(substr($assigne->name, 0, 1)) }}
                                                        </div>
                                                    @endforeach
                                                    @if($task->assignes->count() > 3)
                                                        <div class="w-7 h-7 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-400">
                                                            +{{ $task->assignes->count() - 3 }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Actions rapides -->
                                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button onclick="event.stopPropagation(); duplicateTask('{{ $task->id }}')"
                                                            class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                                        <i class="fas fa-copy text-xs"></i>
                                                    </button>
                                                    <button onclick="event.stopPropagation(); deleteTask('{{ $task->id }}')"
                                                            class="w-6 h-6 text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($colonne->tasks->count() == 0)
                                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-inbox text-2xl mb-2"></i>
                                            <p class="text-sm">Aucune tâche</p>
                                            <button onclick="quickAddTask('{{ $colonne->id }}')"
                                                    class="mt-2 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                Ajouter une tâche
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bouton ajouter tâche -->
                                <button onclick="quickAddTask('{{ $colonne->id }}')"
                                        class="w-full mt-4 p-4 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 flex items-center justify-center group">
                                    <i class="fas fa-plus mr-2 group-hover:scale-110 transition-transform"></i>
                                    <span class="group-hover:font-medium">Ajouter une tâche</span>
                                </button>
                            </div>
                        @endforeach

                        <!-- Colonne pour ajouter une nouvelle colonne -->
                        <div class="bg-gray-100/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 min-w-80 max-w-80 flex flex-col items-center justify-center hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                            <button onclick="addColumn()"
                                    class="w-full h-full flex flex-col items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 min-h-32 group">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-plus text-white text-2xl"></i>
                                </div>
                                <span class="font-medium">Ajouter une colonne</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vue Liste -->
            <div id="list-view" class="view-content hidden h-full p-6 pl-8">
                @include('projets.partials.list-view', ['tasks' => $tasks])
            </div>

            <!-- Vue Calendrier -->
            <div id="calendar-view" class="view-content hidden h-full p-6 pl-8">
                @include('projets.partials.calendar-view', ['tasks' => $tasks])
            </div>
        </div>
    </div>

    @include('projets.partials.task-modal')
    @include('projets.partials.column-modal')

    <!-- Modal de sélection de couleur -->
    <div id="color-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Choisir une couleur</h3>
                <button id="close-color-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-4 gap-4 mb-6">
                <button class="color-option w-16 h-16 bg-blue-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="blue">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-green-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="green">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-purple-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="purple">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-orange-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="orange">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-pink-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="pink">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-red-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="red">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-yellow-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="yellow">
                    <i class="fas fa-check text-lg"></i>
                </button>
                <button class="color-option w-16 h-16 bg-gray-500 rounded-xl hover:scale-110 transition-all duration-200 flex items-center justify-center text-white font-bold" data-color="gray">
                    <i class="fas fa-check text-lg"></i>
                </button>
            </div>

            <div class="flex justify-end space-x-3">
                <button id="cancel-color-btn" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors font-medium">
                    Annuler
                </button>
                <button id="apply-color-btn" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium">
                    Appliquer
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let draggedTask = null;
        let currentView = 'kanban';

        document.addEventListener('DOMContentLoaded', function() {
            initializeDragAndDrop();
            initializeViewButtons();
            initializeColumnMenus();
            initializeColorModal();
            initializeQuickActions();
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
                    if (typeof showNotification === 'function') {
                        showNotification('Succès', 'Tâche déplacée avec succès', 'success');
                    }
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification('Erreur', 'Erreur lors du déplacement', 'error');
                    }
                }
            })
            .catch(error => {
                if (typeof showNotification === 'function') {
                    showNotification('Erreur', 'Erreur lors du déplacement', 'error');
                }
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
                    if (typeof showNotification === 'function') {
                        showNotification('Erreur', 'Impossible de charger la tâche', 'error');
                    }
                });
        }

        function quickAddTask(columnId) {
            const taskTitle = prompt('Nom de la tâche :');
            if (taskTitle && taskTitle.trim()) {
                createQuickTask(columnId, taskTitle.trim());
            }
        }

        function createQuickTask(columnId, title) {
            fetch(`/task/create/${columnId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    titleTask: title
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('Erreur', data.message, 'error');
                } else {
                    showNotification('Succès', 'Tâche créée avec succès', 'success');

                    const droppableZone = document.querySelector(`[data-colonne="${columnId}"]`);
                    if (droppableZone) {
                        const emptyMessage = droppableZone.parentNode.querySelector('.text-center');
                        if (emptyMessage && emptyMessage.textContent.includes('Aucune tâche')) {
                            emptyMessage.remove();
                        }

                        const newTaskCard = document.createElement('div');
                        newTaskCard.className = 'task-card bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700 draggable-task cursor-grab hover:shadow-xl transition-all duration-200 transform hover:scale-105 group';
                        newTaskCard.setAttribute('data-task-id', data.task.id);
                        newTaskCard.setAttribute('onclick', `openTaskModal('${data.task.id}')`);

                        newTaskCard.innerHTML = `
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-5 flex-1 pr-2">
                                    ${data.task.titre || data.task.title}
                                </h4>
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="event.stopPropagation(); quickEditTask('${data.task.id}')"
                                            class="w-5 h-5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex -space-x-2"></div>
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="event.stopPropagation(); duplicateTask('${data.task.id}')"
                                            class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteTask('${data.task.id}')"
                                            class="w-6 h-6 text-gray-400 hover:text-red-600 dark:hover:text-red-400">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        `;

                        droppableZone.appendChild(newTaskCard);

                        initializeDragAndDrop();

                        const columnHeader = document.querySelector(`[data-colonne-id="${columnId}"] .text-sm`);
                        if (columnHeader) {
                            const currentCount = parseInt(columnHeader.textContent.match(/\d+/)[0]) || 0;
                            columnHeader.textContent = `${currentCount + 1} tâche(s)`;
                        }
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(error => {
                showNotification('Erreur', 'Erreur lors de la création', 'error');
            });
        }

        function quickEditTask(taskId) {
            const newTitle = prompt('Nouveau nom de la tâche :');
            if (newTitle && newTitle.trim()) {
                updateTaskTitle(taskId, newTitle.trim());
            }
        }

        function updateTaskTitle(taskId, title) {
            fetch(`/api/tasks/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Succès', 'Tâche mise à jour', 'success');
                    location.reload();
                } else {
                    showNotification('Erreur', 'Erreur lors de la mise à jour', 'error');
                }
            })
            .catch(error => {
                showNotification('Erreur', 'Erreur lors de la mise à jour', 'error');
            });
        }

        function duplicateTask(taskId) {
            if (confirm('Dupliquer cette tâche ?')) {
                showNotification('Info', 'Fonctionnalité de duplication à implémenter', 'info');
            }
        }

        function deleteTask(taskId) {
            if (confirm('Supprimer cette tâche ?')) {
                fetch(`/task/delete/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showNotification('Erreur', data.message, 'error');
                    } else {
                        showNotification('Succès', 'Tâche supprimée', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showNotification('Erreur', 'Erreur lors de la suppression', 'error');
                });
            }
        }

        function editColumnName(columnId) {
            const newName = prompt('Nouveau nom de la colonne :');
            if (newName && newName.trim()) {
                updateColumnName(columnId, newName.trim());
            }
        }

        function updateColumnName(columnId, name) {
            fetch(`/listTask/update-title/${columnId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: name
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('Erreur', data.message, 'error');
                } else {
                    showNotification('Succès', 'Colonne renommée', 'success');
                    location.reload();
                }
            })
            .catch(error => {
                showNotification('Erreur', 'Erreur lors du renommage', 'error');
            });
        }

        function addTask(columnId) {
            quickAddTask(columnId);
        }

        function editColumn(columnId) {
            editColumnName(columnId);
        }

        function addColumn() {
            const columnName = prompt('Nom de la nouvelle colonne :');
            if (columnName && columnName.trim()) {
                createColumn(columnName.trim());
            }
        }

        function createColumn(name) {
            fetch(`/listTask/create/{{ $projet->slug }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: name
                })
            })
                .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('Erreur', data.message, 'error');
                } else {
                    showNotification('Succès', 'Colonne créée', 'success');
                    location.reload();
                }
            })
            .catch(error => {
                showNotification('Erreur', 'Erreur lors de la création', 'error');
            });
        }

        function openCreateTaskModal() {
            showNotification('Info', 'Modal de création de tâche à implémenter', 'info');
        }

        function openAddMemberModal() {
            showNotification('Info', 'Modal d\'ajout de membre à implémenter', 'info');
        }

        function initializeColumnMenus() {
            document.querySelectorAll('.column-menu-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;

                    document.querySelectorAll('.column-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });

                    menu.classList.toggle('hidden');
                });
            });

            document.querySelectorAll('.color-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const columnId = this.dataset.columnId;
                    openColorModal(columnId);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const columnId = this.dataset.columnId;

                    if (confirm('Êtes-vous sûr de vouloir supprimer cette colonne ? Cette action est irréversible.')) {
                        deleteColumn(columnId);
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.column-menu-btn') && !e.target.closest('.column-menu')) {
                    document.querySelectorAll('.column-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });
        }

        function initializeColorModal() {
            document.getElementById('close-color-modal').addEventListener('click', closeColorModal);
            document.getElementById('cancel-color-btn').addEventListener('click', closeColorModal);

            document.getElementById('color-modal').addEventListener('click', function(e) {
                if (e.target.id === 'color-modal') {
                    closeColorModal();
                }
            });

            document.querySelectorAll('.color-option').forEach(option => {
                option.addEventListener('click', function() {
                    document.querySelectorAll('.color-option').forEach(opt => {
                        opt.classList.remove('ring-4', 'ring-blue-300');
                    });

                    this.classList.add('ring-4', 'ring-blue-300');
                    selectedColor = this.getAttribute('data-color');
                });
            });

            document.getElementById('apply-color-btn').addEventListener('click', function() {
                if (selectedColor && currentColumnId) {
                    applyColumnColor(currentColumnId, selectedColor);
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeColorModal();
                }
            });
        }

        function initializeQuickActions() {
            document.querySelectorAll('.task-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.querySelectorAll('.opacity-0').forEach(el => {
                        el.classList.remove('opacity-0');
                    });
                });

                card.addEventListener('mouseleave', function() {
                    this.querySelectorAll('.group-hover\\:opacity-100').forEach(el => {
                        if (!el.closest('.group:hover')) {
                            el.classList.add('opacity-0');
                        }
                    });
                });
            });
        }

        function openColorModal(columnId) {
            document.getElementById('color-modal').classList.remove('hidden');
            currentColumnId = columnId;
            selectedColor = null;

            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('ring-4', 'ring-blue-300');
            });
        }

        function closeColorModal() {
            document.getElementById('color-modal').classList.add('hidden');
            currentColumnId = null;
            selectedColor = null;

            document.querySelectorAll('.column-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }

        function applyColumnColor(columnId, color) {
            const columnElement = document.querySelector(`[data-colonne-id="${columnId}"]`);
            if (columnElement) {
                columnElement.className = columnElement.className.replace(/border-\w+-\d+/g, '');
                columnElement.className = columnElement.className.replace(/bg-\w+-\d+/g, '');
                columnElement.className = columnElement.className.replace(/dark:border-\w+-\d+/g, '');
                columnElement.className = columnElement.className.replace(/dark:bg-\w+-\d+/g, '');

                columnElement.classList.add(`border-${color}-400`, `dark:border-${color}-500`);
                columnElement.classList.add(`bg-${color}-50`, `dark:bg-${color}-900/20`);

                columnElement.setAttribute('data-color', color);

                fetch(`/listTask/update-color/${columnId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ color: color })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showNotification('Succès', 'Couleur de la colonne mise à jour', 'success');
                    } else {
                        showNotification('Erreur', data.message || 'Erreur lors de la mise à jour', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Erreur', 'Erreur lors de la mise à jour de la couleur', 'error');
                });
            }

            closeColorModal();
        }

        function deleteColumn(columnId) {
            fetch(`/listTask/delete/${columnId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('Erreur', data.message || 'Erreur lors de la suppression', 'error');
                } else {
                    showNotification('Succès', 'Colonne supprimée avec succès', 'success');
                    const columnElement = document.querySelector(`[data-colonne-id="${columnId}"]`);
                    if (columnElement) {
                        columnElement.remove();
                    }
                    document.querySelectorAll('.column-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            })
            .catch(error => {
                showNotification('Erreur', 'Erreur lors de la suppression de la colonne', 'error');
            });
        }
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.5;
            transform: rotate(5deg) scale(1.05);
            background: rgba(59, 130, 246, 0.1) !important;
            border: 2px dashed #3b82f6 !important;
        }

        .sortable-chosen {
            transform: rotate(5deg) scale(1.05);
            background: rgba(59, 130, 246, 0.2) !important;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3) !important;
        }

        .sortable-drag {
            transform: rotate(5deg) scale(1.05);
            background: rgba(59, 130, 246, 0.2) !important;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3) !important;
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
        .task-card {
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        /* Scrollbar personnalisée pour le scroll horizontal */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: rgba(156, 163, 175, 0.1);
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.8);
        }

        /* Amélioration du scroll horizontal */
        #kanban-board {
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: rgba(156, 163, 175, 0.5) rgba(156, 163, 175, 0.1);
        }

        /* Assurer que la navigation reste fixe */
        .fixed {
            position: fixed !important;
        }

        /* Responsive pour la navigation */
        @media (max-width: 768px) {
            .ml-64 {
                margin-left: 0;
            }

            section.fixed {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            section.fixed.show {
                transform: translateX(0);
            }
        }

        /* Animations pour les actions rapides */
        .group:hover .opacity-0 {
            opacity: 1 !important;
        }

        /* Style pour les colonnes vides */
        .kanban-column:empty::after {
            content: "Aucune tâche";
            display: block;
            text-align: center;
            color: #9ca3af;
            padding: 2rem;
        }
    </style>
    @endpush
</x-app-layout>