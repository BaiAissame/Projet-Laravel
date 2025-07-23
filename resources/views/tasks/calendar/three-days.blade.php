<!-- Vue 3 jours du calendrier -->
<div class="calendar-three-days">
    <div class="row no-gutters">
        <!-- En-têtes des jours -->
        <div class="col-2 calendar-header">Heure</div>
        @for($day = 0; $day < 3; $day++)
            @php
                $currentDay = $currentDate->copy()->addDays($day);
                $isToday = $currentDay->isSameDay(now());
            @endphp
            <div class="col calendar-header {{ $isToday ? 'bg-primary text-white' : '' }}">
                <div><strong>{{ $currentDay->format('l') }}</strong></div>
                <div>{{ $currentDay->format('j F Y') }}</div>
            </div>
        @endfor
    </div>

    <!-- Grille horaire -->
    @for($hour = 6; $hour < 22; $hour++) <!-- Affichage de 6h à 22h -->
        <div class="row no-gutters">
            <div class="col-2 time-header">
                {{ sprintf('%02d:00', $hour) }}
            </div>
            @for($day = 0; $day < 3; $day++)
                @php
                    $currentDay = $currentDate->copy()->addDays($day);
                    $dateString = $currentDay->format('Y-m-d');
                    $dayTasks = $tasksByDate->get($dateString, collect());
                @endphp

                <div class="col time-slot" data-date="{{ $dateString }}" data-hour="{{ $hour }}" ondrop="drop(event)"
                    ondragover="allowDrop(event)">

                    @if($hour == 9) <!-- Afficher les tâches à 9h pour simplifier -->
                        @foreach($dayTasks as $task)
                            <div class="task-in-slot priority-{{ $task->priority }}" onclick="openTaskModal('{{ $task->id }}')"
                                draggable="true" ondragstart="drag(event, '{{ $task->id }}')"
                                title="{{ $task->title }} - {{ $task->description }}">
                                <strong>{{ Str::limit($task->title, 20) }}</strong>
                                @if($task->description)
                                    <br><small>{{ Str::limit($task->description, 30) }}</small>
                                @endif
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
    .calendar-three-days .time-header {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 12px 8px;
        font-size: 0.85rem;
        text-align: center;
        font-weight: 500;
    }

    .calendar-three-days .time-slot {
        border: 1px solid #eee;
        min-height: 50px;
        position: relative;
        padding: 2px;
    }

    .calendar-three-days .time-slot:hover {
        background-color: #f8f9fa;
    }

    .calendar-three-days .task-in-slot {
        position: absolute;
        left: 2px;
        right: 2px;
        top: 2px;
        background-color: #007bff;
        color: white;
        padding: 4px 6px;
        border-radius: 3px;
        font-size: 0.75rem;
        cursor: pointer;
        z-index: 10;
        overflow: hidden;
    }

    .calendar-three-days .task-in-slot.priority-elevee {
        background-color: #dc3545;
    }

    .calendar-three-days .task-in-slot.priority-moyenne {
        background-color: #fd7e14;
    }

    .calendar-three-days .task-in-slot.priority-basse {
        background-color: #28a745;
    }

    .calendar-three-days .task-in-slot:hover {
        opacity: 0.8;
    }
</style>