<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ImageService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Traits\ModelFinder;
use App\Mail\PasswordChangedMail;
use App\Mail\DeviceStatusChangedMail;
use App\Models\Asset;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateDeviceRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Mail\VerifyRegisterMail;



class AuthController extends Controller
{
    use ModelFinder;
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function view_login()
    {
        return view('login');
    }


    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role) {
                $role = $user->role->name;
                $permissions = $user->role->permissions->pluck('name')->toArray();

                session([
                    'role' => $role,
                    'permissions' => $permissions,
                ]);
                return redirect('/');
            }

            // Nếu user không có role
            return redirect('/')->withErrors(['error_login' => 'Tài khoản không có quyền truy cập']);
        }

        return redirect()->back()->withErrors(['error_login' => 'Thông tin đăng nhập không chính xác']);
    }
    public function logout()
    {
        Auth::logout();
        session()->forget('role');
        return redirect('/')->with('success', 'Đăng xuất thành công');
    }
    public function profileView()
    {
        $user = Auth::user();
        return view('profile.view', ['user' => $user]);
    }


    public function update(UpdateProfileRequest $request, $id)
    {
        $user = $this->findModelOrFail(User::class, $id);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng!'])->withInput();
            }
            if (Hash::check($request->new_password, $user->password)) {
                return back()->withErrors(['new_password' => 'Mật khẩu mới phải khác mật khẩu cũ!'])->withInput();
            }
            $user->password = bcrypt($request->new_password);
            Mail::to($user->email)->send((new PasswordChangedMail($user))->from('phehoan@gmail.com', 'Ban quản trị'));
        }

        if ($request->hasFile('img')) {
            $user->img = $this->imageService->handleImageUpload($request);
        }

        $user->name = $request->name;
        $user->save();


        return redirect()->route('profile.view')->with('success', 'Cập nhật thành công!');
    }

    public function list_equiqment()
    {
        $user = Auth::user();
        $assets = $user->assets;

        return view('profile.list_equiqment', compact('assets'));
    }


    public function update_device(UpdateDeviceRequest $request)
    {
        try {
            $user = Auth::user();

            // Cập nhật trạng thái thiết bị
            $asset = Asset::where('name', $request->name)->first();
            if (!$asset) {
                return back()->with('error', 'Thiết bị không tồn tại!');
            }
            $asset->status = $request->status;
            $asset->save();

            // Gửi mail cho admin
            $adminRoleId = Role::where('name', 'admin')->value('id');
            $admin_emails = User::where('role_id', $adminRoleId)->pluck('email')->toArray();
            $this->sendDeviceStatusMail($admin_emails, $user, $asset->name, $request->status);

            // Gửi mail cho tất cả user quản lý thiết bị (bao gồm cả user thay đổi thiết bị)
            $all_user_emails = $asset->users()->pluck('email')->unique()->toArray();
            $this->sendDeviceStatusMail($all_user_emails, $user, $asset->name, $request->status);

            return back()->with('success', 'Cập nhật thiết bị thành công!');
        } catch (\Throwable $throw) {
            Log::error("Lỗi cập nhật thiết bị: " . $throw->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật thiết bị!');
        }
    }

    protected function sendDeviceStatusMail($emails, $user, $assetName, $status)
    {
        foreach ($emails as $email) {
            try {
                Mail::to($email)->queue(
                    (new DeviceStatusChangedMail($user, $assetName, $status))
                        ->from('phehoan@gmail.com', 'Ban quản trị')
                );
            } catch (\Throwable $e) {
                Log::error("Không gửi được mail tới $email: " . $e->getMessage());
            }
        }
    }

    public function view_register()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:2|confirmed',
        ]);

        $token = Str::random(64);
        // Lưu tạm thông tin đăng ký vào cache (hoặc session)
        Cache::put("pending_user_{$token}", [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ], now()->addMinutes(60)); // Hết hạn sau 60 phút

        // Gửi email xác minh
        Mail::to($request->email)->send(new VerifyRegisterMail($token));

         return redirect('/login')->with('info', 'Đã gửi email xác minh. Vui lòng kiểm tra và xác thực để hoàn tất đăng ký.');
    }
}
