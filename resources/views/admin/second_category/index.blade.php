<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">中カテゴリ一覧</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.second_category.index') }}" method="GET" novalidate>
            <div class="card-header">中カテゴリ一覧</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <!-- 大カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="first_category_id" value="大カテゴリ名" :required="true"/>
                        <x-select-input name="first_category_id" :array="$firstCategories" :old="old('first_category_id', $input['first_category_id'])" />
                        <x-input-error :message="$errors->first('first_category_id')" />
                    </div>

                    <!-- 中カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="中カテゴリ名" />
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $input['name'])" />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">検索</x-primary-button>
                        <x-secondary-button>戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="card mt-4">
        <div class="card-header text-end">
            {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">大カテゴリ名</th>
                        <th>中カテゴリ名</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td scope="rol">
                            {{ $list->first_category->name }}
                        </td>
                        <td>
                            <a href="{{ route('admin.second_category.show', $list) }}">{{ $list->name }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $lists->withQueryString() }}
        </div>
    </div>

</x-admin-layout>