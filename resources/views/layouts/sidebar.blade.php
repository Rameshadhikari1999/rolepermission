<div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>
</div>
<div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
    <x-nav-link :href="route('users')" :active="request()->routeIs('users')">
        {{ __('Users') }}
    </x-nav-link>
</div>
{{-- @can('view permissions')
        <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
            <x-nav-link :href="route('permissions')" :active="request()->routeIs('permissions')">
                {{ __('Permissions') }}
            </x-nav-link>
        </div>
        @endcan --}}
@can('view permissions')
    <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
        <x-nav-link :href="route('onlyUserPermissions')" :active="request()->routeIs('onlyUserPermissions')">
            {{ __('User Permissions') }}
        </x-nav-link>
    </div>
@endcan

@can('view permissions')
    <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
        <x-nav-link :href="route('rolePermissions')" :active="request()->routeIs('rolePermissions')">
            {{ __('Role & Permissions') }}
        </x-nav-link>
    </div>
@endcan

@can('view accounts')
    <ul class="w-full">
        <li class="relative">
            <!-- Dropdown trigger -->
            <button id="accounts"
                class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2 px-5 py-2 cursor-pointer text-left flex items-center justify-between transition-all transition-duration-150 ease-in-out">
                <span>Accounts</span> <span id="open"><i class="fa-solid fa-plus"></i></span> <span id="close"
                    class="hidden"><i class="fa-solid fa-minus"></i></span>
            </button>
            <!-- Dropdown menu -->
            <ul id="dropdown-menu" class="absolute left-2 w-full hidden transition-all transition-duration-150 ease-in-out">
                @can('view accounts')
                    <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
                        <x-nav-link :href="route('accounts')" :active="request()->routeIs('accounts')">
                            {{ __('Cheque Inventory') }}
                        </x-nav-link>
                    </div>
                @endcan
                {{-- <li class="py-2 px-5">Bank List</li>     --}}
            </ul>
        </li>
    </ul>
@endcan





<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}

<script>
    $(document).ready(function() {
        $('#accounts').on('click', function() {
            $('#dropdown-menu').toggleClass('hidden');
            $('#open').toggleClass('hidden');
            $('#close').toggleClass('hidden');
        });
    });
</script>
