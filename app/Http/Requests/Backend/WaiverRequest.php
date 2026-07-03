<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class WaiverRequest extends FormRequest
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

        // Index/Get requests validation fallback
        if ($this->isMethod('get') || $routeName === 'manage-waivers.index' || empty($routeName)) {
            return [
                'WaiverName' => ['required', 'string', 'max:45', new NoScripts()],
                'WaiverHTML' => ['required', 'string'],
                'WaiverID' => ['required', 'string'],
                'WaiverHTML1' => ['required', 'string'],
                'DeleteWaiverID' => ['required', 'integer', 'exists:legal,legalid'],
            ];
        }

        if ($routeName === 'manage-waivers.destroy') {
            return [
                'DeleteWaiverID' => ['required', 'integer', 'exists:legal,legalid'],
            ];
        }

        if ($routeName === 'manage-waivers.update') {
            return [
                'WaiverID' => ['required', 'string'],
                'WaiverHTML1' => ['required', 'string'],
            ];
        }

        return [
            'WaiverName' => ['required', 'string', 'max:45', new NoScripts()],
            'WaiverHTML' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'WaiverName.required' => 'Please enter the waiver name.',
            'WaiverHTML.required' => 'Please enter the waiver content.',
            'WaiverHTML1.required' => 'Please enter the waiver content.',
            'WaiverID.required' => 'Please select a waiver to edit.',
            'DeleteWaiverID.required' => 'Please select a waiver to delete.',
            'DeleteWaiverID.exists' => 'The selected waiver does not exist.',
        ];
    }
}
