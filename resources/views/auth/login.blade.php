<x-guest-layout title="ログイン">
    

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">

            <!-- Session Status -->
            <x-auth-session-status class="" :status="session('status')" />

            <!-- Email Address -->
            <div class="col-12">
                <x-input-label for="email" :value="__('Email')" :required="true" />
                <x-text-input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus />
                <x-input-error :message="$errors->first('email')" />
            </div>

            <!-- Password -->
            <div class="col-12">
                <x-input-label for="password" :value="__('Password')" :required="true" />
                <x-text-input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    type="password"
                    name="password"
                    required />
                <x-input-error :message="$errors->first('password')" />
            </div>

            <!-- Remember Me -->
            <div class="col-12">
                <div class=" form-check">
                    <input id="remember_me" class="form-check-input shadow-sm{{ $errors->has('remember') ? ' is-invalid' : '' }}" 
                        type="checkbox" 
                        name="remember">
                    <label for="remember_me" class="">
                        <span class="">{{ __('Remember me') }}</span>
                    </label>
                    <x-input-error :message="$errors->first('remember')" />
                </div>
            </div>

            @if (Route::has('password.request'))
                <div class="col-12">
                    <a class="link-secondary" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                </div>
            @endif

            <div class="col-12">
                <a class="" href="{{ route('register') }}">ユーザー登録をしたい方はこちら</a>
            </div>
            
            <div class="col-12">
                <x-primary-button class="w-100">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
