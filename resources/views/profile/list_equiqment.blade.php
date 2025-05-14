
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
        <div class="flex-1 p-6">
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
                                        <a href="{ route('asset.edit', $asset->id) }}" class="text-blue-600 hover:underline mr-2">Sửa</a>
                                        <form action="{ route('asset.destroy', $asset->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
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
</html>