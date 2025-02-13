<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">製品情報一覧</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.product.show', $product) }}">製品情報詳細</a></li>
            <li class="breadcrumb-item active" aria-current="page">製品情報編集</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.product.update', $product) }}" method="POST" enctype="multipart/form-data" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">製品情報編集</div>
            <div class="card-body">
                <div class="row g-3">

                    <!-- 大カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="first_category_id" value="大カテゴリ名" :required="true"/>
                        <x-select-input name="first_category_id" :array="$firstCategories" :old="old('first_category_id', $product->first_category_id)" />
                        <x-input-error :message="$errors->first('first_category_id')" />
                    </div>

                    <!-- 中カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="second_category_id" value="中カテゴリ名" :required="true"/>
                        <x-select-input name="second_category_id" :array="array()" :old="old('second_category_id', $product->second_category_id)" />
                        <x-input-error :message="$errors->first('second_category_id')" />
                    </div>

                    <!-- タグ -->
                    <div class="col-md-12">
                        <x-input-label for="tag_ids" value="タグ" :required="true" /><br />
                        @foreach($tags as $tag)
                            {{--<input type="checkbox" class="btn-check" id="tag_ids_{{ $tag->id }}" 
                                value="{{ $tag->id }}" 
                                name="tag_ids[]" 
                                @checked(is_array(old('tag_ids')) && in_array($tag->id, old('tag_ids'))) 
                                autocomplete="off">
                            <label class="btn  btn-outline-success {{ $errors->has('tag_ids') ? ' is-invalid' : '' }}" 
                                for="tag_ids_{{ $tag->id }}">{{ $tag->name }}</label>--}}
                            <div class="form-check form-check-inline">
                                <input type="checkbox" id="tag_ids_{{ $tag->id }}" 
                                    class="form-check-input{{ $errors->has('tag_ids') || $errors->has('tag_ids.*') ? ' is-invalid' : '' }}" 
                                    value="{{ $tag->id }}" 
                                    name="tag_ids[]" 
                                    @checked(is_array(old('tag_ids', $product->tags->pluck('id')->toArray())) && in_array($tag->id, old('tag_ids', $product->tags->pluck('id')->toArray()))) 
                                    autocomplete="off">
                                <label class="form-check-label" 
                                    for="tag_ids_{{ $tag->id }}">{{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                        <div class="mt-0{{ $errors->has('tag_ids') || $errors->has('tag_ids.*') ? ' is-invalid' : '' }}"></div>
                        <x-input-error :message="$errors->first('tag_ids') ?: $errors->first('tag_ids.*')" />
                    </div>

                    <!-- 製品名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="製品名" :required="true"/>
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name', $product->name)" 
                            required />
                        <x-input-error :message="$errors->first('name')" />
                    </div>

                    <!-- 製品画像 -->
                    <div class="col-md-12">
                        <x-input-label for="image" value="製品画像" />
                        <img src="{{ asset($product->image) }}">
                        <input type="file" id="image"
                            class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" name="image"
                            value="{{ old('image', $product->image) }}" required>
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    </div>

                    <!-- 製品詳細 -->
                    <div class="col-md-12">
                        <x-input-label for="detail" value="製品詳細" :required="true" />
                        <x-text-area name="detail" :old="old('detail', $product->detail)" />
                        <x-input-error :message="$errors->first('detail')" />
                    </div>

                    <!-- 表示設定 -->
                    <x-input-label for="release_flg" value="表示設定" :required="true"/>
                    <x-radio-input name="release_flg" :array="$ProductConsts::RELEASE_FLG_LIST" :old="old('release_flg', $product->release_flg)" />
                    <x-input-error :message="$errors->first('release_flg')" />
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button>戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
<script>
    const firstSelect = document.getElementById('first_category_id');
    const secondSelect = document.getElementById('second_category_id');
    
    const secondCategories = {{ Js::from($secondCategories) }};
    const optionsByFirst = {};
    
    // 大カテゴリごとに紐づいている中カテゴリの配列を作成
    secondCategories.forEach(secondCategory => {
        if (!optionsByFirst[secondCategory.first_category_id]) {
            optionsByFirst[secondCategory.first_category_id] = [];
        }
    
        optionsByFirst[secondCategory.first_category_id].push({value: secondCategory.id, text: secondCategory.name});
    });
    
    // 画面が表示されたときに選択されていた大カテゴリと中カテゴリを再現する
    document.addEventListener('DOMContentLoaded', function() {
        const firstOption = firstSelect.value;

        // 大カテゴリが選択されている場合
        if (firstOption) {
            const secondOptions = optionsByFirst[firstOption];
    
            secondSelect.innerHTML = '';
    
            const newOption = document.createElement('option');
            newOption.value = '';
            newOption.text = '---';
            secondSelect.appendChild(newOption);
    
            secondOptions.forEach(item => {
                const newOption = document.createElement('option');
                newOption.value = item.value;
                newOption.text = item.text;
                if (@json(old('second_category_id', $product->second_category_id)) == item.value) {
                    newOption.setAttribute('selected', 'selected');
                }
                secondSelect.appendChild(newOption);
            });
        } else {
            // 大カテゴリが選択されていない、もしくは入力エラーがあった場合
            secondSelect.innerHTML = '';
    
            const newOption = document.createElement('option');
            newOption.value = '';
            newOption.text = '---';
            secondSelect.appendChild(newOption);
        }
    });
    
    // 大カテゴリが選択された場合、それに紐づいた中カテゴリのプルダウンを用意する
    firstSelect.addEventListener('change', function() {
        const firstOption = firstSelect.value;

        // 存在する大カテゴリが選択された場合
        if (firstOption) {
            const secondOptions = optionsByFirst[firstOption];
    
            secondSelect.innerHTML = '';
    
            const newOption = document.createElement('option');
            newOption.value = '';
            newOption.text = '---';
            secondSelect.appendChild(newOption);
    
            secondOptions.forEach(item => {
                const newOption = document.createElement('option');
                newOption.value = item.value;
                newOption.text = item.text;
                secondSelect.appendChild(newOption);
            });
        } else {
            // 空の大カテゴリが選択された場合
            secondSelect.innerHTML = '';
    
            const newOption = document.createElement('option');
            newOption.value = '';
            newOption.text = '---';
            secondSelect.appendChild(newOption);
        }
    });
    </script>