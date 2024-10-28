@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="register-form mt-5">
        <h2 class="text-center">商品登録</h2>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 商品名 -->
            <div class="form-group">
                <label class="form-label" for="name">商品名 <span class="required">必須</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="商品名を入力">
                @if ($errors->has('name'))
                @foreach ($errors->get('name') as $message)
                <span class="form-error">{{ $message }}</span>
                @endforeach
                @endif
            </div>

            <!-- 価格 -->
            <div class="form-group">
                <label class="form-label" for="price">値段 <span class="required">必須</span></label>
                <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" placeholder="価格を入力">
                @if ($errors->has('price'))
                @foreach ($errors->get('price') as $message)
                <span class="form-error">{{ $message }}</span>
                @endforeach
                @endif
            </div>

            <!-- 商品画像 -->
            <div class="form-group">
                <label class="form-label" for="image">商品画像 <span class="required">必須</span></label>
                <input type="file" name="image" id="image" class="form-control-file">
                @if ($errors->has('image'))
                @foreach ($errors->get('image') as $message)
                <span class="form-error">{{ $message }}</span>
                @endforeach
                @endif
            </div>

            <!-- 季節 -->
            <div class="form-group">
                <label class="form-label">季節 <span class="required">必須</span> <small class="select-season__label">複数選択可</small></label>
                <div class="season-checkboxes">
                    @foreach($seasons as $season)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="season[]" id="season_{{ $season->id }}" value="{{ $season->id }}" {{ is_array(old('season')) && in_array($season->id, old('season')) ? 'checked' : '' }}>
                        <label class="form-check-label" for="season_{{ $season->id }}">{{ $season->name }}</label>
                    </div>
                    @endforeach
                </div>
                @if ($errors->has('season'))
                @foreach ($errors->get('season') as $message)
                <span class="form-error">{{ $message }}</span>
                @endforeach
                @endif
            </div>

            <!-- 商品説明 -->
            <div class="form-group">
                <label class="form-label" for="description">商品説明 <span class="required">必須</span></label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                @foreach ($errors->get('description') as $message)
                <span class="form-error">{{ $message }}</span>
                @endforeach
                @endif
            </div>

            <!-- ボタン -->
            <div class="form-group__btn">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
                <button type="submit" class="btn btn-warning">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection