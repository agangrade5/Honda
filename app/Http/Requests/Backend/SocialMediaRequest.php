<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class SocialMediaRequest extends FormRequest
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

        if ($this->isMethod('get') || $routeName === 'manage-social-media.index' || empty($routeName)) {
            return [
                'SocialName' => ['required', 'string', 'max:100', new NoScripts()],
                'Facebook' => ['nullable', 'string', 'max:255', new NoScripts()],
                'Twitter' => ['nullable', 'string', 'max:255', new NoScripts()],
                'Instagram' => ['nullable', 'string', 'max:255', new NoScripts()],
                'DeleteSocialMediaID' => ['required', 'integer', 'exists:socialmedia,socialid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-social-media.destroy') {
            return [
                'DeleteSocialMediaID' => ['required', 'integer', 'exists:socialmedia,socialid', new NoScripts()],
            ];
        }

        $rules = [
            'Facebook' => ['nullable', 'string', 'max:255', new NoScripts()],
            'Twitter' => ['nullable', 'string', 'max:255', new NoScripts()],
            'Instagram' => ['nullable', 'string', 'max:255', new NoScripts()],
        ];

        if ($this->isMethod('post') && $routeName === 'manage-social-media.store') {
            $rules['SocialName'] = ['required', 'string', 'max:100', new NoScripts()];
        } else {
            $rules['SocialName'] = ['nullable', new NoScripts()];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'SocialName.required' => 'Please enter the preset name.',
            'DeleteSocialMediaID.required' => 'The delete social media ID is required.',
            'DeleteSocialMediaID.exists' => 'The selected social media preset to delete is invalid.',
        ];
    }
}
