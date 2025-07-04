<li class="mx-2 max-h-max list-task" draggable="true" data-list-task-id="{{ $listTask->id }}">
    <article class="bg-[#EEEEEE] w-[322px] min-h-[146px] rounded-[16px] px-4 py-4 text-[#262981]">
        <div class="flex justify-between">
            <input class="text-lg font-semibold border-none bg-transparent" value="{{ $listTask->title }}"
                data-list-task-id="{{ $listTask->id }}" name="list-title">
            <div class="relative">
                <i class="fas fa-ellipsis-h text-xl cursor-pointer list-menu-trigger"
                    data-list-task-id="{{ $listTask->id }}"></i>

                <!-- Menu dropdown -->
                <div class="list-menu hidden absolute right-50 top-8 bg-white shadow-lg rounded-md py-2 w-48 z-10">
                    <button class="delete-list-btn w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600 font-semibold"
                        data-list-task-id="{{ $listTask->id }}">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer la liste
                    </button>
                </div>
            </div>
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
