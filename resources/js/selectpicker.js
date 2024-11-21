/*
 * multiple: for multi select
 * se optgroup html element to group related options
 * add an individual option with no value to act as placeholder (ex <option value="">Please select an option</option>)
 * Set the title attribute on individual options to display alternative text when the option is selected (by default inner text is displayed).
 * data-select-size or option.size : sets the size of the select (sm, lg or "" which is default)
 * data-select-search or option.search : sets if search input will be shown (true or false, default is false).
 * data-select-creatable or option.creatable : add more options to the select box (true or false, default is false).
 * data-select-clearable or option.clearable : sets if clear x will be shown to clear selected data (true or false, default is false). Available only for single select.
 * data-select-actions or option.actions : Adds two buttons to the top of the menu - Select All & Deselect All (true or false, default is false). Available only for multiple select.
 * data-select-max-height or option.maxHeight: sets maximum height of options div (default is 360px)
 * option.language: to set the labels language (default is en)
 * add data-select-tokens to individual options with keywords to improve their searchability
 * 
 * example:
 * 
 * <select class="form-select col-6 selectPicker" id="testSelect" data-select-search="true" data-select-max-height="400px"
 *  data-select-size="" data-select-clearable="true" data-select-actions="true" multiple>
 *      <option value="">Please select an option</option>
 *      <optgroup label="First Five">
 *          <option value="1" title="Combo 1" selected>One</option>
 *          <option value="2" data-select-tokens="deux dos">Two</option>
 *          <option value="3">Three</option>
 *          <option value="4" selected>Four</option>
 *          <option value="5">Five</option>
 *      </optgroup>
 *      <optgroup label="Second Five">
 *          <option value="6">Six</option>
 *          <option value="7">Seven</option>
 *          <option value="8">Eight</option>
 *          <option value="9">Nine</option>
 *          <option value="10">Ten</option>
 *      </optgroup>
 * </select>
 * <script>
 * createSelectPicker(document.querySelector('#testSelect'), { language: 'en', creatable: true });
 * //or
 * createSelectPickerAll('.selectPicker', {language: "en"});
 * </script>
 *
 */


selectPickerUpdate = function (button, classElement, classToggler) {
    const value = button.dataset.selectValue;
    const selectTarget = button.closest(`.${classElement}`).previousElementSibling;
    const toggler = selectTarget.nextElementSibling.getElementsByClassName(classToggler)[0];
    const searchInput = selectTarget.nextElementSibling.querySelector("input");
    if (selectTarget.multiple) {
        Array.from(selectTarget.options).filter((option) => option.value === value)[0].selected = true;
    } else {
        selectTarget.value = value;
    }

    //if (selectTarget.multiple) toggler.click();
    if (searchInput && !selectTarget.multiple) searchInput.value = "";
    selectTarget.dispatchEvent(new Event("change"));
    toggler.focus();
}

selectPickerRemoveTag = function (removeButton, classElement, classToggler) {
    const value = removeButton.parentNode.dataset.selectValue;
    const selectTarget = removeButton.closest(`.${classElement}`).previousElementSibling;
    const toggler = selectTarget.nextElementSibling.getElementsByClassName(classToggler)[0];
    const searchInput = selectTarget.nextElementSibling.querySelector("input");

    Array.from(selectTarget.options).filter((option) => option.value === value)[0].selected = false;
    if (searchInput) searchInput.value = "";
    selectTarget.dispatchEvent(new Event("change"));
    toggler.click();
}

