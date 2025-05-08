<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'role_id' => $employee->role_id ?? null,
                'asset_id' => optional($employee->assets->first())->id,
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



    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role_id' => 'required|exists:roles,id',
            'img' => 'nullable|image|max:2048',
        ]);
    
        $employee = User::findOrFail($id);
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->role_id = $request->role_id;
    
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/images'), $filename);
            $employee->img = $filename;
        }
        
        if ($request->has('equipment_manager')) {
            $employee->assets()->sync($request->equipment_manager);
        } else {
            $employee->assets()->detach();
        }
    
        $employee->save();
    
        return redirect()->route('employees.index')->with('success', 'Nhân viên đã được cập nhật.');
    }
    

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->assets()->detach(); 
        $user->delete();
        return response()->json(['success' => true]);
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
