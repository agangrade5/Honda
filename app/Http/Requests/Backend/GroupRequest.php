<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class GroupRequest extends FormRequest
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

        if ($this->isMethod('get') || $routeName === 'manage-groups.index' || empty($routeName)) {
            return [
                'GroupName' => ['required', 'string', 'max:50', new NoScripts()],
                'GroupID' => ['required', 'integer', 'exists:vehiclegroups,groupid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-groups.destroy') {
            return [
                'GroupID' => ['required', 'integer', 'exists:vehiclegroups,groupid', new NoScripts()],
            ];
        }

        return [
            'GroupName' => ['required', 'string', 'max:50', new NoScripts()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'GroupName.required' => 'Please enter the group name.',
            'GroupID.required' => 'The group ID is required.',
            'GroupID.exists' => 'The selected group to delete is invalid.',
        ];
    }
}
