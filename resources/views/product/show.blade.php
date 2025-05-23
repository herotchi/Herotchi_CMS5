<x-app-layout title="製品情報詳細">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('product.index') }}">製品情報一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">製品情報詳細</li>
        </ol>
    </nav>
    
    <div class="card shadow mb-4">
        <div class="card-header">製品情報詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>大カテゴリ名</h5>
                    <span>{{ $product->first_category->name }}</span>
                </li>
                <li class="list-group-item">
                    <h5>中カテゴリ名</h5>
                    <span>{{ $product->second_category->name }}</span>
                </li>
                <li class="list-group-item">
                    <h5>タグ</h5>
                    <span>
                        @foreach ($product->tags as $tag)
                            {{ $tag->name }}@if (!$loop->last),@endif
                        @endforeach
                    </span>
                </li>
                <li class="list-group-item">
                    <h5>製品名</h5>
                    <span>{{ $product->name }}</span>
                </li>
                <li class="list-group-item">
                    <h5>製品画像</h5>
                    <img src="{{ asset($product->image) }}" class="w-100">
                </li>
                <li class="list-group-item">
                    <h5>製品詳細</h5>
                    <span>{!! nl2br(e($product->detail)) !!}</span>
                </li>
                <li class="list-group-item">
                    <h5>表示設定</h5>
                    <span>{{ $ProductConsts::RELEASE_FLG_LIST[$product->release_flg] }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-secondary w-50" href="{{ route('product.index') }}" role="button">戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>