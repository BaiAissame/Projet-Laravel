<nav x-data="{ open: false }"
    class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-white/20 dark:border-gray-700/50 h-20 fixed w-full z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex justify-between h-full">
            <!-- Partie gauche avec logo et navigation -->
            <div class="flex items-center">
                <!-- Logo responsive -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="relative">
                            <img src="{{ asset('logo-kanboard.png') }}" alt="kanboard-logo"
                                class="h-8 sm:h-12 w-auto object-cover transition-transform duration-300 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <span
                            class="ml-2 sm:ml-3 text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Kanboard</span>
                    </a>
                </div>

                <!-- Navigation Links - cachés sur mobile -->
                <div class="hidden lg:flex space-x-1 ms-6 xl:ms-10">
                    <a href="{{ route('dashboard') }}"
                        class="px-3 xl:px-4 py-2 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 font-medium text-sm xl:text-base">
                        <i class="fas fa-layer-group mr-2"></i>Espaces de travail
                    </a>
                    <button onclick="openCreateProjectModal()"
                        class="px-3 xl:px-4 py-2 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 font-medium text-sm xl:text-base">
                        <i class="fas fa-plus mr-2"></i>Créer
                    </button>
                </div>
            </div>

            <!-- Partie droite responsive -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Barre de recherche - adaptée mobile -->
                <form action="" class="hidden md:flex items-center">
                    <div class="relative">
                        <input
                            class="h-8 lg:h-10 w-40 lg:w-64 pl-8 lg:pl-10 pr-3 lg:pr-4 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-700 transition-all duration-200 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400 text-sm lg:text-base"
                            type="text" name="search" id="search" placeholder="Rechercher...">
                        <div class="absolute inset-y-0 left-0 pl-2 lg:pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm lg:text-base"></i>
                        </div>
                    </div>
                </form>

                <!-- Bouton recherche mobile -->
                <button
                    class="md:hidden p-2 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                    <i class="fas fa-search text-lg"></i>
                </button>

                <!-- Bouton de thème -->
                <button onclick="toggleTheme()"
                    class="p-2 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                    <i class="fas fa-moon dark:hidden text-sm sm:text-base"></i>
                    <i class="fas fa-sun hidden dark:block text-sm sm:text-base"></i>
                </button>

                <!-- Dropdown utilisateur - caché sur mobile -->
                <div class="hidden lg:flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 hover:bg-gray-200/80 dark:hover:bg-gray-700/80 transition-all duration-200 font-medium text-sm">
                                <div
                                    class="w-6 h-6 xl:w-8 xl:h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-2 xl:mr-3 text-xs xl:text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden xl:block">{{ Auth::user()->name }}</div>
                                <div class="ms-1 xl:ms-2">
                                    <svg class="fill-current h-3 w-3 xl:h-4 xl:w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Header du dropdown avec infos utilisateur -->
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center sm:block">
                                    <!-- Avatar visible uniquement sur mobile -->
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3 sm:hidden">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm sm:text-base text-gray-900 dark:text-white font-medium truncate">
                                            {{ Auth::user()->name }}
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Lien Profile -->
                            <x-dropdown-link :href="route('profile.edit')"
                                class="flex items-center px-3 sm:px-4 py-2 sm:py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-user mr-3 text-gray-400 text-sm sm:text-base"></i>
                                <span class="text-sm sm:text-base">{{ __('Profile') }}</span>
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center px-3 sm:px-4 py-2 sm:py-3 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3 text-sm sm:text-base"></i>
                                    <span class="text-sm sm:text-base">{{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger mobile -->
                <div class="lg:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                        <i class="fas fa-bars text-lg" :class="{ 'hidden': open }"></i>
                        <i class="fas fa-times text-lg" :class="{ 'hidden': !open }"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu mobile responsive -->
        <div :class="{ 'block': open, 'hidden': !open }"
            class="lg:hidden absolute top-full left-0 right-0 bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 shadow-xl z-50">
            <div class="px-4 py-6 space-y-4">
                <!-- Barre de recherche mobile -->
                <form action="" class="md:hidden flex items-center mb-4">
                    <div class="relative flex-1">
                        <input
                            class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-100/80 dark:bg-gray-800/80 border-0 focus:ring-2 focus:ring-blue-500 text-gray-700 dark:text-gray-300 placeholder-gray-500 dark:placeholder-gray-400"
                            type="text" name="search" placeholder="Rechercher...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </form>

                <!-- Navigation links mobile -->
                <a href="{{ route('dashboard') }}"
                    class="z-10 flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200">
                    <i class="fas fa-layer-group mr-3 text-lg"></i>
                    <span class="font-medium">Espaces de travail</span>
                </a>

                <button onclick="openCreateProjectModal()"
                    class="w-full flex items-center px-4 py-3 rounded-xl text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200">
                    <i class="fas fa-plus mr-3 text-lg"></i>
                    <span class="font-medium">Créer</span>
                </button>
            </div>

            <!-- Section utilisateur mobile -->
            <div class="px-4 py-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center mb-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-2">
                    <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center px-4 py-3 rounded-xl">
                        <i class="fas fa-user mr-3 text-gray-400"></i>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center px-4 py-3 rounded-xl text-red-600 hover:text-red-700">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>


    </div>
</nav>

<script>
    function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }
    }
</script>