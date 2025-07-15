<!-- Vue mensuelle du calendrier avec navigation séparée -->

@php
    // Calculer le début et la fin du mois
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();
    
    // Calculer le début de la première semaine (lundi précédent si nécessaire)
    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
    
    // Calculer la fin de la dernière semaine (dimanche suivant si nécessaire)
    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
    
    $today = now();
    $currentMonth = $currentDate->month;
    
    // Calculer le nombre total de jours à afficher
    $totalDays = $startOfCalendar->diffInDays($endOfCalendar) + 1;
    $totalWeeks = ceil($totalDays / 7);
    
    // Navigation par mois
    $prevMonth = $currentDate->copy()->subMonth()->format('Y-m-d');
    $nextMonth = $currentDate->copy()->addMonth()->format('Y-m-d');
@endphp

<div class="calendar-month">
    <!-- Navigation de mois au-dessus du tableau -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                <a href="{{ route('tasks.calendar', ['projet' => $projet->slug, 'view' => 'month', 'date' => $prevMonth]) }}" 
                   class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i> Mois précédent
                </a>
                
                <h5 class="mb-0">
                    {{ $currentDate->format('F Y') }}
                </h5>
                
                <a href="{{ route('tasks.calendar', ['projet' => $projet->slug, 'view' => 'month', 'date' => $nextMonth]) }}" 
                   class="btn btn-outline-primary">
                    Mois suivant <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- En-têtes des jours -->
    <div class="row no-gutters">
        <div class="col calendar-header">Lun</div>
        <div class="col calendar-header">Mar</div>
        <div class="col calendar-header">Mer</div>
        <div class="col calendar-header">Jeu</div>
        <div class="col calendar-header">Ven</div>
        <div class="col calendar-header">Sam</div>
        <div class="col calendar-header">Dim</div>
    </div>

    @for($week = 0; $week < $totalWeeks; $week++)
        <div class="row no-gutters">
            @for($day = 0; $day < 7; $day++)
                @php
                    $currentDay = $startOfCalendar->copy()->addDays($week * 7 + $day);
                    
                    // Vérifier si on dépasse la fin du calendrier
                    if ($currentDay->gt($endOfCalendar)) {
                        break 2; // Sortir des deux boucles
                    }
                    
                    $dateString = $currentDay->format('Y-m-d');
                    $dayTasks = $tasksByDate->get($dateString, collect());
                    $isToday = $currentDay->isSameDay($today);
                    $isCurrentMonth = $currentDay->month == $currentMonth;
                @endphp
                
                <div class="col calendar-day {{ $isToday ? 'today' : '' }} {{ !$isCurrentMonth ? 'other-month' : '' }}" 
                     data-date="{{ $dateString }}"
                     ondrop="drop(event)" 
                     ondragover="allowDrop(event)">
                    <div class="d-flex justify-content-between">
                        <span class="day-number">{{ $currentDay->format('j') }}</span>
                        @if($dayTasks->count() > 3)
                            <small class="text-muted">+{{ $dayTasks->count() - 3 }}</small>
                        @endif
                    </div>
                    
                    <!-- Affichage des tâches -->
                    @foreach($dayTasks->take(3) as $task)
                        <div class="task-item priority-{{ $task->priority }}" 
                             onclick="showTaskDetails({{ $task->id }})"
                             draggable="true"
                             ondragstart="drag(event, {{ $task->id }})"
                             title="{{ $task->title }} - {{ $task->description }}">
                            {{ Str::limit($task->title, 20) }}
                        </div>
                    @endforeach
                </div>
            @endfor
        </div>
    @endfor
</div>

<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev, taskId) {
    ev.dataTransfer.setData("taskId", taskId);
}

function drop(ev) {
    ev.preventDefault();
    const taskId = ev.dataTransfer.getData("taskId");
    const newDate = ev.currentTarget.getAttribute('data-date');
    
    if (taskId && newDate) {
        updateTaskDueDate(taskId, newDate);
    }
}
</script>

<style>
.calendar-month .calendar-header {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 12px 8px;
    text-align: center;
    font-weight: 500;
    font-size: 0.9rem;
}

.calendar-month .calendar-day {
    border: 1px solid #dee2e6;
    min-height: 120px;
    padding: 8px;
    position: relative;
    background-color: #fff;
}

.calendar-month .calendar-day:hover {
    background-color: #f8f9fa;
}

.calendar-month .calendar-day.today {
    background-color: #e3f2fd;
    border-color: #2196f3;
}

.calendar-month .calendar-day.other-month {
    background-color: #f5f5f5;
    color: #999;
}

.calendar-month .day-number {
    font-weight: 500;
    font-size: 0.9rem;
}

.calendar-month .task-item {
    background-color: #007bff;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.7rem;
    margin-bottom: 2px;
    cursor: pointer;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.calendar-month .task-item.priority-elevee {
    background-color: #dc3545;
}

.calendar-month .task-item.priority-moyenne {
    background-color: #fd7e14;
}

.calendar-month .task-item.priority-basse {
    background-color: #28a745;
}

.calendar-month .task-item:hover {
    opacity: 0.8;
}
</style>