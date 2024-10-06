<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Log;

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

        $products = $query->paginate(6)->appends($request->query());

        return view('products.product_index', compact('products'));
    }

    // 検索機能を使用して商品を表示する
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->has('query')) {
            $queryParam = $request->input('query');
            $query->where('name', 'like', '%' . $queryParam . '%');
        }

        $products = $query->paginate(6)->appends($request->query());

        return view('products.product_index', compact('products'));
    }

    // 商品登録フォームを表示する
    public function create()
    {
        $seasons = Season::all();
        return view('products.product_register', compact('seasons'));
    }

    // 商品を保存する
    public function store(UpdateProductRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $path,
            'description' => $validated['description'],
        ]);

        $product->seasons()->attach($validated['season']);

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
    }

    // 商品の詳細を表示する
    public function show(Product $product)
    {
        $seasons = Season::all();
        $productSeasons = $product->seasons->pluck('id')->toArray();

        return view('products.product_detail', compact('product', 'seasons', 'productSeasons'));
    }

    // 商品更新フォームを表示する
    public function edit(Product $product)
    {
        $seasons = Season::all();
        $productSeasons = $product->seasons->pluck('id')->toArray();

        return view('products.product_edit', compact('product', 'seasons', 'productSeasons'));
    }

    // 商品を更新する
    public function update(UpdateProductRequest $request, Product $product)
    {
        Log::info('Request data:', $request->all());

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->name = $validated['name'];
        $product->price = $validated['price'];
        $product->description = $validated['description'];
        $product->save();

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
