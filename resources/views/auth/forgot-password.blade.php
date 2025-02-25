<x-guest-layout title="パスワードリセット">

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">
    
            <div class="text-secondary">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

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

            <div class="col-12">
                <x-primary-button class="w-100">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>

        </div>
    </form>
</x-guest-layout>
