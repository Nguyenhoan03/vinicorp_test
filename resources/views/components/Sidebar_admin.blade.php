<aside class="w-64 bg-white border-r h-screen p-4">
    <nav class="space-y-4">
        @if(in_array('view_dashboard', $check_permissions))
        <a href="{{ route('dashboard') }}"
           class="block {{ request()->routeIs('dashboard') ? 'text-blue-600 font-semibold' : 'hover:text-blue-500 text-gray-700' }}">
           Bảng điều khiển
        </a>
        @endif

        @if(in_array('view_user', $check_permissions))
        <a href="{{ route('employees.index') }}"
           class="block {{ request()->routeIs('employees.index') ? 'text-blue-600 font-semibold' : 'hover:text-blue-500 text-gray-700' }}">
           Quản lý nhân viên
        </a>
        @endif

        @if(in_array('view_asset', $check_permissions))
        <a href="{{ route('assets.index') }}"
           class="block {{ request()->routeIs('assets.index') ? 'text-blue-600 font-semibold' : 'hover:text-blue-500 text-gray-700' }}">
           Quản lý tài sản
        </a>
        @endif

        @if(in_array('view_decentralization', $check_permissions))
        <a href="{{ route('decentralization.index') }}"
           class="block {{ request()->routeIs('decentralization.index') ? 'text-blue-600 font-semibold' : 'hover:text-blue-500 text-gray-700' }}">
           Phân quyền
        </a>
        @endif
    </nav>
</aside>
