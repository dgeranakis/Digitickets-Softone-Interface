<div class="modal fade text-left modal-borderless" id="alert-modal" tabindex="-1" role="dialog"
    aria-labelledby="alertModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" id="alert-modal-header">
                <h5 class="modal-title" id="alert-modal-title"></h5>
                <button type="button" id="alert-modal-close-icon" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="alert-modal-text"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal"
                    id="alert-modal-close-btn" data-default-title="{{ __('admin.close') }}">
                    <i class="bx bx-x d-block d-sm-none"></i> <span
                        class="d-none d-sm-block">{{ __('admin.close') }}</span>
                </button>
                <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal" id="alert-modal-ok-btn"
                    data-default-title="{{ __('admin.ok') }}">
                    <i class="bx bx-check d-block d-sm-none"></i> <span
                        class="d-none d-sm-block">{{ __('admin.ok') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-outline-primary d-none" data-bs-toggle="modal" data-bs-target="#alert-modal"
    id="toggle-alert-modal"></button>

<script>
    const alertBsModal = document.getElementById("alert-modal");
    alertBsModal.addEventListener('keyup', (event) => {
        if (event.key.toLowerCase() == 'enter') {
            alertBsModal.querySelector("#alert-modal-ok-btn").click();
        } else if (event.key.toLowerCase() == 'shift') {
            if (window.getComputedStyle(alertBsModal.querySelector("#alert-modal-close-btn"), null).display !=
                'none') {
                alertBsModal.querySelector("#alert-modal-close-btn").click();
            }
        }

    }, false);
</script>