selectPickerSearch = function (event, searchInput, classElement, classToggler, creatable, pressEnterLabel) {
    const filterValue = searchInput.value.toLowerCase().trim();
    const itemsContainer = searchInput.parentElement.getElementsByClassName("selectPicker-items")[0];
    const headers = itemsContainer.querySelectorAll(".dropdown-header");
    const items = itemsContainer.querySelectorAll(".dropdown-item");
    const noResults = itemsContainer.nextElementSibling;

    headers.forEach((i) => i.classList.add("d-none"));
    for (const item of items) {
        const filterText = item.textContent + (item.dataset.selectTokens || "");
        if (filterText.toLowerCase().indexOf(filterValue) > -1) {
            item.classList.remove("d-none");
            let header = item;
            while (header = header.previousElementSibling) {
                if (header.classList.contains("dropdown-header")) {
                    header.classList.remove("d-none");
                    break;
                }
            }
        } else {
            item.classList.add("d-none");
        }
    }

    const found = Array.from(items).filter((i) => !i.classList.contains("d-none") && !i.hasAttribute("hidden"));
    if (found.length < 1) {
        noResults.classList.remove("d-none");
        itemsContainer.classList.add("d-none");
        if (creatable) {
            noResults.innerHTML = `${pressEnterLabel} "<strong>${searchInput.value}</strong>"`;
            if (event.key === "Enter") {
                const selectTarget = searchInput.closest(`.${classElement}`).previousElementSibling;
                const toggler = selectTarget.nextElementSibling.getElementsByClassName(classToggler)[0];
                selectTarget.insertAdjacentHTML("afterbegin", `<option value="${searchInput.value}" selected>${searchInput.value}</option>`);
                selectTarget.dispatchEvent(new Event("change"));
                searchInput.value = "";
                searchInput.dispatchEvent(new Event("keyup"));
                toggler.click();
                toggler.focus();
            }
        }
    } else {
        noResults.classList.add("d-none");
        itemsContainer.classList.remove("d-none");
    }
}

selectPickerClear = function (clearButton, classElement) {
    const selectTarget = clearButton.closest(`.${classElement}`).previousElementSibling;
    Array.from(selectTarget.options).forEach((option) => option.selected = false);
    selectTarget.dispatchEvent(new Event("change"));
}

selectAll = function (button, classElement, classToggler) {
    const selectTarget = button.closest(`.${classElement}`).previousElementSibling;
    const toggler = selectTarget.nextElementSibling.getElementsByClassName(classToggler)[0];

    const itemsContainer = toggler.nextElementSibling.getElementsByClassName("selectPicker-items")[0];
    const items = itemsContainer.querySelectorAll(".dropdown-item");

    for (const item of items) {
        if (!item.classList.contains("d-none") && !item.classList.contains("active") && item.getAttribute("data-select-value") !== "") {
            const value = item.dataset.selectValue;
            if (selectTarget.multiple) {
                Array.from(selectTarget.options).filter((option) => option.value === value)[0].selected = true;
            } else {
                selectTarget.value = value;
            }

            selectTarget.dispatchEvent(new Event("change"));
            toggler.focus();
        }
    }
}

deselectAll = function (button, classElement, classToggler) {
    const selectTarget = button.closest(`.${classElement}`).previousElementSibling;
    const toggler = selectTarget.nextElementSibling.getElementsByClassName(classToggler)[0];
    const searchInput = selectTarget.nextElementSibling.querySelector("input");

    const itemsContainer = toggler.nextElementSibling.getElementsByClassName("selectPicker-items")[0];
    const items = itemsContainer.querySelectorAll(".dropdown-item");

    for (const item of items) {
        if (!item.classList.contains("d-none") && item.classList.contains("active")) {
            const value = item.dataset.selectValue;
            Array.from(selectTarget.options).filter((option) => option.value === value)[0].selected = false;
            selectTarget.dispatchEvent(new Event("change"));
            //toggler.click();
        }
    }
}

