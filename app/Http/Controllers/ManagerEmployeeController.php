<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Services\ImageService;
use App\Traits\ModelFinder;
use Illuminate\Support\Facades\Log;

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
        $filterUser = $request->only(['equipment_filter','name_filter','email_filter','role_filter']);
        $employees = User::FilterUser($filterUser)
            ->get()->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'img' => $employee->img,
                    'role' => $employee->role->name ?? 'Không có',
                    'role_id' => $employee->role_id ?? null,
                    'asset_ids' => $employee->assets->pluck('id')->toArray(),
                    'assets' => $employee->assets->pluck('name')->join(', ') ?: 'Không có thiết bị',
                    'status' => $employee->assets->map(function ($asset) {
                        return [
                            'status' => $asset->status,
                            'color' => $asset->status === 'available' ? 'bg-green-500' : 'bg-gray-500',
                        ];
                    })->toArray(),
                ];
            });
        $roles = Role::select('id', 'name')->get();
        $equipment = Asset::select('name', 'id')->get();
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
            'role_id' => $request->role_id,
            'img' => $imageName,
        ]);

        if ($user) {
            $user->assets()->attach($request->equipment_manager);
        }

        return redirect()->route('employees.index')->with('success', 'Thêm nhân viên thành công!');
    }
    public function edit(EmployeeRequest $request, $id)
    {
        $employee = $this->findModelOrFail(User::class, $id);
        $employee->fill($request->only(['name', 'role_id']));
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
        if ($user->img && file_exists(public_path('upload/images/' . $user->img))) {
            unlink(public_path('upload/images/' . $user->img));
        }
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
