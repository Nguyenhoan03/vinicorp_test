<div style="max-width: 400px; margin: 60px auto; padding: 24px; background-color: #fff; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <h2 style="font-size: 24px; color: #333; text-align: center; margin-bottom: 20px;">Xác minh Email</h2>

    <p style="color: #555; font-size: 14px; text-align: center; margin-bottom: 24px;">
        Nhấn vào nút dưới để xác minh email và tạo tài khoản.
    </p>

    <div style="text-align: center;">
        <a href="{{ url('/register/verify/' . $token) }}" style="background-color: #2563eb; color: #fff; padding: 10px 20px; font-size: 14px; text-decoration: none; border-radius: 6px; display: inline-block;">
            Xác minh Email
        </a>
    </div>
</div>
