<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tab.index') }}">タブ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">タブ詳細</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">タブ詳細</div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <h5>タブ名</h5>
                    <span>{{ $tab->name }}</span>
                </li>
            </ul>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-primary w-50" href="{{ route('admin.tab.edit', $tab) }}" role="button">編集</a>
                    <x-secondary-button :href="route('admin.tab.index')">戻る</x-secondary-button>
                    <!-- Button trigger modal -->
                    {{--<button type="button" class="btn btn-outline-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">削除</button>--}}
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
{{--<x-destroy-modal title="タブ" :route="route('admin.tab.destroy', $tab)" />--}}