<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class TruckRequest extends FormRequest
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
        $rules = [
            'TruckName' => ['required', 'string', 'max:50', new NoScripts()],
            'TruckInventory' => ['nullable', 'array'],
            'TruckInventory.*' => ['exists:vehicles,vehicleid'],
            'SetID' => ['nullable', 'integer', 'exists:btsets,btset_id', new NoScripts()],
        ];

        $routeName = $this->route() ? $this->route()->getName() : '';

        if ($this->isMethod('get') || $routeName === 'manage-trucks.index' || empty($routeName)) {
            $rules['DeleteTruckID'] = ['required', 'integer', 'exists:trucks,truckid', new NoScripts()];
        }

        if ($routeName === 'manage-trucks.destroy') {
            return [
                'DeleteTruckID' => ['required', 'integer', 'exists:trucks,truckid', new NoScripts()],
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'TruckName.required' => 'Please enter the truck name.',
            'DeleteTruckID.required' => 'The delete truck ID is required.',
            'DeleteTruckID.exists' => 'The selected truck to delete is invalid.',
        ];
    }
}
