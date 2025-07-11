@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Liste des tâches - {{ $projet->name }}</h3>
                    <div class="btn-group">
                        <a href="{{ route('tasks.list', $projet) }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> Liste
                        </a>
                        <a href="{{ route('tasks.calendar', $projet) }}" class="btn btn-outline-primary">
                            <i class="fas fa-calendar"></i> Calendrier
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Filtres et recherche -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('tasks.list', $projet) }}" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Rechercher..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="category" class="form-select">
                                        <option value="">Toutes catégories</option>
                                        <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="développement" {{ request('category') == 'développement' ? 'selected' : '' }}>Développement</option>
                                        <option value="communication" {{ request('category') == 'communication' ? 'selected' : '' }}>Communication</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="priority" class="form-select">
                                        <option value="">Toutes priorités</option>
                                        <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>Basse</option>
                                        <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                                        <option value="élevée" {{ request('priority') == 'élevée' ? 'selected' : '' }}>Élevée</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="assigned_user" class="form-select">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($projectUsers as $user)
                                            <option value="{{ $user->id }}" {{ request('assigned_user') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="sort_by" class="form-select">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                                        <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priorité</option>
                                        <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Date limite</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('tasks.list', $projet) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Liste des tâches -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Catégorie</th>
                                    <th>Priorité</th>
                                    <th>Liste</th>
                                    <th>Assigné à</th>
                                    <th>Date limite</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <strong>{{ $task->title }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ Str::limit($task->description, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($task->category)
                                                <span class="badge bg-info">{{ $task->category }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'basse' => 'success',
                                                    'moyenne' => 'warning',
                                                    'élevée' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $priorityColors[$task->priority] ?? 'secondary' }}">
                                                {{ $task->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $task->listTask->title }}</span>
                                        </td>
                                        <td>
                                            @if($task->users->count() > 0)
                                                @foreach($task->users as $user)
                                                    <span class="badge bg-primary me-1">{{ $user->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Non assigné</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->due_date)
                                                <span class="text-{{ Carbon\Carbon::parse($task->due_date)->isPast() ? 'danger' : 'success' }}">
                                                    {{ Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">Non définie</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" onclick="editTask({{ $task->id }})"
                                                        title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" onclick="deleteTask({{ $task->id }})"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            Aucune tâche trouvée.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $tasks->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour édition rapide -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la tâche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" id="taskId">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="taskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="taskCategory" class="form-label">Catégorie</label>
                            <select class="form-select" id="taskCategory">
                                <option value="">Aucune</option>
                                <option value="marketing">Marketing</option>
                                <option value="développement">Développement</option>
                                <option value="communication">Communication</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="taskPriority" class="form-label">Priorité</label>
                            <select class="form-select" id="taskPriority">
                                <option value="basse">Basse</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="élevée">Élevée</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="taskDueDate" class="form-label">Date limite</label>
                        <input type="date" class="form-control" id="taskDueDate">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveTask()">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
function editTask(taskId) {
    // Récupérer les données de la tâche et pré-remplir le modal
    // Implémenter selon vos besoins
    $('#editTaskModal').modal('show');
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
}
</script>
@endsection