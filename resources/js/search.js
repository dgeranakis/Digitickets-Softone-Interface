addFilterSearch = function () {
	let searchFilter = document.querySelector('#accordionSearch #add-filter-select');

	let searchFilterValues = Array(...searchFilter.options).reduce((acc, option) => {
		if (option.selected === true) { acc.push(option.value); }
		return acc;
	}, []);

	let searchDivs = document.querySelectorAll('#accordionSearch .search-div');
	searchDivs.forEach((searchDiv) => {
		let dataSearch = searchDiv.getAttribute('data-search');
		if (searchFilterValues.includes(dataSearch)) {
			searchDiv.style.display = 'flex';
			document.querySelector('#accordionSearch #' + dataSearch + '-sch-chk').checked = true;
			searchInputVisibility(dataSearch);
		}
		else searchDiv.style.display = 'none';
	});
}

searchInputVisibility = function (dataSearch) {
	let searchCheckbox = document.querySelector('#accordionSearch #' + dataSearch + '-sch-chk');

	if (searchCheckbox.checked) {
		document.querySelector('#accordionSearch #' + dataSearch + '-sch-operator').style.display = 'block';
		searchInputVisibilityByOperator(dataSearch);
	}
	else {
		document.querySelector('#accordionSearch #' + dataSearch + '-sch-operator').style.display = 'none';
		let searchInput = document.querySelector('#accordionSearch #' + dataSearch + '-sch-input');
		let searchSelect = document.querySelector('#accordionSearch #' + dataSearch + '-sch-input-select');
		let searchRange1 = document.querySelector('#accordionSearch #' + dataSearch + '-sch-range1');
		let searchRange2 = document.querySelector('#accordionSearch #' + dataSearch + '-sch-range2');
		if (searchInput) searchInput.style.display = 'none';
		if (searchSelect) {
			searchSelect.style.display = 'none';
			if (searchSelect.classList.contains('selectPicker')) {
				searchSelect.nextElementSibling.style.display = 'none';
			}
		}
		if (searchRange1) searchRange1.style.display = 'none';
		if (searchRange2) searchRange2.style.display = 'none';
	}
}

searchInputVisibilityByOperator = function (dataSearch) {
	let searchOperator = document.querySelector('#accordionSearch #' + dataSearch + '-sch-operator');
	let searchInput = document.querySelector('#accordionSearch #' + dataSearch + '-sch-input');
	let searchSelect = document.querySelector('#accordionSearch #' + dataSearch + '-sch-input-select');
	let searchRange1 = document.querySelector('#accordionSearch #' + dataSearch + '-sch-range1');
	let searchRange2 = document.querySelector('#accordionSearch #' + dataSearch + '-sch-range2');

	if (searchOperator.options[searchOperator.selectedIndex].getAttribute('data-hide') == 'true') {
		if (searchInput) searchInput.style.display = 'none';
		if (searchSelect) {
			searchSelect.style.display = 'none';
			if (searchSelect.classList.contains('selectPicker')) {
				searchSelect.nextElementSibling.style.display = 'none';
			}
		}
		if (searchRange1) searchRange1.style.display = 'none';
		if (searchRange2) searchRange2.style.display = 'none';
	}
	else {
		if (searchOperator.options[searchOperator.selectedIndex].getAttribute('data-range') == 'true') {
			if (searchInput) searchInput.style.display = 'none';
			if (searchSelect) {
				searchSelect.style.display = 'none';
				if (searchSelect.classList.contains('selectPicker')) {
					searchSelect.nextElementSibling.style.display = 'none';
				}
			}

			if (searchRange1) {
				searchRange1.style.display = 'block';
				searchRange1.focus();
			}
			if (searchRange2) searchRange2.style.display = 'block';
		}
		else {
			if (searchInput) {
				searchInput.style.display = 'block';
				searchInput.focus();
			}
			if (searchSelect) {
				if (searchSelect.classList.contains('selectPicker')) {
					searchSelect.style.display = 'none';
					searchSelect.nextElementSibling.style.display = 'block';
					searchSelect.nextElementSibling.focus();
				}
				else {
					searchSelect.style.display = 'block';
					searchSelect.focus();
				}
			}

			if (searchRange1) searchRange1.style.display = 'none';
			if (searchRange2) searchRange2.style.display = 'none';
		}
	}
}

