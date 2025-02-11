<section class="space-y-6">
    {{--<header>
        <h2 class="">
            {{ __('Delete Account') }}
        </h2>

        <p class="">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>--}}

    <div class="card-header">{{ __('Delete Account') }}</div>
    <div class="card-body">
        <p class="mb-0">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-12 my-2">
                <!-- Button trigger modal -->
                <x-danger-button  data-bs-toggle="modal" data-bs-target="#destroyModal">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal @if($errors->userDeletion->has('password'))@else fade @endif" id="destroyModal" tabindex="-1" aria-labelledby="destroyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}" novalidate>
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="destroyModalLabel">{{ __('Are you sure you want to delete your account?') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                        <div class="row g-3">
                            <!-- パスワード -->
                            <div class="col-md-12">
                                <x-text-input
                                    id="deleteUserPassword" name="password" type="password"
                                    class="form-control{{ $errors->userDeletion->has('password') ? ' is-invalid' : '' }}"
                                    placeholder="{{ __('Password') }}"
                                />
                                <x-input-error :message="$errors->userDeletion->first('password')" class="" />
                            </div>
                        </div>
                        {{--<x-input-label for="password" value="{{ __('Password') }}" class="" />

                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class=""
                            placeholder="{{ __('Password') }}"
                        />

                        <x-input-error :messages="$errors->userDeletion->get('password')" class="" />
                        <x-input-error :message="$errors->userDeletion->first('password')" class="" />--}}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-danger w-50">削除する</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const myModalEl = document.getElementById('destroyModal');
        const myBsModalEl = new bootstrap.Modal(myModalEl);
        const myInputEl = document.getElementById('deleteUserPassword');
        
        // モーダルを閉じ終わったら入力エラークラスを削除する
        myModalEl.addEventListener('hidden.bs.modal', event => {
            myInputEl.classList.remove('is-invalid');
        });

        // モーダルを閉じる瞬間にフェード演出を追加する
        myModalEl.addEventListener('hide.bs.modal', event => {
            myModalEl.classList.add('fade');
        });
        
        // ページ読み込み時にモーダルを表示
        document.addEventListener('DOMContentLoaded', function () {
            // 入力エラーがあった場合
            @if ($errors->userDeletion->has('password'))
                myBsModalEl.show();
            @endif
        });
        
    </script>


    {{--<x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>--}}

    {{--<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="">
                <x-input-label for="password" value="{{ __('Password') }}" class="" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class=""
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="" />
                <x-input-error :message="$errors->userDeletion->first('password')" class="" />
            </div>

            <div class="">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>--}}
</section>
