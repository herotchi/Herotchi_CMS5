<x-app-layout title="大カテゴリCSV登録">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">大カテゴリCSV登録</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('admin.first_category.csv_import') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="card-header">大カテゴリCSV登録</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <div class="col-md-12">
                        <label><a href="{{ asset('storage/first_category/format.csv') }}" download="大カテゴリCSV登録フォーマット.csv">フォーマットをダウンロード</a></label>
                    </div>
    
                    <!-- 文字コード -->
                    <x-input-label for="code" value="文字コード" :required="true"/>
                    <x-radio-input name="code" :array="$FirstCategoryConsts::CSV_CODE_LIST" :old="old('code')" />
                    <x-input-error :message="$errors->first('code')" />
    
                    <div class="col-md-12">
                        <label for="csv_file" class="form-label">CSVファイル
                            <span class="text-danger font-weight-bold">※</span>
                        </label>
                        <input type="file" id="csv_file"
                            class="form-control{{ $errors->has('csv_file') ? ' is-invalid' : '' }}" name="csv_file"
                            value="{{ old('csv_file') }}" required>
                        @if ($errors->has('csv_file'))
                            @foreach ($errors->get('csv_file') as $error)
                                <div class="invalid-feedback">{{ $error }}</div>
                            @endforeach
                        @endif
                    </div>
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <button class="btn btn-primary w-50" type="submit">保存</button>
                        <a class="btn btn-secondary" href="{{ route('admin.top') }}" role="button">戻る</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>