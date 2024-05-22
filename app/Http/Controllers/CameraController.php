<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SurveillanceData;

class CameraController extends Controller
{
    public function index()
    {
        return view('camera');
    }

    public function capture(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $image = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'camera_' . time() . '.png';

        Storage::disk('public')->put($imageName, base64_decode($image));
        SurveillanceData::create([
            'image_path' => $imageName,
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return response()->json(['message' => 'Image saved successfully', 'image' => $imageName]);
    }
}
