<?php

namespace App\Http\Requests\stocks;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StockFavoriteUpdateRequest extends FormRequest
{
    use Responser;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $authUser = User::find(Auth()->id());
        // return  $authUser->hasRole(['admin', 'master']);
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
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1'],
            'stock_id' =>  ['required', 'exists:stocks,id', 'numeric', 'gte:1'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
