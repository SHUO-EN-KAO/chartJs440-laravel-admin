<?php
// php artisan admin:form UserPaymentForm

namespace App\Admin\Forms;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        // 定義表單填入後傳給API之變數
        $id = $request->input('id');
        $date = $request->input('date');

        // 從API來源取值
        $response = Http::post(
            'http://34.100.197.14/statistics/payment/hourly',
            [
                'id' => $id,
                'date' => $date,
            ]
        );
        // 測$response是否有值
        // return $response;

        // 測$date是否有值
        // return $date;

        // 轉json array給頁面傳值用
        $jsonData = $response->json();

        // 定義$result給頁面傳值用
        $result = $jsonData;
        // 測$result 是否有值
        // return $result;

        // 頁面傳值用
        return back()->with([
            'result' => $result,
            'date' => $date,
        ]);
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
