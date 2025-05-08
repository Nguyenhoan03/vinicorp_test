@php
$role = Session::get('role');
@endphp

<aside class="w-64 bg-white border-r h-screen p-4">
    <nav class="space-y-4">

        @if ($role === 'admin')
        <a href="{{ route('dashboard') }}" class="block text-blue-600 font-semibold">Bảng điều khiển</a>
        <a href="{{ route('employees.index') }}" class="block hover:text-blue-500">Quản lý nhân viên</a>
        <a href="{{ route('assets.index') }}" class="block hover:text-blue-500">Quản lý tài sản</a>
        <a href="" class="block hover:text-blue-500">Gán thiết bị</a>
        <a href="{{route('decentralization.index')}}" class="block hover:text-blue-500">Phân quyền</a>
        @elseif ($role === 'employee')
        <a href="{{ route('dashboard') }}" class="block text-blue-600 font-semibold">Bảng điều khiển</a>
        <a href="{{ route('assets.index') }}" class="block hover:text-blue-500">Quản lý tài sản</a>
        @elseif ($role === 'user')
        <a href="{{ route('employees.index') }}" class="block hover:text-blue-500">Quản lý user</a>

        @endif
    </nav>
</aside>