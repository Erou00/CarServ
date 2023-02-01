<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'utilisateur' => 'required',
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'type' => 'required',
            'magasin_id' => 'required',
            'role_id' => 'required',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $admin = $this->route()->parameter('utilisateur');

            $rules['email'] = ['required',
                                Rule::unique('users')->ignore($admin),];
            $rules['password'] = '';

        }//end of if

        return $rules;
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'type' => 'admin'
        ]);

    }//end of prepare for validation
}
