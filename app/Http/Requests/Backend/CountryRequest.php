<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class CountryRequest extends FormRequest
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

        if ($this->isMethod('get') || $routeName === 'manage-countries.index' || empty($routeName)) {
            return [
                'CountryName' => ['required', 'string', 'max:75', new NoScripts()],
                'RegionID' => ['required', 'integer', 'exists:reportregions,regionid', new NoScripts()],
                'CountryCode' => ['nullable', 'string', 'max:10', new NoScripts()],
                'DeleteCountryID' => ['required', 'integer', 'exists:countries,countryid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-countries.destroy') {
            return [
                'DeleteCountryID' => ['required', 'integer', 'exists:countries,countryid', new NoScripts()],
            ];
        }

        return [
            'CountryName' => ['required', 'string', 'max:75', new NoScripts()],
            'RegionID' => ['required', 'integer', 'exists:reportregions,regionid', new NoScripts()],
            'CountryCode' => ['nullable', 'string', 'max:10', new NoScripts()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'CountryName.required' => 'Please enter the country name.',
            'RegionID.required' => 'Please select a region.',
            'DeleteCountryID.required' => 'The delete country ID is required.',
            'DeleteCountryID.exists' => 'The selected country to delete is invalid.',
        ];
    }
}
