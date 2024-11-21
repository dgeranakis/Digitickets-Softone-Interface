<script>
    const user_selectors = {
        name: '#name',
        email: '#email',
        password: '#password',
        password_confirmation: '#password_confirmation',
        lines_per_page: '#lines_per_page'
    };
    const user_multiselectors = {
        roles: '#roles'
    };
    const user_checkbox_selectors = {
        active: '#active',
        is_admin: '#is_admin'
    };

    function create_user() {
        clearErrors('#create_user');
        let data = getFormData(user_selectors, user_checkbox_selectors, user_multiselectors);

        let successModalOptions = {
            okBtnAction: function() {
                location.replace("{{ route('admin.users.index') }}")
            }
        };
        let errorModalOptions = {
            title: "{{ __('admin.error') }}",
            okBtnAction: function() {}
        };
        sendRequest('POST', "{{ route('admin.users.store') }}", "{{ csrf_token() }}", data, null, false,
            successModalOptions, errorModalOptions);
    }

    function update_user(id, url) {
        clearErrors('#edit_user');
        let data = getFormData(user_selectors, user_checkbox_selectors, user_multiselectors);

        let successModalOptions = {
            okBtnAction: function() {
                location.replace(url + '/edit')
            }
        };
        let errorModalOptions = {
            title: "{{ __('admin.error') }}",
            okBtnAction: function() {}
        };
        sendRequest('PUT', url, "{{ csrf_token() }}", data, null, false, successModalOptions, errorModalOptions);
    }

    function delete_user(id, url) {
        openAlertModal({
            title: '<i class="bi bi-trash-fill fs-5"></i> ' + "{{ __('user.delete.title') }}",
            content: "{{ __('user.delete.confirm') }}",
            showCloseBtn: true,
            okBtnText: "{{ __('forms.yes') }}",
            closeBtnText: "{{ __('forms.cancel') }}",
            okBtnAction: function() {
                let successModalOptions = {
                    okBtnAction: function() {
                        location.replace("{{ route('admin.users.index') }}")
                    }
                };
                let errorModalOptions = {
                    title: "{{ __('admin.error') }}",
                    okBtnAction: function() {}
                };
                sendRequest('DELETE', url, "{{ csrf_token() }}", {}, null, false, successModalOptions,
                    errorModalOptions);
            }
        });
    }
</script>
