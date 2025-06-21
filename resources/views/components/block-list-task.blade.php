<li class="mx-2 max-h-max list-task" draggable="true" data-list-task-id="{{ $listTask->id }}">
    <article class="bg-[#EEEEEE] w-[322px] min-h-[146px] rounded-[16px] px-4 py-4 text-[#262981]">
        <div class="flex justify-between">
            <h1 class="text-lg font-semibold ml-4">{{ $listTask->title }}</h1>
            <i class="fas fa-ellipsis-h text-xl"></i>
        </div>
        <div class="mt-4 ">
            <ol>
                @foreach ($listTask->tasks as $task)
                    <x-block-task :task="$task"></x-block-task>
                @endforeach
            </ol>

            <form action="{{ route('task.create', $listTask) }}" method="POST" class="form-create-task"
                data-list-task-id="{{ $listTask->id }}">
                @csrf
                <input type="text" name="task-title" class="border-none w-[100%] min-h-[40px] rounded-md shadow-lg"
                    placeholder="Saississez un titre" required />
                <button
                    class="flex items-center hover:bg-[#DADCF2] rounded-md px-4 py-2 button-create-card mt-2 w-full">
                    <i class="fas fa-plus text-3xl"></i>
                    <p class="ml-6 text-base">Ajouter une tâche</p>
                </button>
            </form>
        </div>
    </article>
</li>
