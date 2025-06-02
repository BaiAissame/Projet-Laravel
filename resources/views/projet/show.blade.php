<x-app-layout>
    <x-nav-left :data="$projets"></x-nav-left>
    <ul class="mt-[6rem] md:mt-[8rem] pl-4 lg:pl-6 flex overflow-auto " id="taskes-list">
        @if (count($projet->tasks) > 0)
            @foreach ($projet->tasks as $task)
                <x-block-task :task="$task" />
            @endforeach
        @endif
        <li id="container-create-task" class="mx-2 max-h-max">
            <form id="form-create-task" action="{{ route('task.create', $projet) }}" method="POST"
                class="bg-[#EEEEEE] w-[322px] min-h-[146px] rounded-[16px] px-4 py-4 text-[#262981]">
                @csrf
                <div class="mt-6 content-card">
                    <input class="bg-white w-[100%] min-h-[40px] rounded-[12px] border-none title-task"
                        placeholder="Titre de la tâche" name="title" />
                    <button class="flex items-center rounded-lg px-4 py-2 mt-4 w-full bg-[#262981] text-white">
                        <p class="ml-6 text-base">Ajouter une tâche</p>
                    </button>
                </div>
            </form>
        </li>
    </ul>
    <script defer>
        document.getElementById('form-create-task').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            const input = this.querySelector('.title-task');
            if (input.value.trim() === '') {
                alert('Veuillez entrer un titre pour la tâche.');
                return;
            }

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('container-create-task').insertAdjacentHTML('beforebegin', data
                        .html);
                    input.value = ''; // Clear the input field after successful submission

                }).catch(error => {
                    console.error('Erreur:', error);
                });
        });
    </script>
</x-app-layout>
