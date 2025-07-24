@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="item-detail-container">
    {{-- 左：商品画像 --}}
    <div class="item-image">
        <img src="{{ asset('storage/sample_images/' . $item->image) }}" alt="{{ $item->name }}">
    </div>

    {{-- 右：商品詳細 --}}
    <div class="item-info">
        <h1 class="item-name">{{ $item->name }}</h1>
        <p class="item-brand">ブランド名{{ $item->brand }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} (税込)</p>

        
        @if(auth()->check() && auth()->user()->likes()->where('item_id', $item->id)->exists())
            <form action="{{ route('items.unlike', $item) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: none; border: none; padding: 0;">
                    <img src="{{ asset('images/liked.png') }}" alt="いいね解除" style="width: 32px; height: 32px;">
                </button>
            </form>
        @elseif(auth()->check())
            <form action="{{ route('items.like', $item) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: none; border: none; padding: 0;">
                    <img src="{{ asset('images/like.png') }}" alt="いいね" style="width: 32px; height: 32px;">
                </button>
            </form>
        @else
            <!-- 未ログイン時 -->
            <a href="{{ route('login') }}">
                <img src="{{ asset('images/like.png') }}" alt="いいね" style="width: 32px; height: 32px;">
            </a>
        @endif

        <a href="#" class="comment-img" style="margin-left: 50px;">
            <img src="{{ asset('images/comment.png') }}" alt="コメント" style="width: 32px; height: 32px;">
        </a>

        <div class="count">
            <span class="like-count">{{ $item->likes()->count() }}</span>
            <span class="comment-count">{{ $item->comments()->count() }}</span>
        </div>

        <a href="{{ route('purchase.show', ['id' => $item->id]) }}" class="purchase-button">購入手続きへ</a>

        <div class="section">
            <h2>商品説明</h2>
            <p>{{ $item->description}}</p>
        </div>

        <div class="section">
            <h2>商品の情報</h2>
            <div class="item-category">
                <p>カテゴリー
                    @foreach ($item->categories as $category)
                        <span>{{ $category->category }}</span>
                    @endforeach
                </p>
            </div>
                <p>商品の状態<span class="item-condition">{{ $item->item_condition->condition }}<span></p>
        </div>

        <div class="section">
            <h2>コメント ({{ $comments->count() }})</h2>
            @foreach ($comments as $comment)
                <div class="comment">
                    <div class="author">
                        @if ($profile && $profile->image)
                            <img src="{{ asset('storage/profile_images/' . $profile->image) }}" alt="プロフィール画像" class="profile-image-preview">
                        @else
                            <div class="profile-image-preview"></div>
                        @endif    
                        {{ $comment->user->name}}
                    </div>
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>

        <div class="section">
            <h2>商品へのコメント</h2>
            <form action="{{ route('item.comment', $item->id) }}" method="POST" class="comment-form">
                @csrf
                <textarea name="comment" rows="3" placeholder="こちらにコメントを入力します。"></textarea>
                <button type="submit">コメントを送信する</button>
            </form>
            <div class="comment__error">
              @error('comment')
                  {{ $message }}
              @enderror
            </div>
        </div>
    </div>
</div>
@endsection