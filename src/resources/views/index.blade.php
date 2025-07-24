@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tabs">
    <a href="{{ route('home', ['page' => 'home']) }}"
       class="tab {{ request('page', 'home') === 'home' ? 'active' : '' }}">おすすめ</a>

    <a href="{{ route('home', ['page' => 'mylist']) }}"
       class="tab {{ request('page') === 'mylist' ? 'active' : '' }}">マイリスト</a>
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
@endsection
