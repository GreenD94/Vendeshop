<?php

namespace App\Http\Requests\post;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class PostStoreRequest extends FormRequest
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
        $rules = [
            'body' =>  ['required'],

            'stock_id' =>  ['required', 'exists:stocks,id', 'numeric', 'gte:1', 'integer'],
            'user_id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1', 'integer'],

        ];


        if ($this->post_id) $rules = [
            'body' =>  ['required'],
            'post_id' =>  ['exists:posts,id', 'numeric', 'gte:1', 'integer'],
            'user_id' =>  ['required', 'exists:users,id', 'numeric', 'gte:1', 'integer'],
        ];
        return $rules;
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
