<x-guest-layout title="ユーザー登録">
    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf
        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">
            <!-- Name -->
            <div class="col-12">
                <x-input-label for="name" :value="__('Name')" :required="true"/>
                <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus />
                <x-input-error :message="$errors->first('name')" />
            </div>

            <!-- Email Address -->
            <div class="col-12">
                <x-input-label for="email" :value="__('Email')" :required="true"/>
                <x-text-input id="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required />
                <x-input-error :message="$errors->first('email')" />
            </div>

            <!-- Password -->
            <div class="col-12">
                <x-input-label for="password" :value="__('Password')" :required="true"/>

                <x-text-input id="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    type="password"
                    name="password"
                    required />
                <x-input-error :message="$errors->first('password')" />
            </div>

            <!-- Confirm Password -->
            <div class="col-12">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" :required="true"/>

                <x-text-input id="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                    type="password"
                    name="password_confirmation" 
                    required />
                <x-input-error :message="$errors->first('password_confirmation')" />
            </div>

            <!-- 利用規約 -->
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" id="user_policy"
                        class="form-check-input shadow-sm text-center{{ $errors->has('user_policy') ? ' is-invalid' : '' }}"
                        name="user_policy" value="yes" {{ old('user_policy') ? 'checked' : 'disabled="disabled"' }} 
                        required>
                    <label class="form-check-label" for="user_policy">
                        <span class="text-info text-decoration-underline" data-bs-toggle="modal"
                            data-bs-target="#user_policy_modal">利用規約</span>に同意する
                    </label>
                    <div class="invalid-feedback">{{ $errors->first('user_policy') }}</div>
                </div>
            </div>

            <div class="col-12">
                <a class="" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>

            <div class="col-12">
                <x-primary-button class="w-100">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
<!-- モーダルの設定 -->
<div class="modal fade" id="user_policy_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="user_policy_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-start">
            <div class="modal-body">
                @include('layouts.terms_of_use_block')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="userPolicy()">閉じる</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function userPolicy() {
        document.querySelector('input[name="user_policy"]').removeAttribute('disabled');
    }
</script>