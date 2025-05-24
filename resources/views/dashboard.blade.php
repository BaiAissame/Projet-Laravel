<x-app-layout>

    @if (isset($projets) && count($projets) > 0)
        <x-nav-left :data="$projets"></x-nav-left>
    @else
        <x-nav-left></x-nav-left>
    @endif

    <div  class="mt-[6rem] md:mt-[8rem]">
      
        @if (isset($results))
            <x-block-projet title="Resultat de la recherche" icon="fas fa-magnifying-glass"
                :data="$results"></x-block-projet>
        @else
            @if (isset($projets) && count($projets) > 0)
                <x-block-projet title="Mes projets" icon="fas fa-folder" :data="$projets"></x-block-projet>
            @endif

            @if (isset($sharedProjects) && count($sharedProjects) > 0)
                <div class="mt-10 lg:mt-16"></div>
                <x-block-projet title="Projets partagés avec moi" icon="fas fa-share-alt"
                    :data="$sharedProjects"></x-block-projet>
            @endif
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mt-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mt-4">
                {{ session('success') }}
            </div>
        @endif

    </div>
</x-app-layout>
