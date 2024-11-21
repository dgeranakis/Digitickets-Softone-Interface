onDocumentReady = function (fn) {
	if (document.readyState !== 'loading') {
		fn();
	} else {
		document.addEventListener('DOMContentLoaded', fn);
	}
}

isInViewport = function (element) {
	const rect = element.getBoundingClientRect();
	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
		rect.right <= (window.innerWidth || document.documentElement.clientWidth)

	);
}

setFocusAtEnd = function (element) {
	element.setSelectionRange(element.value.length, element.value.length);
	element.focus();
}

inPosition = function (element) {
	var currentTop = 0;
	if (element.offsetParent) {
		do {
			currentTop += element.offsetTop;
		} while ((element = element.offsetParent));
		return [currentTop];
	}
}

togglePassword = function (element, inputId) {
	let type = 'password';
	if (document.getElementById(inputId).value.length > 0) {
		type = document.getElementById(inputId).getAttribute('type') === 'password' ? 'text' : 'password';
	}
	document.getElementById(inputId).setAttribute('type', type);
}

dateFormat = function (inputDate, format) {
	const date = new Date(inputDate);

	const day = date.getDate();
	const month = date.getMonth() + 1;
	const year = date.getFullYear();

	format = format.replace("mm", month.toString().padStart(2, "0"));

	//replace the year
	if (format.indexOf("yyyy") > -1) {
		format = format.replace("yyyy", year.toString());
	} else if (format.indexOf("yy") > -1) {
		format = format.replace("yy", year.toString().substr(2, 2));
	}

	format = format.replace("dd", day.toString().padStart(2, "0"));
	return format;
}

showLoadingSpinner = function () {
	let loadingSpinner = document.getElementById("loading-spinner");
	loadingSpinner.style.display = 'block';
}

hideLoadingSpinner = function () {
	let loadingSpinner = document.getElementById("loading-spinner");
	loadingSpinner.style.display = 'none';
}

setFocusToInputEnd = function (inputID) {
	let input = document.getElementById(inputID);
	let end = input.value.length;
	input.setSelectionRange(end, end);
	input.focus();
}

clearErrors = function (elementSelector = null) {

	let invalidFeedbacks = document.querySelectorAll((elementSelector ? (elementSelector + ' ') : '') + '.invalid-feedback');
	invalidFeedbacks.forEach((invalidFeedback) => { invalidFeedback.remove(); });

	let invalidInputs = document.querySelectorAll((elementSelector ? (elementSelector + ' ') : '') + '.is-invalid');
	invalidInputs.forEach((invalidInput) => { invalidInput.classList.remove("is-invalid"); });

}

reset_selectPickers = function (formId) {
	let form = document.getElementById(formId);
	let selectItems = form.querySelectorAll('select.selectPicker');
	for (const item of selectItems) {
		item.dispatchEvent(new Event("change"));
	}
}

getFormData = function (input_selectors, checkbox_selectors = null, mutiselect_selectors = null, date_selectors = null, id = null) {

	let data = {};

	if (input_selectors) {
		Object.entries(input_selectors).forEach((entry) => {
			let [index, selector] = entry;
			if (id) selector = selector + '-' + id;
			data[index] = document.querySelector(selector).value;
		});
	}

	if (checkbox_selectors) {
		Object.entries(checkbox_selectors).forEach((entry) => {
			let [index, selector] = entry;
			if (id) selector = selector + '-' + id;
			data[index] = document.querySelector(selector).checked;
		});
	}

	if (mutiselect_selectors) {
		Object.entries(mutiselect_selectors).forEach((entry) => {
			let [index, selector] = entry;
			if (id) selector = selector + '-' + id;
			let multiSelectInput = document.querySelector(selector);

			let values = Array(...multiSelectInput.options).reduce((acc, option) => {
				if (option.selected === true) { acc.push(option.value); }
				return acc;
			}, []);
			data[index] = values;
		});
	}

	if (date_selectors) {
		Object.entries(date_selectors).forEach((entry) => {
			let [index, selector] = entry;
			if (id) selector = selector + '-' + id;
			let value = new Date($(selector).datepicker("getDate"));
			data[index] = dateFormat(value, 'yyyy-mm-dd');
		});
	}

	return data;
}

