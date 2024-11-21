@isset($id)
    <div class="data-{{ $id }}">
        @if (isset($input_type) && in_array($input_type, ['text', 'textarea', 'number', 'date']))
            {{ $value ?? '' }}
        @elseif (isset($input_type) && in_array($input_type, ['svg_icon']))
            {!! $value ?? '' !!}
        @elseif (isset($input_type) && in_array($input_type, ['float']))
            @if ($value)
                {{ number_format($value, 2, ',', '.') }}
            @endif
        @elseif(isset($input_type) && in_array($input_type, ['checkbox']))
            @if ($value)
                <i class="bi bi-check-circle text-success"></i>
            @endif
        @elseif(isset($input_type) && in_array($input_type, ['multiselect']) && is_array($value))
            @foreach ($value as $specific_value)
                <span class="badge bg-primary"><small>{{ $specific_value }}</small></span>
            @endforeach
        @elseif(isset($input_type) && in_array($input_type, ['select']) && is_array($value))
            {{ $value['value'] ?? '' }}
        @elseif(isset($input_type) && in_array($input_type, ['file']) && isset($src) && filled($src))
            <a href="{{ $src }}" target="_blank">{{ filled($value) ? $value : 'file' }}</a>
        @elseif(isset($input_type) && in_array($input_type, ['image']) && isset($src) && filled($src))
            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal">
                <img src="{{ $src }}" alt="{{ filled($value) ? $value : 'image' }}" class="img-fluid img-thumbnail"
                    style="min-width:100px;max-width:100px">
            </a>
        @endif
    </div>

    <div class="input-{{ $id }}" style="display:none">

        @if (isset($input_type) && in_array($input_type, ['text']))
            <input type="text" class="form-control {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                data-id="{{ $id }}" placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}">
        @elseif(isset($input_type) && in_array($input_type, ['textarea', 'svg_icon']))
            <textarea class="form-control {{ $class ?? '' }}" id="{{ $html_id ?? $id }}" data-id="{{ $id }}"
                placeholder="{{ $placeholder ?? '' }}" rows="2">{{ $value ?? '' }}</textarea>
        @elseif(isset($input_type) && in_array($input_type, ['number']))
            <input type="number" class="form-control {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                data-id="{{ $id }}" placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}"
                min="{{ $min ?? 0 }}">
        @elseif(isset($input_type) && in_array($input_type, ['checkbox']))
            <input type="checkbox" class="form-check-input {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                data-id="{{ $id }}" {{ $value ? 'checked' : '' }}>
        @elseif(isset($input_type) && in_array($input_type, ['date']))
            <div class="form-group has-icon-left">
                <div class="position-relative">
                    <input type="text" class="form-control datepicker {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                        data-id="{{ $id }}" placeholder="{{ $placeholder ?? '' }}" value="{{ $value ?? '' }}">
                    <div class="form-control-icon pb-1"><i class="bi bi-calendar-week"></i></div>
                </div>
            </div>
        @elseif(isset($input_type) &&
                in_array($input_type, ['select']) &&
                is_array($value) &&
                isset($options) &&
                is_array($options))
            <select class="form-select selectPicker {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                data-id="{{ $id }}" data-select-search="true" data-select-clearable="true">
                <option value="">{{ $placeholder ?? '' }}</option>
                @foreach ($options as $option)
                    @if (isset($option['active']) && !$option['active'])
                        @continue
                    @endif
                    <option value="{{ $option['id'] ?? $option['value'] }}"
                        @if ((isset($option['id']) && $option['id'] == $value['id']) || $option['value'] == $value['id']) selected @endif>{{ $option['value'] }}</option>
                @endforeach
            </select>
        @elseif(isset($input_type) &&
                in_array($input_type, ['multiselect']) &&
                is_array($value) &&
                isset($options) &&
                is_array($options))
            <select class="form-select selectPicker {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                data-id="{{ $id }}" multiple data-select-search="true" data-select-clearable="true"
                data-select-actions="true">
                <option value="">{{ $placeholder ?? '' }}</option>
                @foreach ($options as $option)
                    @if (isset($option['active']) && !$option['active'])
                        @continue
                    @endif
                    <option value="{{ $option['id'] ?? $option['value'] }}"
                        @if ((isset($option['id']) && in_array($option['id'], $value)) || in_array($option['value'], $value)) selected @endif>{{ $option['value'] }}</option>
                @endforeach
            </select>
        @elseif(isset($input_type) && in_array($input_type, ['file', 'image']))
            <div class="input-group">
                <input type="file" class="form-control {{ $class ?? '' }}" id="{{ $html_id ?? $id }}"
                    onchange="document.getElementById('clear_btn_{{ $html_id ?? $id }}').classList.remove('d-none');document.getElementById('clear_btn_{{ $html_id ?? $id }}').classList.add('d-block');"
                    placeholder="{{ $placeholder ?? '' }}" data-browse="{{ $browse ?? 'Browse' }}">
                <button class="btn btn-outline-secondary" type="button" id="clear_btn_{{ $html_id ?? $id }}"
                    onclick="document.getElementById('{{ $html_id ?? $id }}').value = null;document.getElementById('clear_btn_{{ $html_id ?? $id }}').classList.remove('d-block');document.getElementById('clear_btn_{{ $html_id ?? $id }}').classList.add('d-none');"
                    style="display:none">{{ $clear ?? 'Clear' }}</button>
            </div>
        @endif

    </div>
@endisset
