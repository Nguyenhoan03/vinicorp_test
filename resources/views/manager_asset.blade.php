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

        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-4">Quản lý thiết bị</h1>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="this.parentElement.remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
            @endif

            <button onclick="toggleAddEquimentForm()" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
                + Thêm thiết bị mới
            </button>

            <!-- Form thêm thiết bị mới -->
            <div id="addDeviceForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Thêm thiết bị mới</h2>
                    <form action="" method="POST">
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
                                <option value="is_use">is_use</option>
                                <option value="break">break</option>
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
                            <a href="{ route('manager.assets.edit', $asset->id) }}" class="text-indigo-600 hover:underline">Sửa</a>
                            <form action="{ route('manager.assets.destroy', $asset->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Xóa thiết bị này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Xoá</button>
                            </form>
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

    <script>
        function toggleAddEquimentForm() {
            const form = document.getElementById('addDeviceForm');
            form.classList.toggle('hidden');
        }

        // Tự động ẩn alert sau vài giây
        window.onload = () => {
            const alertBox = document.querySelector('[role="alert"]');
            if (alertBox) {
                setTimeout(() => alertBox.remove(), 4000);
            }
        };
    </script>
</body>

</html>
