@if(session($type))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">{{ $title ?? 'Thành công!' }}</strong>
    <span class="block sm:inline">{{ session($type) }}</span>
    <span onclick="this.parentElement.remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
</div>
@endif