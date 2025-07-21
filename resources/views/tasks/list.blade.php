@extends('layouts.app')

@section('content')
<!-- Sortir complètement du conteneur flex du layout -->
<div class="fixed inset-0 top-16 bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 transition-all duration-300 overflow-auto">
    <!-- Header avec glassmorphism -->
    <div class="sticky top-0 z-30 backdrop-blur-md bg-white/70 dark:bg-slate-900/70 border-b border-slate-200/50 dark:border-slate-700/50 shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <!-- Titre -->
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
                            {{ $projet->name }}
                        </h1>
                        <p class="text-slate-600 dark:text-slate-400">Liste des tâches</p>
                    </div>
                </div>

                <!-- Boutons de navigation -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('tasks.list', $projet) }}" 
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 shadow-sm">
                        <i class="fas fa-list mr-2"></i>
                        Liste
                    </a>
                    <a href="{{ route('tasks.calendar', $projet) }}" 
                       class="inline-flex items-center px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 text-white hover:from-blue-600 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        <i class="fas fa-calendar mr-2"></i>
                        Calendrier
                    </a>

                        <!-- ✅ Bouton Kanban -->
                <a href="{{ route('projet.show', ['projet' => $projet->slug]) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-white/80 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 shadow-sm">
                    <i class="fas fa-columns mr-2"></i>
                    Kanban
                </a>
                </div>
            </div>
        </div>
    </div>


    <!-- Contenu principal -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden flex flex-col">
            <!-- Filtres et recherche -->
            <div class="p-6 border-b border-slate-200/50 dark:border-slate-700/50 flex-shrink-0">
                <form method="GET" action="{{ route('tasks.list', $projet) }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!-- Recherche -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Rechercher une tâche..." 
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Catégorie -->
                        <div>
                            <select name="category" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Toutes catégories</option>
                                <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="développement" {{ request('category') == 'développement' ? 'selected' : '' }}>Développement</option>
                                <option value="communication" {{ request('category') == 'communication' ? 'selected' : '' }}>Communication</option>
                            </select>
                        </div>
                        
                        <!-- Priorité -->
                        <div>
                            <select name="priority" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Toutes priorités</option>
                                <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="élevée" {{ request('priority') == 'élevée' ? 'selected' : '' }}>Élevée</option>
                            </select>
                        </div>
                        
                        <!-- Utilisateur -->
                        <div>
                            <select name="assigned_user" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Tous les utilisateurs</option>
                                @foreach($projectUsers as $user)
                                    <option value="{{ $user->id }}" {{ request('assigned_user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tri -->
                        <div>
                            <select name="sort_by" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priorité</option>
                                <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Date limite</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 px-6 py-3 rounded-xl text-white font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Rechercher</span>
                        </button>
                        <a href="{{ route('tasks.list', $projet) }}" class="bg-slate-200 dark:bg-slate-700/50 hover:bg-slate-300 dark:hover:bg-slate-600/50 px-6 py-3 rounded-xl text-slate-700 dark:text-white font-medium transition-all duration-200 flex items-center space-x-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Réinitialiser</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Liste des tâches -->
            <div class="flex-1 overflow-auto">
                <table class="w-full">
                    <thead class="sticky top-0 z-10">
                        <tr class="bg-slate-100 dark:bg-slate-700/50 backdrop-blur-sm border-b border-slate-200/50 dark:border-slate-700/50">
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Titre</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Description</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Catégorie</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Priorité</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Liste</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Assigné à</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Date limite</th>
                            <th class="text-left py-4 px-6 text-slate-700 dark:text-slate-200 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="border-b border-slate-200/30 dark:border-slate-700/30 hover:bg-slate-50 dark:hover:bg-slate-700/20 transition-all duration-200">
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $task->title }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-slate-600 dark:text-slate-300 max-w-xs">
                                        {{ Str::limit($task->description, 50) }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/30">
                                            {{ $task->category }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $priorityStyles = [
                                            'basse' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800/30',
                                            'moyenne' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800/30',
                                            'élevée' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800/30'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $priorityStyles[$task->priority] ?? 'bg-slate-100 dark:bg-slate-900/30 text-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-800/30' }} border">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800/30">
                                        {{ $task->listTask->title }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->users->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($task->users as $user)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/30">
                                                    {{ $user->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 text-sm">Non assigné</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->due_date)
                                        <span class="text-sm {{ Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                            {{ Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500 text-sm">Non définie</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        <button onclick="editTask({{ $task->id }})" 
                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-all duration-200 border border-blue-200 dark:border-blue-800/30"
                                                title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteTask({{ $task->id }})" 
                                                class="p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-all duration-200 border border-red-200 dark:border-red-800/30"
                                                title="Supprimer">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="flex flex-col items-center space-y-4">
                                        <svg class="h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <div class="text-slate-600 dark:text-slate-400 text-lg">Aucune tâche trouvée</div>
                                        <div class="text-slate-500 dark:text-slate-600 text-sm">Essayez de modifier vos filtres de recherche</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tasks->hasPages())
                <div class="px-6 py-4 border-t border-slate-200/50 dark:border-slate-700/50 flex-shrink-0">
                    <div class="flex justify-center">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour édition rapide -->
<div id="editTaskModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="editTaskModalContent">
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Modifier la tâche</h3>
                <button onclick="closeModal()" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <form id="editTaskForm">
                    <input type="hidden" id="taskId">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="taskTitle" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Titre</label>
                            <input type="text" id="taskTitle" required
                                   class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div>
                            <label for="taskDescription" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Description</label>
                            <textarea id="taskDescription" rows="3"
                                      class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="taskCategory" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Catégorie</label>
                                <select id="taskCategory"
                                        class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Aucune</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="développement">Développement</option>
                                    <option value="communication">Communication</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="taskPriority" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Priorité</label>
                                <select id="taskPriority"
                                        class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="basse">Basse</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="élevée">Élevée</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="taskDueDate" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">Date limite</label>
                            <input type="date" id="taskDueDate"
                                   class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t border-slate-200 dark:border-slate-700">
                <button onclick="closeModal()" class="px-6 py-3 bg-slate-200 dark:bg-slate-600 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-white rounded-xl transition-all duration-200">
                    Annuler
                </button>
                <button onclick="saveTask()" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function editTask(taskId) {
    const modal = document.getElementById('editTaskModal');
    const content = document.getElementById('editTaskModalContent');
    
    // Afficher le modal
    modal.classList.remove('hidden');
    
    // Animation d'ouverture
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Récupérer les données de la tâche
    fetch(`/api/tasks/${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const task = data.task;
                
                // Pré-remplir le formulaire
                document.getElementById('taskId').value = task.id;
                document.getElementById('taskTitle').value = task.title;
                document.getElementById('taskDescription').value = task.description || '';
                document.getElementById('taskCategory').value = task.category || '';
                document.getElementById('taskPriority').value = task.priority || 'basse';
                document.getElementById('taskDueDate').value = task.due_date ? task.due_date.split('T')[0] : '';
            } else {
                alert('Erreur lors du chargement de la tâche');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement de la tâche');
        });
}

function saveTask() {
    const taskId = document.getElementById('taskId').value;
    const title = document.getElementById('taskTitle').value;
    const description = document.getElementById('taskDescription').value;
    const category = document.getElementById('taskCategory').value;
    const priority = document.getElementById('taskPriority').value;
    const dueDate = document.getElementById('taskDueDate').value;
    
    // Validation côté client
    if (!title.trim()) {
        alert('Le titre est requis');
        return;
    }
    
    // Préparer les données (ne pas inclure status/list_task_id)
    const formData = {
        title: title,
        description: description,
        category: category,
        priority: priority,
        due_date: dueDate
    };
    
    // Envoyer la requête
    fetch(`/api/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tâche mise à jour avec succès');
            closeModal();
            location.reload(); // Recharger la page pour voir les changements
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la sauvegarde');
    });
}

function closeModal() {
    const modal = document.getElementById('editTaskModal');
    const content = document.getElementById('editTaskModalContent');
    
    // Animation de fermeture
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function saveTask() {
    const taskId = document.getElementById('taskId').value;
    const title = document.getElementById('taskTitle').value;
    const description = document.getElementById('taskDescription').value;
    const category = document.getElementById('taskCategory').value;
    const priority = document.getElementById('taskPriority').value;
    const dueDate = document.getElementById('taskDueDate').value;
    
    // Validation côté client
    if (!title.trim()) {
        alert('Le titre est requis');
        return;
    }
    
    // Préparer les données
    const formData = {
        title: title,
        description: description,
        category: category,
        priority: priority,
        due_date: dueDate
    };
    
    // Envoyer la requête
    fetch(`/api/tasks/${taskId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tâche mise à jour avec succès');
            closeModal();
            location.reload(); // Recharger la page pour voir les changements
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la sauvegarde');
    });
}

function deleteTask(taskId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
        fetch(`/task/delete/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Erreur: ' + data.message);
            } else {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('editTaskModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Gérer la soumission du formulaire avec Enter
document.getElementById('editTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    saveTask();
});
</script>
@endsection