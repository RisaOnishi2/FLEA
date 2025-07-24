@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage__content">
    <div class="mypage__group">
        <div class="mypage__group-content">
            <div class="mypage-image-wrapper">
                @if ($profile && $profile->image)
                    <img src="{{ asset('storage/profile_images/' . $profile->image) }}" alt="プロフィール画像" class="profile-image-preview">
                @else
                    <div class="mypage-image-preview"></div>
                @endif
            </div>
            <div class="user-name">{{ $profile->name }}</div>
            <a class="profile-update-link" href="{{ route('profile.edit') }}">プロフィールを編集</a>
        </div>
    </div>
    <div class="tabs">
        <a href="{{ route('mypage', ['page' => 'sell']) }}" class="tab">出品した商品</a>
        <a href="{{ route('mypage', ['page' => 'buy']) }}" class="tab">購入した商品</a> 
    </div>

    <div class="item-grid">
        @foreach($items as $item)
            <div class="item">
                <div class="item-image">
                    @if ($item->image)
                        <a href="{{ route('item.detail', $item->id) }}">
                            <img src="{{ asset('storage/sample_images/' . $item->image) }}" alt="{{ $item->name }}">
                        </a>
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                    @endif

                    @if ($item->is_sold)
                        <span class="sold-label">Sold</span>
                    @endif
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection