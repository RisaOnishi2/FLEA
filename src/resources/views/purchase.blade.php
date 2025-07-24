@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
  {{-- 左側：商品・支払い・配送先 --}}
  <div class="item-section">
    <div class="item-info">
    <div class="item-image">
        <img src="{{ asset('storage/sample_images/' . $item->image) }}" alt="{{ $item->name }}">
    </div>
      <div class="item-details">
        <h2>{{ $item->name }}</h2>
        <p class="price">¥{{ number_format($item->price) }}</p>
      </div>
    </div>

    <hr />

    {{-- 支払い方法 --}}
    <form method="GET" action="{{ route('purchase.show', ['id' => $item->id]) }}">
      @csrf
      <div class="form-group">
        <label for="payment-method"><strong>支払い方法</strong></label>
        <select name="payment_method" onchange="this.form.submit()" class="payment-select">
            <option value="">選択してください</option>
            @foreach ($paymentMethods as $method)
                <option value="{{ $method }}" {{ request('payment_method', 'コンビニ払い') == $method ? 'selected' : '' }}>
                    {{ $method }}
                </option>
            @endforeach
        </select>
        @error('payment_method')
              <div class="error">{{ $message }}</div>
        @enderror
      </div>
    </form>

      <hr />

      {{-- 配送先 --}}
      <div class="address-section">
        <strong>配送先</strong>
        <a href="{{ route('purchase.edit', ['id' => $item->id]) }}" class="change-link">変更する</a>
        <p>〒 {{ $profile->postal_code }}<br>{{ $profile->address }}<br>{{$profile->building}}</p>
      </div>
      @error('address')
          <div class="error">{{ $message }}</div>
      @enderror

      <hr />

      {{-- 右側：購入概要 --}}
      <form method="POST" action="{{ route('purchase.store', ['id' => $item->id]) }}">
          @csrf
          <input type="hidden" name="payment_method" value="{{ $selectedPaymentMethod }}">
          <input type="hidden" name="address" value="{{ $profile->address }}">
          <input type="hidden" name="postal_code" value="{{ $profile->postal_code }}">
          <input type="hidden" name="building" value="{{ $profile->building }}">

          <div class="summary-section">
              <div class="summary-box">
                  <div class="summary-row">
                      <span>商品代金</span>
                      <span>¥{{ number_format($item->price) }}</span>
                  </div>
                  <div class="summary-row">
                      <span>支払い方法</span>
                      <span>{{ request('payment_method', '未選択') }}</span>
                  </div>
              </div>
              <button type="submit" class="purchase-button">購入する</button>
          </div>
          @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
          @endif
      </form>
  </div>
</div>
@endsection