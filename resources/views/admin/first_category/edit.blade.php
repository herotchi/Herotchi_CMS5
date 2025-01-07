<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.first_category.index') }}">大カテゴリ一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.first_category.show', $firstCategory) }}">大カテゴリ詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">大カテゴリ編集</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.first_category.update', $firstCategory) }}" method="POST" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">大カテゴリ編集</div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- タイトル -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="大カテゴリ名" :required="true"/>
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $firstCategory->name)" 
                            required />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button :href="route('admin.first_category.show', $firstCategory)">戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>