<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function index()
    {
        return view('manual.index');
    }

    public function download()
    {
        $file = public_path('SprintZone_Manual_Book.pdf');

        if (file_exists($file)) {
            return response()->file($file, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        abort(404, 'File tidak ditemukan');
    }
}
