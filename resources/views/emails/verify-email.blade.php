<div style="max-width: 400px; margin: 60px auto; padding: 24px; background-color: #fff; border-radius: 12px; box-shadow: 0 0 12px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
    <h2 style="font-size: 24px; color: #333; text-align: center; margin-bottom: 20px;">Xác minh Email</h2>

    @if (session('success'))
        <div style="margin-bottom: 16px; padding: 12px; background-color: #d1fae5; border: 1px solid #10b981; color: #065f46; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    <p style="color: #555; font-size: 14px; text-align: center; margin-bottom: 24px;">
        Một email xác minh đã được gửi đến địa chỉ bạn đã đăng ký. Vui lòng kiểm tra hộp thư của bạn.
    </p>

    <form method="POST" action="{{ route('verification.send') }}" style="text-align: center;">
        @csrf
        <button type="submit"
            style="background-color: #2563eb; color: #fff; padding: 10px 20px; font-size: 14px; border: none; border-radius: 6px; cursor: pointer;">
            Gửi lại email xác minh
        </button>
    </form>
</div>
