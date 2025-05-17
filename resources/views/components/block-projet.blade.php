<section class="w-full">
    <h2 class="mt-8 text-lg font-semibold text-[#262981]"><i class="{{ $icon }} mr-2"></i> {{ $title }}
    </h2>
    <ul class="mt-12 grid grid-cols-4 gap-4 max-w-[968px]">
        @if (isset($data))

            @foreach ($data as $projet)
                <li class="col-span-1 relative">
                    
                    <a href="{{ route('projet.show', $projet) }}" class="block relative">
                        <article class="bg-cover bg-center h-28 w-full bg-[#262981] rounded-lg px-4 py-2 text-white">
                            <h1 class="text-2xl font-bold">
                                {{ $projet->name }}
                            </h1>
                            <i class="fas fa-user mt-2"></i>
                            
                           
                            <div class="absolute top-2 right-2 flex space-x-1">
                              
                                @if($projet->user_id == auth()->id())
                                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-2 py-1 rounded" 
                                       onclick="event.preventDefault(); openAddMemberModal('{{ $projet->slug }}', '{{ $projet->name }}')">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @endif
                                
                               
                                @if($projet->user_id == auth()->id())
                                <form action="{{ route('projet.destroy', $projet) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-xs px-2 py-1 rounded" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </article>
                    </a>
                </li>
            @endforeach

        @endif
    </ul>
</section>
