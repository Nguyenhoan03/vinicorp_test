<?php

namespace App\Http\Controllers;

use App\Exports\ExportFile;
use Illuminate\Http\Request;
use App\Imports\ImportFile;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function export(Request $request)
    {
       return Excel::download(new ExportFile($request->input('equipment_filter')), 'users.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);
        try {
            Excel::import(new ImportFile, $request->file('file'));
            return back()->with('success', 'Import thành công!');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
