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
use App\Admin\Forms\NewUserForm;
use App\Admin\Forms\UserPaymentForm;
use Illuminate\Http\Request;


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
        $content->description('<strong>Today: ' . date('Y-m-d') . '</strong>');
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

    public function newUserForm(Content $content)
    {
        $content->title('New User Data');

        $content->breadcrumb(
            ['text' => 'SearchForm', 'url' => '/form/newUserForm'],
            ['text' => 'NewUser'],
        );

        $content->row(new NewUserForm());

        // 取值給view使用
        // 若有從NewUserForm傳回$date值 
        // 則可從session取值
        if (session()->has('date')) {
            $date = session('date');
            // 測$date值
            // return $date;

            // 取$result值
            $result = session('result');
            // 測$result值
            // $return_result = [
            //     'result' => $result,
            //     'dataType' => gettype($result),
            // ];
            // return $return_result;

            // 取$newAndroidUsers值
            $newAndroidUsers = session('newAndroidUsers');
            // 測$newAndroidUsers值
            // $return_newAndroidUsers = [
            //     'newAndroidUsers' => $newAndroidUsers,
            //     'dataType' => gettype($newAndroidUsers),
            // ];
            // return $return_newAndroidUsers;

            // 取$newAndroidUsers加總$sumA
            $sumA = session('sumA');
            // 測$sumA值
            // $return_sumA = [
            //     'sumA' => $sumA,
            //     'dataType' => gettype($sumA),
            // ];
            // return $return_sumA;

            // 取$newiOSUsers值
            $newiOSUsers = session('newiOSUsers');
            // 測$newiOSUsers值
            // $return_newiOSUsers = [
            //     'newiOSUsers' => $newiOSUsers,
            //     'dataType' => gettype($newiOSUsers),
            // ];
            // return $return_newiOSUsers;

            // 取$newiOSUsers加總$sumI
            $sumI = session('sumI');
            // 測$sumI值
            // $return_sumI = [
            //     'sumI' => $sumI,
            //     'dataType' => gettype($sumI),
            // ];
            // return $return_sumI;

            $content->view('newUser', [
                'result' => $result,
                'date' => $date,
                'newAndroidUsers' => $newAndroidUsers,
                'sumA' => $sumA,
                'newiOSUsers' => $newiOSUsers,
                'sumI' => $sumI,
            ]);
        }

        return $content;
    }

    public function userPaymentForm(Content $content)
    {
        $content->title('User Payment Data');

        $content->breadcrumb(
            ['text' => 'SearchForm', 'url' => '/form/userPaymentForm'],
            ['text' => 'UserPayment'],
        );

        $content->row(new UserPaymentForm());

        // 取值給view用
        // 若有從UserPaymentForm回傳$date值
        // 則可從session取值
        if ($date = session('date')) {
            // 測$date值
            // return $date;

            $result = session('result');
            // 測$result值
            // $return_result = [
            //     'result' => $result,
            //     'dataType' => gettype($result),
            // ];
            // return $return_result;

            // 取$androidUsers數組值
            $androidUsers = session('androidUsers');
            // 測$androidUsers值
            // $return_androidUsers = [
            //     'androidUsers' => $androidUsers,
            //     'dataType' => gettype($androidUsers),
            // ];
            // return $return_androidUsers;

            // 取$androidUsers加總$sumAU值
            $sumAU = session('sumAU');
            // 測$sumAU值
            // $return_sumAU = [
            //     'sumAU' => $sumAU,
            //     'dataType' => gettype($sumAU),
            // ];
            // return $return_sumAU;

            // 取$androidRev數組值
            $androidRev = session('androidRev');
            // 測$androidRev數組值
            // $return_androidRev = [
            //     'androidRev' => $androidRev,
            //     'dataType' => gettype($androidRev),
            // ];
            // return $return_androidRev;

            // 取$androidUserRev加總$sumAR值
            $sumAR = session('sumAR');
            // 測$sumAR值
            // $return_sumAR = [
            //     'sumAR' => $sumAR,
            //     'dataType' => gettype($sumAR),
            // ];
            // return $return_sumAR;

            // 取$iOSUsers數組值
            $iOSUsers = session('iOSUsers');
            // 測$iOSUsers值
            // $return_iOSUsers = [
            //     'iOSUsers' => $iOSUsers,
            //     'dataType' => gettype($iOSUsers),
            // ];
            // return $return_iOSUsers;

            // 取$iOSUsers加總$sumIU
            $sumIU = session('sumIU');
            // 測$sumIU值
            // $return_sumIU = [
            //     'sumIU' => $sumIU,
            //     'dataType' => gettype($sumIU),
            // ];
            // return $return_sumIU;

            // 取$iiOSRev數組值
            $iOSRev = session('iOSRev');
            // 測$iOSRev值
            // $return_iOSRev = [
            //     'iOSRev' => $iOSRev,
            //     'dataType' => gettype($iOSRev),
            // ];
            // return $return_iOSRev;

            // 取$iOSRev加總$sumIR值
            $sumIR = session('sumIR');
            // 測$sumIR值
            // $return_sumIR = [
            //     'sumIR' => $sumIR,
            //     'dataType' => gettype($sumIR),
            // ];
            // return $return_sumIR;

            $content->view(
                'userPayment',
                [
                    'result' => $result,
                    'date' => $date,
                    'androidUsers' => $androidUsers,
                    'sumAU' => $sumAU,
                    'androidRev' => $androidRev,
                    'sumAR' => $sumAR,
                    'iOSUsers' => $iOSUsers,
                    'sumIU' => $sumIU,
                    'iOSRev' => $iOSRev,
                    'sumIR' => $sumIR,
                ]
            );
        };

        return $content;
    }
}
