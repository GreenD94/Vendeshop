<?php

namespace App\Http\Requests\users;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserStoreRequest extends FormRequest
{
    use Responser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $isUserLoggedIn = Auth()->check();

        if (!$isUserLoggedIn) {
            return true;
        }

        $role = Role::find($this->role_id);

        if (!$role) {
            $this->errorResponse(['role' => "role does not exists"], "Validation Error", 444);
        }

        $authUser = User::find(Auth()->id());
        $rolesNames = collect($authUser->getRoleNames());
        if ($rolesNames->count() == 0) {
            return $this->errorResponse(null, "Unauthenticated", 401);
        }

        $authUserRole = $rolesNames[0];

        $canCreateAdministrador = $role->name == 'admin' && ($authUserRole == "master" || $authUserRole == "admin");


        $canCreateRol =  $canCreateAdministrador;
        if (!$canCreateRol || $role->name == "master") {
            $this->errorResponse(['role' => "no tienes permisos para crear Role"], "Validation Error", 422);
        }

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
            'first_name' =>  ['required'],
            'last_name' =>  ['required'],
            'email' =>  ['required', Rule::unique('users'), 'email'],
            'password' =>  ['required', 'min:6'],
            'phone' =>  ['required', 'numeric'],
            'birth_date' =>  ['required'],
            'avatar' => ['image'],
            'role_id' => ['exists:roles,id', 'numeric', 'gte:1'],
        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
