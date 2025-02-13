<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お問い合わせ</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <form action="{{ route('contact.confirm') }}" method="POST" novalidate>
            @csrf
            <div class="card-header">お問い合わせ</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <!-- お問い合わせ内容 -->
                    <div class="col-md-12">
                        <x-input-label for="body" value="お問い合わせ内容" :required="true" />
                        <x-text-area name="body" :old="old('body')" />
                        <x-input-error :message="$errors->first('body')" />
                    </div>
    
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <button class="btn btn-primary w-50" type="submit">確認</button>
                        <a class="btn btn-secondary" href="{{ route('top') }}" role="button">戻る</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>