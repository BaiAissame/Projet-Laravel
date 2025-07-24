<x-app-layout>
    <x-nav-left :data="$projets" :projet="$projet"></x-nav-left>
    <div class="custom-padding-projet bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="mb-8">
             <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <!-- Bouton : Nouvelle liste -->
                <button onclick="openCreateListModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouvelle liste
                </button>

                <!-- Bouton : Liste des tâches -->
                <a href="{{ route('tasks.list', ['projet' => $projet->slug]) }}">
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                        <span class="mr-2">📋</span>
                        Voir la liste des tâches
                    </button>
                </a>

                <!-- Bouton : Calendrier -->
                <a href="{{ route('tasks.calendar', ['projet' => $projet->slug]) }}">
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                        <span class="mr-2">📅</span>
                        Voir le calendrier
                    </button>
                </a>
            @if(Auth::user() && Auth::user()->hasRole('admin'))
                <form method="POST" action="{{ route('projet.destroy', ['projet' => $projet->slug]) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i>Supprimer le projet
                    </button>
                </form>
            @endif
             </div>
        </div>
        <div class="overflow-x-auto pb-4" id="kanban-board">
            <div class="flex gap-6 min-w-max pl-0">
                @foreach($projet->listTasks as $listTask)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 w-80 flex-shrink-0 flex flex-col group border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-200 relative {{ $listTask->color && !str_starts_with($listTask->color, '#') ? 'border-' . $listTask->color . '-400 dark:border-' . $listTask->color . '-500 bg-' . $listTask->color . '-50 dark:bg-' . $listTask->color . '-900/20' : '' }}"
                         data-list-id="{{ $listTask->id }}"
                         data-list-task-id="{{ $listTask->id }}"
                         data-color="{{ $listTask->color }}"
                         @if($listTask->color && str_starts_with($listTask->color, '#'))
                         style="border-color: {{ $listTask->color }}; background-color: {{ $listTask->color }}20;"
                         @endif>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2">
                                <span class="list-handle cursor-grab text-gray-400 hover:text-blue-500"><i class="fas fa-grip-vertical"></i></span>
                                <input class="font-bold text-lg bg-transparent border-none w-3/4 text-gray-900 dark:text-white" value="{{ $listTask->title }}" readonly />
                            </div>
                            <div class="flex items-center space-x-1">
                                <!-- Bouton ajouter tâche rapide -->
                                <button onclick="quickAddTask('{{ $listTask->id }}')"
                                        class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                                    <i class="fas fa-plus text-sm"></i>
                                </button>

                                <!-- Menu d'options -->
                                <div class="relative">
                                    <button class="column-menu-btn w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200" data-column-id="{{ $listTask->id }}">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="column-menu hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700">
                                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium color-btn" data-column-id="{{ $listTask->id }}">
                                            <i class="fas fa-palette mr-2"></i>Changer la couleur
                                        </button>
                                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium" onclick="editColumnName('{{ $listTask->id }}')">
                                            <i class="fas fa-edit mr-2"></i>Renommer
                                        </button>
                                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 font-semibold delete-btn" data-column-id="{{ $listTask->id }}">
                                            <i class="fas fa-trash mr-2"></i>Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3 droppable-zone" data-colonne="{{ $listTask->id }}">
                            @foreach($listTask->tasks as $task)
                                <div class="task-card bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700 draggable-task cursor-grab hover:shadow-xl transition-all duration-200 transform hover:scale-105 group relative overflow-hidden"
                                     data-task-id="{{ $task->id }}"
                                     onclick="openTaskModal('{{ $task->id }}')">

                                    <!-- Indicateur de priorité -->
                                    @if($task->priorite)
                                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r
                                            @switch($task->priorite)
                                                @case('élevée') from-red-500 to-red-600 @break
                                                @case('moyenne') from-yellow-500 to-orange-600 @break
                                                @case('basse') from-green-500 to-emerald-600 @break
                                            @endswitch">
                                        </div>
                                    @endif

                                    <!-- En-tête de la tâche -->
                                    <div class="flex items-start justify-between mb-3 @if($task->priorite) mt-1 @endif">
                                        <div class="flex-1 pr-2">
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-5 mb-1">
                                                {{ $task->title }}
                                            </h4>
                                            @if($task->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">
                                                    {{ Str::limit($task->description, 80) }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            @if($task->priorite)
                                                <span class="priority-badge priority-{{ $task->priorite }}" title="Priorité {{ ucfirst($task->priorite) }}">
                                                    @switch($task->priorite)
                                                        @case('élevée')
                                                            <div class="w-4 h-4 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-exclamation text-white text-xs"></i>
                                                            </div>
                                                            @break
                                                        @case('moyenne')
                                                            <div class="w-4 h-4 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-exclamation-triangle text-white text-xs"></i>
                                                            </div>
                                                            @break
                                                        @case('basse')
                                                            <div class="w-4 h-4 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-minus text-white text-xs"></i>
                                                            </div>
                                                            @break
                                                    @endswitch
                                                </span>
                                            @endif
                                            <button onclick="event.stopPropagation(); quickEditTask('{{ $task->id }}')"
                                                    class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200"
                                                    title="Modifier rapidement">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Métadonnées de la tâche -->
                                    <div class="flex items-center justify-between mb-3 text-xs text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center space-x-3">
                                            @if($task->date_limite)
                                                <div class="flex items-center space-x-1">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span>{{ \Carbon\Carbon::parse($task->date_limite)->format('d/m/Y') }}</span>
                                                </div>
                                            @endif
                                            @if($task->assignes && $task->assignes->count() > 0)
                                                <div class="flex items-center space-x-1">
                                                    <i class="fas fa-users"></i>
                                                    <span>{{ $task->assignes->count() }}</span>
                                                </div>
                                            @endif
                                            @if($task->comments && $task->comments->count() > 0)
                                                <div class="flex items-center space-x-1">
                                                    <i class="fas fa-comment"></i>
                                                    <span>{{ $task->comments->count() }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if($task->categorie)
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs">
                                                {{ $task->categorie }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Tags -->
                                    @if($task->tags && $task->tags->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-3">
                                            @foreach($task->tags->take(3) as $tag)
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                            @if($task->tags->count() > 3)
                                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-xs">
                                                    +{{ $task->tags->count() - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Actions rapides -->
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button onclick="event.stopPropagation(); duplicateTask('{{ $task->id }}')"
                                                    class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200"
                                                    title="Dupliquer">
                                                <i class="fas fa-copy text-xs"></i>
                                            </button>
                                            <button onclick="event.stopPropagation(); deleteTask('{{ $task->id }}')"
                                                    class="w-6 h-6 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                                                    title="Supprimer">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <div class="flex -space-x-1">
                                                @if($task->assignes && $task->assignes->count() > 0)
                                                    @foreach($task->assignes->take(3) as $assignee)
                                                        <div class="w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800"
                                                             title="{{ $assignee->name }}">
                                                            {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                        </div>
                                                    @endforeach
                                                    @if($task->assignes->count() > 3)
                                                        <div class="w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold border-2 border-white dark:border-gray-800">
                                                            +{{ $task->assignes->count() - 3 }}
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($listTask->tasks->count() == 0)
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400 empty-state" id="empty-state-{{ $listTask->id }}">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p class="text-sm">Aucune tâche</p>
                                    <button onclick="quickAddTask('{{ $listTask->id }}')"
                                            class="mt-2 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                        Ajouter une tâche
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal de création de liste -->
    <div id="create-list-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="create-list-content">
            <form onsubmit="return createListFromModal(event, '{{ $projet->slug }}')" class="p-8">
            @csrf
                <div class="flex items-center mb-8">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">Créer une nouvelle liste</h3>
                        <p class="text-gray-600 dark:text-gray-400">Ajoutez une colonne à votre board Kanban</p>
                    </div>
                    <button type="button" onclick="closeCreateListModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="list-title" class="text-sm font-medium text-gray-700 dark:text-gray-300">Nom de la liste</label>
                        <input type="text" id="list-title" name="title" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ex: À faire, En cours, Terminé...">
                    </div>
                </div>
                <div class="flex space-x-4 mt-8">
                    <button type="button" onclick="closeCreateListModal()" class="flex-1 px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i> Créer la liste
                </button>
            </div>
        </form>
        </div>
    </div>


    <!-- Modal réutilisable des tâches -->
    @include('components.task-modal')

    <!-- Modal moderne de sélection de couleur pour les listes -->
    <div id="color-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Choisir une couleur</h3>
                <button id="close-color-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>


            <!-- Prévisualisation de la couleur sélectionnée -->
            <div id="color-preview" class="mb-6 p-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hidden">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="preview-color-box" class="w-8 h-8 rounded-lg"></div>
                        <div>
                            <span id="preview-color-name" class="font-medium text-gray-900 dark:text-white"></span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Prévisualisation de la couleur</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Color picker personnalisé -->
            <div class="mb-6">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Couleur personnalisée :</span>
                    <input type="color" id="custom-color-picker" class="w-12 h-12 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer" value="#3b82f6">
                </div>
                <button id="apply-custom-color" class="w-full px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 font-medium">
                    <i class="fas fa-palette mr-2"></i>Appliquer la couleur personnalisée
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

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .task-card {
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .task-card:hover::before {
            opacity: 1;
        }

        .priority-badge {
            position: relative;
        }

        .priority-badge::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.8;
        }

        /* Animation pour les cartes */
        .task-card {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Styles pour les conteneurs vides */
        .empty-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            color: #9ca3af;
        }

        .empty-container i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        .empty-state {
            cursor: default !important;
            user-select: none;
            pointer-events: auto;
        }

        .empty-state button {
            pointer-events: auto;
            cursor: pointer;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>







    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('task-modal').classList.contains('hidden')) {
            closeTaskModal();
        }
    });

    document.getElementById('task-modal').addEventListener('click', (e) => {
        if (e.target.id === 'task-modal') {
            closeTaskModal();
        }
    });

    document.getElementById('close-task-modal').addEventListener('click', () => {
        closeTaskModal();
    });

    function openCreateListModal() {
        const modal = document.getElementById('create-list-modal');
        const content = document.getElementById('create-list-content');

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCreateListModal() {
        const modal = document.getElementById('create-list-modal');
        const content = document.getElementById('create-list-content');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function createListFromModal(e, projetSlug) {
        e.preventDefault();

        const form = e.target;
        const input = form.querySelector('#list-title');
        const title = input.value.trim();

        if (!title) {
            showNotification('Erreur', 'Veuillez entrer un titre pour la liste', 'error');
            return false;
        }

        fetch(`/listTask/create/${projetSlug}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title })
        })
        .then(async response => {
            const text = await response.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                showNotification('Erreur', 'Erreur serveur : ' + text.substring(0, 100), 'error');
                throw e;
            }
        })
        .then(data => {
            if (data && data.html) {
                const board = document.querySelector('#kanban-board .flex');
                board.insertAdjacentHTML('beforeend', data.html);
                input.value = '';
                closeCreateListModal();
                showNotification('Succès', 'Liste créée avec succès', 'success');

                initializeDragAndDrop();
                initializeColumnMenus();
                initializeQuickActions();

            } else if (data && data.error) {
                showNotification('Erreur', 'Erreur lors de la création de la liste : ' + (data.message || 'Erreur inconnue'), 'error');
            }
        })
        .catch(error => {
            showNotification('Erreur', 'Erreur lors de la création de la liste', 'error');
        });

        return false;
    }

    function createTask(e, listTaskId) {
        e.preventDefault();
        const form = e.target;
        const input = form.querySelector('input');
        const titleTask = input.value.trim();
        if (!titleTask) return;
        fetch(`/task/create/${listTaskId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ titleTask })
        })
        .then(res => res.json())
        .then(data => {
            if (data.html) {
                form.previousElementSibling.insertAdjacentHTML('beforeend', data.html);
                input.value = '';

                updateEmptyColumnDisplay(listTaskId);
                initializeQuickActions();
            }
        });
        return false;
    }
    function deleteList(listTaskId) {
        if (!confirm('Supprimer cette liste ?')) return;
        fetch(`/listTask/delete/${listTaskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.error) {
                document.querySelector(`[data-list-id='${listTaskId}']`).remove();
            }
        });
    }
    new Sortable(document.querySelector('#kanban-board .flex'), {
        animation: 200,
        handle: '.list-handle',
        draggable: '[data-list-id]',
        ghostClass: 'list-ghost',
        chosenClass: 'list-chosen',
        dragClass: 'list-drag',
        onStart: function (evt) {
            evt.item.style.transform = 'rotate(2deg) scale(1.02)';
        },
        onEnd: function (evt) {
            evt.item.style.transform = '';
            const orderList = Array.from(document.querySelectorAll('[data-list-id]')).map((el, idx) => ({
                listTaskId: el.getAttribute('data-list-id'),
                order: idx + 1
            }));
            fetch('/listTask/update-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ orderList })
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        initializeColumnMenus();
        initializeDragAndDrop();
        initializeQuickActions();

        document.querySelectorAll('.droppable-zone').forEach(column => {
            const columnId = column.dataset.colonne;
            if (columnId) {
                updateEmptyColumnDisplay(columnId);
            }
        });
    });

    function initializeColumnMenus() {

        const menuBtns = document.querySelectorAll('.column-menu-btn');

        menuBtns.forEach(btn => {
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

        const deleteBtns = document.querySelectorAll('.delete-btn');

        deleteBtns.forEach(btn => {
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

    function updateEmptyColumnDisplay(columnId) {
        const column = document.querySelector(`[data-colonne="${columnId}"]`);
        if (!column) return;

        const tasks = column.querySelectorAll('.task-card');
        const emptyMessage = column.querySelector('.empty-state');

        if (tasks.length === 0) {
            if (!emptyMessage) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'text-center py-8 text-gray-500 dark:text-gray-400 empty-state';
                emptyDiv.innerHTML = `
                    <i class="fas fa-inbox text-2xl mb-2"></i>
                    <p class="text-sm">Aucune tâche</p>
                    <button onclick="quickAddTask('${columnId}')"
                            class="mt-2 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                        Ajouter une tâche
                    </button>
                `;
                column.appendChild(emptyDiv);
            }
        } else {
            if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    }

    function initializeDragAndDrop() {
        const columns = document.querySelectorAll('.droppable-zone');

        columns.forEach(column => {
            if (column.sortableInstance) {
                column.sortableInstance.destroy();
            }

            column.sortableInstance = new Sortable(column, {
                group: 'kanban',
                animation: 300,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                filter: '.empty-state',
                preventOnFilter: false,
                onStart: function(evt) {
                    evt.item.style.transform = 'rotate(5deg) scale(1.05)';
                },
                onEnd: function(evt) {
                    evt.item.style.transform = '';
                    const taskId = evt.item.dataset.taskId;
                    const newColumnId = evt.to.dataset.colonne;
                    const oldColumnId = evt.from.dataset.colonne;
                    const newPosition = evt.newIndex;

                    updateEmptyColumnDisplay(oldColumnId);
                    updateEmptyColumnDisplay(newColumnId);

                    updateTaskPosition(taskId, newColumnId, newPosition);
                }
            });
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


    function quickAddTask(columnId) {
        const taskTitle = prompt('Nom de la tâche :');
        if (taskTitle && taskTitle.trim()) {
            createQuickTask(columnId, taskTitle.trim());
        }
    }
    window.quickAddTask = quickAddTask;


    function createQuickTask(columnId, title) {
        fetch(`/task/create/${columnId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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

                const column = document.querySelector(`[data-colonne="${columnId}"]`);
                if (column) {
                    const emptyMessage = column.querySelector('.empty-state');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }

                    if (data.html) {
                        column.insertAdjacentHTML('beforeend', data.html);
                    } else if (data.task) {
                        const newTaskCard = document.createElement('div');
                        newTaskCard.className = 'task-card bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700 draggable-task cursor-grab hover:shadow-xl transition-all duration-200 transform hover:scale-105 group relative overflow-hidden';
                        newTaskCard.setAttribute('data-task-id', data.task.id);
                        newTaskCard.setAttribute('onclick', `openTaskModal(${data.task.id})`);

                        newTaskCard.innerHTML = `
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 pr-2">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm leading-5 mb-1">
                                        ${data.task.title || data.task.titre}
                                    </h4>
                                </div>
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="event.stopPropagation(); quickEditTask('${data.task.id}')"
                                            class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200"
                                            title="Modifier rapidement">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="event.stopPropagation(); duplicateTask('${data.task.id}')"
                                            class="w-6 h-6 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200"
                                            title="Dupliquer">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>
                                    <button onclick="event.stopPropagation(); deleteTask('${data.task.id}')"
                                            class="w-6 h-6 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="flex -space-x-1"></div>
                                </div>
                            </div>
                        `;

                        column.appendChild(newTaskCard);
                    }

                    updateEmptyColumnDisplay(columnId);
                    initializeQuickActions();
                    initializeDragAndDrop();
                    return;
                }

                location.reload();
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
    window.quickEditTask = quickEditTask;


    function updateTaskTitle(taskId, title) {
        fetch(`/api/tasks/${taskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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
            const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
            const columnElement = taskElement ? taskElement.closest('.droppable-zone') : null;
            const columnId = columnElement ? columnElement.dataset.colonne : null;

            fetch(`/task/delete/${taskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showNotification('Erreur', data.message, 'error');
                } else {
                    showNotification('Succès', 'Tâche supprimée', 'success');

                    if (taskElement) {
                        taskElement.remove();
                        if (columnId) {
                            updateEmptyColumnDisplay(columnId);
                        }
                    } else {
                        location.reload();
                    }
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
                'X-CSRF-TOKEN': csrfToken
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

    function updateTaskPosition(taskId, columnId, position) {
        fetch(`/task/update-order`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                orderTask: [{
                    taskId: taskId,
                    listTaskId: columnId,
                    order: position
                }]
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showNotification('Erreur', 'Erreur lors du déplacement', 'error');
            } else {
                showNotification('Succès', 'Tâche déplacée avec succès', 'success');
            }
        })
        .catch(error => {
            showNotification('Erreur', 'Erreur lors du déplacement', 'error');
        });
    }

    function deleteColumn(columnId) {

        fetch(`/listTask/delete/${columnId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(async response => {

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const text = await response.text();

            try {
                return JSON.parse(text);
            } catch (e) {
                showNotification('Erreur', 'Réponse serveur invalide', 'error');
                throw e;
            }
        })
        .then(data => {

            if (data.error) {
                showNotification('Erreur', data.message || 'Erreur lors de la suppression', 'error');
            } else {
                showNotification('Succès', 'Colonne supprimée avec succès', 'success');

                const columnElement = document.querySelector(`[data-list-task-id="${columnId}"]`);

                if (columnElement) {
                    columnElement.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    columnElement.style.transform = 'scale(0.8)';
                    columnElement.style.opacity = '0';

                    setTimeout(() => {
                        columnElement.remove();
                    }, 300);
                } else {
                    const altColumnElement = document.querySelector(`[data-list-id="${columnId}"]`);
                    if (altColumnElement) {
                        altColumnElement.remove();
                    }
                }

                document.querySelectorAll('.column-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        })
        .catch(error => {
            showNotification('Erreur', 'Erreur lors de la suppression de la colonne: ' + error.message, 'error');
        });
    }
    Array.from(document.querySelectorAll('[data-list-id] input[readonly]')).forEach(input => {
        input.addEventListener('dblclick', function() {
            this.removeAttribute('readonly');
            this.focus();
            this.select();
        });
        input.addEventListener('blur', function() {
            this.setAttribute('readonly', true);
            const listTaskId = this.closest('[data-list-id]').getAttribute('data-list-id');
            fetch(`/listTask/update-title/${listTaskId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ title: this.value })
            });
        });
    this.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            this.blur();
        }
    });
});
</script>


    <style>
        /* Styles pour les tâches (drag & drop) */
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

        /* Styles pour les listes (colonnes) - pas de changement de couleur */
        .list-ghost {
            opacity: 0.5;
            transform: rotate(2deg) scale(1.02);
        }

        .list-chosen {
            transform: rotate(2deg) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .list-drag {
            transform: rotate(2deg) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Animation des cartes */
        .task-card {
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
        }

        /* Animations pour les actions rapides */
        .group:hover .opacity-0 {
            opacity: 1 !important;
        }

        /* Scrollbar personnalisée */
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
    </style>
</x-app-layout>
