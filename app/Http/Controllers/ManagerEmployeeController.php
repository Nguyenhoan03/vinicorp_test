<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Asset;
use Illuminate\Http\Request;

class ManagerEmployeeController extends Controller
{
    public function index()
    {
        $employees = User::with(['role', 'assets'])->get()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'img' => $employee->img,
                'role' => $employee->role->name ?? 'Không có',
                'assets' => $employee->assets->pluck('name')->join(', ') ?: 'Không có thiết bị',
                'status' => $employee->assets->map(function ($asset) {
                    return [
                        'status' => $asset->status,
                        'color' => $asset->status === 'available' ? 'bg-green-500' : 'bg-gray-500',
                    ];
                })->toArray(),
            ];
        });
        $roles = Role::all();
        $equipment = Asset::select('name','id')->get();
        return view('manager_employee', ['data' => $employees, 'roles' => $roles, 'equipment' => $equipment]);
    }




    public function create(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'password' => 'required|string|min:2|confirmed',
        //     'role' => 'required|exists:roles,id',
        //     'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);


        if ($request->hasFile('img')) {
            $imageName = time() . '.' . $request->img->getClientOriginalExtension();
            $request->img->move(public_path('upload/images'), $imageName);
        } else {
            $imageName = null;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role,
            'img' => $imageName,
            
        ]);

        if($user){
            $user->assets()->attach($request->equipment_manager);
        }

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }



    public function edit($id)
    {
        return view('manager_employee.edit', ['id' => $id]);
    }

    public function destroy($id)
    {
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->input('role_id');
        $user->save();

        return redirect()->route('employees.index')->with('success', 'Role updated successfully');
    }
}
