<?php

namespace App\Http\Controllers;

use App\Exports\DomainsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reports.export');
    }

    public function export(Request $request, string $type): BinaryFileResponse
    {
        $format = $request->string('format', 'xlsx')->toString();
        $filename = "report-{$type}-".now()->format('Y-m-d-His');

        $export = new DomainsExport($request, $type);

        return match ($format) {
            'csv' => Excel::download($export, "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV),
            default => Excel::download($export, "{$filename}.xlsx"),
        };
    }
}
