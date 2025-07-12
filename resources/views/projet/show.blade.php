<x-app-layout>
    <x-nav-left :data="$projets" :projet="$projet"></x-nav-left>
    <section class="mt-[6rem] md:mt-[8rem] pl-4 lg:pl-6 flex overflow-auto">
        <ul class="flex" id="taskes-list" data-projet-id="{{ $projet->id }}">
            @if (count($projet->listTasks) > 0)
                @foreach ($projet->listTasks as $listTask)
                    <x-block-list-task :listTask="$listTask" />
                @endforeach
            @endif
        </ul>
        <form id="form-create-list-task" action="{{ route('listTask.create', $projet) }}" method="POST"
            class="bg-[#EEEEEE] w-[322px] min-w-[322px] min-h-[146px] rounded-[16px] px-4 py-4 text-[#262981] max-h-max mx-2">
            @csrf
            <div class="mt-6 content-card">
                <input class="bg-white w-[100%] min-h-[40px] rounded-md border-none title-task shadow-lg"
                    placeholder="Saississez un titre" name="title" />
                <button class="flex items-center rounded-lg px-4 py-2 mt-2 w-full bg-[#262981] text-white hover:bg-[#1f1f6b] transition-colors duration-200">
                    <p class="ml-6 text-base">Ajouter une liste</p>
                </button>
            </div>
        </form>
    </section>
</x-app-layout>
