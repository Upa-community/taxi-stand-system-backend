<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            "spots_count",
            "spots_day_count",
            "spots_month_count",
        ]);

        return $spots;
    }
}