resetSearch = function (datatable_id) {
	document.querySelector('#adv-search').value = "";
	document.querySelector('#add-filter-select').value = "";
	document.querySelector('#add-filter-select').dispatchEvent(new Event("change"));

	let searchDivs = document.querySelectorAll('#accordionSearch .search-div');
	searchDivs.forEach((searchDiv) => { searchDiv.style.display = 'none'; });

	let searchChecks = document.querySelectorAll('#accordionSearch .form-check-input');
	searchChecks.forEach((checkBox) => { checkBox.checked = true; });

	let searchOperators = document.querySelectorAll('#accordionSearch .sch-operator');
	searchOperators.forEach((operator) => { operator.value = ""; operator.selectedIndex = 0; });

	let searchInputs = document.querySelectorAll('#accordionSearch .sch-input');
	searchInputs.forEach((inp) => { inp.value = ""; });

	let searchSelects = document.querySelectorAll('#accordionSearch .selectPicker');
	searchSelects.forEach((searchSelect) => { searchSelect.value = ""; searchSelect.dispatchEvent(new Event("change")); });

	window.LaravelDataTables[datatable_id].ajax.reload();
}

submitSearch = function (datatable_id) {

	var searchData = [];

	let searchFilter = document.querySelector('#accordionSearch #add-filter-select');
	let searchFilterValues = Array(...searchFilter.options).reduce((acc, option) => {
		if (option.selected === true) { acc.push(option.value); }
		return acc;
	}, []);

	searchFilterValues.forEach((searchValue) => {
		if (document.querySelector('#accordionSearch #' + searchValue + '-sch-chk') && document.querySelector('#accordionSearch #' + searchValue + '-sch-chk').checked) {
			let searchOperator = document.querySelector('#accordionSearch #' + searchValue + '-sch-operator');
			let operator = searchOperator.value;
			let value = '';

			if (searchOperator.options[searchOperator.selectedIndex].getAttribute('data-hide') != 'true') {

				if (searchOperator.options[searchOperator.selectedIndex].getAttribute('data-range') == 'true') {
					let date1 = new Date($('#' + searchValue + '-sch-range1').datepicker("getDate"));
					let date2 = new Date($('#' + searchValue + '-sch-range2').datepicker("getDate"));

					value = dateFormat(date1, 'yyyy-mm-dd') + ',' + dateFormat(date2, 'yyyy-mm-dd');
				}
				else {
					let input = document.querySelector('#accordionSearch #' + searchValue + '-sch-input');
					if (!input) input = document.querySelector('#accordionSearch #' + searchValue + '-sch-input-select');

					if (input) {
						value = input.value;
						if (input.tagName.toLowerCase() == 'select' && input.hasAttribute('multiple')) {
							let values = Array(...input.options).reduce((acc, option) => {
								if (option.selected === true) { acc.push(option.value); }
								return acc;
							}, []);
							value = values.join(',');
						}
						else if (input.classList.contains('datepicker')) {
							let date = new Date($('#' + input.id).datepicker("getDate"));
							value = dateFormat(date, 'yyyy-mm-dd');
						}
						else {
							value = input.value;
						}
					}
				}
			}

			let obj = { 'search': searchValue, 'operator': operator, 'value': value };
			searchData.push(obj);
		}
	});

	//console.log(searchData);
	document.querySelector('#accordionSearch #adv-search').value = JSON.stringify(searchData);
	window.LaravelDataTables[datatable_id].ajax.reload();
}

//document.addEventListener('DOMContentLoaded', function() {
//	const datatableSearch = document.querySelector(".dataTables_filter input[type=search]");
//	datatableSearch.addEventListener("keyup", function(event) {
//		document.querySelector("#accordionSearch #searchReset").click();
//	});
//});

