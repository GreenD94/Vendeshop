<?php

namespace App\Http\Requests\stocks;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Spatie\Permission\Models\Role;

class StockStoreRequest extends FormRequest
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
            'name' =>  ['required'],
            'description' =>  ['required'],
            'price' =>  ['required', 'numeric'],
            'mock_price' =>  ['required', 'numeric'],
            'credits' =>  ['required', 'numeric'],
            //'discount' =>  ['required', 'integer', 'between:1,100'],
            'cover_image' =>  ['required', 'image'],
            'images' =>  ['required'],
            'images.*' =>  ['image'],
            'color_id' =>  ['exists:colors,id', 'numeric', 'gte:1'],
            'colors' => ['array'],
            'videos' => ['array'],

            'sizes' => ['array'],
            'size_id' =>  ['exists:sizes,id', 'numeric', 'gte:1'],
            'ribbon_id' =>  ['exists:ribbons,id', 'numeric', 'gte:1'],
            'categories' =>  ['array'],
            'categories.*' =>  ['exists:categories,id', 'numeric', 'gte:1'],
            'animation_id' =>  ['exists:image360is,id', 'numeric', 'gte:1', 'nullable'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {

        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
