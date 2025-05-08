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
}
