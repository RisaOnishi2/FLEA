@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-form__content">
  <div class="address-form__heading">
    <h2>住所の変更</h2>
  </div>
  <form class="form" action="{{ route('purchase.update', ['id' => $id]) }}" method="post">
    @csrf
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="postal_code" value="{{ old('postal_code', $shippingAddress['postal_code'] ?? '') }}" />
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address', $shippingAddress['address'] ?? '') }}" />
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">建物</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building',  $shippingAddress['building'] ?? '') }}" />
        </div>
      </div>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
</div>
@endsection