createSelectPicker = function (element, option = {}) {
    element.style.display = 'none';

    const labels = {
        'el': {
            searchPlaceholder: 'Αναζήτηση',
            noResults: 'Δεν βρέθηκαν αποτελέσματα',
            clearSelection: 'Καθάρισμα Επιλογής',
            pressEnter: 'Πατήστε Enter για να προσθέσετε το',
            selectAllText: 'Επιλογή όλων',
            deselectAllText: 'Αποεπιλογή όλων'
        },
        'de': {
            searchPlaceholder: 'Suche',
            noResults: 'Keine Ergebnisse gefunden',
            clearSelection: 'Auswahl Löschen',
            pressEnter: 'Press Enter to add',
            selectAllText: 'Alles auswählen',
            deselectAllText: 'Nichts auswählen'
        },
        'en': {
            searchPlaceholder: 'Search',
            noResults: 'No results found',
            clearSelection: 'Clear Selection',
            pressEnter: 'Press Enter to add',
            selectAllText: 'Select All',
            deselectAllText: 'Deselect All'
        }
    };

    const classElement = "selectPicker-wrapper";
    const classNoResults = "selectPicker-no-results";
    const classTag = "selectPicker-tag";
    const classTagRemove = "selectPicker-tag-remove";
    const classPlaceholder = "selectPicker-placeholder";
    const classClearBtn = "selectPicker-clear";
    const classTogglerClearable = "selectPicker-clearable";
    const defaultSearch = false;
    const defaultCreatable = false;
    const defaultClearable = false;
    const defaultActions = false;
    const defaultFixed = false;
    const defaultMaxHeight = "360px";
    const defaultSize = "";
    const defaultLanguage = 'en';
    const search = booleanAttribute("search") || option.search || defaultSearch;
    const creatable = booleanAttribute("creatable") || option.creatable || defaultCreatable;
    const clearable = booleanAttribute("clearable") || option.clearable || defaultClearable;
    const actions = booleanAttribute("actions") || option.actions || defaultActions;
    const fixed = booleanAttribute("fixed") || option.fixed || defaultFixed;
    const maxHeight = element.dataset.selectMaxHeight || option.maxHeight || defaultMaxHeight;

    let size = element.dataset.selectSize || option.size || defaultSize;
    size = size !== "" ? ` form-select-${size}` : "";
    const classToggler = `form-select${size}`;

    let language = option.language || defaultLanguage;
    language = (labels[language] ? language : defaultLanguage);

    const searchInput = search ? `<input onkeydown="return event.key !== 'Enter'" onkeyup="selectPickerSearch(event, this, '${classElement}', '${classToggler}', ${creatable}, '${labels[language].pressEnter}')" type="text" class="form-control" placeholder="${labels[language].searchPlaceholder}" aria-label="${labels[language].searchPlaceholder}" autofocus>` : "";

    function booleanAttribute(attribute) {
        const dataAttribute = `data-select-${attribute}`;
        if (!element.hasAttribute(dataAttribute)) return null;
        const value = element.getAttribute(dataAttribute);
        return value.toLowerCase() === "true";
    }

    function removePrevious() {
        if (element.nextElementSibling && element.nextElementSibling.classList && element.nextElementSibling.classList.contains(classElement)) {
            element.nextElementSibling.remove();
        }
    }

    function isPlaceholder(selectedOption) {
        return selectedOption.getAttribute("value") === "";
    }

    function selectedTag(options, multiple) {
        if (multiple) {
            const selectedOptions = Array.from(options).filter((innerOption) => innerOption.selected && !isPlaceholder(innerOption));
            const placeholderOption = Array.from(options).filter((innerOption) => isPlaceholder(innerOption));
            let tags = [];
            if (selectedOptions.length === 0) {
                const text = placeholderOption.length ? placeholderOption[0].textContent : "&nbsp;";
                tags.push(`<span class="${classPlaceholder}">${text}</span>`);
            }
            else {
                for (const innerOption of selectedOptions) {
                    const text = innerOption.title || innerOption.text;
                    tags.push(`
                        <div class="${classTag}" data-select-value="${innerOption.value}">
                            ${text}
                            <svg onclick="selectPickerRemoveTag(this, '${classElement}', '${classToggler}')" class="${classTagRemove}" width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 2C6.47 2 2 6.47 2 12s4.47 10 10 10 10-4.47 10-10S17.53 2 12 2zm5 13.59L15.59 17 12 13.41 8.41 17 7 15.59 10.59 12 7 8.41 8.41 7 12 10.59 15.59 7 17 8.41 13.41 12 17 15.59z"/></svg>
                        </div>
                    `);
                }
            }
            return tags.join("");
        }
        else {
            const selectedOption = options[options.selectedIndex];
            return isPlaceholder(selectedOption) ? `<span class="${classPlaceholder}">${selectedOption.innerHTML}</span>` : (selectedOption.title || selectedOption.innerHTML);
        }
    }

    function selectedText(options) {
        const selectedOption = options[options.selectedIndex];
        return isPlaceholder(selectedOption) ? "" : selectedOption.textContent;
    }

    function itemTags(options) {
        let items = [];
        for (const [index, innerOption] of options.entries()) {
            if (innerOption.tagName === "OPTGROUP") {
                items.push(`<h6 class="dropdown-header">${innerOption.getAttribute("label")}</h6>`);
            } else {
                const parent = innerOption.parentElement;
                const groupItem = parent.tagName === "OPTGROUP" ? " dropdown-group-item" : "";
                const hidden = isPlaceholder(innerOption) ? "hidden" : "";
                const active = innerOption.selected ? " active" : "";
                const disabled = (element.multiple && innerOption.selected) || innerOption.disabled ? "disabled" : "";
                const value = innerOption.value;
                const dataTokens = innerOption.dataset.selectTokens ? `data-select-tokens="${innerOption.dataset.selectTokens}"` : "";
                let firstLastClass = "";
                if (!isPlaceholder(innerOption)) {
                    if (index == 0 || (index == 1 && parent.tagName !== "OPTGROUP")) firstLastClass = " first-item";
                    else if (index == options.length - 1) firstLastClass = " last-item";
                }

                let text = innerOption.textContent;
                const extraText = innerOption.getAttribute("data-extra-txt");
                if (extraText) {
                    text += ' <span class="extra-txt">' + extraText + '</span>';
                }

                const rightText = innerOption.getAttribute("data-right-txt");
                if (rightText) {
                    text += '<span class="float-end">' + rightText + '</span>';
                }

                const template = `<button ${hidden} class="dropdown-item${groupItem}${firstLastClass}${active}" data-select-value="${value}" type="button" 
                onclick="selectPickerUpdate(this, '${classElement}', '${classToggler}')" ${dataTokens} ${disabled}>${text}</button>`;
                items.push(template);
            }
        }
        items = items.join("");
        return items;
    }

    function createSelect() {
        const autoclose = element.multiple ? 'data-bs-auto-close="outside"' : '';
        const elementDisabled = element.disabled ? 'disabled' : '';
        const additionalClass = Array.from(element.classList).filter((className) => {
            return !["form-select", "form-select-sm", "form-select-lg"].includes(className);
        }).join(" ");

        const clearBtn = clearable && !element.multiple ? `
        <button type="button" class="btn ${classClearBtn}" title="${labels[language].clearSelection}" onclick="selectPickerClear(this, '${classElement}')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" fill="none">
                <path d="M13 1L0.999999 13" stroke-width="2" stroke="currentColor"></path>
                <path d="M1 1L13 13" stroke-width="2" stroke="currentColor"></path>
            </svg>
        </button>
        ` : "";

        const actionsBox = actions && element.multiple ? `
        <div class="col-12 btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selectAll(this, '${classElement}', '${classToggler}')">${labels[language].selectAllText}</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAll(this, '${classElement}', '${classToggler}')">${labels[language].deselectAllText}</button>
        </div>
        ` : "";

        const template = `
        <div class="dropdown ${classElement} ${additionalClass}">
            <button class="${classToggler} ${!element.multiple && clearable ? classTogglerClearable : ""}" 
                data-select-text="${!element.multiple && selectedText(element.options)}" type="button" data-bs-toggle="dropdown" aria-expanded="false" ${autoclose} ${elementDisabled}>
                ${selectedTag(element.options, element.multiple)}
            </button>
            <div class="dropdown-menu shadow ">
                <div class="d-flex flex-column">
                    ${searchInput}
                    ${actionsBox}
                    <div class="selectPicker-items" style="max-height:${maxHeight};">
                        ${itemTags(element.querySelectorAll("*"))}
                    </div>
                    <div class="${classNoResults} d-none">${labels[language].noResults}</div>
                </div>
            </div>
            ${clearBtn}
        </div>
        `;

        removePrevious();
        element.insertAdjacentHTML("afterend", template);
    }

    createSelect();

    function updateSelect() {
        const dropdown = element.nextElementSibling;
        const toggler = dropdown.getElementsByClassName(classToggler)[0];
        const selectPickerItems = dropdown.getElementsByClassName("selectPicker-items")[0];
        toggler.innerHTML = selectedTag(element.options, element.multiple);
        selectPickerItems.innerHTML = itemTags(element.querySelectorAll("*"));
        if (!element.multiple) {
            toggler.dataset.selectText = selectedText(element.options);
        }

        const searchInput = dropdown.querySelector("input");
        if (searchInput && searchInput.value.length > 0) {
            searchInput.dispatchEvent(new Event("keyup"));
        }
    }

    element.addEventListener("change", updateSelect);

    if (fixed) {
        element.nextElementSibling.getElementsByClassName(classToggler)[0].addEventListener('show.bs.dropdown', function () {
            this.nextElementSibling.style.width = this.offsetWidth + "px";
        });
    }

    element.nextElementSibling.getElementsByClassName(classToggler)[0].addEventListener('shown.bs.dropdown', function () {
        const searchInput = this.nextElementSibling.querySelector("input");
        if (searchInput) searchInput.focus();
    });
}

createSelectPickerAll = function (elementSelector, option = {}) {
    let elementList = document.querySelectorAll(elementSelector);
    for (const element of elementList) {
        createSelectPicker(element, option);
    }
}