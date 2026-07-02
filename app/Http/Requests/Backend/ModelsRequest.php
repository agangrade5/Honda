<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class ModelsRequest extends FormRequest
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

        if ($this->isMethod('get') || $routeName === 'manage-models.index' || empty($routeName)) {
            return [
                'ModelName' => ['required', 'string', 'max:50', new NoScripts()],
                'GroupID' => ['nullable', 'integer', new NoScripts()],
                'DeleteModelID' => ['required', 'integer', 'exists:models,modelid', new NoScripts()],
            ];
        }

        if ($routeName === 'manage-models.destroy') {
            return [
                'DeleteModelID' => ['required', 'integer', 'exists:models,modelid', new NoScripts()],
            ];
        }

        return [
            'ModelName' => ['required', 'string', 'max:50', new NoScripts()],
            'GroupID' => ['nullable', 'integer'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'ModelName.required' => 'Please enter the model name.',
            'DeleteModelID.required' => 'The model ID is required.',
            'DeleteModelID.exists' => 'The selected model to delete is invalid.',
        ];
    }
}
