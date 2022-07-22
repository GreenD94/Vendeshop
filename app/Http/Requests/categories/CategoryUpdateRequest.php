<?php

namespace App\Http\Requests\categories;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends FormRequest
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
            'id' =>  ['required', 'integer', 'exists:categories,id', 'numeric', 'gte:1'],
            'name' =>  [Rule::unique('categories')->ignore($this->id)],
            'is_main' =>  ['boolean'],
            'image_id' =>  ['exists:images,id', 'integer', 'numeric', 'gte:1'],
            'image' =>  ['image'],

        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
