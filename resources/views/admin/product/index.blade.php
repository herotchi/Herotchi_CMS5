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

                    <!-- タグ -->
                    <div class="col-md-12">
                        <x-input-label for="tag_ids" value="タグ" /><br />
                        @foreach($tags as $tag)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" id="tag_ids_{{ $tag->id }}" 
                                    class="form-check-input{{ $errors->has('tag_ids') || $errors->has('tag_ids.*') ? ' is-invalid' : '' }}" 
                                    value="{{ $tag->id }}" 
                                    name="tag_ids[]" 
                                    @checked(is_array(old('tag_ids', $input['tag_ids'])) && in_array($tag->id, old('tag_ids', $input['tag_ids']))) 
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
        <form method="POST" action="{{ route('admin.product.batch_destroy') }}" id="productBatchDestroy" novalidate>
            @method('DELETE')
            @csrf
            <div class="card-header">
                <div class="d-inline-flex">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#destroyModal">一括削除</button>
                </div>
                <div class="d-inline-flex float-end pt-1">
                    {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="all_checked" onclick="checkAll();">
                                </div>
                            </th>
                            <th>大カテゴリ名</th>
                            <th>中カテゴリ名</th>
                            <th>製品名</th>
                            <th>表示設定</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($lists as $list)
                        <tr>
                            <td scope="rol">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $list->id }}" name="delete_ids[]" id="delete_ids_{{ $list->id }}">
                                </div>
                            </td>
                            <td>
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
        </form>
    </div>

</x-admin-layout>
<!-- Modal -->
<div class="modal fade" id="destroyModal" tabindex="-1" aria-labelledby="destroyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="destroyModalLabel">製品情報一括削除</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span>※製品情報を一括削除します。よろしいですか？</span>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger w-50" onclick="batchDestroy();">削除する</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>
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

    // 全製品情報削除チェックボックスが操作されたとき
    function checkAll() {
        const allCheckbox = document.getElementById("all_checked");
        const deleteCheckboxes = document.getElementsByName("delete_ids[]");
        if (allCheckbox.checked) {
            for (var i = 0; i < deleteCheckboxes.length; i++) {
                deleteCheckboxes[i].checked = true;
            }
        } else {
            for (var i = 0; i < deleteCheckboxes.length; i++) {
                deleteCheckboxes[i].checked = false;
            }
        }
    }

    // 確認モーダルのボタンからsubmitを実行する
    function batchDestroy() {
        const form = document.getElementById('productBatchDestroy');
        const button = event.target; // クリックされたボタン
        if (form) {
            button.disabled = true; // ボタンを無効化
            form.submit();
        }
    }
</script>