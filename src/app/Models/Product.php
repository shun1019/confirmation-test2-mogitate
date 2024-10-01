<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ホワイトリスト
    protected $fillable = [
        'name',
        'price',
        'image',
        'season',
        'description',
    ];

    // seasonはJSONとして保存されるため、配列にキャスト
    protected $casts = [
        'season' => 'array',
    ];
}
