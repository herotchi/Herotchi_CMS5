<section>
    {{--<header>
        <h2 class="">
            {{ __('Update Password') }}
        </h2>

        <p class="">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>--}}

    <form method="post" action="{{ route('password.update') }}" class="" novalidate>
        @csrf
        @method('put')

        <div class="card-header">{{ __('Update Password') }}</div>
        <div class="card-body">
            <p class="">
                {{ __("Ensure your account is using a long, random password to stay secure.") }}
            </p>

            <div class="row g-3">
                <!-- 現在のパスワード -->
                <div class="col-md-6">
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" :required="true" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" 
                        class="form-control{{ $errors->updatePassword->has('current_password') ? ' is-invalid' : '' }}" autocomplete="current-password" />
                    <x-input-error :message="$errors->updatePassword->first('current_password')" />
                </div>
            </div>

            <div class="row g-3 pt-3">
                <!-- 新しいパスワード -->
                <div class="col-md-6">
                    <x-input-label for="update_password_password" :value="__('New Password')" :required="true" />
                    <x-text-input id="update_password_password" name="password" type="password" 
                        class="form-control{{ $errors->updatePassword->has('password') ? ' is-invalid' : '' }}" autocomplete="new-password" />
                    <x-input-error :message="$errors->updatePassword->first('password')" />
                </div>
            </div>

            <div class="row g-3 pt-3">
                <!-- パスワード（確認用） -->
                <div class="col-md-6">
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" :required="true" />
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                        class="form-control{{ $errors->updatePassword->has('password_confirmation') ? ' is-invalid' : '' }}" />
                    <x-input-error :message="$errors->updatePassword->first('password_confirmation')" />
                </div>
            </div>
        </div>

        {{--<div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="" />
            <x-input-error :message="$errors->updatePassword->first('current_password')" class="" />
        </div>--}}

        {{--<div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="" />
            <x-text-input id="update_password_password" name="new_password" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('new_password')" class="" />
            <x-input-error :message="$errors->updatePassword->first('new_password')" class="" />
        </div>--}}

        {{--<div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="" />
            <x-input-error :message="$errors->updatePassword->first('password_confirmation')" class="" />
        </div>--}}

        {{--<div class="">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class=""
                >{{ __('Saved.') }}</p>
            @endif
        </div>--}}

        <div class="card-footer">
            <div class="row">
                <div class="col-12 my-2">
                    <x-primary-button class="">保存</x-primary-button>
                </div>
            </div>
        </div>
    </form>
</section>
