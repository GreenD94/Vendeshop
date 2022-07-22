<?php

namespace App\Http\Requests\images;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isNan;

class ImageStoreRequest extends FormRequest
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

            'stock_id' =>  ['exists:stocks,id', 'numeric', 'gte:1'],
            'image' => ['required', 'image'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1'],

        ];
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
