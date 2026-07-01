<?php

namespace App\Http\Requests\Backend;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class RegionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeName = $this->route() ? $this->route()->getName() : '';

        if ($routeName === 'manage-regions.store') {
            return [
                'region_name' => ['required', 'string', 'max:100', 'unique:reportregions,regionname', 'regex:/^[a-zA-Z\s\-]+$/', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-regions.update') {
            return [
                'region_id' => ['required', 'exists:reportregions,regionid', new NoScripts()],
                'region_name' => ['required', 'string', 'max:100', 'unique:reportregions,regionname,' . $this->input('region_id') . ',regionid', 'regex:/^[a-zA-Z\s\-]+$/', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-regions.destroy') {
            return [
                'delete_region_id' => ['required', 'exists:reportregions,regionid', new NoScripts()],
            ];
        }

        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'region_name.required' => 'The region name is required.',
            'region_name.string' => 'The region name must be a string.',
            'region_name.max' => 'The region name must not be greater than 100 characters.',
            'region_name.unique' => 'This region name already exists.',
            'region_name.regex' => 'The region name must contain only alphabets, spaces, and hyphens.',
            'region_id.required' => 'The region ID is required.',
            'region_id.exists' => 'The selected region ID is invalid.',
            'delete_region_id.required' => 'The delete region ID is required.',
            'delete_region_id.exists' => 'The selected region to delete is invalid.',
        ];
    }
}
