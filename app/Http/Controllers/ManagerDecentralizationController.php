<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

class ManagerDecentralizationController extends Controller
{
    public function index()
    {
        $data = Role::with('permissions')->get();
        $permissions = Permission::getallPermissions();
        return view('manager_decentralization', ['data' => $data, 'permissions' => $permissions]);
    }

    public function create(RoleRequest $request)
    {
        $role = Role::create(['name' => $request->role_name]);
        $role->permissions()->attach($request->permissions);

        return redirect()->back()->with('success', 'Tạo vai trò thành công!');
    }


    public function edit(RoleRequest $request)
    {
        $role = Role::findOrFail($request->id);
        $role->update(['name' => $request->role_name]);
        $role->permissions()->sync($request->permissions);

        return redirect()->back()->with('success', 'Vai trò đã được cập nhật!');
    }


    public function delete(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['success' => true]);
    }
}
