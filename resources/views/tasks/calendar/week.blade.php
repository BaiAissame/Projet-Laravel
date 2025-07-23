@php
    $startOfWeek = $currentDate->copy()->startOfWeek();
    $endOfWeek = $currentDate->copy()->endOfWeek();
    $prevWeek = $currentDate->copy()->subWeek()->format('Y-m-d');
    $nextWeek = $currentDate->copy()->addWeek()->format('Y-m-d');
@endphp

<div class="calendar-week">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                <a href="{{ route('tasks.calendar', ['projet' => $projet->slug, 'view' => 'week', 'date' => $prevWeek]) }}"
                    class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i> Semaine précédente
                </a>

                <h5 class="mb-0">
                    Semaine du {{ $startOfWeek->format('j/m/Y') }} au {{ $endOfWeek->format('j/m/Y') }}
                </h5>

                <a href="{{ route('tasks.calendar', ['projet' => $projet->slug, 'view' => 'week', 'date' => $nextWeek]) }}"
                    class="btn btn-outline-primary">
                    Semaine suivante <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row no-gutters align-items-center mb-2">
        <div class="col-1 calendar-header p-0 text-center">
            <div style="font-size:0.8em;">Heure</div>
        </div>
        @for($day = 0; $day < 7; $day++)
            @php
                $currentDay = $startOfWeek->copy()->addDays($day);
                $isToday = $currentDay->isSameDay(now());
            @endphp
            <div class="col calendar-header {{ $isToday ? 'bg-primary text-white' : '' }}">
                <div>{{ $currentDay->format('D') }}</div>
                <div>{{ $currentDay->format('j/m') }}</div>
            </div>
        @endfor
        <div class="col-1 calendar-header p-0 text-center">
        </div>
    </div>

    @for($hour = 0; $hour < 24; $hour++)
        <div class="row no-gutters">
            <div class="col-1 time-header">
                {{ sprintf('%02d:00', $hour) }}
            </div>
            @for($day = 0; $day < 7; $day++)
                @php
                    $currentDay = $startOfWeek->copy()->addDays($day);
                    $dateString = $currentDay->format('Y-m-d');
                    $dayTasks = $tasksByDate->get($dateString, collect());
                @endphp

                <div class="col time-slot" data-date="{{ $dateString }}" data-hour="{{ $hour }}" ondrop="drop(event)"
                    ondragover="allowDrop(event)">

                    @if($hour == 8)
                        @foreach($dayTasks as $task)
                            <div class="task-in-slot priority-{{ $task->priority }}" onclick="openTaskModal('{{ $task->id }}')"
                                draggable="true" ondragstart="drag(event, '{{ $task->id }}')" title="{{ $task->title }}">
                                {{ Str::limit($task->title, 15) }}
                            </div>
                        @endforeach
                    @endif
                </div>
            @endfor
            <div class="col-1">
            </div>
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
    .calendar-week .time-header {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px;
        font-size: 0.8rem;
        text-align: center;
        font-weight: 500;
    }

    .calendar-week .time-slot {
        border: 1px solid #eee;
        min-height: 40px;
        position: relative;
        padding: 2px;
    }

    .calendar-week .time-slot:hover {
        background-color: #f8f9fa;
    }

    .calendar-week .task-in-slot {
        position: absolute;
        left: 2px;
        right: 2px;
        top: 2px;
        background-color: #007bff;
        color: white;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 0.7rem;
        cursor: pointer;
        z-index: 10;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .calendar-week .task-in-slot.priority-elevee {
        background-color: #dc3545;
    }

    .calendar-week .task-in-slot.priority-moyenne {
        background-color: #fd7e14;
    }

    .calendar-week .task-in-slot.priority-basse {
        background-color: #28a745;
    }

    .calendar-week .task-in-slot:hover {
        opacity: 0.8;
    }

    .calendar-week .calendar-header {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 12px 8px;
        text-align: center;
        font-weight: 500;
        font-size: 0.85rem;
    }
</style>