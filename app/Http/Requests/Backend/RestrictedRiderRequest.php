<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class RestrictedRiderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $routeName = $this->route() ? $this->route()->getName() : '';

        if ($this->isMethod('get') || $routeName === 'manage-restricted-riders.index' || empty($routeName)) {
            return [
                'RestrictLic' => ['required', 'string', 'max:100', new NoScripts()],
                'RestrictComment' => ['nullable', 'string', 'max:50', new NoScripts()],
                'RestrictID' => ['required', 'integer', 'exists:restrictedriders,restrictid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-restricted-riders.destroy') {
            return [
                'RestrictID' => ['required', 'integer', 'exists:restrictedriders,restrictid', new NoScripts()],
            ];
        }

        return [
            'RestrictLic' => ['required', 'string', 'max:100', new NoScripts()],
            'RestrictComment' => ['nullable', 'string', 'max:50', new NoScripts()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'RestrictLic.required' => 'Please enter the Card/DL number.',
            'RestrictID.required' => 'The restricted rider ID is required.',
            'RestrictID.exists' => 'The selected restricted rider to delete is invalid.',
        ];
    }
}
