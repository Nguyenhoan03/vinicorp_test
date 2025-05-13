@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
    <strong class="font-bold">Lỗi:</strong>
    <ul class="mt-2 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <span onclick="this.parentElement.remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
</div>
@endif