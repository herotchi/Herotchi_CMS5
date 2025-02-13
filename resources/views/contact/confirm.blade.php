<x-app-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お問い合わせ</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <form action="{{ route('contact.store') }}" method="POST" novalidate>
            @csrf
            <div class="card-header">お問い合わせ確認</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h5>お問い合わせ内容</h5>
                        <span>{!! nl2br(e($input['body'])) !!}</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <button class="btn btn-primary w-50" type="submit" name="submit" value="submit">お問い合わせ</button>
                        <button class="btn btn-secondary" type="submit" name="back" value="back">戻る</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>