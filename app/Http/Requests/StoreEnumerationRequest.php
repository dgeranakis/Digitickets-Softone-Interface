<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Enumeration;

class StoreEnumerationRequest extends FormRequest
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
            'domain' => ['bail', 'required', 'exists:domains,id'],
            'code' => ['bail', 'required', 'max:50',  
                Rule::unique('enumerations')->where(function ($query) {
                    return $query->where('code', $this->input('code'))->where('domain_id', $this->input('domain'));
                })],
            'description' => ['bail', 'required',
                function ($attribute, $value, $fail) {
                        
                    $exists = Enumeration::leftJoin('enumeration_translations', 'enumerations.id', '=', 'enumeration_translations.enumeration_id')
                        ->where('enumeration_translations.locale', '=', appLocale())
                        ->where('enumerations.domain_id', '=', $this->input('domain'))
                        ->where('enumeration_translations.description', '=', $value)
                        ->exists();
                    if ($exists)  $fail(__('enumeration.errors.exists'));
                }
            ]
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.unique' => __('enumeration.errors.unique'),
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
            'domain' => __('enumeration.domain.title'),
            'code' => __('enumeration.code.title'),
            'description' => __('enumeration.description.title'),
        ];
    }
}
