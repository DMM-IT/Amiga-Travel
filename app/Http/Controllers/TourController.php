<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        return redirect()->route('tour-package');
    }

    public function show(Tour $tour)
    {
        return view('tour-details', ['tour' => $tour]);
    }
}
