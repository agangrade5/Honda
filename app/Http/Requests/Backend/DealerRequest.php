<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class DealerRequest extends FormRequest
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

        if ($this->isMethod('get') || $routeName === 'manage-dealers.index' || empty($routeName)) {
            return [
                'DealerNumber' => ['required', 'string', 'max:30', new NoScripts()],
                'DealerName' => ['required', 'string', 'max:50', new NoScripts()],
                'DealerLocation' => ['nullable', 'string', 'max:50', new NoScripts()],
                'DealerRegion' => ['nullable', 'string', 'max:50', new NoScripts()],
                'DealerDistrict' => ['nullable', 'string', 'max:50', new NoScripts()],
                'DeleteDealerID' => ['required', 'integer', 'exists:dealers,dealerid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-dealers.destroy') {
            return [
                'DeleteDealerID' => ['required', 'integer', 'exists:dealers,dealerid', new NoScripts()],
            ];
        }

        return [
            'DealerNumber' => ['required', 'string', 'max:30', new NoScripts()],
            'DealerName' => ['required', 'string', 'max:50', new NoScripts()],
            'DealerLocation' => ['nullable', 'string', 'max:50', new NoScripts()],
            'DealerRegion' => ['nullable', 'string', 'max:50', new NoScripts()],
            'DealerDistrict' => ['nullable', 'string', 'max:50', new NoScripts()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'DealerNumber.required' => 'Please enter the dealer number.',
            'DealerName.required' => 'Please enter the dealer name.',
            'DeleteDealerID.required' => 'The delete dealer ID is required.',
            'DeleteDealerID.exists' => 'The selected dealer to delete is invalid.',
        ];
    }
}
