<li
    data-list-task-id="{{ $task->listTask->id }}"
    data-task-id="{{ $task->id }}"
    draggable="true"
    class="container-task bg-white w-[100%] rounded-[16px] mb-2 shadow-lg cursor-pointer px-4 py-2 flex justify-between hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
    <p class="max-w-[80%]">{{ $task->title }}</p>
    <x-modal-task :task="$task" />
</li>
