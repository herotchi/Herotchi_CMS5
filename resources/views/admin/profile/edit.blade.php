<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">プロフィール</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form method="post" action="{{ route('admin.profile.update') }}" class="" novalidate>
            @csrf
            @method('patch')
    
            <div class="card-header">{{ __('Profile Information') }}</div>
            <div class="card-body">

                <div class="row g-3">
                    <!-- 管理者名 -->
                    <div class="col-md-6">
                        <x-input-label for="name" value="管理者名" :required="true" />
                        <x-text-input id="name" name="name" type="text" 
                            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            :value="old('name', $user->name)" required autocomplete="name" />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
                </div>

                <div class="row g-3 pt-3">
                    <!-- ログインID -->
                    <div class="col-md-6">
                        <x-input-label for="login_id" value="ログインID" :required="true" />
                        <x-text-input id="login_id" name="login_id" type="text" 
                            class="form-control{{ $errors->has('login_id') ? ' is-invalid' : '' }}" 
                            :value="old('login_id', $user->login_id)" required />
                        <x-input-error :message="$errors->first('login_id')" />
                    </div>
                </div>

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 my-2">
                        <x-primary-button class="">保存</x-primary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow mb-4">
        <form method="post" action="{{ route('admin.profile.password_update') }}" class="" novalidate>
            @csrf
            @method('put')
    
            <div class="card-header">{{ __('Update Password') }}</div>
            <div class="card-body">
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
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 my-2">
                        <x-primary-button class="">保存</x-primary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>