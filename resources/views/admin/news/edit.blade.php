<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">お知らせ一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.news.show', $news) }}">お知らせ詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ編集</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.news.update', $news) }}" method="POST" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">お知らせ編集</div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- タイトル -->
                    <div class="col-md-12">
                        <x-input-label for="title" value="タイトル" :required="true"/>
                        <x-text-input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="title" 
                            :value="old('title', $news->title)" 
                            required />
                        <x-input-error :message="$errors->first('title')" />
                    </div>
    
                    <!-- リンク設定 -->
                    <x-input-label for="link_flg" value="リンク設定" :required="true"/>
                    <x-radio-input name="link_flg" :array="$NewsConsts::LINK_FLG_LIST" :old="old('link_flg', $news->link_flg)" />
                    <x-input-error :message="$errors->first('link_flg')" />

                    <!-- URL -->
                    <div class="col-md-12">
                        <x-input-label for="url" value="URL" />
                        <x-text-input id="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="url" 
                            :value="old('url')" 
                            required />
                        <x-input-error :message="$errors->first('url')" />
                    </div>

                    <!-- 概要 -->
                    <div class="col-md-12">
                        <x-input-label for="overview" value="概要" />
                        <x-text-area name="overview" :old="old('overview', $news->overview)" />
                        <x-input-error :message="$errors->first('overview')" />
                    </div>

                    <!-- 公開日 -->
                    <div class="col-md-12">
                        <x-input-label for="release_date" value="公開日"  :required="true" />
                        <x-text-input id="release_date" class="form-control{{ $errors->has('release_date') ? ' is-invalid' : '' }}" 
                            type="date" 
                            name="release_date" 
                            :value="old('release_date', $news->release_date->format('Y-m-d'))" 
                            required />
                        <x-input-error :message="$errors->first('release_date')" />
                    </div>
    
                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" :required="true"/>
                    <x-radio-input name="release_flg" :array="$NewsConsts::RELEASE_FLG_LIST" :old="old('release_flg', $news->release_flg)" />
                    <x-input-error :message="$errors->first('release_flg')" />
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button :href="route('admin.news.show', $news)">戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>