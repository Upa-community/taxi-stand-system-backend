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
            "spots_max" => $data["spots_max"],
            "spots_count" => 0,
            "spots_day_count" => "None",
            "spots_month_count" => "None",
        ]);

       return response()->json([
            "message" => "Spot registration success!"
       ]);
    }

    public function spotsUpdate(Request $request, $spotsId) {
        $data = $request->all();

        Spot::where("id", $spotsId)->update([
            "spots_name" => $data["spots_name"],
            "spots_address" => $data["spots_address"],
            "spots_url" => $data["spots_url"],
            "spots_max" => $data["spots_max"],
        ]);

       return response()->json([
            "message" => "Spot update success!"
       ]);
    }

    public function spotsDelete($spotsId) {
        Spot::where("id", $spotsId)->delete();

        return response()->json([
            "message" => "Spot deletetion success!"
        ]);
    }
}
