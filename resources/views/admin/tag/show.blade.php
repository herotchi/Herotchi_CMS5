<x-app-layout title="タグ詳細">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tag.index') }}">タグ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">タグ詳細</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-header">タグ詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>タグ名</h5>
                    <span>{{ $tag->name }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.tag.edit', $tag) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.tag.index')">戻る</x-secondary-button>
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
    <x-destroy-modal title="タグ" :route="route('admin.tag.destroy', $tag)" />
@endif
