<?php

namespace App\Http\Requests\categories;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CategoryStoreRequest extends FormRequest
{
    use Responser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authUser = User::find(Auth()->id());
        return  $authUser->hasRole(['admin', 'master']);
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
            'image' =>  ['required', 'image'],
            'name' =>  ['required', 'unique:categories,name'],
            'is_main' =>  ['required', 'boolean'],
            'color' =>  ['required'],
            //  'image_id' =>  ['required', 'integer', 'exists:images,id', 'numeric', 'gte:1'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
