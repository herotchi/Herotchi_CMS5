<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">製品情報一覧</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.product.index') }}" method="GET" novalidate>
            <div class="card-header">製品情報一覧</div>
            <div class="card-body">
                <div class="row g-3">

                    <!-- 大カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="first_category_id" value="大カテゴリ名" />
                        <x-select-input name="first_category_id" :array="$firstCategories" :old="old('first_category_id', $input['first_category_id'])" />
                        <x-input-error :message="$errors->first('first_category_id')" />
                    </div>

                    <!-- 中カテゴリ名 -->
                    <div class="col-md-12">
                        <x-input-label for="second_category_id" value="中カテゴリ名" />
                        <x-select-input name="second_category_id" :array="array()" :old="old('second_category_id', $input['second_category_id'])" />
                        <x-input-error :message="$errors->first('second_category_id')" />
                    </div>

                    <!-- タブ -->
                    <div class="col-md-12">
                        <x-input-label for="tab_ids" value="タブ" /><br />
                        @foreach($tabs as $tab)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" id="tab_ids_{{ $tab->id }}" 
                                    class="form-check-input{{ $errors->has('tab_ids') || $errors->has('tab_ids.*') ? ' is-invalid' : '' }}" 
                                    value="{{ $tab->id }}" 
                                    name="tab_ids[]" 
                                    @checked(is_array(old('tab_ids', $input['tab_ids'])) && in_array($tab->id, old('tab_ids', $input['tab_ids']))) 
                                    autocomplete="off">
                                <label class="form-check-label" 
                                    for="tab_ids_{{ $tab->id }}">{{ $tab->name }}
                                </label>
                            </div>
                        @endforeach
                        <div class="mt-0{{ $errors->has('tab_ids') || $errors->has('tab_ids.*') ? ' is-invalid' : '' }}"></div>
                        <x-input-error :message="$errors->first('tab_ids') ?: $errors->first('tab_ids.*')" />
                    </div>

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
                if (@json(old('second_category_id', $input['second_category_id'])) == item.value) {
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
/*
    // 全製品情報削除チェックボックスが操作されたとき
    function checkAll() {
        const allCheckbox = document.getElementById("all_checked");
        const deleteCheckboxes = document.getElementsByName("delete_flg[]");
        if (allCheckbox.checked) {
            for (var i = 0; i < deleteCheckboxes.length; i++) {
                deleteCheckboxes[i].checked = true;
            }
        } else {
            for (var i = 0; i < deleteCheckboxes.length; i++) {
                deleteCheckboxes[i].checked = false;
            }
        }
    }*/
</script>