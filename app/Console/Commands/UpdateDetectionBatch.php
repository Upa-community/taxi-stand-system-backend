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
        Log::info("記録の更新処理を開始します");

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

        try {
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $response = curl_exec($curl);
            $response = json_decode($response, true);

            if (is_null($response)) {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            Log::error("detectionAPIの処理が失敗しました。");
        }

        for ($i = 0; $i < count($spots); $i++) {
            $spotsDayCount = explode(",", $spots[$i]["spots_day_count"]);
            $spotsMonthCount = explode(",", $spots[$i]["spots_month_count"]);

            // 初回
            if ($spotsDayCount[0] === "None") {
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_day_count" => $response[$i]["count"],
                ]);
            }

            if ($spotsMonthCount[0] === "None") {
                $spotsMonthCountNew = str_repeat('0,', 34);
                $spotsMonthCountNew = $spotsMonthCountNew . $response[$i]["count"];
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_month_count" => $spotsMonthCountNew,
                ]);
            }

            // １時間
            Spot::where("id", $spots[$i]["id"])->update(["spots_count" => $response[$i]["count"]]);

            // １日
            if (count($spotsDayCount) <= 24) {
                array_push($spotsDayCount, $response[$i]["count"]);
                $spotsDayCountStr = implode(',', $spotsDayCount);
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_day_count" => $spotsDayCountStr,
                ]);
            } else {
                // 35日
                $spotsDayCountInt = array_map('intval', $spotsDayCount);
                $spotsDayCountSum = array_sum($spotsDayCountInt) / 24;
                array_push($spotsMonthCount, $spotsDayCountSum);
                unset($spotsMonthCount[0]);
                $spotsMonthCountStr = implode(',', $spotsMonthCount);
                Spot::where("id", $spots[$i]["id"])->update([
                    "spots_day_count" => $response[$i]["count"],
                    "spots_month_count" => $spotsMonthCountStr
                ]);
            }
        }

        Log::info("記録の更新処理を終了します");
    }
}
