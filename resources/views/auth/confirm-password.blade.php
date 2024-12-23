<x-guest-layout>
    

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">
            <div class="text-secondary">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
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
                <x-primary-button class="w-100">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
