<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.media.index') }}">メディア一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">メディア詳細</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-header">メディア詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>メディア設定</h5>
                    <span>{{ $MediaConsts::MEDIA_FLG_LIST[$media->media_flg] }}</span>
                </li>
                <li class="list-group-item">
                    <h5>メディア画像</h5>
                    <img src="{{ asset($media->image) }}" class="w-100">
                </li>
                <li class="list-group-item">
                    <h5>代替テキスト</h5>
                    <span>{{ $media->alt }}</span>
                </li>
                <li class="list-group-item">
                    <h5>URL</h5>
                    <span>{{ $media->url }}</span>
                </li>
                <li class="list-group-item">
                    <h5>表示設定</h5>
                    <span>{{ $MediaConsts::RELEASE_FLG_LIST[$media->release_flg] }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.media.edit', $media) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.media.index')">戻る</x-secondary-button>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">削除</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-destroy-modal title="メディア" :route="route('admin.media.destroy', $media)" />