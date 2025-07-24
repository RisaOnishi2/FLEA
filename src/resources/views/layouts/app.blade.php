<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coachtech Flea</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="ロゴ">
                </a>
                @if (!in_array(Route::currentRouteName(), ['register', 'login']))
                    <form action="{{ route('item.search') }}" method="GET" class="search-form">
                        @csrf
                        <input type="text" name="keyword" placeholder="何をお探しですか？">
                    </form>

                    @guest
                        <nav>
                            <ul class="header-nav">
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('login') }}">ログイン</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('mypage') }}">マイページ</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="exhibition" href="{{ route('sell.create') }}">出品</a>
                                </li>
                            </ul>
                        </nav>
                    @endguest

                    @auth
                        <nav>
                            <ul class="header-nav">
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="{{ route('mypage') }}">マイページ</a>
                                </li>
                                <li class="header-nav__item">
                                    <form class="form" action="/logout" method="post">
                                        @csrf
                                        <button class="header-nav__button">ログアウト</button>
                                    </form>
                                </li>
                                <li class="header-nav__item">
                                    <a class="exhibition" href="{{ route('sell.create') }}">出品</a>
                                </li>
                            </ul>
                        </nav>
                    @endauth
                @endif
            </div>
        </div>
    </header>

  <main>
    @yield('content')
  </main>
</body>

</html>
