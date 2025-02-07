<x-admin-layout>
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}">お問い合わせ一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">お問い合わせ詳細</li>
        </ol>
    </nav>

    <div class="card">
        <form action="{{ route('admin.contact.status_update', $contact) }}" method="POST" novalidate>
            @method('PUT')
            @csrf
            <div class="card-header">お問い合わせ詳細</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h5>お問い合わせ番号</h5>
                        <span>{{ $contact->no }}</span>
                    </li>
                    <li class="list-group-item">
                        <h5>投稿日</h5>
                        <span>{{ $contact->created_at->format('Y年m月d日 H時i分s秒') }}</span>
                    </li>
                    <li class="list-group-item">
                        <h5>氏名</h5>
                        <span>{{ $contact->user->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <h5>メールアドレス</h5>
                        <span>{{ $contact->user->email }}</span>
                    </li>
                    <li class="list-group-item">
                        <h5>お問い合わせ内容</h5>
                        <span>{!! nl2br(e($contact->body)) !!}</span>
                    </li>
                    <li class="list-group-item">
                        <h5>ステータス</h5>
                        <select id="status" class="form-select{{ $errors->has('status') ? ' is-invalid' : '' }}"
                            name="status">
                            <option value="">---</option>
                            @foreach($ContactConsts::STATUS_LIST as $key => $value)
                                <option value="{{ $key }}" @selected(old('status', $contact->status)==$key)>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :message="$errors->first('status')" />
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        {{--<button class="btn btn-primary w-50" type="submit">ステータス更新</button>
                        <a class="btn btn-secondary" href="{{ route('admin.contact.index') }}" role="button">戻る</a>--}}
                        <x-primary-button class="w-50">保存</x-primary-button>
                        <x-secondary-button :href="route('admin.contact.index')">戻る</x-secondary-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>