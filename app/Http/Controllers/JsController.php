<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsController extends Controller
{
    // Halaman 1: Tabel HTML Biasa
    public function indexHtml()
    {
        return view('js.html_table');
    }

    // Halaman 2: DataTables
    public function indexDatatables()
    {
        return view('js.datatables');
    }

    // Halaman 3: Select & Select2 Kota
    public function indexSelect()
    {
        return view('js.select_kota');
    }
}