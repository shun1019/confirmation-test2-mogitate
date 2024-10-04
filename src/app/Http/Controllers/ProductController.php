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
        $query = Product::query();

        if ($request->has('sort')) {
            $sort = $request->query('sort');
            $query->orderBy('price', $sort == 'asc' ? 'asc' : 'desc');
        }

        if ($request->has('query')) {
            $queryParam = $request->input('query');
            $query->where('name', 'like', '%' . $queryParam . '%');
        }

        $products = $query->paginate(6);

        return view('products.product_index', compact('products'));
    }

    // 商品登録フォームを表示する
    public function create()
    {
        $seasons = Season::all(); // 季節情報を取得
        return view('products.product_register', compact('seasons'));
    }

    // 商品を保存する
    public function store(UpdateProductRequest $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:10000',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'season' => 'required|array',
            'season.*' => 'exists:seasons,id',
            'description' => 'required|string|max:120',
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

    // 商品の詳細を表示する
    public function show(Product $product)
    {
        $seasons = Season::all(); // 季節情報を取得
        $productSeasons = $product->seasons->pluck('id')->toArray(); // 商品に関連する季節のIDを取得

        return view('products.product_detail', compact('product', 'seasons', 'productSeasons'));
    }

    // 商品を更新する
    public function update(UpdateProductRequest $request, Product $product)
    {
        // フォームリクエストによるバリデーションの結果を利用
        $validated = $request->validated();

        // 画像の保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        // 商品情報の更新
        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'];
        $product->save();

        // 中間テーブルの更新
        $product->seasons()->sync($validated['season'] ?? []);

        return redirect()->route('products.index')->with('success', '商品が更新されました。');
    }

    // 商品を削除する
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }
}
