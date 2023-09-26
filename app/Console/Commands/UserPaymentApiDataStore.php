<?php
// php artisan make:command UserPaymentApiDataStore

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\UserPaymentApiData;

class UserPaymentApiDataStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userPaymentApiData:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store User Payment Api Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // API獲取資料
        $response = Http::post('http://34.100.197.14/statistics/payment/hourly', [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
        ]);

        // 處理response資料轉json
        $jsonUserPayment = $response->json();

        // 因為資料結構中statistics內為另一層陣列
        // 所以獨立拉出另外定義後才能對其做foreach並存進DB
        $userPaymentStatistics = $jsonUserPayment['data']['statistics'];

        // 將API資料存入DB
        foreach ($userPaymentStatistics as $data) {

            // 查現有DB有無資料
            $record = UserPaymentApiData::where([
                'game_id' => $jsonUserPayment['data']['id'],
                'date' => $jsonUserPayment['data']['date'],
                'platform' => $data['platform'],
            ])->first();

            // 若有資料則update無資料則create
            if ($record) {
                $record->update([
                    // 因為資料庫這兩個欄位已設定為存入屬性為json
                    // 所以資料需要先json_encode才能存入
                    'user_count' => json_encode($data['userCount']),
                    'revenue' => json_encode($data['revenue']),
                ]);
            } else {
                UserPaymentApiData::create([
                    'game_id' => $jsonUserPayment['data']['id'],
                    'date' => $jsonUserPayment['data']['date'],
                    'platform' => $data['platform'],
                    'user_count' => json_encode($data['userCount']),
                    'revenue' => json_encode($data['revenue']),
                ]);
            }
        }

        $this->info('User Payment Api Data stored successfully');
    }
}

// php artisan userPaymentApiData:store
