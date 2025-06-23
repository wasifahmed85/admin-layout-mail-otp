<div class="flex flex-wrap items-center justify-center gap-3" x-data="{ open: false }">
    <!-- Drag icon -->
    {{-- <i data-lucide="layout-grid" class="reorder cursor-move text-xl"></i> --}}
    <i class='bx bx-grid reorder cursor-move text-xl'></i>

    <!-- Dropdown container -->
    <div class="relative dropdown">
        <!-- Toggle button -->
        <button type="button" class="action-btn btn btn-ghost btn-circle text-black dark:text-white" @click="open = !open"
            @click.outside="open = false">
            <i  class="bx bx-cog text-2xl"></i>
        </button>

        <!-- Dropdown menu -->
        <ul class="menu menu-sm dropdown-content bg-base-100 dark:bg-gray-900 text-gray-800 dark:text-white rounded-box shadow min-w-44 w-fit mt-2 right-0 z-50 transition-all duration-200 ease-in-out"
            x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" style="display: none;">
            @foreach ($menuItems as $key => $menuItem)
                @php
                    $check = false;

                    // Permissions (if you want to re-enable)
                    // if (
                    //     (!isset($menuItem['permissions']) ||
                    //         !is_array($menuItem['permissions']) ||
                    //         count($menuItem['permissions']) == 0 ||
                    //         !admin()->hasAnyPermission($menuItem['permissions'])) &&
                    //     !isSuperAdmin()
                    // ) {
                    //     continue;
                    // } elseif (
                    //     (isset($menuItem['permissions']) &&
                    //         is_array($menuItem['permissions']) &&
                    //         admin()->hasAnyPermission($menuItem['permissions'])) ||
                    //     isSuperAdmin()
                    // ) {
                    //     $check = true;
                    // }

                    $parameterArray = $menuItem['params'] ?? [];
                    if (!isset($menuItem['routeName']) || $menuItem['routeName'] == '') {
                        continue;
                    } elseif ($menuItem['routeName'] === 'javascript:void(0)') {
                        $check = true;
                        $route = 'javascript:void(0)';
                    } else {
                        $check = true;
                        $route = route($menuItem['routeName'], $parameterArray);
                    }

                    $delete = false;
                    $pDelete = false;
                    $issue = false;
                    $div_id = '';

                    if (isset($menuItem['delete']) && isset($menuItem['params'][0]) && $menuItem['delete'] === true) {
                        $div_id = 'delete-form-' . $menuItem['params'][0];
                        $delete = true;
                    }

                    if (
                        isset($menuItem['p-delete']) &&
                        isset($menuItem['params'][0]) &&
                        $menuItem['p-delete'] === true
                    ) {
                        $div_id = 'delete-form-' . $menuItem['params'][0];
                        $pDelete = true;
                    }
                    if (isset($menuItem['issue']) && isset($menuItem['params'][0]) && $menuItem['issue'] === true) {
                        $div_id = 'delete-form-' . $menuItem['params'][0];
                        $issue = true;
                    }
                @endphp

                @if ($check)
                    <li>
                        <a href="{{ $delete || $pDelete || $issue ? 'javascript:void(0)' : $route }}"
                            target="{{ $menuItem['target'] ?? '' }}" title="{{ $menuItem['title'] ?? '' }}"
                            class="px-4 py-2 text-sm rounded-md text-black dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-150 ease-in-out {{ $menuItem['className'] ?? '' }} block"
                            @if ($delete) @click="open = false; confirmDelete(() => document.getElementById('{{ $div_id }}').submit())"
                            @elseif($pDelete)
                                @click="open = false; confirmPermanentDelete(() => document.getElementById('{{ $div_id }}').submit())"
                            @elseif($issue)
                                @click="open = false; confirmBookIssue(() => window.location.href = '{{ $route }}')" @endif
                            @if (isset($menuItem['data-id'])) data-id="{{ $menuItem['data-id'] }}" @endif>
                            {{ __($menuItem['label']) }}
                        </a>

                        @if ($delete || $pDelete)
                            <form id="delete-form-{{ $menuItem['params'][0] }}" action="{{ $route }}"
                                method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
