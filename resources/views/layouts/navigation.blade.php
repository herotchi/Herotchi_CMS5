<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-bottom-dark mb-3" data-bs-theme="dark">
    <div class="container-fluid">
        <x-application-logo class="" alt="" width="32" height="32" />
        <span class="text-danger fs-4 fw-semibold ps-2">管理画面</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if($page=='admin.top') active @endif" @if($page=='admin.top')aria-current="page"@endif href="{{ route('admin.top') }}">TOP</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.news.create' || $page=='admin.news.index' || $page=='admin.news.show' || $page=='admin.news.edit') active @endif" 
                        @if($page=='admin.news.create' || $page=='admin.news.index' || $page=='admin.news.show' || $page=='admin.news.edit')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">お知らせ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.news.create') active @endif" href="{{ route('admin.news.create') }}">お知らせ登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.news.index') active @endif" href="{{ route('admin.news.index') }}">お知らせ一覧</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle
                        @if($page=='admin.first_category.create' || $page=='admin.first_category.index' || $page=='admin.first_category.show' || $page=='admin.first_category.edit'|| $page=='admin.first_category.csv_add' 
                        || $page=='admin.second_category.create' || $page=='admin.second_category.index' || $page=='admin.second_category.show' || $page=='admin.second_category.edit' || $page=='admin.second_category.csv_add') active @endif" 
                        @if($page=='admin.first_category.create' || $page=='admin.first_category.index' || $page=='admin.first_category.show' || $page=='admin.first_category.edit' 
                        || $page=='admin.second_category.create' || $page=='admin.second_category.index' || $page=='admin.second_category.show' || $page=='admin.second_category.edit')aria-current="page"@endif
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">カテゴリ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.first_category.create') active @endif" href="{{ route('admin.first_category.create') }}">大カテゴリ登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.first_category.index') active @endif" href="{{ route('admin.first_category.index') }}">大カテゴリ一覧</a></li>
                        <li><a class="dropdown-item @if($page=='admin.first_category.csv_add') active @endif" href="{{ route('admin.top') }}">大カテゴリCSV登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.second_category.create') active @endif" href="{{ route('admin.second_category.create') }}">中カテゴリ登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.second_category.index') active @endif" href="{{ route('admin.second_category.index') }}">中カテゴリ一覧</a></li>
                        <li><a class="dropdown-item @if($page=='admin.second_category.csv_add') active @endif" href="{{ route('admin.top') }}">中カテゴリCSV登録</a></li>
                        
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.tag.create' || $page=='admin.tag.index' || $page=='admin.tag.show' || $page=='admin.tab.edit') active @endif" 
                        @if($page=='admin.tag.create' || $page=='admin.tag.index' || $page=='admin.tag.show' || $page=='admin.tag.edit')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">タグ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.tag.create') active @endif" href="{{ route('admin.tag.create') }}">タグ登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.tag.index') active @endif" href="{{ route('admin.tag.index') }}">タグ一覧</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.product.create' || $page=='admin.product.index' || $page=='admin.product.show' || $page=='admin.product.edit') active @endif" 
                        @if($page=='admin.product.create' || $page=='admin.product.index' || $page=='admin.product.show' || $page=='admin.product.edit')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">製品情報
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.product.create') active @endif" href="{{ route('admin.product.create') }}">製品情報登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.product.index') active @endif" href="{{ route('admin.product.index') }}">製品情報一覧</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.media.add' || $page=='admin.media.list' || $page=='admin.media.detail' || $page=='admin.media.edit') active @endif" 
                        @if($page=='admin.media.add' || $page=='admin.media.list' || $page=='admin.media.detail' || $page=='admin.media.edit')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">メディア
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.media.add') active @endif" href="{{ route('admin.top') }}">メディア登録</a></li>
                        <li><a class="dropdown-item @if($page=='admin.media.list') active @endif" href="{{ route('admin.top') }}">メディア一覧</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.contact.list' || $page=='admin.contact.detail') active @endif" 
                        @if($page=='admin.contact.list' || $page=='admin.contact.detail')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">お問い合わせ
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.contact.list') active @endif" href="{{ route('admin.top') }}">お問い合わせ一覧</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if($page=='admin.user.login') active @endif" 
                        @if($page=='admin.user.login')aria-current="page"@endif href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">ユーザー
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item @if($page=='admin.user.login') active @endif" href="{{ route('admin.top') }}">ログイン情報変更</a></li>
                    </ul>
                </li>
            </ul>
            <form class="" action="{{ route('admin.destroy') }}" method="POST">
                @csrf
                <button class="btn btn-outline-success" type="submit">ログアウト</button>
            </form>
        </div>
    </div>
</nav>

{{--<nav x-data="{ open: false }" class="">
    <!-- Primary Navigation Menu -->
    <div class="">
        <div class="">
            <div class="">
                <!-- Logo -->
                <div class="">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="">
                                <svg class="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="">
                <button @click="open = ! open" class="">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="">
        <div class="">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="">
            <div class="px-4">
                <div class="">{{ Auth::user()->name }}</div>
                <div class="">{{ Auth::user()->email }}</div>
            </div>

            <div class="">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>--}}
