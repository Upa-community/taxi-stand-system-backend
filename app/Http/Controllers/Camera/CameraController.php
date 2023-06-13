<?php

namespace App\Http\Controllers\Camera;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Camera\Camera;

class CameraController extends Controller
{
    public function CameraRegister(Request $request, $spotsId) {
        $data = $request->all();
        Camera::insertGetId([
            "spots_id" => $spotsId,
            "cameras_name" => $data["cameras_name"],
            "cameras_url" => $data["cameras_url"],
            "cameras_status" => "None",
            "cameras_count" => 0
        ]);

       return response()->json([
            "message" => "Camera registration success!"
       ], Response::HTTP_OK);
    }
}
