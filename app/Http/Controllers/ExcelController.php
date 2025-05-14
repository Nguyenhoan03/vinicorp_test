<?php

namespace App\Http\Controllers;

use App\Exports\ExportFile;
use Illuminate\Http\Request;
use App\Imports\ImportFile;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExcelController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new ExportFile($request->only(['equipment_filter','name_filter','email_filter','role_filter'])), 'users.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        try {
            Excel::import(new ImportFile, $request->file('file'));
            return back()->with('success', 'Import thành công!');
        } catch (\Throwable $e) {
            Log::error('Lỗi import Excel: ' . $e->getMessage());
            return back()->withErrors(['import_error' => 'Lỗi khi import: ' . $e->getMessage()]);
        }
    }
}
