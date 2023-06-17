<?php

namespace App\Http\Controllers\Spot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Spot\Spot;
use App\Models\Day\Day;

class SpotController extends Controller
{
    public function spotsRegister(Request $request, $userId) {
        // グラフ用のカラーコードを生成
        $data = $request->all();
        $r = rand(130, 255);
        $g = rand(130, 255);
        $b = rand(180, 255);
        $color = "rgba(". (string)$r . ", " . (string)$g . ", " . (string)$b . ", " . "1)";

        Spot::insertGetId([
            "users_id" => $userId,
            "spots_name" => $data["spots_name"],
            "spots_latitude" => "None",
            "spots_longitude" => "None",
            "spots_address" => $data["spots_address"],
            "spots_url" => $data["spots_url"],
            "spots_status" => "None",
            "spots_color" => $color,
            "spots_max" => $data["spots_max"],
            "spots_count" => 0,
            "spots_day_count" => "None",
            "spots_month_count" => "None",
        ]);

       return response()->json(["message" => "Spot registration success!"], Response::HTTP_OK);
    }

    public function spotsUpdate(Request $request, $spotsId) {
        $data = $request->all();
        Spot::where("id", $spotsId)->update([
            "spots_name" => $data["spots_name"],
            "spots_address" => $data["spots_address"],
            "spots_url" => $data["spots_url"],
            "spots_max" => $data["spots_max"],
        ]);

       return response()->json(["message" => "Spot update success!"], Response::HTTP_OK);
    }

    public function spotsDelete($spotsId) {
        Spot::where("id", $spotsId)->delete();

        return response()->json(["message" => "Spot deletetion success!"], Response::HTTP_OK);
    }

    public function spotsData($spotsId) {
        $days = Day::where("spots_id", $spotsId)->get();
        $daysData = array_fill(0, 23, 0);

        for ($i = 0; $i < count($days); $i++) {
            $daysCount = explode(",", $days[$i]["days_count"]);

            for ($j = 0; $j < count($daysData); $j++) {
                $daysData[$j] = $daysData[$j] + (Int)$daysCount[$j];
            }
        }

        for ($i = 0; $i < count($daysData); $i++) {
            $daysData[$i] = $daysData[$i] / count($days);
        }

        return response()->json(["days_data" => $daysData], Response::HTTP_OK);
    }
}
