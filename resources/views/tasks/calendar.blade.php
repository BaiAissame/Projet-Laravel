@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Calendrier des tâches - {{ $projet->name }}</h3>
                        <div class="btn-group">
                            <a href="{{ route('tasks.list', $projet) }}" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> Liste
                            </a>
                            <a href="{{ route('tasks.calendar', $projet) }}" class="btn btn-primary">
                                <i class="fas fa-calendar"></i> Calendrier
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Navigation et vues -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="btn-group me-3">
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => $currentDate->copy()->subDay()->format('Y-m-d')])) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <button class="btn btn-outline-secondary" disabled>
                                    @if($view == 'day')
                                        {{ $currentDate->format('d/m/Y') }}
                                    @elseif($view == 'threeDays')
                                        {{ $currentDate->format('d/m') }} - {{ $currentDate->copy()->addDays(2)->format('d/m/Y') }}
                                    @elseif($view == 'week')
                                        {{ $startDate->format('d/m') }} - {{ $endDate->format('d/m/Y') }}
                                    @else
                                        {{ $currentDate->format('F Y') }}
                                    @endif
                                </button>
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => $currentDate->copy()->addDay()->format('Y-m-d')])) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['date' => now()->format('Y-m-d')])) }}" 
                               class="btn btn-outline-info">
                                Aujourd'hui
                            </a>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group float-end">
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'day'])) }}" 
                                   class="btn {{ $view == 'day' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Jour
                                </a>
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'threeDays'])) }}" 
                                   class="btn {{ $view == 'threeDays' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    3 Jours
                                </a>
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'week'])) }}" 
                                   class="btn {{ $view == 'week' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Semaine
                                </a>
                                <a href="{{ route('tasks.calendar', array_merge(['projet' => $projet], request()->query(), ['view' => 'month'])) }}" 
                                   class="btn {{ $view == 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Mois
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Affichage du calendrier -->
                    @if($view == 'day')
                        @include('tasks.calendar.day')
                    @elseif($view == 'threeDays')
                        @include('tasks.calendar.three-days')
                    @elseif($view == 'week')
                        @include('tasks.calendar.week')
                    @else
                        @include('tasks.calendar.month')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir/éditer une tâche -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalTitle">Détails de la tâche</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="taskModalBody">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="editTaskInModal()">Modifier</button>
            </div>
        </div>
    </div>
</div>

<script>
function showTaskDetails(taskId) {
    // Afficher les détails de la tâche dans le modal
    fetch(`/task/details/${taskId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('taskModalTitle').textContent = data.title;
            document.getElementById('taskModalBody').innerHTML = `
                <div class="mb-3">
                    <strong>Description:</strong>
                    <p>${data.description || 'Aucune description'}</p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Catégorie:</strong>
                        <span class="badge bg-info">${data.category || 'Aucune'}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Priorité:</strong>
                        <span class="badge bg-${data.priority == 'élevée' ? 'danger' : data.priority == 'moyenne' ? 'warning' : 'success'}">${data.priority}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Assigné à:</strong>
                    ${data.users ? data.users.map(user => `<span class="badge bg-primary me-1">${user.name}</span>`).join('') : 'Non assigné'}
                </div>
                <div class="mt-3">
                    <strong>Date limite:</strong>
                    ${data.due_date ? new Date(data.due_date).toLocaleDateString('fr-FR') : 'Non définie'}
                </div>
            `;
            $('#taskModal').modal('show');
        });
}

function editTaskInModal() {
    // Rediriger vers la page d'édition ou ouvrir un modal d'édition
}

// Fonction pour mettre à jour la date limite par glisser-déposer
function updateTaskDueDate(taskId, newDate) {
    fetch(`/task/update-due-date/${taskId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            due_date: newDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert('Erreur: ' + data.message);
            location.reload(); // Recharger en cas d'erreur
        } else {
            // Optionnel: afficher une notification de succès
        }
    });
}
</script>

<style>
.calendar-day {
    min-height: 120px;
    border: 1px solid #dee2e6;
    padding: 5px;
    background-color: #fff;
}

.calendar-day.today {
    background-color: #e3f2fd;
}

.calendar-day.other-month {
    background-color: #f8f9fa;
    color: #6c757d;
}

.task-item {
    background-color: #007bff;
    color: white;
    padding: 2px 6px;
    margin: 1px 0;
    border-radius: 3px;
    font-size: 0.75rem;
    cursor: pointer;
    word-wrap: break-word;
}

.task-item:hover {
    background-color: #0056b3;
}

.task-item.priority-elevee {
    background-color: #dc3545;
}

.task-item.priority-moyenne {
    background-color: #fd7e14;
}

.task-item.priority-basse {
    background-color: #28a745;
}

.calendar-header {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: center;
    padding: 10px;
    border: 1px solid #dee2e6;
}

.time-slot {
    height: 60px;
    border-bottom: 1px solid #eee;
    position: relative;
}

.task-in-slot {
    position: absolute;
    left: 2px;
    right: 2px;
    background-color: #007bff;
    color: white;
    padding: 2px 4px;
    border-radius: 2px;
    font-size: 0.7rem;
    cursor: pointer;
    z-index: 10;
}
</style>
@endsection