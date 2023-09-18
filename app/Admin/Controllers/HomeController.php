<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Support\Facades\Http;
use App\Models\NewUserApiData;
use App\Models\UserPaymentApiData;
use App\Admin\Forms\Test;


class HomeController extends Controller
{
    public function index(Content $content)
    {
        // return $content
        //     ->title('Dashboard')
        //     ->description('Description...')
        //     ->row(Dashboard::title())
        //     ->row(function (Row $row) {

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::environment());
        //         });

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::extensions());
        //         });

        //         $row->column(4, function (Column $column) {
        //             $column->append(Dashboard::dependencies());
        //         });
        //     });

        // 從DB讀取new_user_api_data資料
        $newUserApiDataDB = NewUserApiData::where([
            'game_id' => 'NBS',
            'date' => date('Y-m-d'),
        ])->get();
        // dd('newUserApiDataDB:', $newUserApiDataDB);

        // 若new_user_api_data沒有資料
        // 則先呼叫newUserApiDataStore建立資料再讀取
        if ($newUserApiDataDB->isEmpty()) {
            $this->newUserApiDataStore();

            $newUserApiDataDB = NewUserApiData::where([
                'game_id' => 'NBS',
                'date' => date('Y-m-d'),
            ])->get();
        }

        // 從DB讀取user_payment_api_data資料
        $userPaymentApiDataDB = UserPaymentApiData::where([
            'game_id' => 'NBS',
            'date' => date('Y-m-d'),
        ])->get();
        // dd('userPaymentApiDataDB:', $userPaymentApiDataDB);

        // 若user_payment_api_data沒有資料
        // 則先呼叫userPaymentApiDataStore建立資料再讀取
        if ($userPaymentApiDataDB->isEmpty()) {
            $this->userPaymentApiDataStore();

            $userPaymentApiDataDB = UserPaymentApiData::where([
                'game_id' => 'NBS',
                'date' => date('Y-m-d'),
            ])->get();
        }


        // 頁面呈現
        $content->title('Index');
        $content->description('Today  : ' . date('Y-m-d'));
        $content->view('chartJs440', [
            'newUserApiDataDB' => $newUserApiDataDB,
            'userPaymentApiDataDB' => $userPaymentApiDataDB,
        ]);

        return $content;
    }

    public function testResult(Content $content)
    {
        // dd('test view : testResult');

        // 從DB讀取new_user_api_data資料
        $newUserApiDataDB = NewUserApiData::where([
            'game_id' => 'NBS',
            'date' => date('Y-m-d'),
        ])->get();
        // dd('newUserApiDataDB:', $newUserApiDataDB);

        // 若new_user_api_data沒有資料
        // 則先呼叫newUserApiDataStore建立資料再讀取
        if ($newUserApiDataDB->isEmpty()) {
            $this->newUserApiDataStore();

            $newUserApiDataDB = NewUserApiData::where([
                'game_id' => 'NBS',
                'date' => date('Y-m-d'),
            ])->get();
        }

        // 從DB讀取user_payment_api_data資料
        $userPaymentApiDataDB = UserPaymentApiData::where([
            'game_id' => 'NBS',
            'date' => date('Y-m-d'),
        ])->get();
        // dd('userPaymentApiDataDB:', $userPaymentApiDataDB);

        // 若user_payment_api_data沒有資料
        // 則先呼叫userPaymentApiDataStore建立資料再讀取
        if ($userPaymentApiDataDB->isEmpty()) {
            $this->userPaymentApiDataStore();

            $userPaymentApiDataDB = UserPaymentApiData::where([
                'game_id' => 'NBS',
                'date' => date('Y-m-d'),
            ])->get();
        }


        // 頁面呈現
        $content->title('testResult');
        $content->description('Today  : ' . date('Y-m-d'));
        $content->view('chartJs440', [
            'newUserApiDataDB' => $newUserApiDataDB,
            'userPaymentApiDataDB' => $userPaymentApiDataDB,
        ]);

        return $content;
    }

    public function newUserApiDataStore()
    {
        // dd('test view : newUserApiDataStore');

        $response = Http::post('http://34.100.197.14/statistics/user/new/hourly', [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
            // 'date' => ('2023-08-10'),
            // 日期寫死測試
        ]);

        $jsonNewUser = $response->json();
        // dd($jsonNewUser);

        $dataDate = $jsonNewUser['data']['date'];
        // dd('dataDate:', $dataDate);

        // 預設表示資料未被建立
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

                // 改為表示資料已被建立
                $dataCreated = true;
            }
        }

        return [
            'jsonNewUser' => $jsonNewUser,
            'dataDate' => $dataDate,
            'dataCreated' => $dataCreated,
        ];
    }

    public function userPaymentApiDataStore()
    {
        // dd('test view: userPaymentStore');

        // API獲取資料
        $response = Http::post('http://34.100.197.14/statistics/payment/hourly', [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
        ]);
        // dd('response:', $response);

        // 處理response資料轉json
        $jsonUserPayment = $response->json();
        // dd('jsonUserPayment:', $jsonUserPayment);

        // 因為資料結構中statistics內為另一層陣列
        // 所以獨立拉出另外定義後才能對其做foreach並存進DB
        $userPaymentStatistics = $jsonUserPayment['data']['statistics'];
        // dd('userPaymentStatistics:', $userPaymentStatistics);

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

        return [
            'jsonUserPayment' => $jsonUserPayment,
        ];
    }

    public function testForm(Content $content)
    {
        $content->title('TestForm');
        $content->row(new Test());

        return $content;
    }
}
