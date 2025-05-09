<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Services\ImageService;
use App\Traits\ModelFinder;
class ManagerEmployeeController extends Controller
{
    use ModelFinder;
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
  
    public function index(Request $request)
    {
        $filterEquipment = $request->input('equipment_filter');
        $employees = User::with(['role', 'assets'])
        ->when($filterEquipment, function ($query, $filterEquipment) {
            return $query->whereHas('assets', function ($q) use ($filterEquipment) {
                $q->where('assets.id', $filterEquipment);
            });
        })
        ->get()->map(function ($employee) {
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
        $roles = Role::select('id','name')->get();
        $equipment = Asset::select('name','id')->get();
        return view('manager_employee', [
            'data' => $employees,
            'roles' => $roles,
            'equipment' => $equipment,
        ]);
        }

    public function create(EmployeeRequest $request)
    {
        $imageName = $this->imageService->handleImageUpload($request);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role,
            'img' => $imageName,
        ]);
    
        if ($user) {
            $user->assets()->attach($request->equipment_manager);
        }
    
        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }
    public function edit(Request $request, $id)
    {
        $employee = $this->findModelOrFail(User::class, $id);
        $employee->fill($request->only(['name', 'email', 'role_id']));
        $employee->img = $this->imageService->handleImageUpload($request) ?? $employee->img;   
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
        $user = $this->findModelOrFail(User::class, $request->id);
        $user->assets()->detach(); 
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);
        $user = $this->findModelOrFail(User::class, $id);
        $user->role_id = $request->input('role_id');
        $user->save();
        return redirect()->route('employees.index')->with('success', 'Role updated successfully');
    }
}
