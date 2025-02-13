<x-app-layout>
    <x-slot name="header">
        <h2 class="">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="">
            <div class="">
                <div class="">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    <p>test</p>

    <!-- Authentication -->
    <form method="POST" action="{{ route('admin.destroy') }}">
        @csrf

        <x-dropdown-link :href="route('admin.destroy')"
                onclick="event.preventDefault();
                            this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-dropdown-link>
    </form>
</x-app-layout>
