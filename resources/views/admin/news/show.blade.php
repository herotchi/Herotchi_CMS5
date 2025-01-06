<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">お知らせ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ詳細</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">お知らせ詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>タイトル</h5>
                    <span>{{ $news->title }}</span>
                </li>
                <li class="list-group-item">
                    <h5>リンク設定</h5>
                    <span>{{ $NewsConsts::LINK_FLG_LIST[$news->link_flg] }}</span>
                </li>
                @if ($news->link_flg == $NewsConsts::LINK_FLG_ON)
                    <li class="list-group-item">
                        <h5>URL</h5>
                        <span>{{ $news->url }}</span>
                    </li>
                @else 
                    <li class="list-group-item">
                        <h5>概要</h5>
                        <span>{!! nl2br(e($news->overview)) !!}</span>
                    </li>
                @endif
                <li class="list-group-item">
                    <h5>公開日</h5>
                    <span>{{ $news->release_date->format('Y/m/d') }}</span>
                </li>
                <li class="list-group-item">
                    <h5>表示設定</h5>
                    <span>{{ $NewsConsts::RELEASE_FLG_LIST[$news->release_flg] }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.news.edit', $news) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.news.index')">戻る</x-secondary-button>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">削除</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
<x-destroy-modal title="お知らせ" :route="route('admin.news.destroy', $news)" />