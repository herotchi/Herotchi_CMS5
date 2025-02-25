<x-guest-layout title="管理画面 | ログイン">
    <form method="POST" action="{{ route('admin.create') }}" novalidate>
        @csrf

        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">

            <!-- Session Status -->
            <x-auth-session-status class="" :status="session('status')" />

            <!-- Login ID -->
            <div class="col-12">
                <x-input-label for="login_id" :value="__('Login ID')" :required="true" />
                <x-text-input id="login_id" class="form-control{{ $errors->has('login_id') ? ' is-invalid' : '' }}" 
                    type="text" 
                    name="login_id" 
                    :value="old('login_id')" 
                    required 
                    autofocus />
                <x-input-error :message="$errors->first('login_id')" />
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

            <div class="col-12">
                <x-primary-button class="w-100 mt-4">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