// title, content, color, showCloseBtn, okBtnText, closeBtnText, okBtnAction
openAlertModal = function (options = {}) {
	let alertModal = document.getElementById("alert-modal");

	// set modal title
	if (options.hasOwnProperty("title")) alertModal.querySelector("#alert-modal-title").innerHTML = options.title;
	else alertModal.querySelector("#alert-modal-title").innerHTML = "";

	// set modal content
	if (options.hasOwnProperty("content")) alertModal.querySelector("#alert-modal-text").innerHTML = options.content;
	else alertModal.querySelector("#alert-modal-text").innerHTML = "";

	// set modal ok button
	if (options.hasOwnProperty("okBtnText")) alertModal.querySelector("#alert-modal-ok-btn").innerHTML = options.okBtnText;
	else alertModal.querySelector("#alert-modal-ok-btn").innerHTML = alertModal.querySelector("#alert-modal-ok-btn").getAttribute('data-default-title');

	// set modal close button
	if (options.hasOwnProperty("closeBtnText")) alertModal.querySelector("#alert-modal-close-btn").innerHTML = options.closeBtnText;
	else alertModal.querySelector("#alert-modal-close-btn").innerHTML = alertModal.querySelector("#alert-modal-close-btn").getAttribute('data-default-title');

	// set modal colors
	if (options.hasOwnProperty("color") && options.color.length > 0) {
		alertModal.querySelector("#alert-modal-header").className = "modal-header bg-" + options.color;
		alertModal.querySelector("#alert-modal-title").className = "modal-title white";
		alertModal.querySelector("#alert-modal-close-icon").className = "btn-close d-none";
		alertModal.querySelector("#alert-modal-ok-btn").className = "btn btn-" + options.color + " ml-1";
	}
	else {
		alertModal.querySelector("#alert-modal-header").className = "modal-header";
		alertModal.querySelector("#alert-modal-title").className = "modal-title";
		alertModal.querySelector("#alert-modal-close-icon").className = "btn-close " + (options.hasOwnProperty("showCloseBtn") && options.showCloseBtn ? "" : "d-none");
		alertModal.querySelector("#alert-modal-ok-btn").className = "btn btn-primary ml-1";
	}

	// show modal close button
	if (options.hasOwnProperty("showCloseBtn") && options.showCloseBtn) alertModal.querySelector("#alert-modal-close-btn").className = "btn btn-light-secondary";
	else alertModal.querySelector("#alert-modal-close-btn").className = "btn btn-light-secondary d-none";

	// set ok button onclick action
	if (options.hasOwnProperty("okBtnAction")) alertModal.querySelector("#alert-modal-ok-btn").onclick = options.okBtnAction;
	else alertModal.querySelector("#alert-modal-ok-btn").onclick = "";

	// set close button onclick action
	if (options.hasOwnProperty("closeBtnAction")) alertModal.querySelector("#alert-modal-close-btn").onclick = options.closeBtnAction;
	else alertModal.querySelector("#alert-modal-close-btn").onclick = "";

	// show modal
	document.getElementById("toggle-alert-modal").click();
}


