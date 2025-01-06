@props(['title', 'route'])

<!-- Modal -->
<div class="modal fade" id="destroyModal" tabindex="-1" aria-labelledby="destroyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ $route }}" novalidate>
                @method('DELETE')
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">{{ $title }}削除</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>※{{ $title }}を削除します。よろしいですか？</span>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-danger w-50">削除する</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                </div>
            </form>
        </div>
    </div>
</div>
