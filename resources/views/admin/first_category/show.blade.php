<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.first_category.index') }}">大カテゴリ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">大カテゴリ詳細</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">大カテゴリ詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>大カテゴリ名</h5>
                    <span>{{ $firstCategory->name }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.first_category.edit', $firstCategory) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.first_category.index')">戻る</x-secondary-button>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">削除</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
<x-destroy-modal title="大カテゴリ" :route="route('admin.first_category.destroy', $firstCategory)" />