<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewUserApiData extends Model
{
    use HasFactory;

    protected $table = 'new_user_api_data';

    protected $fillable = [
        'game_id',
        'date',
        'platform',
        'user_count',
    ];
}
