<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ一覧</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.news.index') }}" method="GET" novalidate>
            <div class="card-header">お知らせ一覧</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <!-- タイトル -->
                    <div class="col-md-12">
                        <x-input-label for="title" value="タイトル" />
                        <x-text-input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="title" 
                            :value="old('title', $input['title'])" />
                        <x-input-error :message="$errors->first('title')" />
                    </div>

                    <!-- リンク設定 -->
                    <x-input-label for="link_flg" value="リンク設定" />
                    <x-checkbox-input name="link_flg" :array="$NewsConsts::LINK_FLG_LIST" :old="old('link_flg', $input['link_flg'])" />
                    <x-input-error :message="$errors->first('link_flg')" />
                    <x-input-error :message="$errors->first('link_flg.*')" />

                    <!-- 公開日～ -->
                    <div class="col-md-6">
                        <x-input-label for="release_date_from" value="公開日～" />
                        <x-text-input id="release_date_from" class="form-control{{ $errors->has('release_date_from') ? ' is-invalid' : '' }}" 
                            type="date" 
                            name="release_date_from" 
                            :value="old('release_date_from', $input['release_date_from'])" />
                        <x-input-error :message="$errors->first('release_date_from')" />
                    </div>

                    <!-- ～公開日 -->
                    <div class="col-md-6">
                        <x-input-label for="release_date_to" value="～公開日" />
                        <x-text-input id="release_date_to" class="form-control{{ $errors->has('release_date_to') ? ' is-invalid' : '' }}" 
                            type="date" 
                            name="release_date_to" 
                            :value="old('release_date_to', $input['release_date_to'])" />
                        <x-input-error :message="$errors->first('release_date_to')" />
                    </div>

                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" />
                    <x-checkbox-input name="release_flg" :array="$NewsConsts::RELEASE_FLG_LIST" :old="old('link_flg', $input['release_flg'])" />
                    <x-input-error :message="$errors->first('release_flg')" />
                    <x-input-error :message="$errors->first('release_flg.*')" />
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">検索</x-primary-button>
                        <x-secondary-button>戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="card mt-4">
        <div class="card-header text-end">
            {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">タイトル</th>
                        <th>リンク設定</th>
                        <th>公開日</th>
                        <th>表示設定</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td scope="rol">
                            <a href="{{ route('admin.news.show', $list) }}">{{ $list->title }}</a>
                        </td>
                        <td>
                            {{ $NewsConsts::LINK_FLG_LIST[$list->link_flg] }}
                        </td>
                        <td>
                            {{ $list->release_date->format('Y/m/d') }}
                        </td>
                        <td>
                            {{ $NewsConsts::RELEASE_FLG_LIST[$list->release_flg] }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $lists->withQueryString() }}
        </div>
    </div>

</x-admin-layout>