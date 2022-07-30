<?php

namespace App\Http\Requests\users;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserIndexRequest extends FormRequest
{
    use Responser;
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
     * @return array
     */
    public function rules()
    {
        return [
            'id' =>  ['exists:users,id', 'numeric', 'gte:1'],
            'page' => ['numeric', 'gte:1', 'integer'],
            'limit' => ['integer', 'numeric', 'gte:1'],
            'is_solicitud' => ['boolean'],
            'role' => [Rule::in(['customer', 'admin', 'master'])],

        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
