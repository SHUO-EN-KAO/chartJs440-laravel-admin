<?php
// php artisan make:command Command_newUserApiDataStore

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewUserApiData;

class Command_newUserApiDataStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command_newUserApiData:store {--id=} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store New User Api Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->option('id');
        $date = $this->option('date');

        $response = Http::post('http://34.100.197.14/statistics/user/new/hourly', [
            'id' => $id,
            'date' => $date,
        ]);

        $jsonNewUser = $response->json();

        // 因為資料結構中statistics內為另一層陣列
        // 所以獨立拉出另外定義後才能對其做foreach並存進DB
        $newUserStatistics = $jsonNewUser['data']['statistics'];

        // 將API回傳值存入DB
        foreach ($newUserStatistics as $data) {

            // 查詢DB現有資料紀錄
            $record = NewUserApiData::where([
                'game_id' => $jsonNewUser['data']['id'],
                'date' => $jsonNewUser['data']['date'],
                'platform' => $data['platform']
            ])->first();

            // 若有資料則update無資料則create
            if ($record) {
                $record->update([
                    'user_count' => json_encode($data['userCount'])
                ]);
            } else {
                NewUserApiData::create([
                    'game_id' => $jsonNewUser['data']['id'],
                    'date' => $jsonNewUser['data']['date'],
                    'platform' => $data['platform'],
                    'user_count' => json_encode($data['userCount'])
                ]);
            }
        }

        $this->info('New User Api Data stored successfully');
    }
}

// php artisan command_newUserApiData:store {--id=NBS} {--date=2023-10-10}
