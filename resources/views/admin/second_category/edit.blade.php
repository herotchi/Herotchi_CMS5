<x-app-layout title="中カテゴリ編集">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.second_category.index') }}">中カテゴリ一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.second_category.show', $secondCategory) }}">中カテゴリ詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">中カテゴリ編集</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('admin.second_category.update', $secondCategory) }}" method="POST" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">中カテゴリ編集</div>
            <div class="card-body">
                <div class="row g-3">
                    
                    <!-- 大カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="first_category_id" value="大カテゴリ名" :required="true"/>
                        <x-select-input name="first_category_id" :array="$firstCategories" :old="old('first_category_id', $secondCategory->first_category_id)" />
                        <x-input-error :message="$errors->first('first_category_id')" />
                    </div>

                    <!-- 中カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="中カテゴリ名" :required="true"/>
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $secondCategory->name)" 
                            required />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button :href="route('admin.second_category.show', $secondCategory)">戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>