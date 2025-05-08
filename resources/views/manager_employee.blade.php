<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý nhân viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900">
    @include('components.Header_admin')

    <div class="flex">
        @include('components.Sidebar_admin')

        <div class="p-6 flex-1">
            <h2 class="text-2xl font-bold mb-4">Danh sách nhân viên</h2>
            <button onclick="toggleAddEmployeeForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                + Thêm nhân viên
            </button>

            <div id="addEmployeeForm" class="hidden fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Thêm nhân viên mới</h3>
                    <form method="POST" action="{{ route('employees.create') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium">Tên</label>
                                <input type="text" name="name" id="name" required class="w-full border px-3 py-2 rounded">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" id="email" required class="w-full border px-3 py-2 rounded">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium">Mật khẩu</label>
                                <input type="password" name="password" id="password" required class="w-full border px-3 py-2 rounded">
                            </div>
                            <div>
                                <label for="equipment_manager" class="block text-sm font-medium">Thiết bị quản lý</label>
                                <select name="equipment_manager" id="equipment_manager" class="w-full border px-3 py-2 rounded">
                                    @foreach ($equipment as $eq)
                                    <option value="{{ $eq->id }}">{{$eq->id}} - {{ ucfirst($eq->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium">Vai trò</label>
                                <select name="role" id="role" required class="w-full border px-3 py-2 rounded">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="img" class="block text-sm font-medium">Ảnh</label>
                                <input type="file" name="img" id="img" accept="image/*" class="w-full border px-3 py-2 rounded">
                            </div>
                        </div>
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Lưu</button>
                            <button type="button" onclick="toggleAddEmployeeForm()" class="ml-2 text-gray-600 hover:underline">Hủy</button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- end form thêm nhân viên -->

            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="w-full table-auto border-collapse shadow-sm rounded overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-sm uppercase">
                            <th class="px-4 py-3 text-left">Ảnh</th>
                            <th class="px-4 py-3 text-left">Tên</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Chức vụ</th>
                            <th class="px-4 py-3 text-left">Thiết bị</th>
                            <th class="px-4 py-3 text-left">Trạng thái</th>
                            <th class="px-4 py-3 text-left">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $employee)
                        <tr class="border-b hover:bg-gray-50 text-sm">
                            <td class="px-4 py-3">
                                <img src="{{ asset('/upload/images/' . $employee['img']) }}" alt="Ảnh" class="w-10 h-10 rounded-full object-cover border">
                            </td>
                            <td class="px-4 py-3">{{ $employee['name'] }}</td>
                            <td class="px-4 py-3">{{ $employee['email'] }}</td>

                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('employees.updateRole', $employee['id']) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{$employee['role'] == $role->name ? 'selected' : ''}}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>

                            <td class="px-4 py-3">{{ $employee['assets'] }}</td>
                            <td class="px-4 py-3">
                                @if (!empty($employee['status']))
                                <ul class="flex flex-col gap-1">
                                    @foreach ($employee['status'] as $index => $status)
                                    <li>
                                        <span class="px-2 py-1 text-xs rounded text-white {{ $status['color'] }}">
                                            {{ $status['status'] }}
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-gray-500 italic text-sm">Không có thiết bị</span>
                                @endif
                            </td>


                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ url('/employees/' . $employee['id'] . '/edit') }}"
                                    class="text-indigo-600 hover:underline text-sm">Sửa</a>
                                <form method="POST" action="{{ url('/employees/' . $employee['id']) }}" class="inline-block"
                                    onsubmit="return confirm('Xóa nhân viên này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Xoá</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 italic text-sm">
                                Không có nhân viên nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</body>
<script>
    function toggleAddEmployeeForm() {
        const form = document.getElementById('addEmployeeForm');
        form.classList.toggle('hidden');
    }
</script>

</html>