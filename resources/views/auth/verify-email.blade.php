<x-guest-layout>

    <div class="row g-3 card mt-3 pt-2 pb-4 px-4 shadow">

        <div class="text-secondary">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
    
        @if (session('status') == 'verification-link-sent')
            <div class="text-success fw-medium">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif


        <form method="POST" action="{{ route('verification.send') }}" novalidate>
            @csrf

            <div class="col-12">
                <x-primary-button class="w-100">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <div class="col-12">
                <button type="submit" class="w-100 btn btn-secondary">
                    {{ __('Log Out') }}
                </button>
            </div>
            
        </form>
    </div>
</x-guest-layout>
