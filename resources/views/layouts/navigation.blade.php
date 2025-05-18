<nav x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b-[1px] border-[#262981] dark:border-gray-700 text-[#262981] h-[64px] fixed w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between ">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('logo-kanboard.png') }}" alt="kanboard-logo" class="h-14 object-cover">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 ms-10 lg:flex">
                    <p class="cursor-pointer">Espaces de travail</p>
                    <p class="cursor-pointer">Récent</p>
                    <p class="cursor-pointer nav-create-table">Crée</p>
                </div>
            </div>

            <div class="flex items-center">
                <form action="" class="flex items-center mr-4">
                    <input
                        class="h-8 rounded-l-lg rounded-r-lg sm:rounded-r-none px-2 py-0 box-border bg-gray-200 border-hidden w-26"
                        type="text" name="search" id="search" placeholder="Rechercher"><!--
                    --><button
                        class="hidden sm:block border h-8 px-2 border-hidden box-border text-white bg-[#262981] text-sm">Chercher</button>
                </form>

                <!-- Settings Dropdown -->
                <div class="hidden lg:flex items-center ms-6">

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center ">
                    <i class="fas fa-bars lg:hidden text-xl ml-6 text-[#262981] w-[17.5px]" :class="{ 'hidden': open }"></i>
                    <i class="fas fa-xmark lg:hidden text-xl ml-6 text-[#262981] w-[17.5px]" :class="{ 'hidden': !open }"></i>
                </button>
            </div>

        </div>
        <!-- Responsive Navigation Menu -->
        <div :class="{ 'fixed': open, 'hidden': !open }" class=" bg-white w-52 lg:hidden right-1">
            <div class="pt-2 pb-3 space-y-1">
                {{-- {{ __('Home') }} --}}
                <p class="ml-4">Espaces de travail</p>
                <p class="ml-4">Récent</p>
                <p class="ml-4 nav-create-table">Crée</p>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>


</nav>
