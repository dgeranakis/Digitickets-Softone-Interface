<div class="col-md-6 col-12 mb-5">
    <div class="mb-5">
        <input type="text" id="{{ 'remove_' . $input_id }}" name="{{ $remove_name ?? 'remove_' . $input_id }}"
            value="0" style="display:none">
        <label for="{{ $input_id }}" class="form-label">{{ $input_label }}</label>
        <div class="input-group">
            <input type="file" class="form-control" id="{{ $input_id }}" name="{{ $input_name ?? $input_id }}"
                onchange="imageChange('{{ $input_id }}')" placeholder="{{ $input_placeholder }}"
                data-browse="{{ $input_browse }}">
            <button class="btn btn-outline-secondary" type="button" id="clear_btn_{{ $input_id }}"
                onclick="clearImage('{{ $input_id }}')" style="display:none">{{ $input_clear }}</button>
        </div>
    </div>
    <div class="position-relative">
        @php
            $display = 'd-none';
            if (filled($input_src)) {
                $display = 'd-block';
            }
        @endphp
        <button type="button" id="remove_btn_{{ $input_id }}" onclick="removeImage('{{ $input_id }}')"
            title="{{ $input_remove }}"
            class="position-absolute top-0 end-0 me-4 btn-close btn-outline-danger {{ $display }}"
            aria-label="Close"></button>
        <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal"><img src="{{ $input_src }}"
                data-original_src="{{ $input_src }}" id="image_preview_{{ $input_id }}"
                class="img-fluid rounded mx-auto {{ $display }}" style="max-height:200px;"></a>
    </div>
</div>

<script>
    function elementShow(element) {
        element.classList.remove('d-none');
        element.classList.add('d-block');
    }

    function elementHide(element) {
        element.classList.remove('d-block');
        element.classList.add('d-none');
    }

    function imageChange(inputID) {
        let image = document.getElementById('image_preview_' + inputID);
        image.src = URL.createObjectURL(event.target.files[0]);
        elementShow(image);
        elementShow(document.getElementById('clear_btn_' + inputID));
    }

    function clearImage(inputID, reset = false) {

        let image = document.getElementById('image_preview_' + inputID);
        let inputImg = document.getElementById(inputID);

        inputImg.value = null;
        let originalSrc = image.getAttribute('data-original_src');
        if (originalSrc && originalSrc.length > 0) {
            image.src = originalSrc;
            elementShow(image);
            elementShow(document.getElementById('remove_btn_' + inputID));
        } else {
            image.src = '';
            elementHide(document.getElementById('remove_btn_' + inputID));
            elementHide(image);
        }

        elementHide(document.getElementById('clear_btn_' + inputID));

        if (reset) {
            let removeInput = document.getElementById('remove_' + inputID);
            removeInput.value = '0';
        }
    }

    function removeImage(inputID) {
        let image = document.getElementById('image_preview_' + inputID);
        let inputImg = document.getElementById(inputID);
        let removeInput = document.getElementById('remove_' + inputID);

        inputImg.value = null;
        removeInput.value = '1';
        image.src = '';
        elementHide(image);
        elementHide(document.getElementById('remove_btn_' + inputID));
        elementHide(document.getElementById('clear_btn_' + inputID));
    }
</script>
