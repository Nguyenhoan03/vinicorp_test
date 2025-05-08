    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Trang quản lý</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body class="bg-gray-100 font-sans text-gray-900">
        @include('components.Header_admin')

        <div class="flex">
            @include('components.Sidebar_admin')

            <!-- Vai trò và phân quyền -->
            <div class="container mx-auto px-4 py-6">
                <h2 class="text-2xl font-bold text-center mb-6">Quản lý Vai Trò và Quyền</h2>

                <!-- Danh sách vai trò -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Danh sách Vai Trò</h3>
                        <button onclick="openRoleModal()"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Thêm Vai Trò Mới
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="py-2 px-4 border-b">Tên Vai Trò</th>
                                    <th class="py-2 px-4 border-b">Quyền</th>
                                    <th class="py-2 px-4 border-b">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $role)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $role->name }}</td>
                                    <td class="py-2 px-4 border-b">
                                        @foreach ($role->permissions as $permission)
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-1 mt-1 rounded text-sm">
                                            {{ $permission->name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <button onclick='openEditRoleModal({{ $role->id }}, "{{ $role->name }}", @json($role->permissions->pluck("id")))'
                                            class="text-indigo-600 hover:underline focus:outline-none">
                                            Sửa
                                        </button>
                                        <button onclick='openDeleteRoleModal({{ $role->id }})'
                                            class="text-red-600 ml-2 hover:underline focus:outline-none">
                                            Xóa
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal thêm vai trò -->
            <div id="roleModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
                    <h3 class="text-xl font-semibold mb-4">Thêm Vai Trò Mới</h3>
                    <form id="roleForm" action="{{ route('decentralization.create') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="roleName" class="block font-medium mb-1">Tên Vai Trò</label>
                            <input type="text" id="roleName" name="role_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Chọn Quyền</label>
                            @foreach($permissions as $permission)
                            <label class="flex items-center space-x-2 mb-1">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox">
                                <span>{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeRoleModal()"
                                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 transition">Đóng</button>
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Lưu Vai Trò</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal sửa vai trò -->
            <div id="editRoleModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl">
                    <h3 class="text-xl font-semibold mb-4">Sửa Vai Trò</h3>
                    <form id="editRoleForm" method="POST" action="{{ route('decentralization.edit') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="editRoleName" class="block font-medium mb-1">Tên Vai Trò</label>
                            <input type="text" id="editRoleName" name="role_name"
                                class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Chọn Quyền</label>
                            <div id="editPermissionsContainer">
                                @foreach($permissions as $permission)
                                <label class="flex items-center space-x-2 mb-1">
                                    <input type="checkbox" class="edit-permission-checkbox form-checkbox"
                                        value="{{ $permission->id }}" name="permissions[]">
                                    <span>{{ $permission->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeEditRoleModal()"
                                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 transition">Đóng</button>
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Cập Nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script>
            function openRoleModal() {
                document.getElementById('roleModal').classList.remove('hidden');
                document.getElementById('roleModal').classList.add('flex');
            }

            function closeRoleModal() {
                document.getElementById('roleModal').classList.remove('flex');
                document.getElementById('roleModal').classList.add('hidden');
            }

            function openEditRoleModal(roleId, roleName, permissionIds) {
            document.getElementById('editRoleName').value = roleName;

           document.querySelectorAll('.edit-permission-checkbox').forEach(cb => cb.checked = false);

            permissionIds.forEach(id => {
                const checkbox = document.querySelector(`.edit-permission-checkbox[value="${id}"]`);
                if (checkbox) checkbox.checked = true;
            });

            const form = document.getElementById('editRoleForm');
            form.action = "{{ route('decentralization.edit') }}?id=" + roleId; 
            document.getElementById('editRoleModal').classList.remove('hidden');
            document.getElementById('editRoleModal').classList.add('flex');
        }

        function closeEditRoleModal() {
            document.getElementById('editRoleModal').classList.remove('flex');
            document.getElementById('editRoleModal').classList.add('hidden');
        }

        function openDeleteRoleModal(roleId) {
            if (confirm('Bạn có chắc chắn muốn xóa vai trò này?')) {
                $.ajax({
                    url: "{{ route('decentralization.delete') }}",
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