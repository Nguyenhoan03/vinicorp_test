<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ImageService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
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
            }
            return redirect()->route('dashboard');
        }
        return redirect()->back()->withErrors(['error_login' => 'thông tin đăng nhập không chính xác']);
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

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'new_password' => 'nullable|string|min:2|confirmed',
    ]);

    $user = User::findOrFail($id);

    // Xử lý ảnh đại diện
    if ($request->hasFile('img')) {
        $img = $this->imageService->handleImageUpload($request);
        $user->img = $img;
    }
    $user->name = $request->name;

    // Xử lý đổi mật khẩu nếu có nhập
    if ($request->filled('new_password')) {
        // Kiểm tra đã nhập mật khẩu cũ correct chưa
        if (!$request->filled('current_password')) {
            return back()->withErrors(['current_password' => 'Vui lòng nhập mật khẩu cũ để đổi mật khẩu!'])->withInput();
        }
        // Kiểm tra mật khẩu cũ có đúng không
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng!'])->withInput();
        }
        // Kiểm tra mật khẩu mới khác mật khẩu cũ
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Mật khẩu mới phải khác mật khẩu cũ!'])->withInput();
        }
        $user->password = bcrypt($request->new_password);
    }

    $user->save();

    return redirect()->route('profile.view')->with('success', 'Cập nhật thành công!');
}
}