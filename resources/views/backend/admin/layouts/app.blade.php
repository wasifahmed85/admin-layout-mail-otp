<!DOCTYPE html>
<html html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ isset($title) ? $title . ' - ' : '' }}
        {{ config('app.name', 'Dashboard Setup') }}
    </title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  
    <script>
        // On page load, immediately apply theme from localStorage to prevent flash
        (function() {
            let theme = localStorage.getItem('theme') || 'system';

            // Apply theme immediately
            if (theme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', systemPrefersDark);
                document.documentElement.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
            }
        })();
    </script>
    <script src="{{ asset('assets/js/toggle-theme.js') }}"></script>
    {{-- BoxIcon  --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

    {{-- sweetalert2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert('success', '{{ session('success') }}');
            @endif

            @if (session('error'))
                showAlert('error', '{{ session('error') }}');
            @endif

            @if (session('warning'))
                showAlert('warning', '{{ session('warning') }}');
            @endif
        });

        const content_image_upload_url = '{{ route('file.ci_upload') }}';
    </script>

    @stack('cs')
</head>

<body class="bg-gray-50 dark:bg-gray-900 font-inter antialiased" x-data="adminDashboard()" x-cloak>
    <!-- Loading Overlay -->
    {{-- <div x-show="isLoading" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-white dark:bg-gray-900 z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Loading dashboard...</p>
        </div>
    </div> --}}

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-admin::sidebar :active="$page_slug"/>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <x-admin::header :breadcrumb="$breadcrumb"/>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('assets/js/lucide-icon.js') }}"></script>
    <script>
        function adminDashboard() {
            return {

                 // Responsive state
                desktop: window.innerWidth >= 1024,
                mobile: window.innerWidth <= 768,
                tablet: window.innerWidth < 1024,
                sidebar_expanded: window.innerWidth >= 1024,
                mobile_menu_open: false,

                // App state
                searchQuery: '',
                darkMode: true,
                 handleResize() {
                    this.desktop = window.innerWidth >= 1024;
                    if (this.desktop) {
                        this.mobile_menu_open = false;
                        this.sidebar_expanded = true;
                    } else {
                        this.sidebar_expanded = false;
                    }
                },

                toggleSidebar() {
                    if (this.desktop) {
                        this.sidebar_expanded = !this.sidebar_expanded;
                    } else {
                        this.mobile_menu_open = !this.mobile_menu_open;
                    }
                },

                closeMobileMenu() {
                    if (!this.desktop) {
                        this.mobile_menu_open = false;
                    }
                },
            }
              // Initialize Lucide icons after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // if (typeof lucide !== 'undefined') {
            lucide.createIcons();
            // }
        });

        // Smooth scrolling for anchor links
        document.addEventListener('click', function(e) {
            if (e.target.matches('a[href^="#"]')) {
                e.preventDefault();
                const target = document.querySelector(e.target.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });

        }
        
    </script>

    <script src="{{ asset('assets/js/details-modal.js') }}"></script>
    @stack('js')
</body>

</html>
