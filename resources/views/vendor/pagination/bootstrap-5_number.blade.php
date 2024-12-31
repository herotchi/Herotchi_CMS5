@if ($paginator->total() === 0)
<nav class="d-flex justify-items-center justify-content-center">
    <div class="flex-fill d-flex align-items-center justify-content-end">
        <div>
            <p class="small text-muted my-1">
                <span class="fw-semibold">{{ $paginator->total() }}件</span>
            </p>
        </div>
    </div>
</nav>
@elseif ($paginator->total() === 1)
<nav class="d-flex justify-items-center justify-content-center">
    <div class="flex-fill d-flex align-items-center justify-content-end">
        <div>
            <p class="small text-muted my-1">
                <span class="fw-semibold">{{ $paginator->total() }}件中</span>
                <span class="fw-semibold">{{ $paginator->total() }}件目</span>
            </p>
        </div>
    </div>
</nav>
@elseif ($paginator->hasPages())
<nav class="d-flex justify-items-center justify-content-center">
    <div class="flex-fill d-flex align-items-center justify-content-end">
        <div>
            <p class="small text-muted my-1">
                <span class="fw-semibold">{{ $paginator->total() }}件中</span>
                <span class="fw-semibold">{{ $paginator->firstItem() }}～{{ $paginator->lastItem() }}件目</span>
            </p>
        </div>
    </div>
</nav>
@else
<nav class="d-flex justify-items-center justify-content-center">
    <div class="flex-fill d-flex align-items-center justify-content-end">
        <div>
            <p class="small text-muted my-1">
                <span class="fw-semibold">{{ $paginator->total() }}件中</span>
                <span class="fw-semibold">{{ $paginator->firstItem() }}～{{ $paginator->lastItem() }}件目</span>
            </p>
        </div>
    </div>
</nav>
@endif
