@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="detail-form mt-5">
        <a href="{{ route('products.index') }}" class="back-link">商品一覧 > {{ $product->name }}</a>
        <div class="row mt-3">
            <div class="col-md-5">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                <div class="form-group mt-3">
                    <label for="image">商品画像</label>
                    <input type="file" name="image" id="image" class="form-control-file">
                    @error('image')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-7">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- 商品名 -->
                    <div class="form-group">
                        <label for="name">商品名</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" >
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 価格 -->
                    <div class="form-group">
                        <label for="price">値段</label>
                        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" >
                        @error('price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 季節 -->
                    <div class="form-group">
                        <label>季節</label>
                        <div class="season-checkboxes">
                            @foreach($seasons as $season)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="season[]" id="season_{{ $season->id }}" value="{{ $season->id }}"
                                    {{ in_array($season->id, old('season', $productSeasons)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="season_{{ $season->id }}">{{ $season->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('season')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- 商品説明 -->
                    <div class="form-group">
                        <label for="description">商品説明</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ボタン -->
                    <div class="form-group text-center">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
                        <button type="submit" class="btn btn-warning">変更を保存</button>
                    </div>
                </form>

                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection