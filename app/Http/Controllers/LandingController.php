<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data sampah untuk menampilkan harga terkini di landing page
        $sampahs = Sampah::orderBy('sampah_harga_kg', 'desc')->take(6)->get();

        return view('welcome', compact('sampahs'));
    }
}
