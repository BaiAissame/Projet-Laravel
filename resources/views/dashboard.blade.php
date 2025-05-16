<x-app-layout>

    @if (isset($projets) && count($projets) > 0)
        <x-nav-left :data="$projets"></x-nav-left>
    @else
        <x-nav-left></x-nav-left>
    @endif

    <div class="col-span-1"></div>
    <div class="col-span-8">

        {{-- projets récemment consultés --}}
        {{-- @if (isset($consults) && count($consults) > 0)
            <x-block-projet title="Récemment consultés" icon="fas fa-clock" :data="$consults"></x-block-projet>
        @endif --}}

        <div class="mt-20"></div>

        @if (isset($results))
            <x-block-projet title="Resultat de la recherche" icon="fas fa-magnifying-glass" :data="$results"></x-block-projet>
        @elseif (isset($projets))
            <x-block-projet title="Mes projets" icon="fas fa-folder" :data="$projets"></x-block-projet>
        @endif

    </div>
    <div class="col-span-1"></div>
</x-app-layout>
