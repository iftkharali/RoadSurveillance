<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveillanceData;

class ImageController extends Controller
{
    public function index()
    {
        $images = SurveillanceData::all()->sortByDesc("created_at");
        return view('images.index', compact('images'));
    }
}