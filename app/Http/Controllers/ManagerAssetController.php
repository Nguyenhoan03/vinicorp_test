<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\ImageService;
use App\Http\Requests\AssetRequest;
use Illuminate\Http\Request;
use App\Traits\ModelFinder;
class ManagerAssetController extends Controller
{
    use ModelFinder;
    protected $imageService;
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $data = Asset::all();
        return view('manager_asset', ['data' => $data]);
    }
    public function create(AssetRequest $request)
    {
        try {
            Asset::create($request->validated());
        return redirect()->back()->with('success_create_manager_asset', 'Tạo tài sản thành công!');    
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error_create_ManagerAsset' => 'Lỗi khi thêm: ' . $e->getMessage()]);
        }
    }
    public function edit(AssetRequest $request)
    {
        $asset = Asset::findOrFail($request->id);
        $asset->update($request->validated());
        return redirect()->back()->with('success_edit_manager_asset', 'Cập nhật tài sản thành công!');
    }

    public function delete(Request $request)
    {
        $asset = $this->findModelOrFail(Asset::class,$request->id);
        $asset->users()->detach();
        $asset->delete();
        return response()->json(['success' => true]);
    }
}
