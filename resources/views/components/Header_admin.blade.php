<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold"><a href="/"> Trang chủ </a></h1>
    <div class="flex items-center space-x-4">
        @auth
        <div class="relative group">
            <button class="flex items-center space-x-2 focus:outline-none group" >
                <img
                    src="{{ asset('upload/images/' . Auth::user()->img) }}"
                    onerror="this.onerror=null;this.src='https://thumbs.dreamstime.com/z/default-avatar-profile-icon-social-media-user-image-gray-blank-silhouette-vector-illustration-305504015.jpg';"
                    alt="avatar"
                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 shadow-sm transition duration-200"
                >
                <span class="text-base font-medium text-gray-800">{{ Auth::user()->name ?? 'nmh03' }}</span>
                <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 group-focus-within:opacity-100 transition-opacity z-50">
                <a href="{{ route('profile.view') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Cập nhật tài khoản</a>
                <a href="{{ route('profile.list_equiqment') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Danh sách thiết bị được cấp</a>
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