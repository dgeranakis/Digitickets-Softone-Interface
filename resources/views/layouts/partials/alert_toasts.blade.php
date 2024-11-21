<div class="toast align-items-center text-white bg-primary border-0 {{ session()->has('success') ? 'show' : '' }}"
    role="alert" aria-live="assertive" aria-atomic="true" id="toast-success">
    <div class="d-flex">
        <div class="toast-body p-4" id="toast-success-content">
            {{ session()->has('success') ? session('success') : '' }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
</div>

<div class="toast align-items-center text-white bg-danger border-0 {{ session()->has('error') ? 'show' : '' }}"
    role="alert" aria-live="assertive" aria-atomic="true" id="toast-error">
    <div class="d-flex">
        <div class="toast-body p-4">
            <span id="toast-error-content">
                {{ session()->has('error') ? session('error') : '' }}
            </span>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
</div>
