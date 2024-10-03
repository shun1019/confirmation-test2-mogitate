@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- 左側の検索フォーム -->
    <div class="sidebar">
        <h3 class="title">商品一覧</h3>
        <form action="{{ route('products.index') }}" method="GET" class="mb-3">
            <div class="form-group">
                <input type="text" name="query" value="{{ request('query') }}" class="form-control" placeholder="商品名で検索">
            </div>
            <button type="submit" class="btn btn-warning w-100">検索</button>
        </form>
        <div class="form-group">
            <label for="price_order">価格順で表示</label>
            <select id="price_order" class="form-control" onchange="location.href=this.value;">
                <option value="" disabled selected>価格の並び替え</option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'desc'])) }}" {{ request('sort') == 'desc' ? 'selected' : '' }}>
                    高い順に表示
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'asc'])) }}" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                    低い順に表示
                </option>
            </select>

            <!-- 並び替えタグの表示 -->
            @if(request('sort'))
            <div class="sort-tag">
                <span>{{ request('sort') == 'asc' ? '高い順に表示' : '低い順に表示' }}</span>
                <a href="{{ route('products.index', \Illuminate\Support\Arr::except(request()->query(), 'sort')) }}" class="remove-sort">×</a>
            </div>
            @endif
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
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection