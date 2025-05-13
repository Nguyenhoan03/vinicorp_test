@if ($errors->has($type))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">{{ $title ?? 'Lỗi' }}</strong>
        <span class="block sm:inline">{{ $errors->first($type) }}</span>
        <span onclick="this.parentElement.remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
    </div>
@endif
