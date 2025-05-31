<div class="p-6">
  <!-- Filtres et recherche -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <div class="flex flex-wrap gap-4 items-center">
      <div class="flex-1 min-w-64">
        <input type="text" id="search-tasks" placeholder="Rechercher des tâches..."
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
      </div>

      <select id="filter-status"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Tous les statuts</option>
        @foreach($colonnes as $colonne)
      <option value="{{ $colonne->id }}">{{ $colonne->nom }}</option>
    @endforeach
      </select>

      <select id="filter-priority"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Toutes les priorités</option>
        <option value="haute">Haute</option>
        <option value="moyenne">Moyenne</option>
        <option value="basse">Basse</option>
      </select>

      <select id="filter-assignee"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <option value="">Tous les assignés</option>
        @foreach($projet->membres as $membre)
      <option value="{{ $membre->id }}">{{ $membre->name }}</option>
    @endforeach
      </select>
    </div>
  </div>

  <!-- Liste des tâches -->
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Tâche
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Statut
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Priorité
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Assigné à
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Date limite
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="tasks-table-body">
        @foreach($tasks as $task)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 task-row" data-task-id="{{ $task->id }}"
          data-status="{{ $task->colonne_id }}" data-priority="{{ $task->priorite }}"
          data-assignees="{{ $task->assignes->pluck('id')->implode(',') }}">
          <td class="px-6 py-4 whitespace-nowrap">
          <div class="flex items-center">
            <div>
            <div class="text-sm font-medium text-gray-900 dark:text-white">
              {{ $task->titre }}
            </div>
            @if($task->description)
          <div class="text-sm text-gray-500 dark:text-gray-400">
          {{ Str::limit($task->description, 60) }}
          </div>
        @endif
            @if($task->categorie)
          <span
          class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs mt-1">
          {{ $task->categorie }}
          </span>
        @endif
            </div>
          </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
          <span
            class="px-2 py-1 text-xs font-semibold rounded-full {{ $task->colonne->couleur ?? 'bg-gray-100 text-gray-800' }}">
            {{ $task->colonne->nom }}
          </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
          @if($task->priorite)
        <span
          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
          <span
          class="w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full border-2 border-white dark:border-gray-700 flex items-center justify-center text-xs">
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
          <button onclick="openTaskModal({!! $task->id !!})"
            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
            Modifier
          </button>
          <button onclick="deleteTask({!! $task->id !!})"
            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
            Supprimer
          </button>
          </td>
        </tr>
    @endforeach
      </tbody>
    </table>
  </div>
</div>

<script>
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

    const rows = document.querySelectorAll('.task-row');

    rows.forEach(row => {
      const title = row.querySelector('.text-sm.font-medium').textContent.toLowerCase();
      const status = row.dataset.status;
      const priority = row.dataset.priority;
      const assignees = row.dataset.assignees.split(',');

      const matchesSearch = title.includes(searchTerm);
      const matchesStatus = !statusFilter || status === statusFilter;
      const matchesPriority = !priorityFilter || priority === priorityFilter;
      const matchesAssignee = !assigneeFilter || assignees.includes(assigneeFilter);

      if (matchesSearch && matchesStatus && matchesPriority && matchesAssignee) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
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
            document.querySelector(`[data-task-id="${taskId}"]`).remove();
            showNotification('Succès', 'Tâche supprimée avec succès', 'success');
          } else {
            showNotification('Erreur', 'Erreur lors de la suppression', 'error');
          }
        });
    }
  }
</script>