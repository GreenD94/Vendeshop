<?php

namespace App\Http\Requests\post;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PostUpdateRequest extends FormRequest
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

    public function rules()
    {
        return [


            'stock_id' =>  ['exists:stocks,id', 'numeric', 'gte:1', 'integer'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1', 'integer'],
            'id' =>  ['required', 'exists:posts,id', 'numeric', 'gte:1', 'integer'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
