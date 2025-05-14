<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ImageService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Traits\ModelFinder;
class AuthController extends Controller
{
    use ModelFinder;
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


    public function update(UpdateProfileRequest $request, $id)
    {
        $user = $this->findModelOrFail(User::class,$id);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng!'])->withInput();
            }
            if (Hash::check($request->new_password, $user->password)) {
                return back()->withErrors(['new_password' => 'Mật khẩu mới phải khác mật khẩu cũ!'])->withInput();
            }
            $user->password = bcrypt($request->new_password);
        }

        if ($request->hasFile('img')) {
            $user->img = $this->imageService->handleImageUpload($request);
        }

        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile.view')->with('success', 'Cập nhật thành công!');
    }
}
