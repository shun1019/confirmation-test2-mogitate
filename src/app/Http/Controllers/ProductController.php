<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 商品一覧を表示する
    public function index(Request $request)
    {
        // 検索クエリの取得
        $query = $request->input('query');
        // ソートの設定
        $sortOrder = $request->input('sort', 'asc');

        // 商品の取得
        $products = Product::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('name', 'like', '%' . $query . '%');
        })
            ->orderBy('price', $sortOrder)
            ->paginate(6);

        return view('products.product_index', compact('products'));
    }

    // 新規商品の登録フォームを表示する
    public function create()
    {
        return view('products.product_create');
    }

    // 新規商品を保存する
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 画像の保存
        $path = $request->file('image')->store('products', 'public');

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $path,
        ]);

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
    }

    // 商品詳細を表示する
    public function show(Product $product)
    {
        return view('products.product_detail', compact('product'));
    }

    // 商品の編集フォームを表示する
    public function edit(Product $product)
    {
        return view('products.product_edit', compact('product'));
    }

    // 商品を更新する
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // 画像の更新
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->save();

        return redirect()->route('products.show', $product)->with('success', '商品が更新されました。');
    }

    // 商品を削除する
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }
}
