<div class="toast-container position-fixed top-0 end-0 p-3">
    @if (session('msg_success'))
        <div id="successToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('msg_success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>    
    @elseif (session('msg_failure'))
        <div id="failureToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('msg_failure') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successToast = document.getElementById('successToast');
        const failureToast = document.getElementById('failureToast');
        const option = {
            'animation' : true,
            'autohide'  : true,
            'delay'     : 5000,
        };
        if (successToast) {
            const toast = new bootstrap.Toast(successToast, option);
            toast.show();
        } else if (failureToast) {
            const toast = new bootstrap.Toast(failureToast, option);
            toast.show();
        }
    });
</script>