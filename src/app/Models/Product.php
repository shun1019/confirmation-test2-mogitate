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
        'description',
    ];

    // seasonsリレーションを定義
    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'product_season');
    }

    // ローカルスコープ - 商品名で検索
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', '%' . $term . '%');
    }
}
