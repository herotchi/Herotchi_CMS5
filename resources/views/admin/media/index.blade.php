<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">メディア一覧</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.media.index') }}" method="GET" novalidate>
            <div class="card-header">メディア一覧</div>
            <div class="card-body">
                <div class="row g-3">


                    <!-- 製品名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="製品名" />
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $input['name'])" />
                        <x-input-error :message="$errors->first('name')" />
                    </div>

                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" />
                    <x-checkbox-input name="release_flg" :array="$ProductConsts::RELEASE_FLG_LIST" :old="old('release_flg', $input['release_flg'])" />
                    <x-input-error :message="$errors->first('release_flg') ?: $errors->first('release_flg.*')" />

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
                        <th>製品名</th>
                        <th>表示設定</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td scope="rol">
                            {{ $list->first_category->name }}
                        </td>
                        <td>
                            {{ $list->second_category->name }}
                        </td>
                        <td>
                            <a href="{{ route('admin.product.show', $list) }}">{{ $list->name }}</a>
                        </td>
                        <td>
                            {{ $ProductConsts::RELEASE_FLG_LIST[$list->release_flg] }}
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
