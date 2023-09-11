<?php

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

    //     資料結構
    //     array:1 [▼ // app\Admin\Controllers\HomeController.php:46
    //   "data" => array:3 [▼
    //     "id" => "NBS"
    //     "date" => "2023-09-11"
    //     "statistics" => array:2 [▼
    //       0 => array:2 [▼
    //         "platform" => "Android"
    //         "userCount" => array:24 [▼
    //           0 => 4401
    //           1 => 6173
    //           2 => 2188
    //           3 => 6429
    //           4 => 2197
    //           5 => 7038
    //           6 => 3787
    //           7 => 3612
    //           8 => 8092
    //           9 => 1323
    //           10 => 2336
    //           11 => 7866
    //           12 => 3021
    //           13 => 2228
    //           14 => 4255
    //           15 => 3416
    //           16 => 3574
    //           17 => 8208
    //           18 => 5156
    //           19 => 7919
    //           20 => 5307
    //           21 => 4188
    //           22 => 6368
    //           23 => 4708
    //         ]
    //       ]
    //       1 => array:2 [▼
    //         "platform" => "iOS"
    //         "userCount" => array:24 [▼
    //           0 => 4631
    //           1 => 2235
    //           2 => 7161
    //           3 => 9119
    //           4 => 1709
    //           5 => 5474
    //           6 => 9038
    //           7 => 7801
    //           8 => 6464
    //           9 => 9025
    //           10 => 8931
    //           11 => 8106
    //           12 => 1073
    //           13 => 3068
    //           14 => 2920
    //           15 => 7339
    //           16 => 9634
    //           17 => 1770
    //           18 => 6738
    //           19 => 6100
    //           20 => 9267
    //           21 => 3052
    //           22 => 1211
    //           23 => 3668
    //         ]
    //       ]
    //     ]
    //   ]
    // ]

    public function up()
    {
        Schema::create('new_user_api_data', function (Blueprint $table) {
            $table->id();
            $table->string('game_id');
            $table->date('date');
            $table->string('platform');
            $table->json('user_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_user_api_data');
    }
};
