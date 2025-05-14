<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Quản lý nhân viên</h1>
    <div class="flex items-center space-x-4">
        @auth
            <div class="relative group">
                <button class="flex items-center space-x-2 focus:outline-none">
                    <span>👤 {{ Auth::user()->name ?? 'nmh03' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity z-50">
                    <a href="{{ route('profile.view') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cập nhật tài khoản</a>
                    <a href="{{ route('password.change') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Đổi mật khẩu</a>
                    <form action="/logout" method="post" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">Đăng xuất</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="text-green-500 hover:underline">Đăng nhập</a>
        @endauth
    </div>
</header>