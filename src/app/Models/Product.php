<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // ホワイトリストで指定
    protected $fillable = [
        'name',
        'price',
        'image',
    ];
}
