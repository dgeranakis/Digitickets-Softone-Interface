
clickInlineEditBtn = function (id, focusInput, datatableId = 'data-table') {
	clearErrors('#' + datatableId + ' .input-' + id);

	let dataElements = document.querySelectorAll('#' + datatableId + ' .data-' + id);
	dataElements.forEach((dataElement) => { dataElement.style.display = 'none'; });

	let inputElements = document.querySelectorAll('#' + datatableId + ' .input-' + id);
	inputElements.forEach((inputElement) => {
		inputElement.style.display = 'block';
		let selectPickerElement = inputElement.querySelector('.selectPicker');
		if (selectPickerElement) {
			createSelectPicker(selectPickerElement, {
				language: document.documentElement.lang
			});
		}

		let datePickerElement = $('#' + datatableId + ' .input-' + id + ' .datepicker');
		if (datePickerElement) {
			datePickerElement.datepicker({
				clearBtn: true,
				todayBtn: 'linked',
				todayHighlight: true,
				weekStart: 1,
				enableOnReadonly: false,
				format: 'dd/mm/yyyy',
				language: document.documentElement.lang,
				showOnFocus: true
			});
		}
	});

	setFocusToInputEnd(focusInput + '-' + id);
}

clickInlineCancelBtn = function (id, datatableId = 'data-table') {

	let inputElements = document.querySelectorAll('#' + datatableId + ' .input-' + id);
	inputElements.forEach((inputElement) => { inputElement.style.display = 'none'; });

	let dataElements = document.querySelectorAll('#' + datatableId + ' .data-' + id);
	dataElements.forEach((dataElement) => { dataElement.style.display = 'block'; });
}

newLineCreateButtons = function (insertLabel, cancelLabel) {
	let html = '<td class=" text-center text-nowrap d-print-none">'
		+ '<button type="button" class="btn btn-link py-0 px-1 mx-1 insert-btn" title="' + insertLabel + '" ><i class="bi bi-save fs-6"></i></button>'
		+ '<button type="button" class="btn btn-link text-danger py-0 px-1 mx-1 cancel-insert-btn" title="' + cancelLabel + '"><i class="bi bi-x-square fs-6"></i></button></td>';

	return html;
}
