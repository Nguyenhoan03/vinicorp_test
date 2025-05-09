<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý nhân viên</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>

<body class="bg-gray-100 text-gray-900">
    @include('components.Header_admin')

    <div class="flex">

        @include('components.Sidebar_admin')

        <div class="container mx-auto px-4">


            @include('components.alert', ['type' => 'success_create_manager_asset', 'title' => 'Thành công!'])
            @include('components.alert', ['type' => 'success_edit_manager_asset', 'title' => 'Thành công!'])
            <h1 class="text-2xl font-bold mb-4">Quản lý thiết bị</h1>
            @if(in_array('create_asset', $check_permissions))
            <button onclick="toggleAddEquimentForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                + Thêm thiết bị mới
            </button>
            @endif

            <!-- Form thêm thiết bị mới -->
            <div id="addDeviceForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Thêm thiết bị mới</h2>
                    <form action="{{route('assets.create')}}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block mb-1 font-medium">Tên thiết bị</label>
                            <input type="text" name="name" id="name" required class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="type" class="block mb-1 font-medium">Loại thiết bị</label>
                            <input type="text" name="type" id="type" required class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block mb-1 font-medium">Trạng thái</label>
                            <select name="status" id="status" required class="w-full border border-gray-300 rounded px-3 py-2">
                                <option value="available">available</option>
                                <option value="in_use">in_use</option>
                                <option value="broken">broken</option>
                            </select>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="toggleAddEquimentForm()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Huỷ</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bảng thiết bị -->
            <table class="w-full table-auto border-collapse bg-white shadow-md rounded">
                <thead class="bg-gray-200 text-left">
                    <tr>
                        <th class="px-4 py-2">Tên thiết bị</th>
                        <th class="px-4 py-2">Loại</th>
                        <th class="px-4 py-2">Trạng thái</th>
                        <th class="px-4 py-2">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $asset)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $asset->name }}</td>
                        <td class="px-4 py-2">{{ $asset->type }}</td>
                        <td class="px-4 py-2">{{ $asset->status }}</td>
                        <td class="px-4 py-2">
                            @if(in_array('edit_asset', $check_permissions))
                            <button onclick="showEditForm({{ $asset->id }}, '{{ $asset->name }}', '{{ $asset->type }}', '{{ $asset->status }}')" class="text-indigo-600 hover:underline">
                                Sửa
                            </button>
                            @endif
                            @if(in_array('delete_asset', $check_permissions))
                            <button
                                onclick="openDeleteModal({{ $asset->id }}, '{{ route('assets.delete') }}', 'thiết bị')"
                                class="text-red-600 ml-2 hover:underline focus:outline-none">
                                Xóa
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Không có thiết bị nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal sửa thiết bị -->
    <div id="editDeviceForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4">Sửa thiết bị</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="edit_name" class="block mb-1 font-medium">Tên thiết bị</label>
                    <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="edit_type" class="block mb-1 font-medium">Loại thiết bị</label>
                    <input type="text" name="type" id="edit_type" required class="w-full border border-gray-300 rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label for="edit_status" class="block mb-1 font-medium">Trạng thái</label>
                    <select name="status" id="edit_status" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="available">available</option>
                        <option value="in_use">in_use</option>
                        <option value="broken">broken</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="toggleEditForm()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Huỷ</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function toggleAddEquimentForm() {
            const form = document.getElementById('addDeviceForm');
            form.classList.toggle('hidden');
        }

        window.onload = () => {
            const alertBox = document.querySelector('[role="alert"]');
            if (alertBox) {
                setTimeout(() => alertBox.remove(), 4000);
            }
        };
    </script>
    <!-- script modal edit -->
    <script>
        function toggleEditForm() {
            const form = document.getElementById('editDeviceForm');
            form.classList.toggle('hidden');
        }

        function showEditForm(id, name, type, status) {
            // Mở form
            toggleEditForm();

            // Set form action
            const form = document.getElementById('editForm');
            form.action = `/${id}`;
            form.action = "{{ route('assets.edit') }}?id=" + id;


            // Đổ dữ liệu vào input
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_status').value = status;
        }
    </script>
    <script src="{{ asset('js/delete.js') }}"></script>

</body>

</html>