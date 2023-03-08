<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spot\Spot;

class SpotController extends Controller
{
    public function spotsRegister(Request $request, $userId) {
        $data = $request->all();
        Spot::insertGetId([
            "users_id" => $userId,
            "spots_name" => $data["spots_name"],
            "spots_latitude" => "None",
            "spots_longitude" => "None",
            "spots_address" => $data["spots_address"],
            "spots_url" => $data["spots_url"],
            "spots_status" => "None",
            "spots_count" => 0,
            "spots_day_count" => "None",
            "spots_month_count" => "None",
        ]);

       return response()->json([
            "message" => "Spot registration success!"
       ]);
    }
}