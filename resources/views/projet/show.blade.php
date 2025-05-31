<x-app-layout>
  <x-nav-left :data="$projets"></x-nav-left>
  <div class="mt-[6rem] md:mt-[8rem] ml-4 lg:ml-8">
    <x-block-task :title="'Titre de la tâche'" />
  </div>
</x-app-layout>