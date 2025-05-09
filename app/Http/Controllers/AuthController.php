<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
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
}
