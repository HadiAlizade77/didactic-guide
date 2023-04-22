<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsvController extends Controller
{
    public function index()
    {
        $files = Storage::disk('public')->files('csv'); // assuming your CSV files are stored in the 'csv' folder in the 'public' disk
        $csvFiles = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
                $csvFiles[] = [
                    'name' => pathinfo($file, PATHINFO_FILENAME),
                    'link' => Storage::disk('public')->url($file),
                ];
            }
        }

        return response()->json($csvFiles);
    }
}