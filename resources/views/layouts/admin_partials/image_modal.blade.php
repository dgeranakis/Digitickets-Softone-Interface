<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-body position-relative">
                <button type="button" class="position-absolute top-0 end-0 m-4 mt-3 btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <img src="" class="img-fluid rounded mx-auto d-block my-5 image-view">
            </div>
        </div>
    </div>
</div>

<script>
    var imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function(event) {
        let button = event.relatedTarget;
        let imageSrc = button.querySelector('img').src;

        if (imageSrc) imageModal.querySelector('img.image-view').src = imageSrc;
        else imageModal.querySelector('img.image-view').src = '';
    });
</script>
