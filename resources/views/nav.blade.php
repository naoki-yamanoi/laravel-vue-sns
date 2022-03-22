<nav class="navbar navbar-expand navbar-dark blue-gradient">

  <a class="navbar-brand" href="/"><i class="far fa-sticky-note mr-1"></i>memo</a>

  <ul class="navbar-nav ml-auto">

    {{-- ログインしていない状態の時 --}}
    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">ユーザー登録</a>
      </li>
    @endguest

    {{-- ログインしていない状態の時 --}}
    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">ログイン</a>
      </li>
    @endguest

    {{-- ログイン済みの状態の時 --}}
    @auth
      <li class="nav-item">
        <a class="nav-link" href="{{ route('articles.create') }}"><i class="fas fa-pen mr-1"></i>投稿する</a>
      </li>
    @endauth

    {{-- ログイン済みの状態の時 --}}
    @auth
      <!-- Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user-circle"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <button class="dropdown-item" type="button" onclick="location.href=''">
            マイページ
          </button>
          <div class="dropdown-divider"></div>
          <button form="logout-button" class="dropdown-item" type="submit">
            ログアウト
          </button>
        </div>
      </li>
      {{-- buttonタグのform="logout-button"と関連づけ --}}
      <form id="logout-button" method="POST" action="{{ route('logout') }}">
        @csrf
      </form>
      <!-- Dropdown -->
    @endauth

  </ul>

</nav>
