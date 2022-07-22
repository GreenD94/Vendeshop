<?php

namespace App\Http\Requests\stocks;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class StockUpdateRequest extends FormRequest
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

        $ribbonId = $this->ribbon_id == 0 ? [] : ['exists:ribbons,id', 'numeric', 'gte:1'];
        if ($this->id == '*') return [

            'price' =>  ['numeric'],
            'mock_price' =>  ['numeric'],
            'credits' =>  ['numeric'],
            'discount' =>  [],
            'cover_image_id' =>  ['exists:images,id', 'numeric', 'gte:1'],
            'color_id' =>  ['exists:colors,id', 'numeric', 'gte:1'],
            'size_id' =>  ['exists:sizes,id', 'numeric', 'gte:1'],
            'ribbon_id' => $ribbonId,
            'categories' =>  ['array'],
            'categories.*' =>  ['exists:categories,id', 'numeric', 'gte:1'],
            'colors' => ['array'],
            'sizes' => ['array'],
            'videos' => ['array'],
            'where_category_id' =>  ['exists:categories,id', 'numeric', 'gte:1'],

        ];



        if (is_array($this->id)) return [
            'id' =>  ['required'],
            'id.*' => ['exists:stocks,id', 'numeric', 'gte:1'],
            'price' =>  ['numeric'],
            'mock_price' =>  ['numeric'],
            'credits' =>  ['numeric'],
            'discount' =>  [],
            'cover_image_id' =>  ['exists:images,id', 'numeric', 'gte:1'],
            'color_id' =>  ['exists:colors,id', 'numeric', 'gte:1'],
            'size_id' =>  ['exists:sizes,id', 'numeric', 'gte:1'],
            'ribbon_id' => $ribbonId,
            'categories' =>  ['array'],
            'categories.*' =>  ['exists:categories,id', 'numeric', 'gte:1'],
            'colors' => ['array'],
            'sizes' => ['array'],

        ];



        return [
            'id' =>  ['required', 'exists:stocks,id', 'numeric', 'gte:1'],
            'price' =>  ['numeric'],
            'mock_price' =>  ['numeric'],
            'credits' =>  ['numeric'],
            'discount' =>  [],
            'cover_image_id' =>  ['exists:images,id', 'numeric', 'gte:1'],
            'color_id' =>  ['exists:colors,id', 'numeric', 'gte:1'],
            'size_id' =>  ['exists:sizes,id', 'numeric', 'gte:1'],
            'ribbon_id' => $ribbonId,
            'categories' =>  ['array'],
            'categories.*' =>  ['exists:categories,id', 'numeric', 'gte:1'],
            'colors' => ['array'],
            'sizes' => ['array'],

        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
