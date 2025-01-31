<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-bottom-dark mb-3" data-bs-theme="dark">
    <div class="container-fluid">
        <x-application-logo class="" alt="" width="32" height="32" />
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if($page=='index') active @endif" @if($page=='index')aria-current="page"@endif href="{{ route('index') }}">TOP</a>
                </li>
                {{--<li class="nav-item">
                    <a class="nav-link @if($page=='news.list' || $page=='news.detail') active @endif" @if($page=='news.list' || $page=='news.detail')aria-current="page"@endif href="{{ route('news.list') }}">お知らせ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($page=='product.list' || $page=='product.detail') active @endif" @if($page=='product.list' || $page=='product.detail')aria-current="page"@endif href="{{ route('product.list') }}">製品情報</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($page=='contact.add' || $page=='contact.confirm' || $page=='contact.complete') active @endif" @if($page=='contact.add' || $page=='contact.confirm' || $page=='contact.complete')aria-current="page"@endif href="{{ route('contact.add') }}">お問い合わせ</a>
                </li>--}}
                <li class="nav-item">
                    <a class="nav-link @if($page=='profile.edit') active @endif" @if($page=='profile.edit')aria-current="page"@endif href="{{ route('profile.edit') }}">プロフィール</a>
                </li>
            </ul>
            <form class="" action="{{ route('logout') }}" method="POST">
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
