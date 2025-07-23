<div class="calendar-day-view">
    <div class="row">
        <div class="col-12">
            <div class="day-header text-center mb-4">
                <h4>{{ $currentDate->format('l j F Y') }}</h4>
                @if($currentDate->isSameDay(now()))
                    <span class="badge bg-primary">Aujourd'hui</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <div class="time-column">
                @for($hour = 0; $hour < 24; $hour++)
                    <div class="time-slot-label">
                        {{ sprintf('%02d:00', $hour) }}
                    </div>
                @endfor
            </div>
        </div>

        <div class="col-10">
            <div class="tasks-column">
                @php
                    $dateString = $currentDate->format('Y-m-d');
                    $dayTasks = $tasksByDate->get($dateString, collect());
                @endphp

                @for($hour = 0; $hour < 24; $hour++)
                    <div class="hour-slot" data-date="{{ $dateString }}" data-hour="{{ $hour }}" ondrop="drop(event)"
                        ondragover="allowDrop(event)">

                        @if($hour == 9) <!-- Afficher toutes les tâches à 9h -->
                            @foreach($dayTasks as $task)
                                <div class="task-block priority-{{ $task->priority }}" onclick="openTaskModal('{{ $task->id }}')"
                                    draggable="true" ondragstart="drag(event, '{{ $task->id }}')">
                                    <div class="task-header">
                                        <strong>{{ $task->title }}</strong>
                                        <span class="badge bg-secondary ms-2">{{ $task->listTask->title }}</span>
                                    </div>

                                    @if($task->description)
                                        <div class="task-description">
                                            {{ Str::limit($task->description, 100) }}
                                        </div>
                                    @endif

                                    <div class="task-meta">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($task->category)
                                                    <span class="badge bg-info">{{ $task->category }}</span>
                                                @endif
                                                <span
                                                    class="badge bg-{{ $task->priority == 'élevée' ? 'danger' : ($task->priority == 'moyenne' ? 'warning' : 'success') }}">
                                                    {{ $task->priority }}
                                                </span>
                                            </div>
                                            <div>
                                                @if($task->users->count() > 0)
                                                    @foreach($task->users as $user)
                                                        <span class="badge bg-primary">{{ $user->name }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="hour-line"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    @if($dayTasks->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Résumé des tâches du jour ({{ $dayTasks->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($dayTasks as $task)
                                <div class="col-md-4 mb-3">
                                    <div class="card task-summary priority-{{ $task->priority }}">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $task->title }}</h6>
                                            <p class="card-text">{{ Str::limit($task->description, 80) }}</p>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">{{ $task->listTask->title }}</small>
                                                <span
                                                    class="badge bg-{{ $task->priority == 'élevée' ? 'danger' : ($task->priority == 'moyenne' ? 'warning' : 'success') }}">
                                                    {{ $task->priority }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                    <h5>Aucune tâche prévue pour ce jour</h5>
                    <p>Profitez de cette journée libre ou ajoutez de nouvelles tâches !</p>
                </div>
            </div>
        </div>
    @endif
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
    .calendar-day-view .time-column {
        border-right: 2px solid #dee2e6;
    }

    .calendar-day-view .time-slot-label {
        height: 60px;
        padding: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        text-align: center;
        border-bottom: 1px solid #eee;
        background-color: #f8f9fa;
    }

    .calendar-day-view .hour-slot {
        height: 60px;
        border-bottom: 1px solid #eee;
        position: relative;
        padding: 4px;
    }

    .calendar-day-view .hour-slot:hover {
        background-color: #f8f9fa;
    }

    .calendar-day-view .hour-line {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background-color: #dee2e6;
    }

    .calendar-day-view .task-block {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .calendar-day-view .task-block:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .calendar-day-view .task-block.priority-elevee {
        border-left: 4px solid #dc3545;
    }

    .calendar-day-view .task-block.priority-moyenne {
        border-left: 4px solid #fd7e14;
    }

    .calendar-day-view .task-block.priority-basse {
        border-left: 4px solid #28a745;
    }

    .calendar-day-view .task-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .calendar-day-view .task-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .calendar-day-view .task-meta {
        font-size: 0.8rem;
    }

    .calendar-day-view .task-summary {
        height: 100%;
        transition: all 0.2s;
    }

    .calendar-day-view .task-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .calendar-day-view .task-summary.priority-elevee {
        border-left: 4px solid #dc3545;
    }

    .calendar-day-view .task-summary.priority-moyenne {
        border-left: 4px solid #fd7e14;
    }

    .calendar-day-view .task-summary.priority-basse {
        border-left: 4px solid #28a745;
    }

    .day-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>