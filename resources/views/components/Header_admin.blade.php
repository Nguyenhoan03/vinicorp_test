<header class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">Quản lý nhân viên</h1>
    <div>

      <span class="mr-4">Xin chào, {{Auth::user()->name ?? 'nmh03'}}</span>
      <form action="/logout" method="post">
        @csrf
      <button type="submit" class="text-red-500">Đăng xuất</button>
      </form>
    </div>
  </header>