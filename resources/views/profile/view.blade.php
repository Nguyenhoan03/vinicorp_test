<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin cá nhân</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 text-gray-900">
    @include('components.Header_admin')

    <div class="flex min-h-screen">
        @include('components.Sidebar_admin')

        <div class="flex-1 p-6">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow-md">
            @include('components.alert.alert_validate')
            @include('components.alert.alert', ['type' => 'success', 'title' => 'Thành công!'])

                <h2 class="text-xl font-semibold mb-4">Chỉnh sửa thông tin tài khoản <p class="text-red-500">{{$user->email}}</p>
                </h2>


            <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Ảnh đại diện hiện tại:</label>
                        <img
                            src="{{ asset('upload/images/' . $user->img) }}"
                            onerror="this.onerror=null;this.src='https://thumbs.dreamstime.com/z/default-avatar-profile-icon-social-media-user-image-gray-blank-silhouette-vector-illustration-305504015.jpg';"
                            alt=""
                            class="w-24 h-24 rounded-full object-cover mb-2 img_avatar"
                            id="avatarPreview">
                        <input type="file" name="img" id="showImg" class="block w-full border border-gray-300 rounded px-3 py-2">
                    </div>


                    <div class="mb-4">
                        <label class="block mb-1 font-medium">Tên:</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>



                    <div class="mb-4">
                        <button type="button" onclick="toggleChangePassword()" class="bg-gray-200 hover:bg-blue-500 hover:text-white px-4 py-2 rounded transition">
                            Đổi mật khẩu
                        </button>
                    </div>

                    <fieldset id="changePasswordBox" class="mb-6 border border-gray-200 rounded p-4 hidden">
                        <legend class="px-2 text-base font-semibold text-blue-600 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2V7a2 2 0 10-4 0v2c0 1.104.896 2 2 2zm6 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6m12 0a2 2 0 00-2-2H8a2 2 0 00-2 2m12 0H6"></path>
                            </svg>
                            Đổi mật khẩu
                        </legend>
                        <div class="grid gap-4 mt-2">
                            <div class="relative">
                                <label class="block mb-1 font-medium">Mật khẩu cũ:</label>
                                <input type="password" name="current_password" id="current_password" class="w-full border border-gray-300 rounded px-3 py-2 pr-10">
                                <button type="button" onclick="togglePassword('current_password', this)" class="absolute right-2 top-9 text-gray-500">
                                    <!-- icon -->
                                </button>
                            </div>
                            <div class="relative">
                                <label class="block mb-1 font-medium">Mật khẩu mới:</label>
                                <input type="password" name="new_password" id="new_password" class="w-full border border-gray-300 rounded px-3 py-2 pr-10">
                                <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-2 top-9 text-gray-500">
                                    <!-- icon -->
                                </button>
                            </div>
                            <div class="relative">
                                <label class="block mb-1 font-medium">Nhập lại mật khẩu mới:</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2 pr-10">
                                <button type="button" onclick="togglePassword('new_password_confirmation', this)" class="absolute right-2 top-9 text-gray-500">
                                    <!-- icon -->
                                </button>
                            </div>
                        </div>
                    </fieldset>
                    <div class="text-right">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    var showImg = document.getElementById("showImg");
    var avatarPreview = document.getElementById("avatarPreview");

    showImg.addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    function toggleChangePassword() {
        var box = document.getElementById('changePasswordBox');
        box.classList.toggle('hidden');
        var inputs = box.querySelectorAll('input');
        inputs.forEach(input => {
            input.required = !box.classList.contains('hidden');
        });
    }
</script>
<!-- validate password -->
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        var box = document.getElementById('changePasswordBox');
        if (!box.classList.contains('hidden')) {
            var oldPass = document.getElementById('current_password').value.trim();
            var newPass = document.getElementById('new_password').value.trim();
            var confirmPass = document.getElementById('new_password_confirmation').value.trim();

            if (newPass.length < 2) {
                alert('Mật khẩu mới phải có ít nhất 2 ký tự!');
                e.preventDefault();
                return;
            }
            if (newPass !== confirmPass) {
                alert('Mật khẩu mới và xác nhận không khớp!');
                e.preventDefault();
                return;
            }
            if (oldPass && oldPass === newPass) {
                alert('Mật khẩu mới phải khác mật khẩu cũ!');
                e.preventDefault();
                return;
            }
        }
    });
</script>
</html>