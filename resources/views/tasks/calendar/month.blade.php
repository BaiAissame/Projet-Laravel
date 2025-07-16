<!-- Vue mensuelle du calendrier -->
<div class="calendar-month">
    <div class="row no-gutters">
        <!-- En-têtes des jours -->
        <div class="col calendar-header">Lun</div>
        <div class="col calendar-header">Mar</div>
        <div class="col calendar-header">Mer</div>
        <div class="col calendar-header">Jeu</div>
        <div class="col calendar-header">Ven</div>
        <div class="col calendar-header">Sam</div>
        <div class="col calendar-header">Dim</div>
    </div>

    @php
        $currentWeekStart = $startDate->copy();
        $today = now();
        $currentMonth = $currentDate->month;
    @endphp

    @while($currentWeekStart->lte($endDate))
        <div class="row no-gutters">
            @for($day = 0; $day < 7; $day++)
                @php
                    $currentDay = $currentWeekStart->copy()->addDays($day);
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
        @php
            $currentWeekStart->addWeek();
        @endphp
    @endwhile
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