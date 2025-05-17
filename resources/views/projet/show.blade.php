<x-app-layout>
    <x-nav-left :data="$projets" ></x-nav-left>
    <div class="col-span-1"></div>
    <div class="col-span-8">
       {{-- <x-block-projet title="Récemment consultés" icon="fas fa-clock" :data="null"></x-block-projet> --}}
       {{-- <div class="mt-20"></div> --}}
       {{-- <x-block-projet title="Espaces de travail" icon="fas fa-briefcase" :data="$projets"></x-block-projet> --}}
    </div>
    <div class="col-span-1"></div>
 </x-app-layout>