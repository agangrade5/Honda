<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class SmsTemplateRequest extends FormRequest
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
        if ($this->isMethod('get') || $routeName === 'manage-sms-templates.index' || empty($routeName)) {
            return [
                'EmailTemplateSubj' => ['required', 'string', 'max:100', new NoScripts()],
                'EmailSubject' => ['required', 'string', 'max:255', new NoScripts()],
                'TemplateBlob' => ['required', 'string', 'max:160', new NoScripts()],
                'EmailTemplateSub' => ['required', 'string', 'max:255', new NoScripts()],
                'TemplateBlob1' => ['required', 'string', 'max:160', new NoScripts()],
                'DeleteEmailTemplateID' => ['required', 'integer', 'exists:smstemplates,templateid'],
            ];
        }

        if ($routeName === 'manage-sms-templates.destroy') {
            return [
                'DeleteEmailTemplateID' => ['required', 'integer', 'exists:smstemplates,templateid'],
            ];
        }

        if ($routeName === 'manage-sms-templates.update') {
            return [
                'EmailTemplateSub' => ['required', 'string', 'max:255', new NoScripts()],
                'TemplateBlob1' => ['required', 'string', 'max:160', new NoScripts()],
            ];
        }

        return [
            'EmailTemplateSubj' => ['required', 'string', 'max:100', new NoScripts()],
            'EmailSubject' => ['required', 'string', 'max:255', new NoScripts()],
            'TemplateBlob' => ['required', 'string', 'max:160', new NoScripts()],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'EmailTemplateSubj.required' => 'Please enter the SMS template name.',
            'EmailSubject.required' => 'Please enter the SMS subject.',
            'TemplateBlob.required' => 'Please enter the SMS template contents.',
            'TemplateBlob.max' => 'The SMS template contents may not be greater than 160 characters.',
            'EmailTemplateSub.required' => 'Please enter the template subject.',
            'TemplateBlob1.required' => 'Please enter the template contents.',
            'TemplateBlob1.max' => 'The SMS template contents may not be greater than 160 characters.',
            'DeleteEmailTemplateID.required' => 'Please select a template to delete.',
            'DeleteEmailTemplateID.exists' => 'The selected template does not exist.',
        ];
    }
}
