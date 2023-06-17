<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Spot\Spot;

class HomeController extends Controller
{
    public function homeData($usersId) {
        $spots = Spot::where("users_id", $usersId)->get([
            "id",
            "spots_name",
            "spots_latitude",
            "spots_longitude",
            "spots_address",
            "spots_url",
            "spots_status",
            "spots_color",
            "spots_max",
            "spots_count",
            "spots_day_count",
            "spots_month_count",
        ]);

        $spotsData = $this->createSpotsData($spots);
        $weekData = $this->createWeekData();
        $monthLabelsData = $this->createMonthLabelsData();
        $daylabelsData = range(0, 23);

        return response()->json([
            "spots_data" => $spotsData,
            "spots_week" => $weekData,
            "day_labels_data" => $daylabelsData,
            "month_labels_data" => $monthLabelsData
        ], Response::HTTP_OK);
    }

    public function homeSearch($usersId, $searchWord) {
        $spots = Spot::where("users_id", $usersId)->where("spots_name", "LIKE", "%" . $searchWord . "%")->get([
            "id",
            "spots_name",
            "spots_latitude",
            "spots_longitude",
            "spots_address",
            "spots_url",
            "spots_status",
            "spots_color",
            "spots_max",
            "spots_count",
            "spots_day_count",
            "spots_month_count",
        ]);

        $spotsData = $this->createSpotsData($spots);
        $weekData = $this->createWeekData();
        $monthLabelsData = $this->createMonthLabelsData();
        $daylabelsData = range(0, 23);

        return response()->json([
            "spots_data" => $spotsData,
            "spots_week" => $weekData,
            "day_labels_data" => $daylabelsData,
            "month_labels_data" => $monthLabelsData
        ], Response::HTTP_OK);
    }

    private function createSpotsData($spots) {
        $spotsData = [];

        for ($i = 0; $i < count($spots); $i++) {
            $spotsDayCount = explode(",", $spots[$i]["spots_day_count"]);
            $spotsMonthCount = explode(",", $spots[$i]["spots_month_count"]);
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
                "border_color" => $spots[$i]["spots_color"]
            ];

            array_push($spotsData, $data);
        }

        return $spotsData;
    }

    private function createWeekData() {
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

        return $weekData;
    }

    private function createMonthLabelsData() {
        $monthLabelsData = [];

        for ($i = 0; $i < 35; $i++) {
            $objDateTime = date('Y-m-d', strtotime("-$i day"));
            array_push($monthLabelsData, $objDateTime);
        }

        $monthLabelsData = array_reverse($monthLabelsData);

        return $monthLabelsData;
    }
}
