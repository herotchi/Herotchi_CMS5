<x-app-layout title="中カテゴリ詳細">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.second_category.index') }}">中カテゴリ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">中カテゴリ詳細</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-header">中カテゴリ詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>大カテゴリ名</h5>
                    <span>{{ $secondCategory->first_category->name }}</span>
                </li>
                <li class="list-group-item">
                    <h5>中カテゴリ名</h5>
                    <span>{{ $secondCategory->name }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.second_category.edit', $secondCategory) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.second_category.index')">戻る</x-secondary-button>
                    @if ($deleteFlg)
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">削除</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@if ($deleteFlg)
    <x-destroy-modal title="中カテゴリ" :route="route('admin.second_category.destroy', $secondCategory)" />
@endif
