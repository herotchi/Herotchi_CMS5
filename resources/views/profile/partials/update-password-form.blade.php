<section>
    <header>
        <h2 class="">
            {{ __('Update Password') }}
        </h2>

        <p class="">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="" />
            {{--<x-text-input id="update_password_password" name="new_password" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('new_password')" class="" />--}}
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="" />
        </div>

        <div class="">
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
        </div>
    </form>
</section>
