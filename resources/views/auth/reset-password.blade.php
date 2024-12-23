<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="col-12">
                <x-input-label for="email" :value="__('Email')" :required="true" />
                <x-text-input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                    type="email" 
                    name="email" 
                    :value="old('email', $request->email)" 
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

            <!-- Confirm Password -->
            <div class="col-12">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" :required="true" />

                <x-text-input id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                    type="password"
                    name="password_confirmation" 
                    required />
                <x-input-error :message="$errors->first('password_confirmation')" />
            </div>

            <div class="col-12">
                <x-primary-button class="w-100">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>

        </div>
    </form>
</x-guest-layout>
