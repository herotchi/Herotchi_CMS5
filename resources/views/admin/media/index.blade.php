<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">メディア一覧</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('admin.media.index') }}" method="GET" novalidate>
            <div class="card-header">メディア一覧</div>
            <div class="card-body">
                <div class="row g-3">

                    <!-- メディア設定 -->
                    <x-input-label for="media_flg" value="メディア設定" />
                    <x-checkbox-input name="media_flg" :array="$MediaConsts::MEDIA_FLG_LIST" :old="old('media_flg', $input['media_flg'])" />
                    <x-input-error :message="$errors->first('media_flg') ?: $errors->first('media_flg.*')" />

                    <!-- 代替テキスト -->
                    <div class="col-md-12">
                        <x-input-label for="alt" value="製品名" />
                        <x-text-input id="alt" class="form-control{{ $errors->has('alt') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="alt" 
                            :value="old('alt', $input['alt'])" />
                        <x-input-error :message="$errors->first('alt')" />
                    </div>

                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" />
                    <x-checkbox-input name="release_flg" :array="$MediaConsts::RELEASE_FLG_LIST" :old="old('release_flg', $input['release_flg'])" />
                    <x-input-error :message="$errors->first('release_flg') ?: $errors->first('release_flg.*')" />

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
    
    <div class="card shadow mb-4">
        <div class="card-header text-end">
            {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($lists as $list)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <a class="link-underline link-underline-opacity-0" href="{{ route('admin.media.show', $list) }}">
                        <div class="card text-bg-light">
                            <img src="{{ asset($list->image) }}" class="card-img-top w-100 h-auto" alt="{{ $list->alt }}">
                            <div class="card-body">
                                <p class="card-text">メディア設定：{{ $MediaConsts::MEDIA_FLG_LIST[$list->media_flg] }}</p>
                                <p class="card-text">表示設定：{{ $MediaConsts::RELEASE_FLG_LIST[$list->release_flg] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            {{ $lists->withQueryString() }}
        </div>
    </div>

</x-app-layout>
