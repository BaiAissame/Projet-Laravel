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
                                     onclick="openTaskModal({{ $task->id }})">

                                    <!-- Indicateur de priorité -->
                                    @if($task->priorite)
                                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r
                                            @switch($task->priorite)
                                                @case('haute') from-red-500 to-red-600 @break
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
                                                        @case('haute')
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

    <!-- Modal moderne des tâches -->
    <div id="task-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-visible transform transition-all duration-300 scale-95 opacity-0" id="task-modal-content">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">Détails de la tâche</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Modifiez et gérez votre tâche</p>
                        </div>
                    </div>
                    <button id="close-task-modal" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-all duration-200 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex gap-4 p-6">
                    <!-- Main Content -->
                    <div class="flex-1 pr-3 space-y-4">
                        <!-- Title -->
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm p-4 space-y-4">
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-heading text-blue-400 text-xs"></i> Titre
                                </label>
                                <input type="text" id="task-title" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-base text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm" placeholder="Titre de la tâche">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-align-left text-blue-400 text-xs"></i> Description
                                </label>
                                <textarea id="task-description" rows="3" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm resize-none" placeholder="Décrivez votre tâche en détail..."></textarea>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-tags text-blue-400 text-xs"></i> Tags
                                </label>
                                <div class="flex flex-wrap gap-2 mb-2 min-h-[24px]" id="tags-container">
                                    <!-- Tags will be added here -->
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" id="new-tag" class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" placeholder="Ajouter un tag...">
                                    <button id="add-tag" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-plus"></i>Ajouter
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-comments text-blue-400 text-xs"></i> Commentaires
                                </label>
                                <div id="comments-container" class="space-y-1 mb-2 max-h-32 overflow-y-auto pr-1">
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <textarea id="new-comment" rows="1" class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none" placeholder="Ajouter un commentaire..."></textarea>
                                    <button id="add-comment" class="px-3 py-1 bg-green-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-paper-plane"></i>Envoyer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="w-64 flex-shrink-0 space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm p-4 space-y-3">
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-columns text-blue-400 text-xs"></i> Statut
                                </label>
                                <select id="task-status" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    @foreach($projet->listTasks ?? [] as $listTask)
                                        <option value="{{ $listTask->id }}">{{ $listTask->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-flag text-blue-400 text-xs"></i> Priorité
                                </label>
                                <select id="task-priority" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="">Aucune</option>
                                    <option value="basse">Basse</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="haute">Haute</option>
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-folder text-blue-400 text-xs"></i> Catégorie
                                </label>
                                <select id="task-category" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                    <option value="">Aucune</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="développement">Développement</option>
                                    <option value="communication">Communication</option>
                                </select>
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-calendar-alt text-blue-400 text-xs"></i> Date limite
                                </label>
                                <input type="date" id="task-due-date" class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1">
                                    <i class="fas fa-users text-blue-400 text-xs"></i> Assignés
                                </label>
                                <div id="assignees-container" class="space-y-2 mb-2 min-h-[24px]">
                                    <!-- Current assignees will be shown here -->
                                </div>
                                <div class="flex gap-2">
                                    <select id="assignee-select" class="flex-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <option value="">Sélectionner un membre...</option>
                                        @foreach($projet->members ?? [] as $membre)
                                            <option value="{{ $membre->id }}">{{ $membre->name }}</option>
                                        @endforeach
                                    </select>
                                    <button id="add-assignee" class="px-3 py-1 bg-green-600 hover:bg-emerald-700 text-white rounded-lg transition-all duration-200 text-xs flex items-center gap-1 shadow-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 pt-2">
                            <button id="save-task" class="w-full px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-sm text-sm flex items-center justify-center gap-1">
                                <i class="fas fa-save"></i>Sauvegarder
                            </button>
                            <button id="delete-task" class="w-full px-3 py-2 bg-red-600 hover:bg-pink-700 text-white rounded-lg transition-all duration-200 font-semibold shadow-sm text-sm flex items-center justify-center gap-1">
                                <i class="fas fa-trash"></i>Supprimer la tâche
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let currentTaskId = null;
    let currentTask = null;

    function openTaskModal(taskId) {
        currentTaskId = taskId;

        // Fetch task data
        fetch(`/api/tasks/${taskId}`)
            .then(response => response.json())
            .then(data => {
                currentTask = data.task;
                populateModal(data.task);

                // Show modal with animation
                const modal = document.getElementById('task-modal');
                const modalContent = document.getElementById('task-modal-content');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            })
            .catch(error => {
                showNotification('Erreur', 'Impossible de charger les détails de la tâche', 'error');
            });
    }

    function populateModal(task) {
        // Basic fields
        document.getElementById('task-title').value = task.title || '';
        document.getElementById('task-description').value = task.description || '';
        document.getElementById('task-status').value = task.list_task_id || '';
        document.getElementById('task-priority').value = task.priorite || '';
        document.getElementById('task-category').value = task.categorie || '';

        let dateValue = '';
        if (task.date_limite) {
            try {
                if (task.date_limite.includes('/')) {
                    const parts = task.date_limite.split('/');
                    if (parts.length === 3) {
                        dateValue = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    }
                } else if (task.date_limite.includes('-')) {
                    dateValue = task.date_limite.split('T')[0];
                } else {
                    const date = new Date(task.date_limite);
                    if (!isNaN(date.getTime())) {
                        dateValue = date.toISOString().split('T')[0];
                    }
                }
            } catch (error) {
                dateValue = '';
            }
        }
        document.getElementById('task-due-date').value = dateValue;

        // Tags
        populateTags(task.tags || []);

        // Comments
        populateComments(task.comments || []);

        // Assignees
        populateAssignees(task.assignes || []);
    }

    function populateTags(tags) {
        const container = document.getElementById('tags-container');
        if (!container) {
            return;
        }

        container.innerHTML = '';

        if (!tags || tags.length === 0) {
            container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm">Aucun tag ajouté</div>';
            return;
        }

        tags.forEach(tag => {
            const tagElement = createTagElement(tag);
            if (tagElement) {
                container.appendChild(tagElement);
            }
        });
    }

    function createTagElement(tag) {
        if (!tag || typeof tag !== 'string') {
            return null;
        }

        const div = document.createElement('div');
        div.className = 'inline-flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium shadow-sm hover:shadow-md transition-all duration-200 transform hover:scale-105';
        div.innerHTML = `
            <span>${tag}</span>
            <button onclick="removeTag(this)" class="text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100 hover:bg-blue-200 dark:hover:bg-blue-800/50 rounded-full p-1 transition-all duration-200">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        return div;
    }

    function populateComments(comments) {
        const container = document.getElementById('comments-container');
        if (!container) {
            return;
        }

        container.innerHTML = '';

        if (!comments || comments.length === 0) {
            container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm text-center py-6">Aucun commentaire</div>';
            return;
        }

        comments.forEach(comment => {
            const commentElement = createCommentElement(comment);
            if (commentElement) {
                container.appendChild(commentElement);
            }
        });
    }

    function createCommentElement(comment) {
        if (!comment) {
            return null;
        }

        const div = document.createElement('div');
        div.className = 'bg-white dark:bg-gray-700 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-all duration-200';

        const userName = comment.user?.name || 'Utilisateur';
        const userInitial = userName.charAt(0).toUpperCase();
        const commentDate = comment.created_at ? new Date(comment.created_at).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : 'Date inconnue';
        const commentContent = comment.content || 'Contenu vide';

        div.innerHTML = `
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    ${userInitial}
                </div>
                <div class="flex-1">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">${userName}</span>
                    <div class="text-xs text-gray-500 dark:text-gray-400">${commentDate}</div>
                </div>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">${commentContent}</p>
        `;
        return div;
    }

    function populateAssignees(assignees) {
        const container = document.getElementById('assignees-container');
        if (!container) {
            return;
        }

        container.innerHTML = '';

        if (!assignees || assignees.length === 0) {
            container.innerHTML = '<div class="text-gray-400 dark:text-gray-500 text-sm">Aucun assigné</div>';
            return;
        }

        assignees.forEach(assignee => {
            const assigneeElement = createAssigneeElement(assignee);
            if (assigneeElement) {
                container.appendChild(assigneeElement);
            }
        });
    }

    function createAssigneeElement(assignee) {
        // Vérifier que assignee existe et a les propriétés nécessaires
        if (!assignee || !assignee.id) {
            return null;
        }

        const div = document.createElement('div');
        div.className = 'flex items-center justify-between p-3 bg-white dark:bg-gray-700 rounded-xl shadow-sm border border-gray-100 dark:border-gray-600 hover:shadow-md transition-all duration-200';
        div.setAttribute('data-assignee-id', assignee.id);
        div.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                    ${assignee.name ? assignee.name.charAt(0).toUpperCase() : 'U'}
                </div>
                <span class="text-sm font-medium text-gray-900 dark:text-white">${assignee.name || 'Utilisateur'}</span>
            </div>
            <button onclick="removeAssignee(${assignee.id})" class="w-6 h-6 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full flex items-center justify-center transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        return div;
    }

    // Event listeners
    document.getElementById('close-task-modal').addEventListener('click', () => {
        closeTaskModal();
    });

    function closeTaskModal() {
        const modal = document.getElementById('task-modal');
        const modalContent = document.getElementById('task-modal-content');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('add-tag').addEventListener('click', () => {
        const input = document.getElementById('new-tag');
        const tag = input.value.trim();

        if (tag) {
            const container = document.getElementById('tags-container');
            const tagElement = createTagElement(tag);
            container.appendChild(tagElement);
            input.value = '';
        }
    });

    document.getElementById('add-comment').addEventListener('click', () => {
        const textarea = document.getElementById('new-comment');
        const content = textarea.value.trim();

        if (content) {
            addComment(content);
            textarea.value = '';
        }
    });

    document.getElementById('add-assignee').addEventListener('click', () => {
        const select = document.getElementById('assignee-select');
        const assigneeId = select.value;

        if (assigneeId) {
            addAssignee(assigneeId);
            select.value = '';
        }
    });

    document.getElementById('save-task').addEventListener('click', saveTask);
    document.getElementById('delete-task').addEventListener('click', deleteCurrentTask);

    // Helper functions
    function removeTag(button) {
        button.parentElement.remove();
    }

    function removeAssignee(assigneeId) {
        if (confirm('Êtes-vous sûr de vouloir retirer cet assigné ?')) {
            fetch(`/api/tasks/${currentTaskId}/assignees/${assigneeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from UI
                    const assigneeElements = document.querySelectorAll(`[data-assignee-id="${assigneeId}"]`);
                    assigneeElements.forEach(el => el.remove());
                    showNotification('Succès', 'Assigné retiré avec succès', 'success');

                    // Mettre à jour la carte de tâche
                    updateTaskCard();

                } else {
                    showNotification('Erreur', data.message || 'Erreur lors du retrait', 'error');
                }
            });
        }
    }

    function addComment(content) {
        fetch(`/api/tasks/${currentTaskId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('comments-container');
                const commentElement = createCommentElement(data.comment);
                container.appendChild(commentElement);
                showNotification('Succès', 'Commentaire ajouté', 'success');
            } else {
                showNotification('Erreur', data.message || 'Erreur lors de l\'ajout du commentaire', 'error');
            }
        });
    }

    function addAssignee(assigneeId) {
        fetch(`/api/tasks/${currentTaskId}/assignees`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ user_id: assigneeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('assignees-container');
                const assigneeElement = createAssigneeElement(data.assignee);
                if (assigneeElement && container) {
                    container.appendChild(assigneeElement);
                    showNotification('Succès', 'Assigné ajouté avec succès', 'success');

                    updateTaskCard();

                } else {
                    showNotification('Erreur', 'Erreur lors de la création de l\'élément assigné', 'error');
                }
            } else {
                showNotification('Erreur', data.message || 'Erreur lors de l\'ajout', 'error');
            }
        });
    }

    function saveTask() {
        const tagsContainer = document.getElementById('tags-container');
        const tags = tagsContainer ? Array.from(tagsContainer.children)
            .filter(tag => tag.querySelector && tag.querySelector('span'))
            .map(tag => tag.querySelector('span').textContent)
            .filter(text => text && text.trim() !== '') : [];

        const formData = {
            title: document.getElementById('task-title')?.value || '',
            description: document.getElementById('task-description')?.value || '',
            status: document.getElementById('task-status')?.value || '',
            priority: document.getElementById('task-priority')?.value || '',
            category: document.getElementById('task-category')?.value || '',
            due_date: document.getElementById('task-due-date')?.value || '',
            tags: tags
        };

        fetch(`/api/tasks/${currentTaskId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Succès', 'Tâche mise à jour avec succès', 'success');

                refreshTaskModal();
                updateTaskCard();

            } else {
                showNotification('Erreur', data.message || 'Erreur lors de la sauvegarde', 'error');
            }
        });
    }

    function refreshTaskModal() {
        if (currentTaskId) {
            fetch(`/api/tasks/${currentTaskId}`)
                .then(response => response.json())
                .then(data => {
                    currentTask = data.task;
                    populateModal(data.task);
                })
                .catch(error => {
                });
        }
    }

    function updateTaskCard() {
        if (currentTaskId) {
            fetch(`/api/tasks/${currentTaskId}`)
                .then(response => response.json())
                .then(data => {
                    const task = data.task;
                    const taskCard = document.querySelector(`[data-task-id="${currentTaskId}"]`);

                    if (taskCard && task) {
                        const titleElement = taskCard.querySelector('h4');
                        if (titleElement) {
                            titleElement.textContent = task.title;
                        }

                        const descriptionElement = taskCard.querySelector('p.line-clamp-2');
                        if (descriptionElement) {
                            if (task.description) {
                                descriptionElement.textContent = task.description.substring(0, 80) + (task.description.length > 80 ? '...' : '');
                                descriptionElement.style.display = 'block';
                            } else {
                                descriptionElement.style.display = 'none';
                            }
                        }

                        const assigneesContainer = taskCard.querySelector('.flex.-space-x-1');
                        if (assigneesContainer) {
                            updateTaskCardAssignees(assigneesContainer, task.assignes || []);
                        }

                        const dateElement = taskCard.querySelector('.fa-calendar-alt')?.parentElement;
                        if (dateElement) {
                            const spanElement = dateElement.querySelector('span');
                            if (task.date_limite && spanElement) {
                                const date = new Date(task.date_limite);
                                spanElement.textContent = date.toLocaleDateString('fr-FR');
                                dateElement.style.display = 'flex';
                            } else if (spanElement) {
                                dateElement.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => {
                });
        }
    }

    function updateTaskCardAssignees(container, assignees) {
        container.innerHTML = '';

        if (assignees && assignees.length > 0) {
            assignees.slice(0, 3).forEach(assignee => {
                const assigneeDiv = document.createElement('div');
                assigneeDiv.className = 'w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-white dark:border-gray-800';
                assigneeDiv.title = assignee.name;
                assigneeDiv.textContent = assignee.name.charAt(0).toUpperCase();
                container.appendChild(assigneeDiv);
            });

            if (assignees.length > 3) {
                const moreDiv = document.createElement('div');
                moreDiv.className = 'w-6 h-6 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 text-xs font-bold border-2 border-white dark:border-gray-800';
                moreDiv.textContent = `+${assignees.length - 3}`;
                container.appendChild(moreDiv);
            }
        }
    }

    function deleteCurrentTask() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ? Cette action est irréversible.')) {
            const taskElement = document.querySelector(`[data-task-id="${currentTaskId}"]`);
            const columnElement = taskElement ? taskElement.closest('.droppable-zone') : null;
            const columnId = columnElement ? columnElement.dataset.colonne : null;

            fetch(`/api/tasks/${currentTaskId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeTaskModal();
                    showNotification('Succès', 'Tâche supprimée avec succès', 'success');
                    // Remove from UI
                    const taskRow = document.querySelector(`[data-task-id="${currentTaskId}"]`);
                    if (taskRow) {
                        taskRow.style.transform = 'scale(0.8)';
                        taskRow.style.opacity = '0';
                        setTimeout(() => {
                            taskRow.remove();
                            if (columnId) {
                                updateEmptyColumnDisplay(columnId);
                            }
                        }, 300);
                    }
                } else {
                    showNotification('Erreur', data.message || 'Erreur lors de la suppression', 'error');
                }
            });
        }
    }

    // Fonction pour afficher les notifications (toaster amélioré)
    function showNotification(title, message, type = 'info') {
        try {

            // Vérifier que les paramètres sont valides
            if (!title || !message) {
                return;
            }

            // Créer un conteneur pour les notifications s'il n'existe pas
            let notificationContainer = document.getElementById('notification-container');
            if (!notificationContainer) {
                notificationContainer = document.createElement('div');
                notificationContainer.id = 'notification-container';
                // Styles CSS complets et forcés pour le conteneur
                notificationContainer.style.cssText = `
                    position: fixed !important;
                    bottom: 25px !important;
                    right: 200px !important;
                    z-index: 99999 !important;
                    display: flex !important;
                    flex-direction: column !important;
                    gap: 12px !important;
                    pointer-events: none !important;
                `;
                document.body.appendChild(notificationContainer);
            }

            // Supprimer les notifications existantes pour éviter l'accumulation
            const existingNotifications = notificationContainer.querySelectorAll('.toast-notification');
            if (existingNotifications.length > 3) {
                existingNotifications[0].remove();
            }

            // Créer l'élément de notification
            const notification = document.createElement('div');
            notification.className = 'toast-notification';

            // Définir les couleurs selon le type AVANT d'appliquer les styles
            let backgroundColor, iconClass;
            switch(type) {
                case 'success':
                    backgroundColor = '#10b981'; // Vert solide
                    iconClass = 'fa-check-circle';
                    break;
                case 'error':
                    backgroundColor = '#ef4444'; // Rouge solide
                    iconClass = 'fa-exclamation-circle';
                    break;
                case 'warning':
                    backgroundColor = '#f59e0b'; // Orange solide
                    iconClass = 'fa-exclamation-triangle';
                    break;
                default:
                    backgroundColor = '#3b82f6'; // Bleu solide
                    iconClass = 'fa-info-circle';
            }

            // Styles CSS complets et forcés pour la notification
            notification.style.cssText = `
                background: ${backgroundColor} !important;
                color: white !important;
                padding: 16px !important;
                border-radius: 12px !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
                transform: translateX(100%) !important;
                transition: all 0.3s ease !important;
                min-width: 320px !important;
                max-width: 400px !important;
                font-size: 14px !important;
                font-weight: 500 !important;
                margin: 0 !important;
                position: relative !important;
                pointer-events: auto !important;
                border: none !important;
                outline: none !important;
            `;


            // Échapper les caractères HTML pour éviter les injections
            const safeTitle = title.replace(/[<>&"']/g, function(m) {
                return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;',"'":'&#39;'}[m];
            });
            const safeMessage = message.replace(/[<>&"']/g, function(m) {
                return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;',"'":'&#39;'}[m];
            });

            notification.innerHTML = `
                <div style="display: flex !important; align-items: center !important; gap: 12px !important;">
                    <div style="flex-shrink: 0 !important;">
                        <i class="fas ${iconClass}" style="font-size: 20px !important; color: white !important;"></i>
                    </div>
                    <div style="flex: 1 !important;">
                        <h4 style="font-weight: 600 !important; margin: 0 0 4px 0 !important; color: white !important;">${safeTitle}</h4>
                        <p style="margin: 0 !important; opacity: 0.9 !important; font-size: 13px !important; color: white !important;">${safeMessage}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="color: white !important; opacity: 0.8 !important; background: none !important; border: none !important; cursor: pointer !important; padding: 4px !important; font-size: 16px !important;">
                        <i class="fas fa-times" style="color: white !important;"></i>
                    </button>
                </div>
            `;

            notificationContainer.appendChild(notification);

            // Animer l'entrée
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Auto-supprimer après 5 secondes
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);

        } catch (error) {
        }
    }

    // Fonction de test pour le toaster
    function testNotification() {
        showNotification('Test', 'Le toaster fonctionne correctement !', 'success');
    }

    // Test automatique au chargement (commenté par défaut)
    // setTimeout(() => testNotification(), 1000);

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('task-modal').classList.contains('hidden')) {
            closeTaskModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('task-modal').addEventListener('click', (e) => {
        if (e.target.id === 'task-modal') {
            closeTaskModal();
        }
    });

    // Close modal with close button
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
    // Drag & drop des listes (colonnes)
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
    // Initialisation des nouvelles fonctionnalités
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

        // Gestion des boutons de menu
        const menuBtns = document.querySelectorAll('.column-menu-btn');

        menuBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;

                // Ferme tous les autres menus
                document.querySelectorAll('.column-menu').forEach(m => {
                    if (m !== menu) m.classList.add('hidden');
                });

                // Toggle ce menu
                menu.classList.toggle('hidden');
            });
        });

        // Gestion des boutons de couleur
        document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const columnId = this.dataset.columnId;
                openColorModal(columnId);
            });
        });

        // Gestion des boutons de suppression
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

        // Fermer les menus quand on clique ailleurs
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
        // Actions rapides pour les tâches
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

                // Mise à jour dynamique sans rechargement de page
                const column = document.querySelector(`[data-colonne="${columnId}"]`);
                if (column) {
                    // Retirer le message "aucune tâche" avant d'ajouter la nouvelle tâche
                    const emptyMessage = column.querySelector('.empty-state');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }

                    if (data.html) {
                        column.insertAdjacentHTML('beforeend', data.html);
                    } else if (data.task) {
                        // Créer dynamiquement la nouvelle carte de tâche
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

                // Supprimer la colonne du DOM
                const columnElement = document.querySelector(`[data-list-task-id="${columnId}"]`);

                if (columnElement) {
                    columnElement.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    columnElement.style.transform = 'scale(0.8)';
                    columnElement.style.opacity = '0';

                    setTimeout(() => {
                        columnElement.remove();
                    }, 300);
                } else {
                    // Essayer avec l'autre sélecteur
                    const altColumnElement = document.querySelector(`[data-list-id="${columnId}"]`);
                    if (altColumnElement) {
                        altColumnElement.remove();
                    }
                }

                // Fermer tous les menus
                document.querySelectorAll('.column-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        })
        .catch(error => {
            showNotification('Erreur', 'Erreur lors de la suppression de la colonne: ' + error.message, 'error');
        });
    }
    // Edition inline du titre de liste
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

    <script>
    // Variables globales pour le modal de couleur
    let currentColumnId = null;
    let selectedColor = null;

    // Fonction pour ouvrir le modal de couleur
    function openColorModal(columnId) {
        const modal = document.getElementById('color-modal');
        if (modal) {
            modal.classList.remove('hidden');
            currentColumnId = columnId;
            selectedColor = null;
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('ring-4', 'ring-blue-300');
            });
        } else {
        }
    }

    // Fonction pour fermer le modal de couleur
    function closeColorModal() {
        const modal = document.getElementById('color-modal');
        if (modal) {
            modal.classList.add('hidden');
        } else {
        }
        currentColumnId = null;
        selectedColor = null;
        document.querySelectorAll('.column-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Fonction pour afficher la prévisualisation de la couleur
    function showColorPreview(color, colorName) {
        const preview = document.getElementById('color-preview');
        const previewBox = document.getElementById('preview-color-box');
        const previewName = document.getElementById('preview-color-name');

        if (preview && previewBox && previewName) {
            preview.classList.remove('hidden');

            // Définir la couleur de la boîte de prévisualisation
            previewBox.style.backgroundColor = color;
            previewBox.className = 'w-8 h-8 rounded-lg';
            previewName.textContent = colorName;
        }
    }

    // Fonction pour fermer le modal de couleur (alias pour éviter les conflits)
    window.closeColorModal = closeColorModal;
    window.openColorModal = openColorModal;

    // Fonction pour appliquer la couleur
    function applyColumnColor(columnId, color) {
        // Trouver la colonne avec le bon sélecteur
        const columnElement = document.querySelector(`[data-list-task-id="${columnId}"]`);
        if (columnElement) {

            // Supprimer les anciennes classes de couleur
            columnElement.className = columnElement.className.replace(/border-\w+-\d+/g, '');
            columnElement.className = columnElement.className.replace(/bg-\w+-\d+/g, '');
            columnElement.className = columnElement.className.replace(/dark:border-\w+-\d+/g, '');
            columnElement.className = columnElement.className.replace(/dark:bg-\w+-\d+/g, '');

            // Appliquer la couleur personnalisée
            columnElement.style.borderColor = color;
            columnElement.style.backgroundColor = color + '20'; // Ajouter transparence
            columnElement.setAttribute('data-color', color);

            // Sauvegarder en base de données
            fetch(`/listTask/update-color/${columnId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ color: color })
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
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
        } else {
        }

        // Fermer le modal manuellement
        const modal = document.getElementById('color-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
        currentColumnId = null;
        selectedColor = null;

        // Masquer la prévisualisation
        const preview = document.getElementById('color-preview');
        if (preview) {
            preview.classList.add('hidden');
        }

        document.querySelectorAll('.column-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    // Initialisation des gestionnaires d'événements pour le modal de couleur
    document.addEventListener('DOMContentLoaded', function() {

        // Gestionnaires pour fermer le modal
        const closeBtn = document.getElementById('close-color-modal');
        const cancelBtn = document.getElementById('cancel-color-btn');
        const modal = document.getElementById('color-modal');
        const applyBtn = document.getElementById('apply-color-btn');

        if (closeBtn) {
            closeBtn.onclick = function() {
                const modal = document.getElementById('color-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
                currentColumnId = null;
                selectedColor = null;
                document.querySelectorAll('.column-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            };
        }

        if (cancelBtn) {
            cancelBtn.onclick = function() {
                const modal = document.getElementById('color-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
                currentColumnId = null;
                selectedColor = null;
                document.querySelectorAll('.column-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            };
        }

        if (modal) {
            modal.onclick = function(e) {
                if (e.target.id === 'color-modal') {
                    const modal = document.getElementById('color-modal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                    currentColumnId = null;
                    selectedColor = null;
                    document.querySelectorAll('.column-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            };
        }



        // Color picker personnalisé
        const customColorPicker = document.getElementById('custom-color-picker');
        const applyCustomColorBtn = document.getElementById('apply-custom-color');

        if (customColorPicker && applyCustomColorBtn) {
            applyCustomColorBtn.onclick = function() {
                const customColor = customColorPicker.value;

                // Retirer la sélection des couleurs prédéfinies
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('ring-4', 'ring-blue-300');
                });

                selectedColor = customColor;
                showColorPreview(customColor, 'Personnalisée');
            };
        }

        // Appliquer la couleur
        if (applyBtn) {
            applyBtn.onclick = function() {
                if (selectedColor && currentColumnId) {
                    applyColumnColor(currentColumnId, selectedColor);
                }
            };
        }

        // Touche Escape pour fermer
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('color-modal');
                if (modal) {
                    modal.classList.add('hidden');
                }
                currentColumnId = null;
                selectedColor = null;
                document.querySelectorAll('.column-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });


        // Appliquer les couleurs sauvegardées au chargement de la page
        applySavedColors();
    });

    // Fonction pour appliquer les couleurs sauvegardées
    function applySavedColors() {
        document.querySelectorAll('[data-list-task-id]').forEach(column => {
            const savedColor = column.getAttribute('data-color');
            if (savedColor) {

                // Supprimer d'abord toutes les anciennes classes de couleur
                column.className = column.className.replace(/border-\w+-\d+/g, '');
                column.className = column.className.replace(/bg-\w+-\d+/g, '');
                column.className = column.className.replace(/dark:border-\w+-\d+/g, '');
                column.className = column.className.replace(/dark:bg-\w+-\d+/g, '');

                // Réinitialiser les styles inline
                column.style.borderColor = '';
                column.style.backgroundColor = '';

                if (savedColor.startsWith('#')) {
                    // Couleur hexadécimale personnalisée
                    column.style.borderColor = savedColor;
                    column.style.backgroundColor = savedColor + '20';
                } else if (savedColor) {
                    // Couleur prédéfinie (nom)
                    column.classList.add(`border-${savedColor}-400`, `dark:border-${savedColor}-500`);
                    column.classList.add(`bg-${savedColor}-50`, `dark:bg-${savedColor}-900/20`);
                }
            }
        });
    }
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

    <script>
    // Fonction de test pour le toaster (accessible dans la console)
    function testNotificationSystem() {

        // Meilleure vérification de FontAwesome (CSS)
        const hasFA = document.querySelector('link[href*="font-awesome"]') !== null;

        // Test simple
        showNotification('Test', 'Le toaster fonctionne !', 'info');

        // Test avec tous les types
        setTimeout(() => showNotification('Succès', 'Notification de succès en bas à droite', 'success'), 1000);
        setTimeout(() => showNotification('Attention', 'Notification d\'avertissement avec couleur', 'warning'), 2000);
        setTimeout(() => showNotification('Erreur', 'Notification d\'erreur colorée', 'error'), 3000);

        return 'Test lancé - vérifiez les notifications en bas à droite de l\'écran';
    }

    // Fonction pour nettoyer toutes les notifications
    function clearAllNotifications() {
        const container = document.getElementById('notification-container');
        if (container) {
            const notifications = container.querySelectorAll('.toast-notification');
            notifications.forEach(notification => {
                notification.remove();
            });
        } else {
        }
    }

    // Test automatique avec positionnement correct
    function testPositioning() {
        showNotification('Position Test', 'Cette notification devrait apparaître en bas à droite avec une couleur bleue', 'info');
        setTimeout(() => {
            showNotification('Couleur Test', 'Cette notification devrait être verte', 'success');

            // Vérification après création
            setTimeout(() => {
                const container = document.getElementById('notification-container');
                const notifications = document.querySelectorAll('.toast-notification');


                if (notifications.length > 0) {
                    const notif = notifications[0];
                }
            }, 200);
        }, 1500);
    }

    // Test complet des couleurs et icônes
    function testFullSystem() {
        showNotification('Test Info', 'Notification bleue avec icône info', 'info');
        setTimeout(() => showNotification('Test Succès', 'Notification verte avec icône check', 'success'), 1000);
        setTimeout(() => showNotification('Test Avertissement', 'Notification orange avec icône warning', 'warning'), 2000);
        setTimeout(() => showNotification('Test Erreur', 'Notification rouge avec icône erreur', 'error'), 3000);
        return 'Tests lancés avec toutes les couleurs et icônes';
    }

    // Test simple pour vérifier l'exécution
    function simpleTest() {
        try {
            showNotification('Test Simple', 'Message de test', 'info');
        } catch (error) {
        }
        return 'Test simple terminé';
    }

    // Test simple et direct
    function quickTest() {

        // Test avec la fonction directement
        try {
            // Créer le conteneur manuellement
            let container = document.getElementById('notification-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'notification-container';
                container.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px;';
                document.body.appendChild(container);
            }

            // Créer une notification simple
            const notif = document.createElement('div');
            notif.style.cssText = `
                background: linear-gradient(to right, #10b981, #059669);
                color: white;
                padding: 16px;
                border-radius: 12px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                min-width: 300px;
                max-width: 400px;
                font-weight: 500;
            `;
            notif.innerHTML = `
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                    <div>
                        <h4 style="margin: 0 0 4px 0; font-weight: 600;">Test Direct</h4>
                        <p style="margin: 0; opacity: 0.9; font-size: 13px;">Notification créée directement</p>
                    </div>
                </div>
            `;

            container.appendChild(notif);

            // Auto-supprimer après 5 secondes
            setTimeout(() => {
                if (notif.parentNode) {
                    notif.remove();
                }
            }, 5000);

        } catch (error) {
        }

        return 'Test direct exécuté';
    }

    // Exposer les fonctions pour le débogage
    window.testNotificationSystem = testNotificationSystem;
    window.clearAllNotifications = clearAllNotifications;
    window.testPositioning = testPositioning;
    window.testFullSystem = testFullSystem;
    window.simpleTest = simpleTest;
    window.quickTest = quickTest;
    window.showNotification = showNotification;
    </script>
</x-app-layout>
