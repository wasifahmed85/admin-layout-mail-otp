<x-frontend::layout>
    <x-slot name="title">Home</x-slot>
    <x-slot name="page_slug">home</x-slot>

    
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4">
        
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ config('app.name', 'Dashboard') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300">
                    @if(Auth::guard('admin')->check())
                        Welcome, Admin!
                    @elseif(Auth::check()) {{-- Check for regular user login --}}
                        Welcome, User!
                    @else
                        Please select your login portal
                    @endif
                </p>
            </div>

            <div class="space-y-4">
                @if(Auth::guard('admin')->check())
                    <a href="{{ url('/admin/dashboard') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                <i data-lucide="layout-dashboard" class="w-6 h-6 text-indigo-600 dark:text-indigo-300"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Go to Admin Dashboard</h2>
                                <p class="text-gray-600 dark:text-gray-400">Access your admin panel</p>
                            </div>
                            <div class="ml-auto text-indigo-600 dark:text-indigo-400">
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>

                    <form method="POST" action="{{ url('/admin/logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-400 text-left">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <i data-lucide="log-out" class="w-6 h-6 text-red-600 dark:text-red-300"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Logout from Admin</h2>
                                    <p class="text-gray-600 dark:text-gray-400">Sign out of your admin account</p>
                                </div>
                                <div class="ml-auto text-red-600 dark:text-red-400">
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </button>
                    </form>

                @elseif(Auth::check()) {{-- If a regular user is logged in --}}
                    <a href="{{ url('user/dashboard') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i data-lucide="user" class="w-6 h-6 text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Go to User Dashboard</h2>
                                <p class="text-gray-600 dark:text-gray-400">Access your user panel</p>
                            </div>
                            <div class="ml-auto text-blue-600 dark:text-blue-400">
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>

                    <form method="POST" action="{{ url('/logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-400 text-left">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <i data-lucide="log-out" class="w-6 h-6 text-red-600 dark:text-red-300"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Logout</h2>
                                    <p class="text-gray-600 dark:text-gray-400">Sign out of your account</p>
                                </div>
                                <div class="ml-auto text-red-600 dark:text-red-400">
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </div>
                            </div>
                        </button>
                    </form>

                @else {{-- If neither admin nor regular user is logged in --}}
                    <a href="{{ url('/login') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i data-lucide="user" class="w-6 h-6 text-blue-600 dark:text-blue-300"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Log In</h2>
                                <p class="text-gray-600 dark:text-gray-400">Access your User dashboard</p>
                            </div>
                            <div class="ml-auto text-blue-600 dark:text-blue-400">
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ url('/register') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i data-lucide="user-plus" class="w-6 h-6 text-blue-600 dark:text-blue-300"></i> {{-- Changed icon for register --}}
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Register</h2>
                                <p class="text-gray-600 dark:text-gray-400">Make a new account</p>
                            </div>
                            <div class="ml-auto text-blue-600 dark:text-blue-400">
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ url('/admin/login') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-400">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                                <i data-lucide="user-cog" class="w-6 h-6 text-indigo-600 dark:text-indigo-300"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Admin Login</h2>
                                <p class="text-gray-600 dark:text-gray-400">Access admin control panel</p>
                            </div>
                            <div class="ml-auto text-indigo-600 dark:text-indigo-400">
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-frontend::layout>