<?php
// 創建model同時生成migration
// php artisan make:model UserPaymentApiData -m

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentApiData extends Model
{
    use HasFactory;

    protected $table = 'user_payment_api_data';

    protected $fillable = [
        'game_id',
        'date',
        'plateform',
        'user_count',
        'revenue',
    ];
}

// 設定好user_payment_api_data migration
// 也設定好UserPaymentApiData model
// php artisan migrate:status
// php artisan migrate