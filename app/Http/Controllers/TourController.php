<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index()
    {
        // Placeholder: redirect to home if no tours UI is available yet
        return redirect('/');
    }

    public function show(Tour $tour)
    {
        // Placeholder: redirect to home; actual implementation may render a tour page
        return redirect('/');
    }
}
