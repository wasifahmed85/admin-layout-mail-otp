
<header class="sticky top-0 z-30 pt-2 px-2">
    <div class="glass-card rounded-xl">
        <div class="flex items-center justify-between p-4 lg:p-6">
            <div class="flex items-center gap-4">
                <!-- Menu Toggle Button -->
                <button @click="toggleSidebar()"
                    class="p-2 rounded-xl hover:bg-bg-black/10 dark:hover:bg-bg-white/10 dark:text-text-white text-text-light-primary transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/20 group"
                    :aria-label="desktop ? (sidebar_expanded ? 'Collapse sidebar' : 'Expand sidebar') : (mobile_menu_open ?
                        'Close menu' : 'Open menu')">
                    <i data-lucide="menu" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                </button>

                <div class="hidden sm:block">

                    <h1 class="text-xl lg:text-2xl font-bold dark:text-text-white text-text-light-primary">
                        {{ __('Good day,') }}
                        {{ admin()->name }}
                    </h1>
                    <p class="text-text-light-secondary dark:text-text-dark-primary text-sm">
                        {{ __("Here's what's happening today") }}
                    </p>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-3">
                <button @click="$store.theme.toggleTheme()"
                    class="p-2 rounded-xl hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
                    data-tooltip="Toggle theme"
                    :title="$store.theme.current.charAt(0).toUpperCase() + $store.theme.current.slice(1) + ' mode'">
                    <i data-lucide="sun" x-show="!$store.theme.darkMode"
                        class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>
                    <i data-lucide="moon" x-show="$store.theme.darkMode"
                        class="w-5 h-5 text-text-light-primary dark:text-text-white"></i>
                </button>




                <!-- Profile -->
                <div class="relative" x-data="{ open: false }">


                    <button @click="open = !open" class="avatar">
                        <div class="w-10 h-10 border rounded-full">
                            <img src="{{ auth_storage_url(admin()->image) }}" alt=""
                                class=" object-cover w-full h-full">
                        </div>
                    </button>

                    <!-- Profile Dropdown -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="hidden absolute right-0 mt-2 w-fit min-w-40 glass-card bg-bg-white dark:bg-bg-dark-tertiary rounded-xl shadow-lg py-2 z-50"
                        :class="open ? '!block' : '!hidden'">
                        <div class="p-2">
                            <a href="#"
                                class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-user mr-3 w-4"></i>Profile
                            </a>
                            <a href="#"
                                class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 w-4"></i>Settings
                            </a>
                            <x-admin.profile-navlink route="{{ route('admin.logout') }}" logout='true'
                                name="{{ __('Sign Out') }}" />

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        <div class="px-4 lg:px-6 pb-4">
            <nav class="flex items-center gap-2 text-sm text-text-light-primary/60 dark:text-text-dark-primary">
                <a href="{{ route('admin.dashboard') }}">{{ __('Admin Dashboard') }}</a>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                <span class="text-text-light-primary dark:text-text-white capitalize"> {{ $breadcrumb }}</span>
            </nav>
        </div>
    </div>

</header>
