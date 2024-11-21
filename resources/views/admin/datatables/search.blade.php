<section class="section" id="search">
    <div class="card bg-white mb-2">
        <div class="card-body py-2">
            @php
                $adv_search = '';
                $adv_search_array = [];
                foreach ($columns as $column) {
                    if (isset($column['url_search']) && filled($column['url_search'])) {
                        $operator =
                            $column['operators'] == 'translation'
                                ? 't_is'
                                : ($column['operators'] == 'datetime'
                                    ? 'is_time'
                                    : 'is');
                        $adv_search_array[] =
                            '{"search":"' .
                            $column['value'] .
                            '","operator":"' .
                            $operator .
                            '","value":"' .
                            $column['url_search'] .
                            '"}';
                    }
                }

                if (!empty($adv_search_array)) {
                    $adv_search = '[' . implode(',', $adv_search_array) . ']';
                }
            @endphp

            <div class="accordion" id="accordionSearch">
                <div class="accordion-item border border-white">
                    <h2 class="accordion-header" id="headingSearch">
                        <button class="accordion-button collapsed border border-white py-1" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseSearch"
                            aria-expanded="{{ filled($adv_search) ? 'true' : 'false' }}" aria-controls="collapseSearch">
                            <i class="bi bi-search"></i>&nbsp;&nbsp;{{ __('search.search') }}
                        </button>
                    </h2>
                    <div id="collapseSearch" class="accordion-collapse collapse {{ filled($adv_search) ? 'show' : '' }}"
                        aria-labelledby="headingSearch" data-bs-parent="#accordionSearch">
                        <div class="accordion-body pb-1">
                            <div id="searchForm">
                                <input type="text" id="adv-search" style="display:none" value="{{ $adv_search }}">
                                <div class="form-group">
                                    <select class="form-select selectPicker" id="add-filter-select" multiple
                                        data-select-search="true" onchange="addFilterSearch()">
                                        <option value="">{{ __('search.add_filter') }}...</option>
                                        @foreach ($columns as $column)
                                            <option value="{{ $column['value'] }}"
                                                @if (isset($column['url_search']) && filled($column['url_search'])) selected disabled @endif>
                                                {{ $column['title'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @foreach ($columns as $column)
                                    <div class="input-group rangedatepicker search-div mb-1"
                                        id="{{ $column['value'] }}-search" data-search="{{ $column['value'] }}"
                                        @if (!isset($column['url_search']) || !filled($column['url_search'])) style="display: none" @endif>
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0" type="checkbox"
                                                id="{{ $column['value'] }}-sch-chk" aria-label="{{ $column['title'] }}"
                                                data-search="{{ $column['value'] }}"
                                                onchange="searchInputVisibility('{{ $column['value'] }}')" checked>
                                        </div>
                                        <span class="input-group-text">{{ $column['title'] }}</span>
                                        <select class="form-select sch-operator"
                                            id="{{ $column['value'] }}-sch-operator"
                                            data-search="{{ $column['value'] }}"
                                            onchange="searchInputVisibilityByOperator('{{ $column['value'] }}')">
                                            @include('admin.datatables.searchOperators.' . $column['operators'])
                                        </select>

                                        @if ($column['operators'] == 'string' || $column['operators'] == 'translation' || $column['operators'] == 'number')
                                            <input type="text" class="form-control sch-input"
                                                id="{{ $column['value'] }}-sch-input"
                                                data-search="{{ $column['value'] }}"
                                                value="{{ isset($column['url_search']) && filled($column['url_search']) ? $column['url_search'] : '' }}">
                                        @elseif($column['operators'] == 'list')
                                            <select class="form-select col-6 sch-input selectPicker"
                                                id="{{ $column['value'] }}-sch-input-select"
                                                title="{{ __('search.please_choose') }}"
                                                data-search="{{ $column['value'] }}" data-select-search="true"
                                                data-select-clearable="true"
                                                @if (isset($column['multiselect']) && $column['multiselect']) multiple data-select-actions="true" @endif>
                                                <option value="">{{ __('forms.please_choose') }}...</option>
                                                @foreach ($column['options'] as $obj)
                                                    <option
                                                        value="{{ isset($obj['id']) ? $obj['id'] : $obj['value'] }}"
                                                        @if (isset($column['url_search']) &&
                                                                filled($column['url_search']) &&
                                                                isset($obj['id']) &&
                                                                $column['url_search'] == $obj['id']
                                                        ) selected @endif>
                                                        {{ $obj['value'] }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($column['operators'] == 'date' || $column['operators'] == 'datetime')
                                            <input type="text" class="form-control datepicker sch-input"
                                                id="{{ $column['value'] }}-sch-input"
                                                data-search="{{ $column['value'] }}"
                                                value="{{ isset($column['url_search']) && filled($column['url_search']) ? changeDateFormat($column['url_search']) : '' }}">
                                            <input type="text" class="form-control multidatepicker sch-input"
                                                id="{{ $column['value'] }}-sch-range1"
                                                data-search="{{ $column['value'] }}" style="display:none">
                                            <input type="text" class="form-control multidatepicker sch-input"
                                                id="{{ $column['value'] }}-sch-range2"
                                                data-search="{{ $column['value'] }}" style="display:none">
                                        @endif

                                    </div>
                                @endforeach

                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <button type="button" class="btn btn-light-secondary me-1 mb-1" id="searchReset"
                                        onclick="resetSearch('{{ $datatable_id }}')">{{ __('forms.reset') }}</button>
                                    <button type="button" class="btn btn-primary me-1 mb-1"
                                        onclick="submitSearch('{{ $datatable_id }}')">{{ __('forms.submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
