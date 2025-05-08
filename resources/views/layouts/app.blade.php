<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị hệ thống')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 min-h-screen text-gray-800">


    @include('components.Header_admin')

    <div class="flex">

        {{-- @include('components.sidebar') --}}

        <main class="flex-1 p-6 max-w-7xl mx-auto">
            @yield('content')
        </main>
    </div>

</body>

</html>
