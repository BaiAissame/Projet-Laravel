<!-- Vue hebdomadaire du calendrier -->
<div class="calendar-week">
    <div class="row no-gutters">
        <!-- En-têtes des jours -->
        <div class="col-1 calendar-header">Heure</div>
        @for($day = 0; $day < 7; $day++)
            @php
                $currentDay = $startDate->copy()->addDays($day);
                $isToday = $currentDay->isSameDay(now());
            @endphp
            <div class="col calendar-header {{ $isToday ? 'bg-primary text-white' : '' }}">
                <div>{{ $currentDay->format('D') }}</div>
                <div>{{ $currentDay->format('j/m') }}</div>
            </div>
        @endfor
    </div>

    <!-- Grille horaire -->
    @for($hour = 0; $hour < 24; $hour++)
        <div class="row no-gutters">
            <div class="col-1 time-header">
                {{ sprintf('%02d:00', $hour) }}
            </div>
            @for($day = 0; $day < 7; $day++)
                @php
                    $currentDay = $startDate->copy()->addDays($day);
                    $dateString = $currentDay->format('Y-m-d');
                    $dayTasks = $tasksByDate->get($dateString, collect());
                @endphp
                
                <div class="col time-slot" 
                     data-date="{{ $dateString }}"
                     data-hour="{{ $hour }}"
                     ondrop="drop(event)" 
                     ondragover="allowDrop(event)">
                    
                    @if($hour == 8) <!-- Afficher les tâches à 8h pour simplifier -->
                        @foreach($dayTasks as $task)
                            <div class="task-in-slot priority-{{ $task->priority }}" 
                                 onclick="showTaskDetails({{ $task->id }})"
                                 draggable="true"
                                 ondragstart="drag(event, {{ $task->id }})"
                                 title="{{ $task->title }}">
                                {{ Str::limit($task->title, 15) }}
                            </div>
                        @endforeach
                    @endif
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
.time-header {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 8px;
    font-size: 0.8rem;
    text-align: center;
}

.time-slot {
    border: 1px solid #eee;
    min-height: 40px;
    position: relative;
}

.time-slot:hover {
    background-color: #f8f9fa;
}
</style>