<?php
// 創建model同時生成migration
// php artisan make:model UserPaymentApiData -m

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    // 資料結構
    //     "jsonUserPayment:" // app\Admin\Controllers\HomeController.php:147
    // array:1 [▼ // app\Admin\Controllers\HomeController.php:147
    //   "data" => array:3 [▼
    //     "id" => "NBS"
    //     "date" => "2023-09-15"
    //     "statistics" => array:2 [▼
    //       0 => array:3 [▼
    //         "platform" => "Android"
    //         "userCount" => array:24 [▼
    //           0 => 4322
    //           1 => 1029
    //           2 => 9536
    //           3 => 1639
    //           4 => 9634
    //           5 => 7671
    //           6 => 8544
    //           7 => 1563
    //           8 => 3321
    //           9 => 4698
    //           10 => 4646
    //           11 => 5997
    //           12 => 3205
    //           13 => 7708
    //           14 => 4290
    //           15 => 7788
    //           16 => 9107
    //           17 => 5263
    //           18 => 6974
    //           19 => 3918
    //           20 => 8543
    //           21 => 1802
    //           22 => 1774
    //           23 => 8961
    //         ]
    //         "revenue" => array:24 [▼
    //           0 => 1881
    //           1 => 6821
    //           2 => 9145
    //           3 => 3180
    //           4 => 7005
    //           5 => 4344
    //           6 => 4897
    //           7 => 3356
    //           8 => 2385
    //           9 => 2327
    //           10 => 6744
    //           11 => 4018
    //           12 => 2255
    //           13 => 5235
    //           14 => 9313
    //           15 => 4320
    //           16 => 4444
    //           17 => 6738
    //           18 => 9749
    //           19 => 4489
    //           20 => 5644
    //           21 => 5865
    //           22 => 1780
    //           23 => 7926
    //         ]
    //       ]
    //       1 => array:3 [▼
    //         "platform" => "iOS"
    //         "userCount" => array:24 [▶]
    //         "revenue" => array:24 [▶]
    //       ]
    //     ]
    //   ]
    // ]

    public function up()
    {
        Schema::create('user_payment_api_data', function (Blueprint $table) {
            $table->id();
            $table->string('game_id');
            $table->date('date');
            $table->string('platform');
            $table->json('user_count');
            $table->json('revenue');
            $table->timestamps();
        });
    }

    // 設定好user_payment_api_data migration
    // 也設定好UserPaymentApiData model
    // php artisan migrate:status
    // php artisan migrate

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_payment_api_data');
    }
};
