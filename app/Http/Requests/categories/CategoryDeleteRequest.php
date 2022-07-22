<?php

namespace App\Http\Requests\categories;

use App\Models\Category;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CategoryDeleteRequest extends FormRequest
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
            'id' =>  [
                'required',
                'integer',
                'exists:categories,id',
                'numeric', 'gte:1',
                function ($attribute, $value, $fail) {
                    $category = Category::find($this->id);
                    $stocks = $category ? $category->stocks : collect([]);
                    if ($stocks->count() > 0) {
                        $fail('The category [ ' . $category->name . ' ] has (' . $stocks->count() . ') stocks related');
                    }
                },
            ],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
