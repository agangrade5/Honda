<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class InventoryRequest extends FormRequest
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
            'VehicleNickName' => ['required', 'string', 'max:100', new NoScripts()],
            'VehicleModel' => ['required', 'integer', new NoScripts()],
            'VehicleColor' => ['nullable', 'string', 'max:45', new NoScripts()],
            'VehicleTruckID' => ['nullable', 'integer', new NoScripts()],
            'VehicleLicPlate' => ['nullable', 'string', 'max:45', new NoScripts()],
            'VehicleVIN' => ['nullable', 'string', 'max:100', new NoScripts()],
            'VehicleCOV' => ['nullable', 'string', 'max:100', new NoScripts()],
            'ModelID' => ['nullable', 'integer', new NoScripts()],
            'VehicleType' => ['required', 'string', 'in:display,demo', new NoScripts()],
            'EventArchive' => ['required', 'integer', 'in:0,1', new NoScripts()],
        ];

        $routeName = $this->route() ? $this->route()->getName() : '';

        if ($this->isMethod('get') || $routeName === 'manage-inventory.index' || empty($routeName)) {
            $rules['DeleteInventoryID'] = ['required', 'integer', 'exists:vehicles,vehicleid', new NoScripts()];
        }

        if ($routeName === 'manage-inventory.destroy') {
            return [
                'DeleteInventoryID' => ['required', 'integer', 'exists:vehicles,vehicleid', new NoScripts()],
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
            'VehicleNickName.required' => 'Please enter the vehicle nickname.',
            'VehicleModel.required' => 'Please select a group.',
            'VehicleType.required' => 'Please select the vehicle type.',
            'EventArchive.required' => 'Please specify if the vehicle is archived.',
            'DeleteInventoryID.required' => 'The delete vehicle ID is required.',
            'DeleteInventoryID.exists' => 'The selected vehicle to delete is invalid.',
        ];
    }
}
