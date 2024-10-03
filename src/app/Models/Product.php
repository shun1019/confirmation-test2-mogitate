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

    // 検索用のローカルスコープを追加
    public function scopeSearchByName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    // ソート用のローカルスコープを追加
    public function scopeSortByPrice($query, $order)
    {
        if ($order === 'asc' || $order === 'desc') {
            return $query->orderBy('price', $order);
        }
        return $query;
    }
}
