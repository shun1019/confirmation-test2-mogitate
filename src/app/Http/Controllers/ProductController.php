<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    // 商品一覧を表示する
    public function index(Request $request)
    {
        $products = Product::paginate(6);
        return view('products.product_index', compact('products'));
    }

    // 商品登録フォームを表示する
    public function create()
    {
        $seasons = Season::all(); // 季節情報を取得
        return view('products.product_register', compact('seasons'));
    }

    // 商品を保存する
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'season' => 'required|array',
            'season.*' => 'exists:seasons,id',
            'description' => 'required|string|max:1000',
        ]);

        // 画像の保存
        $path = $request->file('image')->store('products', 'public');

        // 商品を保存
        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $path,
            'description' => $validated['description'],
        ]);

        // 中間テーブルに季節を保存
        $product->seasons()->attach($validated['season']);

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
    }

    // 商品を削除する
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }
}
