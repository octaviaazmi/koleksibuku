<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneratorController extends Controller
{
    public function index() {
        return view('generator.index');
    }

    public function cetakUndangan() {
        // kirimin data nama user
        $data = ['nama' => 'Octavia Nuzulul Azmi']; 
        $pdf = Pdf::loadview('generator.undangan', $data)->setPaper('a4', 'portrait');
        return $pdf->download('undangan_rapat.pdf');
    }

    public function cetakSertifikat() {
        $data = ['nama' => 'Octavia Nuzulul Azmi'];
        $pdf = Pdf::loadview('generator.sertifikat', $data)->setPaper('a4', 'landscape');
        return $pdf->download('sertifikat_penghargaan.pdf');
    }
}