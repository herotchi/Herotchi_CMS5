<x-app-layout title="お知らせ一覧">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お知らせ一覧</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-body">
            @foreach($lists as $list)
            <p class="mb-1">{{ $list->release_date->format('Y年m月d日') }}</p>
            <p class="ms-4 mb-4">
                @if ($list->link_flg == $NewsConsts::LINK_FLG_ON)
                <a href="{{ $list->url }}" target="_blank" rel="noopener noreferrer">
                    {{ $list->title }}<x-blank />
                </a>
                @else
                    <a href="{{ route('news.show', $list) }}">
                        {{ $list->title }}
                    </a>
                @endif
            </p>
            @endforeach
        </div>
        <div class="card-footer">
            {{ $lists->withQueryString() }}
        </div>
    </div>
</x-app-layout>