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
        dump('サンプルコマンド実行');
        $spots = Spot::get([
            "id",
            "spots_url",
        ]);

        dump($spots);

        $url = env("DETECTION_API_URL") . "api/detect/";
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $spots,
            CURLOPT_RETURNTRANSFER => true,
        );

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        Log::info($response);
        dump('サンプルコマンド実行syu');

        return 0;
    }
}
