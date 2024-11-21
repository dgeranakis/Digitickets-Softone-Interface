<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:255'],
            'email' =>  ['bail', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['bail', 'required', 'string', Password::default()->min(10)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'lines_per_page' => ['bail', 'nullable', 'integer'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('user.name.title'),
            'email' => __('user.email.title'),
            'password' => __('auth.password'),
            'lines_per_page' => __('user.lines_per_page.title'),
        ];
    }
}
