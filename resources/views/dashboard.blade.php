<x-app-layout>
    {{-- Navigation latérale --}}
    <x-nav-left :data="$projets" />

    <div class="flex-1 p-8-dashboard">
        {{-- En-tête du dashboard --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Tableau de bord
            </h1>
            <p class="text-gray-600 dark:text-gray-400">Bienvenue sur votre espace de travail Kanboard</p>
        </div>

        {{-- Statistiques principales (si aucune recherche) --}}
        @unless(isset($results))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $stats = [
                        [
                            'count' => isset($projets) ? count($projets) : 0,
                            'label' => 'Mes projets',
                            'icon' => 'fas fa-folder',
                            'gradient' => 'from-blue-500 to-blue-600',
                            'bg' => 'from-blue-50 to-blue-100',
                            'dark_bg' => 'from-blue-900/20 to-blue-800/20',
                        ],
                        [
                            'count' => isset($sharedProjects) ? count($sharedProjects) : 0,
                            'label' => 'Projets partagés',
                            'icon' => 'fas fa-share-alt',
                            'gradient' => 'from-green-500 to-emerald-600',
                            'bg' => 'from-green-50 to-emerald-100',
                            'dark_bg' => 'from-green-900/20 to-emerald-800/20',
                        ],
                        [
                            'count' => $totalTasks ?? 0,
                            'label' => 'Tâches totales',
                            'icon' => 'fas fa-tasks',
                            'gradient' => 'from-purple-500 to-purple-600',
                            'bg' => 'from-purple-50 to-purple-100',
                            'dark_bg' => 'from-purple-900/20 to-purple-800/20',
                        ],
                        [
                            'count' => $completedTasks ?? 0,
                            'label' => 'Tâches terminées',
                            'icon' => 'fas fa-check-circle',
                            'gradient' => 'from-emerald-500 to-green-600',
                            'bg' => 'from-emerald-50 to-green-100',
                            'dark_bg' => 'from-emerald-900/20 to-green-800/20',
                        ],
                    ];
                @endphp

                @foreach ($stats as $stat)
                    <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $stat['count'] }}</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ $stat['label'] }}</p>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-r {{ $stat['gradient'] }} rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="{{ $stat['icon'] }} text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 bg-gradient-to-r {{ $stat['bg'] }} dark:{{ $stat['dark_bg'] }} rounded-xl p-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Progression</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    @if($stat['count'] > 0)
                                        {{ round(($stat['count'] / max(array_column($stats, 'count'))) * 100) }}%
                                    @else
                                        0%
                                    @endif
                                </span>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r {{ $stat['gradient'] }} h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $stat['count'] > 0 ? min(($stat['count'] / max(array_column($stats, 'count'))) * 100, 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endunless

        {{-- Résultats de recherche --}}
        @isset($results)
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-search text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Résultats de recherche</h2>
                        <p class="text-gray-600 dark:text-gray-400">{{ count($results) }} résultat(s) trouvé(s)</p>
                    </div>
                </div>
                <x-block-projet :data="$results" />
            </div>
        @else
            {{-- Mes projets --}}
            @if(!empty($projets))
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-folder text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Mes projets</h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ count($projets) }} projet(s)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('projects.export') }}" class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-file-excel mr-2"></i>Exporter en Excel
                            </a>
                            <button onclick="openCreateProjectModal()" 
                                    class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>Nouveau projet
                            </button>
                        </div>
                    </div>
                    <x-block-projet :data="$projets" />
                </div>
            @else
                {{-- État vide moderne --}}
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-folder-plus text-3xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Aucun projet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        Commencez par créer votre premier projet pour organiser vos tâches et collaborer avec votre équipe
                    </p>
                    <div class="space-y-4">
                        <button onclick="openCreateProjectModal()"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-plus mr-3"></i>
                            Créer votre premier projet
                        </button>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                            Les projets vous permettent d'organiser vos tâches en tableaux Kanban
                        </div>
                    </div>
                </div>
            @endif

            {{-- Projets partagés --}}
            @if(!empty($sharedProjects))
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6 mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-share-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Projets partagés avec moi</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ count($sharedProjects) }} projet(s) partagé(s)</p>
                        </div>
                    </div>
                    <x-block-projet :data="$sharedProjects" />
                </div>
            @endif

            {{-- Activité récente --}}
            @if(!empty($recentActivity))
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 dark:border-gray-700/50 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Activité récente</h2>
                            <p class="text-gray-600 dark:text-gray-400">Dernières actions sur vos projets</p>
                        </div>
                    </div>
                            <div class="space-y-4">
                                @foreach($recentActivity as $activity)
                            <div class="flex items-start space-x-4 p-4 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200">
                                        <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-{{ $activity->icon ?? 'circle' }} text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 dark:text-white font-medium">
                                                {{ $activity->description }}
                                            </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                                {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                    </div>
                </div>
            @endif
        @endisset

        {{-- Messages de session modernes --}}
        @foreach (['error' => 'red', 'success' => 'green', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color)
            @if (session($type))
                <div class="fixed top-24 right-6 z-50">
                    <div class="bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center transform transition-all duration-500 translate-x-full opacity-0" id="session-{{ $type }}">
                        <i class="fas fa-{{ $type === 'error' ? 'exclamation-circle' : ($type === 'success' ? 'check-circle' : ($type === 'warning' ? 'exclamation-triangle' : 'info-circle')) }} text-2xl mr-4"></i>
                        <div>
                            <div class="font-bold text-lg">{{ ucfirst($type) }}</div>
                            <div class="text-sm opacity-90">{{ session($type) }}</div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="ml-4 text-white/80 hover:text-white transition-colors">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('session-{{ $type }}');
                        if (toast) {
                            toast.classList.remove('translate-x-full', 'opacity-0');
                            setTimeout(() => {
                                toast.classList.add('translate-x-full', 'opacity-0');
                                setTimeout(() => toast.remove(), 500);
                            }, 5000);
                        }
                    }, 100);
                </script>
            @endif
        @endforeach

        {{-- Indicateur hors ligne moderne --}}
        <div id="offline-indicator"
            class="hidden fixed top-24 right-6 bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-50 flex items-center transform transition-all duration-500">
            <i class="fas fa-wifi-slash text-2xl mr-4"></i>
            <div>
                <div class="font-bold text-lg">Mode hors ligne</div>
                <div class="text-sm opacity-90">Certaines fonctionnalités peuvent être limitées</div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('online', () => {
                const indicator = document.getElementById('offline-indicator');
                if (indicator) {
                    indicator.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => indicator.classList.add('hidden'), 500);
                }
            });

            window.addEventListener('offline', () => {
                const indicator = document.getElementById('offline-indicator');
                if (indicator) {
                    indicator.classList.remove('hidden', 'translate-x-full', 'opacity-0');
                }
            });

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.grid > div');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
</x-app-layout>