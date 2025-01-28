<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.media.index') }}">メディア一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.media.show', $media) }}">メディア詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">メディア編集</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.media.update', $media) }}" method="POST" enctype="multipart/form-data" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">メディア編集</div>
            <div class="card-body">
                <div class="row g-3">

                    <!-- メディア設定 -->
                    <x-input-label for="media_flg" value="メディア設定" :required="true"/>
                    <x-radio-input name="media_flg" :array="$MediaConsts::MEDIA_FLG_LIST" :old="old('media_flg', $media->media_flg)" />
                    <x-input-error :message="$errors->first('media_flg')" />

                    <!-- メディア画像 -->
                    <div class="col-md-12">
                        <x-input-label for="image" value="メディア画像" />
                        <img src="{{ asset($media->image) }}">
                        <input type="file" id="image"
                            class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image"
                            value="{{ old('image', $media->image) }}" required>
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    </div>

                    <!-- 代替テキスト -->
                    <div class="col-md-12">
                        <x-input-label for="alt" value="代替テキスト" :required="true"/>
                        <x-text-input id="alt" class="form-control{{ $errors->has('alt') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="alt" 
                            :value="old('alt', $media->alt)" 
                            required />
                        <x-input-error :message="$errors->first('alt')" />
                    </div>

                    <!-- URL -->
                    <div class="col-md-12">
                        <x-input-label for="url" value="URL" :required="true"/>
                        <x-text-input id="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="url" 
                            :value="old('url', $media->url)" 
                            required />
                        <x-input-error :message="$errors->first('url')" />
                    </div>

                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" :required="true"/>
                    <x-radio-input name="release_flg" :array="$MediaConsts::RELEASE_FLG_LIST" :old="old('release_flg', $media->release_flg)" />
                    <x-input-error :message="$errors->first('release_flg')" />
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button>戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
