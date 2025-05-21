<section class="ml-4 lg:ml-8">
    <h2 class="mt-8 text-lg font-semibold text-[#262981]"><i class="{{ $icon }} mr-2"></i> {{ $title }}
    </h2>
    <ul class="mt-8 md:mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @if (isset($data))
            @foreach ($data as $projet)
                <li class="col-span-1 ">
                    <a href="{{ route('projet.show', $projet) }}">
                        <article class="h-28 w-[13.5rem] md:w-[11.5rem] lg:w-[13.5rem] bg-[#262981] rounded-lg px-4 py-2 text-white">
                            <div class="flex justify-between">
                                <h1 class="text-xl font-bold max-w-[100px]">
                                    {{ $projet->name }}
                                </h1>
                                <div class="flex items-center self-start">
                                    @if ($projet->user_id == auth()->id())
                                        <button type="button"
                                            class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-2 py-1 rounded mr-[1.5px]"
                                            onclick="event.preventDefault(); openAddMemberModal('{{ $projet->slug }}', '{{ $projet->name }}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <form action="{{ route('projet.destroy', $projet) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet?')"
                                            class="ml-[1.5px] flex items-center">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-700 text-white text-xs px-2 py-1 rounded"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <i class="fas fa-user mt-2"></i>
                            <div class="top-2 right-2 flex space-x-1">
                            </div>
                        </article>
                    </a>
                </li>
            @endforeach

        @endif
    </ul>
</section>
