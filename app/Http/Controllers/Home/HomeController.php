<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Spot\Spot;

class HomeController extends Controller
{
    public function homeData($usersId) {
        $spotsData = [];
        $monthLabelsData = [];
        $dayLabelsData = [];
        $spots = Spot::where("users_id", $usersId)->get([
            "id",
            "spots_name",
            "spots_latitude",
            "spots_longitude",
            "spots_address",
            "spots_url",
            "spots_status",
            "spots_max",
            "spots_count",
            "spots_day_count",
            "spots_month_count",
        ]);

        for ($i = 0; $i < count($spots); $i++) {
            $spotsDayCount = explode(",", $spots[$i]["spots_day_count"]);
            $spotsMonthCount = explode(",", $spots[$i]["spots_month_count"]);
            $r = rand(100, 255);
            $g = rand(100, 255);
            $b = rand(150, 255);
            $borderColor = "rgba(". (string)$r . ", " . (string)$g . ", " . (string)$b . ", " . "1)";

            $data = [
                "id" => $spots[$i]["id"],
                "spots_name" => $spots[$i]["spots_name"],
                "spots_latitude" => $spots[$i]["spots_latitude"],
                "spots_longitude" => $spots[$i]["spots_longitude"],
                "spots_address" => $spots[$i]["spots_address"],
                "spots_url" => $spots[$i]["spots_url"],
                "spots_status" => $spots[$i]["spots_status"],
                "spots_max" => $spots[$i]["spots_max"],
                "spots_count" => $spots[$i]["spots_count"],
                "spots_day_count" => $spotsDayCount,
                "spots_month_count" => $spotsMonthCount,
                "border_color" => $borderColor
            ];
            
            array_push($spotsData, $data);
        }

        for ($i = 0; $i < 24; $i++) {
            $objDateTime = date('Y-m-d', strtotime("-$i day"));
            array_push($dayLabelsData, $i);
        }

        for ($i = 0; $i < 30; $i++) {
            $objDateTime = date('Y-m-d', strtotime("-$i day"));
            array_push($monthLabelsData, $objDateTime);
        }

        $monthLabelsData = array_reverse($monthLabelsData);

        $response = [
            "spots_data" => $spotsData,
            "day_labels_data" => $dayLabelsData,
            "month_labels_data" => $monthLabelsData
        ];

        return $response;
    }
}
