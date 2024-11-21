@isset($id)
    @php
        if (!isset($route_parameters) || !is_array($route_parameters) || empty($route_parameters)) {
            $route_parameters = [$route_parameter => $id];
        }

    @endphp

    <div class="data-{{ $id }}">

        @if (isset($can_edit) && $can_edit)
            @if (isset($edit_type) && $edit_type == 'inline')
                <button type="button" class="btn btn-link py-0 px-1 mx-1 edit-btn"
                    title="{{ $edit_label ?? __('forms.edit') }}" data-id="{{ $id }}"
                    onclick="clickInlineEditBtn({{ $id }}, '{{ $focus_input }}', '{{ $datatable_id }}')">
                    <i class="bi bi-pencil-square fs-6"></i>
                </button>
            @elseif(isset($edit_type) && $edit_type == 'form')
                <!--  <a class="btn btn-link py-0 px-1 mx-1" href="{{ route($route . '.show', $route_parameters) }}" title="{{ $view_label ?? __('forms.view') }}">
                                                <i class="bi bi-eye fs-6"></i>
                                                </a> -->

                <a class="btn btn-link py-0 px-1 mx-1" href="{{ route($route . '.edit', $route_parameters) }}"
                    title="{{ $edit_label ?? __('forms.edit') }}">
                    <i class="bi bi-pencil-square fs-6"></i>
                </a>
            @endif
        @endif


        @if (isset($can_delete) && $can_delete)
            <button type="button" class="btn btn-link py-0 px-1 mx-1 text-danger delete-btn"
                title="{{ $delete_label ?? __('forms.delete') }}" data-id="{{ $id }}"
                onclick="delete_{{ $js_function_identifier ?? '' }}({{ $id }}, '{{ route($route . '.destroy', $route_parameters) }}' {{ isset($delete_reload_url) ? ', \'' . $delete_reload_url . '\'' : '' }})">
                <i class="bi bi-trash-fill fs-6"></i>
            </button>
        @endif

    </div>


    @if (isset($can_edit) && $can_edit && isset($edit_type) && $edit_type == 'inline')
        <div class="input-{{ $id }}" style="display:none">
            <button type="button" data-id="{{ $id }}" class="btn btn-link py-0 px-1 mx-1 update-btn"
                title="{{ $save_label ?? __('forms.save') }}"
                onclick="update_{{ $js_function_identifier ?? '' }}({{ $id }}, '{{ route($route . '.update', $route_parameters) }}')">
                <i class="bi bi-save fs-6"></i>
            </button>
            <button type="button" data-id="{{ $id }}"
                class="btn btn-link text-danger py-0 px-1 mx-1 cancel-update-btn"
                title="{{ $cancel_label ?? __('forms.cancel') }}"
                onclick="clickInlineCancelBtn({{ $id }}, '{{ $datatable_id }}')">
                <i class="bi bi-x-square fs-6"></i>
            </button>
        </div>
    @endif

@endisset
