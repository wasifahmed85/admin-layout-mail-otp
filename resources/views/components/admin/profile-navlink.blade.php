@props(['route' => '', 'active' => '', 'logout' => false, 'name' => ''])

@if ($logout)
    <div class="border-t border-border-black/10 dark:border-border-white/10 my-2"></div>
    <a href="javascript:void(0)"
        class="block px-4 py-2 text-text-dark-secondary dark:text-text-white hover:bg-bg-black/10 dark:hover:bg-bg-white/10 transition-colors mx-1 rounded-md"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ $name }}</a>

    <form id="logout-form" action="{{ $route }}" method="POST" style="display: none;">
        @csrf
    </form>
@else
    <a href="{{ $route }}"
        class="block px-4 py-2 text-text-dark-secondary dark:text-text-white hover:bg-bg-black/10 dark:hover:bg-bg-white/10 transition-colors mx-1 rounded-md">{{ $name }}</a>
@endif
