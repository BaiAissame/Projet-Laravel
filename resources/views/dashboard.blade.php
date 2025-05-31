<x-app-layout>
    @if (isset($projets) && count($projets) > 0)
        <x-nav-left :data="$projets"></x-nav-left>
    @else
        <x-nav-left></x-nav-left>
    @endif

    <div class="mt-[6rem] md:mt-[8rem]">

        @if(!isset($results))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                            <i class="fas fa-folder text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ isset($projets) ? count($projets) : 0 }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">Mes projets</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                            <i class="fas fa-share-alt text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ isset($sharedProjects) ? count($sharedProjects) : 0 }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">Projets partagés</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                            <i class="fas fa-tasks text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $totalTasks ?? 0 }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">Tâches totales</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (isset($results))
            <x-block-projet title="Résultat de la recherche" icon="fas fa-magnifying-glass"
                :data="$results"></x-block-projet>
        @else
            @if (isset($projets) && count($projets) > 0)
                <x-block-projet title="Mes projets" icon="fas fa-folder" :data="$projets"></x-block-projet>
            @else
                <!-- État vide -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                        <i class="fas fa-folder-plus text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Aucun projet</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Commencez par créer votre premier projet</p>
                    <div class="mt-6">
                        <a href="{{ route('projets.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Créer un projet
                        </a>
                    </div>
                </div>
            @endif

            @if (isset($sharedProjects) && count($sharedProjects) > 0)
                <div class="mt-10 lg:mt-16"></div>
                <x-block-projet title="Projets partagés avec moi" icon="fas fa-share-alt"
                    :data="$sharedProjects"></x-block-projet>
            @endif

            <!-- Activité récente -->
            @if(isset($recentActivity) && count($recentActivity) > 0)
                <div class="mt-10 lg:mt-16">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                Activité récente
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($recentActivity as $activity)
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <i
                                                    class="fas fa-{{ $activity->icon ?? 'circle' }} text-xs text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                {{ $activity->description }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Messages de session -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mt-4 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mt-4 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Indicateur hors ligne -->
        <div id="offline-indicator"
            class="hidden fixed top-4 right-4 bg-yellow-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
            <i class="fas fa-wifi-slash mr-2"></i>
            Mode hors ligne
        </div>
    </div>

    @push('scripts')
        <script>
            // Détection du mode hors ligne
            window.addEventListener('online', function () {
                document.getElementById('offline-indicator').classList.add('hidden');
            });

            window.addEventListener('offline', function () {
                document.getElementById('offline-indicator').classList.remove('hidden');
            });
        </script>
    @endpush
</x-app-layout>