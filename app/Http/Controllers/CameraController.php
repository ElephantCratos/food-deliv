<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CameraController extends Controller
{
    public function showCamera()
    {
        return view('kitchen.camera'); // путь к вашей вьюшке камеры
    }
}
