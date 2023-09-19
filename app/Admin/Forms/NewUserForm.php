<?php
// php artisan admin:form NewUserForm

namespace App\Admin\Forms;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        // 定義表單傳給API之變數
        $id = $request->input('id');
        $date = $request->input('date');

        // 獲取post API來源值
        $response =
            Http::post(
                'http://34.100.197.14/statistics/user/new/hourly',
                [
                    'id' => $id,
                    'date' => $date,
                ]
            );

        // 測試$response是否有值
        // return $response;

        // 獲得API值$response轉為json array給頁面傳值使用
        $jsonData = $response->json();

        // 定義$result給頁面傳值使用
        $result = $jsonData;

        // 測試$result是否有值
        // return $result;

        // admin_success('Processed successfully.');

        // 頁面傳值用
        return back()->with(['result' => $result]);
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
