@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="form-container">
    <h2>商品の出品</h2>

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像 -->
        <label for="image">商品画像</label>
        <div class="image-upload-area">
            <label for="image" class="upload-button">画像を選択する</label>
            <input type="file" name="image" id="image" class="hidden-input">
        </div>
        <div class="form__error">
          @error('image')
          {{ $message }}
          @enderror
        </div>

        <!-- カテゴリー -->
        <label>商品の詳細</label>
        <p>カテゴリー</p>
        <div class="category-tags">
            @foreach ($categories as $category)
                <label class="category-tag">
                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"> {{ $category->category }}
                </label>
            @endforeach
        </div>
        <div class="form__error">
          @error('categories[]')
          {{ $message }}
          @enderror
        </div>

        <!-- 商品の状態 -->
        <label for="condition">商品の状態</label>
        <select name="item_condition_id" id="condition">
            <option disabled selected>選択してください</option>
            @foreach ($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
            @endforeach
        </select>
        <div class="form__error">
          @error('item_condition_id')
          {{ $message }}
          @enderror
        </div>
        

        <!-- 商品名と説明 -->
        <label>商品名</label>
        <input type="text" name="name">
        <div class="form__error">
          @error('name')
          {{ $message }}
          @enderror
        </div>

        <label>ブランド名</label>
        <input type="text" name="brand">

        <label>商品の説明</label>
        <textarea name="description" rows="4"></textarea>
        <div class="form__error">
          @error('description')
          {{ $message }}
          @enderror
        </div>

        <label>販売価格</label>
        <input type="number" name="price" placeholder="¥">
        <div class="form__error">
          @error('price')
          {{ $message }}
          @enderror
        </div>

        <button type="submit" class="exhibit-button">出品する</button>
    </form>
</div>
@endsection