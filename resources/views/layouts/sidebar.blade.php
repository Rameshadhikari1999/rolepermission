
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
        {{-- @can('view roles')
        <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
            <x-nav-link :href="route('roles')" :active="request()->routeIs('roles')">
                {{ __('Roles') }}
            </x-nav-link>
        </div>
        @endcan --}}
        @can('view permissions')
        <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
            <x-nav-link :href="route('rolePermissions')" :active="request()->routeIs('rolePermissions')">
                {{ __('Role & Permissions') }}
            </x-nav-link>
        </div>
        @endcan
        @can('view accounts')
        <div class="w-full space-y-1 bg-yellow-500 font-bold text-white rounded my-2">
            <x-nav-link :href="route('accounts')" :active="request()->routeIs('accounts')">
                {{ __('Accounts') }}
            </x-nav-link>
        </div>
        @endcan
