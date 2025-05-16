<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quên mật khẩu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 400px;">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="text-center mb-4">Quên mật khẩu</h4>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
          <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email đăng ký</label>
            <input type="email" class="form-control" name="email" required autofocus>
          </div>
          <button type="submit" class="btn btn-primary w-100">Gửi liên kết đặt lại mật khẩu</button>
        </form>

        <div class="text-center mt-3">
          <a href="{{ route('view_login') }}">Quay lại đăng nhập</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
