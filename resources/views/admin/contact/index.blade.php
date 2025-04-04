<x-app-layout title="お問い合わせ一覧">
    <nav aria-label="パンくずリスト">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.top') }}">TOP</a></li>
            <li class="breadcrumb-item active" aria-current="page">お問い合わせ一覧</li>
        </ol>
    </nav>
    <div class="card shadow mb-4">
        <form action="{{ route('admin.contact.index') }}" method="GET" novalidate>
            <div class="card-header">お問い合わせ一覧</div>
            <div class="card-body">
                <div class="row g-3">
    
                    <!-- お問い合わせ番号 -->
                    <div class="col-md-6">
                        <x-input-label for="no" value="お問い合わせ番号" />
                        <x-text-input id="no" class="form-control{{ $errors->has('no') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="no" 
                            :value="old('no', $input['no'])" inputmode="numeric" />
                        <x-input-error :message="$errors->first('no')" />
                    </div>

                    <!-- お問い合わせ内容 -->
                    <div class="col-md-6">
                        <x-input-label for="body" value="お問い合わせ内容" />
                        <x-text-input id="body" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" 
                            type="text" 
                            name="body" 
                            :value="old('body', $input['body'])" />
                        <x-input-error :message="$errors->first('body')" />
                    </div>

                    <!-- 投稿日～ -->
                    <div class="col-md-6">
                        <x-input-label for="created_at_from" value="投稿日～" />
                        <x-text-input id="created_at_from" class="form-control{{ $errors->has('created_at_from') ? ' is-invalid' : '' }}" 
                            type="date" 
                            name="created_at_from" 
                            :value="old('created_at_from', $input['created_at_from'])" />
                        <x-input-error :message="$errors->first('created_at_from')" />
                    </div>

                    <!-- ～投稿日 -->
                    <div class="col-md-6">
                        <x-input-label for="created_at_to" value="～投稿日" />
                        <x-text-input id="created_at_to" class="form-control{{ $errors->has('created_at_to') ? ' is-invalid' : '' }}" 
                            type="date" 
                            name="created_at_to" 
                            :value="old('created_at_to', $input['created_at_to'])" />
                        <x-input-error :message="$errors->first('created_at_to')" />
                    </div>

                    <!-- ステータス -->
                    <x-input-label for="status" value="ステータス" />
                    <x-checkbox-input name="status" :array="$ContactConsts::STATUS_LIST" :old="old('status', $input['status'])" />
                    <x-input-error :message="$errors->first('status') ?: $errors->first('status.*')" />

                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-center my-2">
                        <button class="btn btn-primary w-50" type="submit" name="submit" value="submit">検索</button>
                        <a class="btn btn-secondary" href="{{ route('admin.top') }}" role="button">戻る</a>
                        <button class="btn btn-success float-end" type="submit" name="csv_export" value="csv_export">CSVダウンロード</button>
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
                        <th scope="col">お問い合わせ番号</th>
                        <th>氏名</th>
                        <th>メールアドレス</th>
                        <th>投稿日</th>
                        <th>ステータス</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td scope="rol">
                            <a href="{{ route('admin.contact.show', $list) }}">{{ $list->no }}</a>
                        </td>
                        <td>
                            {{ $list->user->name }}
                        </td>
                        <td>
                            {{ $list->user->email }}
                        </td>
                        <td>
                            {{ $list->created_at->format('Y/m/d') }}
                        </td>
                        <td>
                        {{ $ContactConsts::STATUS_LIST[$list->status] }}
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