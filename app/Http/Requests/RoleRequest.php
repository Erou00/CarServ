<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'name' => 'required|unique:roles',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {

            $role = $this->route()->parameter('role');

            $rules['name'] = 'required|unique:roles,id,' . $role->id;

        }//end of if

        return $rules;
    }
}
