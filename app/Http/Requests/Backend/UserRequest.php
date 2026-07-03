<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NoScripts;

class UserRequest extends FormRequest
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

        // If index/get requests, return empty rules or index fallback validation
        if ($this->isMethod('get') || $routeName === 'manage-users.index' || empty($routeName)) {
            return [
                'FirstName' => ['required', 'string', 'max:50', new NoScripts()],
                'LastName' => ['required', 'string', 'max:50', new NoScripts()],
                'UserName' => ['required', 'string', 'max:25', new NoScripts()],
                'UserLevel' => ['required', 'integer'],
                'UserPhone' => ['nullable', 'string', 'max:45', new NoScripts()],
                'UserPass' => ['required', 'string', 'max:25', new NoScripts()],
                'Region' => ['nullable', 'array'],
                'Events' => ['nullable', 'array'],
                'Country' => ['nullable', 'array'],
                'DeleteUserID' => ['required', 'integer', 'exists:users,userid'],
            ];
        }

        if ($routeName === 'manage-users.destroy') {
            return [
                'DeleteUserID' => ['required', 'integer', 'exists:users,userid'],
            ];
        }

        $userId = $this->route('manage_user');

        return [
            'FirstName' => ['required', 'string', 'max:50', new NoScripts()],
            'LastName' => ['required', 'string', 'max:50', new NoScripts()],
            'UserName' => [
                'required',
                'string',
                'max:25',
                $userId ? 'unique:users,username,' . $userId . ',userid' : 'unique:users,username',
                new NoScripts()
            ],
            'UserLevel' => ['required', 'integer'],
            'UserPhone' => ['nullable', 'string', 'max:45', new NoScripts()],
            'UserPass' => ['required', 'string', 'max:25', new NoScripts()],
            'Region' => ['nullable', 'array'],
            'Events' => ['nullable', 'array'],
            'Country' => ['nullable', 'array'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'FirstName.required' => 'Please enter the first name.',
            'LastName.required' => 'Please enter the last name.',
            'UserName.required' => 'Please enter the username.',
            'UserName.unique' => 'This username is already taken.',
            'UserLevel.required' => 'Please select permission level.',
            'UserPass.required' => 'Please enter the user password.',
        ];
    }
}
