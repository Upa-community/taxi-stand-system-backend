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
        $dayLabelsData = range(0, 23);
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
            $r = rand(130, 255);
            $g = rand(130, 255);
            $b = rand(180, 255);
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

        $week = ["日", "月", "火", "水", "木", "金", "土"];
        $weekResult = [];
        $weekData = [];

        for ($i = 0; $i < 7; $i++) {
            $objDateTime = strtotime("-$i day");
            $dayOfWeek = date('w', $objDateTime);
            array_push($weekResult, $week[$dayOfWeek]);
        }

        for ($i = 0; $i < 5; $i++) {
            $weekData = array_merge($weekData, $weekResult);
        }

        $weekData = array_reverse($weekData);

        for ($i = 0; $i < 35; $i++) {
            $objDateTime = date('Y-m-d', strtotime("-$i day"));
            array_push($monthLabelsData, $objDateTime);
        }

        $monthLabelsData = array_reverse($monthLabelsData);

        $response = [
            "spots_data" => $spotsData,
            "spots_week" => $weekData,
            "day_labels_data" => $dayLabelsData,
            "month_labels_data" => $monthLabelsData
        ];

        return $response;
    }
}
