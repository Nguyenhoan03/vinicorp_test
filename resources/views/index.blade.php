<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng nhập quản trị</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #3a7bd5, #3a6073);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background: white;
      border-radius: 12px;
      padding: 40px 30px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      max-width: 400px;
      width: 100%;
    }

    .login-box h2 {
      text-align: center;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .form-control {
      border-radius: 8px;
    }

    .form-icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      font-size: 18px;
      color: #6c757d;
    }

    .form-group {
      position: relative;
      margin-bottom: 20px;
    }

    .form-group input {
      padding-left: 40px;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #6c757d;
    }

    .login-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #555;
    }

    .login-footer a {
      color: #007bff;
      text-decoration: none;
    }

    .login-footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-box">
    <h2>Đăng nhập hệ thống</h2>
    <form method="POST" action="/login" id="loginForm">
      @csrf
      <div class="form-group">
        <i class='bx bx-user form-icon'></i>
        <input type="email" class="form-control" placeholder="Tài khoản quản trị" id="email" name="email" required />
      </div>
      <div class="form-group">
        <i class='bx bx-lock form-icon'></i>
        <input type="password" class="form-control" placeholder="Mật khẩu" id="password" name="password" required />
        <i class='bx bx-hide toggle-password' id="togglePassword"></i>
      </div>
      <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
      <div class="text-end mt-2">
        <a href="/forgot.html">Quên mật khẩu?</a>
      </div>
    </form>
    <div class="login-footer mt-4">
      &copy; <script>document.write(new Date().getFullYear());</script> Code bởi 
      <a href="https://www.facebook.com/truongvo.vd1503/" target="_blank">NMHoan</a>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.classList.toggle("bx-show");
      this.classList.toggle("bx-hide");
    });
  </script>
</body>
</html>
