<?php
// php artisan admin:form NewUserForm

namespace App\Admin\Forms;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\NewUserApiData;

class NewUserForm extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = 'Search New User Data';

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

        // 從DB取資料
        $newUserApiDataDB =
            NewUserApiData::where('game_id', $id)
            ->where('date', $date)
            ->get();
        // 測$newUserApiDataDB值
        // return $newUserApiDataDB;

        // 若DB有資料則呈現blade
        // 若無資則顯示No Data
        if (!$newUserApiDataDB->isEmpty()) {
            $result = $newUserApiDataDB;
            // 測$result值及類型
            // $returnResult = [
            //     'result' => $result,
            //     'dataType' => gettype($result),
            // ];
            // return $returnResult;

            // 取$newAndroidUsers值
            // 從DB取出值為會被laravel-admin轉為字串
            // 需要json_decode解析為數組
            // 才能傳值給其他頁面使用
            // 第二參數true是為了確保解碼為關聯式數組
            $newAndroidUsers = json_decode($result[0]['user_count'], true);
            // 測$newAndroidUsers值及類型
            // $returnNewAndroidUsers = [
            //     'newAndroidUsers' => $newAndroidUsers,
            //     'dataType' => gettype($newAndroidUsers),
            // ];
            // return $returnNewAndroidUsers;

            // 取$newiOSUsers值
            $newiOSUsers = json_decode($result[1]['user_count'], true);
            // 測$newiOSUsers值
            // return '$newiOSUsers:' . json_encode($newiOSUsers);

            // 取$newAndroidUsers加總
            $sumA = 0;
            foreach ($newAndroidUsers as $userA) {
                $sumA += $userA;
            };
            // 測$sumA值
            // return '$sumA:' . json_encode($sumA);

            // 取$newiOSUsers加總
            $sumI = 0;
            foreach ($newiOSUsers as $userI) {
                $sumI += $userI;
            };
            // 測$sumI值
            // return '$sumI:' . json_encode($sumI);


            // 頁面傳值用
            return back()->with([
                'result' => $result,
                'date' => $date,
                'newAndroidUsers' => $newAndroidUsers,
                'newiOSUsers' => $newiOSUsers,
                'sumA' => $sumA,
                'sumI' => $sumI,
            ]);
        } else {
            admin_warning('No Data in ' . $date . '!!','Please select other date.');
            return back();
        }
    }

    /**
     * Build a form here.
     */

    // 此處為設定表單格式內容
    public function form()
    {
        // $this->text('id','ID:')->rules('required');

        // 將input處改為下拉式選單
        $this->select('id', 'ID:')->options([
            'NBS' => 'NBS',
            'TEST' => 'TEST',
        ])->rules('required');

        $this->date('date', 'DATE:')->rules('required');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */

    // 此處為表單內預設帶入值
    public function data()
    {
        return [
            'id' => 'NBS',
            'date' => date('Y-m-d'),
        ];
    }
}
