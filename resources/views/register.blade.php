<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng ký tài khoản</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/index.css')}}">
</head>

<body>
  <div class="login-box">
    <h2>Đăng ký tài khoản</h2>
    @include('components.alert.alert_validate')
    <form method="POST" action="{{route('register')}}" id="registerForm">
      @csrf
      <div class="form-group">
        <i class='bx bx-user form-icon'></i>
        <input type="text" class="form-control" placeholder="Tên" id="name" name="name" required />
      </div>
      <div class="form-group">
        <i class='bx bx-envelope form-icon'></i>
        <input type="email" class="form-control" placeholder="Email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <i class='bx bx-lock form-icon'></i>
        <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="password" required />
        <i class='bx bx-hide toggle-password' id="togglePassword"></i>
      </div>
      <div class="form-group">
        <i class='bx bx-lock form-icon'></i>
        <input type="password" class="form-control" placeholder="Xác nhận mật khẩu" id="password_confirmation" name="password_confirmation" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
      <div class="text-end mt-2">
        <a href="/login">Đã có tài khoản? Đăng nhập</a>
      </div>
    </form>
    <div class="login-footer mt-4">
      &copy; <script>
        document.write(new Date().getFullYear());
      </script> Code bởi
      <a href="https://www.facebook.com/truongvo.vd1503/" target="_blank">NMHoan</a>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const passwordConfirmationInput = document.getElementById("password_confirmation");

    togglePassword.addEventListener("click", function() {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      passwordConfirmationInput.setAttribute("type", type);
      this.classList.toggle("bx-show");
      this.classList.toggle("bx-hide");
    });
  </script>
</body>

</html>