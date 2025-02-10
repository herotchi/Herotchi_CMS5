<section>
    {{--<header>
        <h2 class="">
            {{ __('Profile Information') }}
        </h2>

        <p class="">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>--}}

    {{--<form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>--}}

    <form method="post" action="{{ route('profile.update') }}" class="" novalidate>
        @csrf
        @method('patch')

        <div class="card-header">{{ __('Profile Information') }}</div>
        <div class="card-body">
            <p class="">
                {{ __("Update your account's profile information and email address.") }}
            </p>
            <div class="row g-3">
                <!-- 氏名 -->
                <div class="col-md-6">
                    <x-input-label for="name" :value="__('Name')" :required="true" />
                    <x-text-input id="name" name="name" type="text" 
                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                        :value="old('name', $user->name)" required autocomplete="name" />
                    <x-input-error :message="$errors->first('name')" />
                </div>
            </div>

            <div class="row g-3 pt-3">
                <!-- メール -->
                <div class="col-md-6">
                    <x-input-label for="email" :value="__('Email')" :required="true" />
                    <x-text-input id="email" name="email" type="email" 
                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="" :message="$errors->first('email')" />
                </div>
            </div>
        </div>

        {{--<div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="" :message="$errors->first('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="" :message="$errors->first('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>--}}

        {{--<div class="">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
