<x-app-layout title="タグ登録">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">タグ登録</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('admin.tag.store') }}" method="POST" novalidate>
            @csrf
            <div class="card-header">タグ登録</div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- タグ名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="タグ名" :required="true"/>
                        <x-text-input id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="name" 
                            :value="old('name')" 
                            required />
                        <x-input-error :message="$errors->first('name')" />
                    </div>
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