sendRequest = function (method, url, token, data, id = null, inline = false, successModalOptions = {}, errorModalOptions = {}, json = true) {
	showLoadingSpinner();

	let xhr = new XMLHttpRequest();
	xhr.open(method, url, true);
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	if (json) xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	xhr.setRequestHeader('X-CSRF-TOKEN', token);

	xhr.onload = function () {
		hideLoadingSpinner();

		if (xhr.readyState == 4 && xhr.status == 200) {
			let result = xhr.responseText;
			let resultJson = JSON.parse(result);

			successModalOptions.content = resultJson.message;
			if (!successModalOptions.hasOwnProperty("color")) successModalOptions.color = 'success';
			openAlertModal(successModalOptions);
		} else {
			let result = xhr.responseText;
			let resultJson = JSON.parse(result);

			if (resultJson.hasOwnProperty("errors") && Object.keys(resultJson.errors).length > 0) {
				Object.entries(resultJson.errors).forEach((entry) => {
					let [index, message] = entry;
					let selector = '#' + index.replace(".", "-");
					if (id && inline) selector += '-' + id;

					let element = document.querySelector(selector);

					let newMessageNode = document.createElement('div');
					newMessageNode.className = 'invalid-feedback d-block';
					newMessageNode.innerHTML = message;

					if (element.tagName.toLowerCase() === 'input' && element.getAttribute('type') === 'checkbox') {
						element.classList.add("is-invalid");
						if (element.parentNode.tagName.toLowerCase() == 'td') {
							element.parentNode.innerHTML += '<br><div class="invalid-feedback text-wrap">' + message + '</div>';
						} else {
							let parent = element.closest('.form-group');
							parent.innerHTML += '<div class="invalid-feedback d-block">' + message + '</div>';
						}
					}
					else if (element.classList.contains('selectPicker')) {
						element.classList.add("is-invalid");
						element.parentNode.appendChild(newMessageNode);
					}
					else {
						//element.setAttribute("value", data[index]);
						element.classList.add("is-invalid");

						// Insert the new node after the element
						element.parentNode.insertBefore(newMessageNode, element.nextSibling);
					}
				});
			} else {
				errorModalOptions.content = resultJson.message;
				if (!errorModalOptions.hasOwnProperty("color")) errorModalOptions.color = 'danger';
				openAlertModal(errorModalOptions);
			}
		} // if not status 200
	};

	if (json) data = JSON.stringify(data);
	xhr.send(data);
}

jsParseFloat = function (s) {
	s = s.replace(/[^\d,.-]/g, ''); // strip everything except numbers, dots, commas and negative sign
	if (/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)(?:,\d+)?$/.test(s)) // either in German locale or not match #,###.###### and now matches #.###,########
	{
		s = s.replace(/\./g, ''); // strip out dots
		s = s.replace(/,/g, '.'); // replace comma with dot
		return parseFloat(s);
	}
	else if (/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(s)) // if not in German locale and matches #,###.######
	{
		s = s.replace(/,/g, ''); // strip out commas
		return parseFloat(s); // convert to number
	}
	else // try #,###.###### anyway
	{
		s = s.replace(/,/g, ''); // strip out commas
		return parseFloat(s); // convert to number
	}
}

number_format = function (number, decimals, decPoint, thousandsSep) {
	//   example 1: number_format(1234.56) => returns 1: '1,235'
	//   example 2: number_format(1234.56, 2, ',', ' ') => returns 2: '1 234,56'
	//   example 3: number_format(1234.5678, 2, '.', '') => returns 3: '1234.57'
	//   example 4: number_format(67, 2, ',', '.') =>  returns 4: '67,00'
	//   example 5: number_format(1000) => returns 5: '1,000'
	//   example 6: number_format(67.311, 2) => returns 6: '67.31'
	//   example 7: number_format(1000.55, 1) => returns 7: '1,000.6'
	//   example 8: number_format(67000, 5, ',', '.') => returns 8: '67.000,00000'
	//   example 9: number_format(0.9, 0) => returns 9: '1'
	//  example 10: number_format('1.20', 2) => returns 10: '1.20'
	//  example 11: number_format('1.20', 4) => returns 11: '1.2000'
	//  example 12: number_format('1.2000', 3) => returns 12: '1.200'
	//  example 13: number_format('1 000,50', 2, '.', ' ') => returns 13: '100 050.00'
	//  example 14: number_format(1e-8, 8, '.', '') => returns 14: '0.00000001'
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	const n = !isFinite(+number) ? 0 : +number;
	const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
	const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
	const dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
	let s = '';
	const toFixedFix = function (n, prec) {
		if (('' + n).indexOf('e') === -1) {
			return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
		} else {
			const arr = ('' + n).split('e');
			let sig = '';
			if (+arr[1] + prec > 0) {
				sig = '+';
			}
			return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
		}
	}
	// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}
