<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">製品情報一覧</li>
        </ol>
    </nav>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow">
                <form action="{{ route('product.index') }}" method="GET" novalidate>
                    <div class="card-header">製品情報検索</div>
                    <div class="card-body">
                        <div class="row g-3">
        
                            <!-- キーワード -->
                            <div class="col-md-12">
                                <x-input-label for="keyword" value="キーワード" />
                                <x-text-input id="keyword" class="form-control{{ $errors->has('keyword') ? ' is-invalid' : '' }}" 
                                    type="text" 
                                    name="keyword" 
                                    :value="old('keyword', $input['keyword'])" />
                                <x-input-error :message="$errors->first('keyword')" />
                            </div>

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
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 text-center my-2">
                                <x-primary-button class="w-100">検索</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header text-end">
                    {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($lists as $list)
                        <div class="col-xxl-3 col-lg-4 col-md-6 mb-3">
                            <a class="link-underline link-underline-opacity-0" href="{{ route('product.show', $list) }}">
                                <div class="card text-bg-light">
                                    <img src="{{ asset($list->image) }}" class="card-img-top w-100 h-auto">
                                    <div class="card-body">
                                        <p class="card-text mb-1 text-secondary fs-6">
                                            <small>
                                                カテゴリ:
                                                {{ $list->first_category->name }},
                                                {{ $list->second_category->name }}
                                            </small>
                                        </p>
                                        <p class="card-text mb-1 text-secondary fs-6">
                                            <small>
                                                タグ:
                                                @foreach ($list->tags as $tag)
                                                    {{ $tag->name }}@if (!$loop->last),@endif                                                
                                                @endforeach
                                            </small>
                                        </p>
                                        <p class="card-text"><b>{{ $list->name }}</b></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                    {{ $lists->withQueryString() }}
                </div>
            </div>
        </div>
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
</script>