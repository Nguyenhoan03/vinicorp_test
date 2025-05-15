<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách thiết bị được cấp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 text-gray-900">
    @include('components.Header_admin')
    <div class="flex min-h-screen">
        @include('components.Sidebar_admin')
        <!-- Sửa form update device -->

        <div id="updateDeviceForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
                <h2 class="text-xl font-semibold mb-4">Cập nhật thiết bị</h2>
                <form action="{{route('profile.update_device')}}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="edit_name" class="block mb-1 font-medium">Tên thiết bị</label>
                        <input type="text" name="name" id="edit_name" class="w-full border border-gray-300 rounded px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="edit_type" class="block mb-1 font-medium">Loại thiết bị</label>
                        <input type="text" name="type" id="edit_type" class="w-full border border-gray-300 rounded px-3 py-2" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="edit_status" class="block mb-1 font-medium">Trạng thái</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded px-3 py-2">
                            <!-- <option value="">Chọn trạng thái</option> -->
                            <option value="available">available</option>
                            <option value="in_use">in_use</option>
                            <option value="broken">broken</option>
                        </select>
                        <!-- <span id="statusError" class="text-red-500 text-sm hidden">Vui lòng chọn trạng thái.</span> -->
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="toggleAddEquimentForm()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded">Huỷ</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- ...existing code... -->
        
        <div class="flex-1 p-6">
             @include('components.alert.alert', ['type' => 'success', 'title' => 'Thành công!'])
             @include('components.alert.alert_fail', ['type' => 'error', 'title' => 'Thất bại!'])
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-lg font-semibold mb-4">Danh sách thiết bị được cấp</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left">Tên thiết bị</th>
                                <th class="px-4 py-2 text-left">Loại</th>
                                <th class="px-4 py-2 text-left">Trạng thái</th>
                                <th class="px-4 py-2 text-left">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $asset->name }}</td>
                                <td class="px-4 py-2">{{ $asset->type }}</td>
                                <td class="px-4 py-2">
                                    @if($asset->status === 'available')
                                    <span class="text-green-600">available</span>
                                    @elseif($asset->status === 'in_use')
                                    <span class="text-blue-600">in_use</span>
                                    @else
                                    <span class="text-gray-600">{{ $asset->status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <button
                                        type="button"
                                        onclick="showUpdateForm(this)"
                                        class="text-red-600 hover:underline text-sm flex items-center gap-1"
                                        data-id="{{ $asset['id'] }}"
                                        data-name="{{ $asset['name'] }}"
                                        data-type="{{ $asset['type'] }}"
                                        data-status="{{ $asset['status'] ?? '' }}">

                                        <span class="inline-block w-4 h-4 mr-1">
                                            <!-- SVG hình cây bút (edit) -->
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="text-blue-500 w-4 h-4">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0l-9.5 9.5A2 2 0 004 13.914V16a1 1 0 001 1h2.086a2 2 0 001.414-.586l9.5-9.5a2 2 0 000-2.828l-2.586-2.586zM5 15v-1.586l9-9L16.586 6l-9 9H5zm2.414-2.414l9-9L17.414 4.586l-9 9L7.414 12.586z" />
                                            </svg>
                                        </span>
                                        Update device status
                                    </button>

                                    <!-- <form action="{ route('asset.destroy', $asset->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                                    </form> -->
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">Không có thiết bị nào được cấp.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function showUpdateForm(button) {
        // Lấy dữ liệu từ button
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const type = button.getAttribute('data-type');
        const status = button.getAttribute('data-status');

        // Đổ dữ liệu vào form
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_type').value = type;
        document.getElementById('edit_status').value = status;

        // Set action cho form
        document.getElementById('editForm').action = "{{route('profile.update_device')}}?id=" + id;
        // Hiện form
        document.getElementById('updateDeviceForm').classList.remove('hidden');
    }

    // Hàm ẩn form
    function toggleAddEquimentForm() {
        document.getElementById('updateDeviceForm').classList.add('hidden');
    }
</script>

</html>