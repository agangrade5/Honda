<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class EmailTemplateRequest extends FormRequest
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
        if ($this->isMethod('get') || $routeName === 'manage-email-templates.index' || empty($routeName)) {
            return [
                'EmailTemplateSubj' => ['required', 'string', 'max:100', new NoScripts()],
                'EmailSubject' => ['required', 'string', 'max:255'],
                'TemplateBlob' => ['required', 'string'],
                'EmailTemplateSub' => ['required', 'string', 'max:255', new NoScripts()],
                'TemplateBlob1' => ['required', 'string'],
                'DeleteEmailTemplateID' => ['required', 'integer', 'exists:emailtemplates,templateid'],
            ];
        }

        if ($routeName === 'manage-email-templates.destroy') {
            return [
                'DeleteEmailTemplateID' => ['required', 'integer', 'exists:emailtemplates,templateid'],
            ];
        }

        if ($routeName === 'manage-email-templates.send-test') {
            return [
                'EmailSubject' => ['required', 'email'],
                'EmailTemplateSubject' => ['required', 'string'],
                'template' => ['required', 'string'],
            ];
        }

        if ($routeName === 'manage-email-templates.update') {
            return [
                'EmailTemplateSub' => ['required', 'string', 'max:255', new NoScripts()],
                'TemplateBlob1' => ['required', 'string'],
            ];
        }

        return [
            'EmailTemplateSubj' => ['required', 'string', 'max:100', new NoScripts()],
            'EmailSubject' => ['required', 'string', 'max:255', new NoScripts()],
            'TemplateBlob' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'EmailTemplateSubj.required' => 'Please enter the email template name.',
            'EmailSubject.required' => 'Please enter the subject or test email address.',
            'EmailSubject.email' => 'Please enter a valid test email address.',
            'TemplateBlob.required' => 'Please enter the email template contents.',
            'EmailTemplateSub.required' => 'Please enter the template subject.',
            'TemplateBlob1.required' => 'Please enter the template contents.',
            'DeleteEmailTemplateID.required' => 'Please select a template to delete.',
            'DeleteEmailTemplateID.exists' => 'The selected template does not exist.',
        ];
    }
}
