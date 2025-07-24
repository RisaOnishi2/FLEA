@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
  <div class="profile-form__heading">
    <h2>プロフィール設定</h2>
  </div>
  <form class="form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form__group">
      <div class="form__group-content">
        <div class="profile-image-wrapper">
          @if ($profile && $profile->image)
            <img src="{{ asset('storage/profile_images/' . $profile->image) }}" alt="プロフィール画像" class="profile-image-preview">
          @else
            <div class="profile-image-preview"></div>
          @endif

          {{-- ファイル選択ボタン --}}
          <label for="imageUpload" class="image-upload-button">画像を選択する</label>
          <input id="imageUpload" type="file" name="image" accept="image/png, image/jpeg" style="display: none;">
        </div>
        <div class="form__error">
          @error('image')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">ユーザー名</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="name" value="{{ old('name', $user->name) }}" />
        </div>
        <div class="form__error">
          @error('name')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">郵便番号</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" />
        </div>
        <div class="form__error">
          @error('postal_code')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">住所</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="address" value="{{ old('address', $profile->address ?? '') }}" />
        </div>
        <div class="form__error">
          @error('address')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__group">
      <div class="form__group-title">
        <span class="form__label--item">建物</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--text">
          <input type="text" name="building" value="{{ old('building', $profile->building ?? '') }}" />
        </div>
        <div class="form__error">
          @error('building')
          {{ $message }}
          @enderror
        </div>
      </div>
    </div>
    <div class="form__button">
      <button class="form__button-submit" type="submit">更新する</button>
    </div>
  </form>
</div>
@endsection