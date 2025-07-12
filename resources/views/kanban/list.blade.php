<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
  <!-- Header avec titre et actions -->
  <div class="mb-8">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $projet->nom ?? 'Toutes les tâches' }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Gérez vos tâches et suivez leur progression</p>
      </div>
      <div class="flex items-center gap-3">
        <button onclick="openCreateTaskModal()" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Nouvelle tâche
        </button>
        <button onclick="toggleViewMode()" id="view-toggle" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Filtres et recherche -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
      <!-- Recherche -->
      <div class="lg:col-span-2">
        <div class="relative">
          <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <input type="text" id="search-tasks" placeholder="Rechercher des tâches..."
            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
      </div>

      <!-- Filtres -->
      <select id="filter-status"
        class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">Tous les statuts</option>
        @foreach($colonnes as $colonne)
          <option value="{{ $colonne->id }}">{{ $colonne->nom }}</option>
        @endforeach
      </select>

      <select id="filter-priority"
        class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">Toutes les priorités</option>
        <option value="haute">Haute</option>
        <option value="moyenne">Moyenne</option>
        <option value="basse">Basse</option>
      </select>

      <select id="filter-assignee"
        class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">Tous les assignés</option>
        @foreach($projet->membres as $membre)
          <option value="{{ $membre->id }}">{{ $membre->name }}</option>
        @endforeach
      </select>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
      <div class="text-center">
        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $tasks->count() }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tasks->where('priorite', 'haute')->count() }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400">Priorité haute</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tasks->where('date_limite', '<', now())->count() }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400">En retard</div>
      </div>
      <div class="text-center">
        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $tasks->whereNull('assignes')->count() }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400">Non assignées</div>
      </div>
    </div>
  </div>

  <!-- Vue des tâches -->
  <div id="tasks-container">
    <!-- Vue en cartes (par défaut) -->
    <div id="card-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($tasks as $task)
        <div class="task-card bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 cursor-pointer" 
             data-task-id="{{ $task->id }}" 
             data-status="{{ $task->colonne_id }}" 
             data-priority="{{ $task->priorite }}"
             data-assignees="{{ $task->assignes->pluck('id')->implode(',') }}"
             onclick="openTaskModal({{ $task->id }})">
          
          <!-- Header de la carte -->
          <div class="p-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between mb-2">
              <h3 class="font-semibold text-gray-900 dark:text-white text-sm line-clamp-2">{{ $task->titre }}</h3>
              <div class="flex items-center gap-1">
                @if($task->priorite === 'haute')
                  <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                @elseif($task->priorite === 'moyenne')
                  <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                @elseif($task->priorite === 'basse')
                  <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                @endif
              </div>
            </div>
            
            @if($task->description)
              <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($task->description, 80) }}</p>
            @endif
          </div>

          <!-- Tags -->
          @if($task->categorie)
            <div class="px-4 py-2">
              <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs">
                {{ $task->categorie }}
              </span>
            </div>
          @endif

          <!-- Footer de la carte -->
          <div class="p-4">
            <!-- Statut -->
            <div class="flex items-center justify-between mb-3">
              <span class="px-2 py-1 text-xs font-medium rounded-full {{ $task->colonne->couleur ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                {{ $task->colonne->nom }}
              </span>
              
              @if($task->date_limite)
                <span class="text-xs {{ $task->date_limite < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                  {{ \Carbon\Carbon::parse($task->date_limite)->format('d/m') }}
                </span>
              @endif
            </div>

            <!-- Assignés -->
            <div class="flex items-center justify-between">
              <div class="flex -space-x-2">
                @forelse($task->assignes->take(3) as $assigne)
                  <img src="{{ $assigne->avatar ?? '/default-avatar.png' }}" alt="{{ $assigne->name }}"
                    class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-700" title="{{ $assigne->name }}">
                @empty
                  <div class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700 flex items-center justify-center">
                    <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                  </div>
                @endforelse
                @if($task->assignes->count() > 3)
                  <span class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700 flex items-center justify-center text-xs">
                    +{{ $task->assignes->count() - 3 }}
                  </span>
                @endif
              </div>
              
              <!-- Actions rapides -->
              <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="event.stopPropagation(); quickEditTask({{ $task->id }})" class="p-1 text-gray-400 hover:text-blue-500">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Vue en tableau (cachée par défaut) -->
    <div id="table-view" class="hidden">
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Tâche
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Statut
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Priorité
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Assigné à
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Date limite
                </th>
                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="tasks-table-body">
              @foreach($tasks as $task)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 task-row transition-colors" 
                    data-task-id="{{ $task->id }}"
                    data-status="{{ $task->colonne_id }}" 
                    data-priority="{{ $task->priorite }}"
                    data-assignees="{{ $task->assignes->pluck('id')->implode(',') }}">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white cursor-pointer hover:text-blue-600" onclick="openTaskModal({{ $task->id }})">
                          {{ $task->titre }}
                        </div>
                        @if($task->description)
                          <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ Str::limit($task->description, 60) }}
                          </div>
                        @endif
                        @if($task->categorie)
                          <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs mt-1">
                            {{ $task->categorie }}
                          </span>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $task->colonne->couleur ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' }}">
                      {{ $task->colonne->nom }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    @if($task->priorite)
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $task->priorite === 'haute' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                        {{ $task->priorite === 'moyenne' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                        {{ $task->priorite === 'basse' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                        {{ ucfirst($task->priorite) }}
                      </span>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex -space-x-1">
                      @forelse($task->assignes->take(3) as $assigne)
                        <img src="{{ $assigne->avatar ?? '/default-avatar.png' }}" alt="{{ $assigne->name }}"
                          class="w-8 h-8 rounded-full border-2 border-white dark:border-gray-700" title="{{ $assigne->name }}">
                      @empty
                        <span class="text-gray-400 text-sm">Non assigné</span>
                      @endforelse
                      @if($task->assignes->count() > 3)
                        <span class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700 flex items-center justify-center text-xs">
                          +{{ $task->assignes->count() - 3 }}
                        </span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    @if($task->date_limite)
                      <span class="{{ $task->date_limite < now() ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                        {{ \Carbon\Carbon::parse($task->date_limite)->format('d/m/Y') }}
                      </span>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center gap-2">
                      <button onclick="openTaskModal({{ $task->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                      <button onclick="deleteTask({{ $task->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Message si aucune tâche -->
  @if($tasks->isEmpty())
    <div class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucune tâche</h3>
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer votre première tâche.</p>
      <div class="mt-6">
        <button onclick="openCreateTaskModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Nouvelle tâche
        </button>
      </div>
    </div>
  @endif
</div>

<script>
let currentView = 'card'; // 'card' ou 'table'

// Filtrage en temps réel
document.getElementById('search-tasks').addEventListener('input', filterTasks);
document.getElementById('filter-status').addEventListener('change', filterTasks);
document.getElementById('filter-priority').addEventListener('change', filterTasks);
document.getElementById('filter-assignee').addEventListener('change', filterTasks);

function filterTasks() {
    const searchTerm = document.getElementById('search-tasks').value.toLowerCase();
    const statusFilter = document.getElementById('filter-status').value;
    const priorityFilter = document.getElementById('filter-priority').value;
    const assigneeFilter = document.getElementById('filter-assignee').value;

    const elements = currentView === 'card' 
        ? document.querySelectorAll('.task-card')
        : document.querySelectorAll('.task-row');

    elements.forEach(element => {
        const title = element.querySelector('.text-sm.font-medium, .font-semibold')?.textContent.toLowerCase() || '';
        const status = element.dataset.status;
        const priority = element.dataset.priority;
        const assignees = element.dataset.assignees.split(',');

        const matchesSearch = title.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesPriority = !priorityFilter || priority === priorityFilter;
        const matchesAssignee = !assigneeFilter || assignees.includes(assigneeFilter);

        if (matchesSearch && matchesStatus && matchesPriority && matchesAssignee) {
            element.style.display = '';
        } else {
            element.style.display = 'none';
        }
    });
}

function toggleViewMode() {
    const cardView = document.getElementById('card-view');
    const tableView = document.getElementById('table-view');
    const toggleButton = document.getElementById('view-toggle');

    if (currentView === 'card') {
        cardView.classList.add('hidden');
        tableView.classList.remove('hidden');
        currentView = 'table';
        toggleButton.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
        `;
    } else {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
        currentView = 'card';
        toggleButton.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
        `;
    }
}

function deleteTask(taskId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
        fetch(`/api/tasks/${taskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.querySelector(`[data-task-id="${taskId}"]`);
                if (element) {
                    element.remove();
                    showNotification('Succès', 'Tâche supprimée avec succès', 'success');
                }
            } else {
                showNotification('Erreur', 'Erreur lors de la suppression', 'error');
            }
        });
    }
}

function openCreateTaskModal() {
    // Cette fonction sera implémentée pour ouvrir une modal de création de tâche
    showNotification('Info', 'Fonctionnalité de création de tâche à implémenter', 'info');
}

function quickEditTask(taskId) {
    // Cette fonction sera implémentée pour l'édition rapide
    openTaskModal(taskId);
}

// Ajouter la classe group aux cartes pour les effets hover
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.task-card');
    cards.forEach(card => {
        card.classList.add('group');
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>