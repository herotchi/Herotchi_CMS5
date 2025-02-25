<x-app-layout title="お問い合わせ完了">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お問い合わせ</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-header">お問い合わせ完了</div>
        <div class="card-body">
            お問い合わせが完了しました。お問い合わせ番号は&ensp;<strong>{{ $no }}</strong>&ensp;です。担当から連絡があるまでお待ちください。
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-center my-2">
                    <a class="btn btn-secondary w-50" href="{{ route('top') }}" role="button">戻る</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>