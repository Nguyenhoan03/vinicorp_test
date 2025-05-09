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

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="this.parentElement.remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
            @endif
            <h2 class="text-2xl font-bold mb-4">Danh sách nhân viên</h2>

            @if(in_array('create_user', $check_permissions))
            <button onclick="toggleAddEmployeeForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
                + Thêm nhân viên
            </button>
            @endif

            <div class="mb-4 flex items-center gap-4">
                <form method="GET" action="{{ route('employees.index') }}" class="flex items-center gap-2">
                    <label for="equipment_filter" class="text-sm font-medium">Lọc theo thiết bị:</label>
                    <select name="equipment_filter" id="equipment_filter" class="border px-3 py-2 rounded">
                        <option value="">Tất cả</option>
                        @foreach ($equipment as $eq)
                        <option value="{{ $eq->id }}" {{ request('equipment_filter') == $eq->id ? 'selected' : '' }}>
                            {{ $eq->name }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lọc</button>
                </form>
            </div>

            <!-- FORM THÊM NHÂN VIÊN -->
            <div id="addEmployeeForm" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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
                                    <option value="{{ $eq->id }}">{{ $eq->id }} - {{ ucfirst($eq->name) }}</option>
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
                            <button type="button" onclick="toggleAddEmployeeForm()" class="text-gray-600 hover:underline">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM SỬA NHÂN VIÊN -->
            <div id="editEmployeeForm" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
                    <h3 class="text-lg font-semibold mb-4">Sửa thông tin nhân viên</h3>
                    <form method="POST" action="" id="editForm" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-medium">Tên</label>
                                <input type="text" name="name" id="edit_name" required class="w-full border px-3 py-2 rounded">
                            </div>
                            <div>
                                <label for="edit_email" class="block text-sm font-medium">Email</label>
                                <input type="email" name="email" id="edit_email" required readonly class="w-full border px-3 py-2 rounded bg-gray-100">
                            </div>
                            <div>
                                <label for="edit_asset_id" class="block text-sm font-medium">Thiết bị quản lý</label>
                                <select name="equipment_manager" id="edit_asset_id" class="w-full border px-3 py-2 rounded">
                                    <option value="">Không có</option>
                                    @foreach ($equipment as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->id }} - {{ $asset->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="edit_role" class="block text-sm font-medium">Vai trò</label>
                                <select name="role_id" id="edit_role" class="w-full border px-3 py-2 rounded">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="edit_img" class="block text-sm font-medium">Ảnh mới (nếu muốn thay)</label>
                                <input type="file" name="img" id="edit_img" accept="image/*" class="w-full border px-3 py-2 rounded">
                            </div>
                        </div>
                        <div class="pt-2 flex justify-end gap-2">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Cập nhật</button>
                            <button type="button" onclick="toggleEditEmployeeForm(false)" class="text-gray-600 hover:underline">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DANH SÁCH NHÂN VIÊN -->
            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="w-full table-auto border-collapse">
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
                                    <select name="role_id" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ $employee['role'] == $role->name ? 'selected' : '' }}>
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
                                    @foreach ($employee['status'] as $status)
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
                                @if(in_array('edit_user', $check_permissions))
                                <button
                                    type="button"
                                    onclick="showEditForm(this)"
                                    class="text-indigo-600 hover:underline text-sm"
                                    data-id="{{ $employee['id'] }}"
                                    data-name="{{ $employee['name'] }}"
                                    data-email="{{ $employee['email'] }}"
                                    data-role-id="{{ $employee['role_id'] ?? '' }}"
                                    data-asset-id="{{ $employee['asset_id'] ?? '' }}">
                                    Sửa
                                </button>
                                @endif
                                @if(in_array('delete_user', $check_permissions))
                                <button onclick='openDeleteRoleModal({{ $employee["id"] }})'
                                    class="text-red-600 ml-2 hover:underline focus:outline-none">
                                    Xóa
                                </button>

                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500 italic text-sm">Không có nhân viên nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleAddEmployeeForm() {
            document.getElementById('addEmployeeForm').classList.toggle('hidden');
        }

        function toggleEditEmployeeForm(show = null) {
            const modal = document.getElementById('editEmployeeForm');
            if (show === true) modal.classList.remove('hidden');
            else if (show === false) modal.classList.add('hidden');
            else modal.classList.toggle('hidden');
        }

        function showEditForm(button) {
            toggleEditEmployeeForm(true);

            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const roleId = button.getAttribute('data-role-id');
            const assetId = button.getAttribute('data-asset-id');

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = roleId;
            document.getElementById('edit_asset_id').value = assetId;

            document.getElementById('editForm').action = `/employees/${id}`;
        }
    </script>
    <script>
        function openDeleteRoleModal(roleId) {
            if (confirm('Bạn có chắc chắn muốn xóa không?')) {
                $.ajax({
                    url: "{{ route('employees.delete') }}",
                    type: "DELETE",
                    data: {
                        id: roleId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert('Xóa vai trò thành công!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                });
            }
        }
    </script>
</body>

</html>