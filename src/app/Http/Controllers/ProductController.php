<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // 商品一覧を表示する
    public function index(Request $request)
    {
        // クエリパラメータから検索キーワードとソート順を取得
        $query = $request->input('query');
        $sort = $request->input('sort');

        // ローカルスコープを使用して検索とソートを実施
        $products = Product::searchByName($query)
            ->sortByPrice($sort)
            ->paginate(6);

        return view('products.product_index', compact('products'));
    }

    // 商品を保存する
    public function store(Request $request)
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

    public function update(Request $request, Product $product)
    {
        // デバッグログを追加して、リクエストの内容を確認
        Log::info('Request data:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:10000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'season' => 'sometimes|array',  // sometimesを使用して、seasonが存在する場合のみバリデーションを行う
            'season.*' => 'exists:seasons,id',
            'description' => 'required|string|max:120',
        ]);

        Log::info('After validation:', $validated);

        if ($request->hasFile('image')) {
            // 画像の保存
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'];
        $product->save();

        // 中間テーブルの更新
        $product->seasons()->sync($validated['season'] ?? []);

        Log::info('Updated product:', $product->toArray());

        return redirect()->route('products.index')->with('success', '商品が更新されました。');
    }

    // 商品を削除する
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }
}
