<x-app-layout title="お知らせ詳細">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">お知らせ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ詳細</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-header"><h5 class="my-2">{{ $news->title }}</h5></div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <span>{!! nl2br(e($news->overview)) !!}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-secondary w-50" href="{{ route('news.index') }}" role="button">戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>