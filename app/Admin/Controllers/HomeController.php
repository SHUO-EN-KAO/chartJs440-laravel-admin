<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Http;
use App\Models\NewUserApiData;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Dashboard')
            ->description('Description...')
            ->row(Dashboard::title())
            ->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }

    public function testResult(Content $content)
    {
        // dd('test view');

        $response = Http::post('http://34.100.197.14/statistics/user/new/hourly', [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
        ]);

        $jsonNewUser = $response->json();
        // dd($jsonNewUser);

        // 因為資料結構中statistics內為另一層陣列
        // 所以獨立拉出另外定義後才能對其做foreach並存進DB
        $newUserStatistics = $jsonNewUser['data']['statistics'];
        // dd('newUserStatistics:', $newUserStatistics);

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

        // 從DB提取資料
        $newUserDataFromDB = NewUserApiData::all();

        $content->title('testResult');
        $content->description('Today  : ' . date('Y-m-d'));

        // 直接取用API傳值呈現view
        $content->view('chartJs440', [
            'jsonNewUser' => $jsonNewUser,
            'newUserDataFromDB' => $newUserDataFromDB,
        ]);

        return $content;
    }

    public function newUserApiDataStore(Content $content)
    {
        // dd('test newUserApiDataStore view');

        $response = Http::post('http://34.100.197.14/statistics/user/new/hourly', [
            'id' => 'NBS',
            // 'date' => date('Y-m-d'),
            'date' => ('2023-09-09'),
            // 日期寫死測試
        ]);

        $jsonNewUser = $response->json();
        // dd($jsonNewUser);

        // 預設表示資料未被創建
        $dataCreated = false;

        // 因為資料結構中statistics內為另一層陣列
        // 所以獨立拉出另外定義後才能對其做foreach並存進DB
        $newUserStatistics = $jsonNewUser['data']['statistics'];
        // dd('newUserStatistics:', $newUserStatistics);

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

                // 改為表示資料已被創建
                $dataCreated = true;
            }
        }

        // 頁面顯示結果
        if ($dataCreated) {
            return $content->row('data created');
        } else {
            return $content->row('data updated');
        }
    }
}
