<x-app-layout title="タグ一覧">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">タグ一覧</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('admin.tag.index') }}" method="GET" novalidate>
            <div class="card-header">タグ一覧</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <!-- タグ名 -->
                    <div class="col-md-12">
                        <x-input-label for="name" value="タグ名" />
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
    
    <div class="card shadow mb-4">
        <div class="card-header text-end">
            {{ $lists->links('vendor.pagination.bootstrap-5_number') }}
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">タグ名</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td scope="rol">
                            <a href="{{ route('admin.tag.show', $list) }}">{{ $list->name }}</a>
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

</x-app-layout>