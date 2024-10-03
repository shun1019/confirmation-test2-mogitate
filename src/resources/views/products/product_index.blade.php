@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- 左側の検索フォーム -->
    <div class="sidebar">
        <h3>商品一覧</h3>
        <form action="{{ route('products.index') }}" method="GET" class="mb-3">
            <div class="form-group">
                <input type="text" name="query" class="form-control" placeholder="商品名で検索">
            </div>
            <button type="submit" class="btn btn-warning w-100">検索</button>
        </form>
        <div class="form-group">
            <label for="price_order">価格順で表示</label>
            <select id="price_order" class="form-control" onchange="location.href=this.value;">
                <option value="?sort=asc">価格で昇順</option>
                <option value="?sort=desc">価格で降順</option>
            </select>
        </div>
    </div>

    <!-- 右側の商品カード -->
    <div class="products-container">
        <div class="text-right">
            <a href="{{ route('products.create') }}" class="btn btn-warning product-add-btn">+ 商品を追加</a>
        </div>
        <div class="row">
            @foreach($products as $product)
            <div class="product-card">
                <div class="card">
                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">¥{{ number_format($product->price) }}</p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="pagination justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection