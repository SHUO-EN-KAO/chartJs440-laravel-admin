<?php
// php artisan admin:form UserPaymentForm

namespace App\Admin\Forms;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserPaymentApiData;

class UserPaymentForm extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Search User Payment Data';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        //dump($request->all());

        // 定義表單填入之變數
        $id = $request->input('id');
        $date = $request->input('date');

        // 從DB取值
        $userPaymentApiDataDB =
            UserPaymentApiData::where('game_id', $id)
            ->where('date', $date)->get();
        // 測$userPaymentApiDataDB值
        // $return_userPaymentApiDataDB = [
        //     'userPaymentApiDataDB' => $userPaymentApiDataDB,
        //     'dataType' => gettype($userPaymentApiDataDB),
        // ];
        // return $return_userPaymentApiDataDB;

        // 若DB有資料則呈現blade
        // 若無資則顯示No Data
        if (!$userPaymentApiDataDB->isEmpty()) {
            $result = $userPaymentApiDataDB;
            // 測$result值
            // $return_result = [
            //     'result' => $result,
            //     'dataType' => gettype($result),
            // ];
            // return $return_result;

            // 取$androidUsers值
            // 從DB取出值會被laravel-admin轉為字串
            // 所以需要json_decode後才能轉成array來使用
            $androidUsers = json_decode($result[0]['user_count'], true);
            // 測$androidUsers值
            // $return_androidUsers = [
            //     'androidUsers' => $androidUsers,
            //     'dataType' => gettype($androidUsers),
            // ];
            // return $return_androidUsers;

            // 取$androidUsers值加總$sumAU值
            $sumAU = 0;
            foreach ($androidUsers as $userA) {
                $sumAU += $userA;
            };
            // 測$androidUsers值加總$sumAU值
            // $return_sumAU = [
            //     'sumAU' => $sumAU,
            //     'dataType' => gettype($sumAU),
            // ];
            // return $return_sumAU;

            // 取$androidRev值
            $androidRev = json_decode($result[0]['revenue']);
            // 測$androidRev值
            // $return_androidRev = [
            //     'androidRev' => $androidRev,
            //     'dataType' => gettype($androidRev),
            // ];
            // return $return_androidRev;

            // 取$androidRev值加總$sumAR值
            $sumAR = 0;
            foreach ($androidRev as $revA) {
                $sumAR += $revA;
            };
            // 測$sumAR值
            // $return_sumAR = [
            //     'sumAR' => $sumAR,
            //     'dataType' => gettype($sumAR),
            // ];
            // return $return_sumAR;

            // 取$iOSUsers值
            $iOSUsers = json_decode($result[1]['user_count']);
            // 測$iOSUsers值
            // $return_iOSUsers = [
            //     'iOSUsers' => $iOSUsers,
            //     'dataType' => gettype($iOSUsers),
            // ];
            // return $return_iOSUsers;

            // 取$iOSUsers值加總$sumIU值
            $sumIU = 0;
            foreach ($iOSUsers as $userI) {
                $sumIU += $userI;
            }
            // 測$sumIU值
            // $return_sumIU = [
            //     'sumIU' => $sumIU,
            //     'dataType' => gettype($sumIU),
            // ];
            // return $return_sumIU;

            // 取$iOSRev值
            $iOSRev = json_decode($result[1]['revenue']);
            // 測$iOSRev值
            // $return_iOSRev = [
            //     'iOSRev' => $iOSRev,
            //     'dataType' => gettype($iOSRev),
            // ];
            // return $return_iOSRev;

            // 取$iOSRev值加總$sumIR值
            $sumIR = 0;
            foreach ($iOSRev as $revI) {
                $sumIR += $revI;
            };
            // 測$sumIR值
            // $return_sumIR = [
            //     'sumIR' => $sumIR,
            //     'dataType' => gettype($sumIR),
            // ];
            // return $return_sumIR;

            // 給controller頁面傳值用
            return back()->with([
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
            ]);
        } else {
            admin_warning(
                'No Data in ' . $date . '!!',
                'Please select other date.'
            );

            return back();
        };
    }

    /**
     * Build a form here.
     */

    //  建立表單格式內容
    public function form()
    {
        $this->select('id', 'ID:')->options(
            [
                'NBS' => 'NBS',
                'TEST' => 'TEST',
            ]
        )->rules('required');

        $this->date('date', 'Date:')->rules('required');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */

    // 預設自動帶入表單值
    public function data()
    {
        return [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
        ];
    }
}
