<x-app-layout title="{{ __('Profile') }}">
    {{--<x-slot name="header">
        <h2 class="">
            {{ __('Profile') }}
        </h2>
    </x-slot>--}}
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Profile') }}</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="card shadow mb-4">
        @include('profile.partials.update-password-form')
    </div>

    <div class="card shadow mb-4">
        @include('profile.partials.delete-user-form')
    </div>

    {{--<div class="">
        <div class="">
            <div class="">
                <div class="">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="">
                <div class="">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="">
                <div class="">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>--}}
</x-app-layout>
