<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Traits\ModelFinder;

class ManagerDecentralizationController extends Controller
{
    use ModelFinder;
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
        $role = $this->findModelOrFail(Role::class, $request->id);
        $role->update(['name' => $request->role_name]);
        $role->permissions()->sync($request->permissions);

        return redirect()->back()->with('success', 'Vai trò đã được cập nhật!');
    }


    public function delete(Request $request)
    {
        $role = $this->findModelOrFail(Role::class, $request->id);
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['success' => true]);
    }
}
