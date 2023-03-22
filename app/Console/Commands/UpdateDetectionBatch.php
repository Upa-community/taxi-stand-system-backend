<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Spot\Spot;
use Illuminate\Support\Facades\Log;

class UpdateDetectionBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateDetection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推論結果の更新を行います';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $spots = Spot::get([
            "id",
            "spots_url",
            "spots_day_count",
            "spots_month_count"
        ]);

        $json = json_encode($spots);

        $url = env("DETECTION_API_URL") . "api/detect/";
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS =>  $json,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        $response = json_decode($response, true);
        Log::info($response);

        for ($i = 0; $i < count($spots); $i++) {
            $spotsDayCount = explode(",", $spots[$i]["spots_day_count"]);
            $spotsMonthCount = explode(",", $spots[$i]["spots_month_count"]);

            if ($spotsDayCount[0] === "None") {
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_count" => $response[$i]["count"],
                    "spots_day_count" => $response[$i]["count"],
                ]);
            }

            if ($spotsMonthCount[0] === "None") {
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_count" => $response[$i]["count"],
                    "spots_month_count" => $response[$i]["count"],
                ]);
            }

            if (count($spotsDayCount) > 24) {
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_count" => $response[$i]["count"],
                    "spots_day_count" => $response[$i]["count"],
                ]);
            } else {
                array_push($spotsDayCount, $response[$i]["count"]);
                $spotsDayCountStr = implode(',', $spotsDayCount);
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_count" => $response[$i]["count"],
                    "spots_day_count" => $spotsDayCountStr,
                ]);
            }
        }
        return $response;
    }
}
