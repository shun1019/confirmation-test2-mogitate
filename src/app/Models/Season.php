<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // productsリレーションを定義
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_season');
    }
}
