<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;

class ManagerAssetController extends Controller
{
    public function index()
    {
        $data = Asset::all();
        return view('manager_asset', ['data' => $data]);
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        Asset::create($request->all());

        return redirect()->back()->with('success_create_manager_asset', 'Tạo tài sản thành công!');
    }
    public function edit(Request $request)
    {
        $asset = Asset::findOrFail($request->id);
        $asset->name = $request->input('name');
        $asset->type = $request->input('type');
        $asset->status = $request->input('status');
        $asset->save();

        return redirect()->back()->with('success_edit_manager_asset', 'Cập nhật tài sản thành công!');
    }
    public function delete(Request $request)
    {
        $asset = Asset::findOrFail($request->id);
        $asset->users()->detach(); 
        $asset->delete();
        return response()->json(['success' => true]);
    }
}
