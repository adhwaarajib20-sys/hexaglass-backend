<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class DownloadController extends Controller
{
    public function pdf($filename)
    {
        $filePath = public_path('img/' . $filename);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
