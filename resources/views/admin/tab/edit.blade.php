<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tab.index') }}">タブ一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tab.show', $tab) }}">タブ詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">タブ編集</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.tab.update', $tab) }}" method="POST" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">タブ編集</div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- 大カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="タブ名" :required="true"/>
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $tab->name)" 
                            required />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button :href="route('admin.tab.show', $tab)">戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>