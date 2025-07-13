@extends('layouts.app')

@section('content')
<div class="h-screen w-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white overflow-hidden flex flex-col">
    <!-- Header avec dégradé -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 shadow-2xl flex-shrink-0">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $projet->name }}</h1>
                        <p class="text-blue-100 mt-1">Gestion des tâches</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('tasks.list', $projet) }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span>Liste</span>
                    </a>
                    <a href="{{ route('tasks.calendar', $projet) }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg transition-all duration-200 flex items-center space-x-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Calendrier</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 w-full px-4 sm:px-6 lg:px-8 py-8 overflow-hidden">
        <div class="bg-gray-800/50 backdrop-blur-xl rounded-2xl shadow-2xl border border-gray-700/50 overflow-hidden h-full flex flex-col">
            <!-- Filtres et recherche -->
            <div class="p-6 border-b border-gray-700/50 flex-shrink-0">
                <form method="GET" action="{{ route('tasks.list', $projet) }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <!-- Recherche -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Rechercher une tâche..." 
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Catégorie -->
                        <div>
                            <select name="category" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Toutes catégories</option>
                                <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="développement" {{ request('category') == 'développement' ? 'selected' : '' }}>Développement</option>
                                <option value="communication" {{ request('category') == 'communication' ? 'selected' : '' }}>Communication</option>
                            </select>
                        </div>
                        
                        <!-- Priorité -->
                        <div>
                            <select name="priority" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Toutes priorités</option>
                                <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                                <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="élevée" {{ request('priority') == 'élevée' ? 'selected' : '' }}>Élevée</option>
                            </select>
                        </div>
                        
                        <!-- Utilisateur -->
                        <div>
                            <select name="assigned_user" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
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
                            <select name="sort_by" class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priorité</option>
                                <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Date limite</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 px-6 py-3 rounded-xl text-white font-medium transition-all duration-200 flex items-center space-x-2 shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Rechercher</span>
                        </button>
                        <a href="{{ route('tasks.list', $projet) }}" class="bg-gray-700/50 hover:bg-gray-600/50 px-6 py-3 rounded-xl text-white font-medium transition-all duration-200 flex items-center space-x-2">
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
                        <tr class="bg-gray-700/50 backdrop-blur-sm border-b border-gray-700/50">
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Titre</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Description</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Catégorie</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Priorité</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Liste</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Assigné à</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Date limite</th>
                            <th class="text-left py-4 px-6 text-gray-200 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr class="border-b border-gray-700/30 hover:bg-gray-700/20 transition-all duration-200">
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-white">{{ $task->title }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-gray-300 max-w-xs">
                                        {{ Str::limit($task->description, 50) }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->category)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                            {{ $task->category }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $priorityStyles = [
                                            'basse' => 'bg-green-500/20 text-green-300 border-green-500/30',
                                            'moyenne' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30',
                                            'élevée' => 'bg-red-500/20 text-red-300 border-red-500/30'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $priorityStyles[$task->priority] ?? 'bg-gray-500/20 text-gray-300 border-gray-500/30' }} border">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                        {{ $task->listTask->title }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->users->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($task->users as $user)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                                    {{ $user->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Non assigné</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($task->due_date)
                                        <span class="text-sm {{ Carbon\Carbon::parse($task->due_date)->isPast() ? 'text-red-300' : 'text-green-300' }}">
                                            {{ Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">Non définie</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex space-x-2">
                                        <button onclick="editTask({{ $task->id }})" 
                                                class="p-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 rounded-lg transition-all duration-200 border border-blue-500/30"
                                                title="Modifier">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteTask({{ $task->id }})" 
                                                class="p-2 bg-red-500/20 hover:bg-red-500/30 text-red-300 rounded-lg transition-all duration-200 border border-red-500/30"
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
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <div class="text-gray-400 text-lg">Aucune tâche trouvée</div>
                                        <div class="text-gray-500 text-sm">Essayez de modifier vos filtres de recherche</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tasks->hasPages())
                <div class="px-6 py-4 border-t border-gray-700/50 flex-shrink-0">
                    <div class="flex justify-center">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour édition rapide -->
<div id="editTaskModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 max-w-md w-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-700">
                <h3 class="text-xl font-semibold text-white">Modifier la tâche</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition-colors">
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
                            <label for="taskTitle" class="block text-sm font-medium text-gray-200 mb-2">Titre</label>
                            <input type="text" id="taskTitle" required
                                   class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        
                        <div>
                            <label for="taskDescription" class="block text-sm font-medium text-gray-200 mb-2">Description</label>
                            <textarea id="taskDescription" rows="3"
                                      class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="taskCategory" class="block text-sm font-medium text-gray-200 mb-2">Catégorie</label>
                                <select id="taskCategory"
                                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Aucune</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="développement">Développement</option>
                                    <option value="communication">Communication</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="taskPriority" class="block text-sm font-medium text-gray-200 mb-2">Priorité</label>
                                <select id="taskPriority"
                                        class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="basse">Basse</option>
                                    <option value="moyenne">Moyenne</option>
                                    <option value="élevée">Élevée</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="taskDueDate" class="block text-sm font-medium text-gray-200 mb-2">Date limite</label>
                            <input type="date" id="taskDueDate"
                                   class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end space-x-3 p-6 border-t border-gray-700">
                <button onclick="closeModal()" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-all duration-200">
                    Annuler
                </button>
                <button onclick="saveTask()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function editTask(taskId) {
    document.getElementById('editTaskModal').classList.remove('hidden');
    // Récupérer les données de la tâche et pré-remplir le modal
    // Implémenter selon vos besoins
}

function closeModal() {
    document.getElementById('editTaskModal').classList.add('hidden');
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
        });
    }
}

function saveTask() {
    // Implémenter la sauvegarde des modifications
    // Utiliser les routes existantes pour mettre à jour
    closeModal();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('editTaskModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection