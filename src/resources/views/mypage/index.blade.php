@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-container">
    <div class="profile-header">
        <div class="profile-image">
            @if ($profile->image)
                <img src="{{ asset('storage/profile_images/' . $profile->image) }}" alt="プロフィール画像">
            @else
                {{-- ダミー画像（CSSでグレーの丸） --}}
                <div class="profile-placeholder"></div>
            @endif
        </div>
    <div class="profile-info">
        <h2>{{ $user->name }}</h2>
    </div>
    <div class="edit-profile">
        <a href="{{ route('profile.edit') }}" class="edit-button">プロフィールを編集</a>
    </div>
</div>

<div class="tab-menu">
    <a href="{{ route('mypage', ['page' => 'sell']) }}" class="{{ request('page') == 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage', ['page' => 'buy']) }}" class="{{ request('page') == 'buy' ? 'active' : '' }}">購入した商品</a>
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
