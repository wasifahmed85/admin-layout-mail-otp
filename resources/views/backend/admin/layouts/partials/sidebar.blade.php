<aside class="transition-all duration-300 ease-in-out z-50 max-h-screen py-2 pl-2"
    :class="{
        // 'relative': desktop,
        'w-72': desktop && sidebar_expanded,
        'w-20': desktop && !sidebar_expanded,
        'fixed top-0 left-0 h-full': !desktop,
        'w-72 translate-x-0': !desktop && mobile_menu_open,
        'w-72 -translate-x-full': !desktop && !mobile_menu_open,
    }">

    <div class="sidebar-glass-card bg-bg-white dark:bg-bg-black h-full custom-scrollbar rounded-xl overflow-y-auto">
        <!-- Sidebar Header -->
        <a href="{{ route('admin.dashboard') }}" class="p-4 border-b border-white/10 inline-block">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 glass-card shadow inset-shadow-lg bg-bg-white dark:bg-bg-black p-0 rounded-xl flex items-center justify-center">
                    <i data-lucide="zap" class="!w-4 !h-4"></i>
                </div>
                <div x-show="(desktop && sidebar_expanded) || (!desktop && mobile_menu_open)"
                    x-transition:enter="transition-all duration-300 delay-75"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition-all duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    <h1 class="text-xl font-bold text-text-light-primary dark:text-text-white">{{ __('MakTech') }}</h1>
                    <p class="text-text-light-secondary dark:text-text-dark-primary text-sm">{{ __('Admin Dashboard') }}
                    </p>
                </div>
            </div>
        </a>



        <!-- Navigation Menu -->
        <nav class="p-2 space-y-2 ">
            <!-- Dashboard -->

            {{-- 1. SINGLE NAVLINK (replaces your original single-navlink) --}}
            <x-admin.navlink type="single" icon="layout-dashboard" name="Dashboard" :route="route('admin.dashboard')"
                active="admin-dashboard" :page_slug="$active" />

            {{-- 2. SIMPLE DROPDOWN (multiple items under one parent) --}}

            <x-admin.navlink type="dropdown" icon="users" name="Admin Management" :page_slug="$active"
                :items="[
                    [
                        'name' => 'Admin',
                        'route' => route('am.admin.index'),
                        'icon' => 'user',
                        'active' => 'admin',
                    ],
                ]" />
        </nav>
    </div>
     <!-- User Profile -->
     <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 dark:border-gray-700">
         <div class="flex items-center space-x-3 transition-all duration-20 ease-in-out"
             :class="{ 'hidden': !sidebar_expanded }">
             <div>
                 <img src="{{ auth_storage_url(admin()->image) }}" alt=""
                     class="w-10 h-10 rounded-full ring-2 ring-primary-500">
             </div>
             <div class="transition-all duration-20 ">
                 <p class="text-sm font-medium text-gray-900 dark:text-white">Admin User</p>
                 <p class="text-xs text-gray-500 dark:text-gray-400">admin@company.com</p>
             </div>
         </div>
     </div>
 </aside>
