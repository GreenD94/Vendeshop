<?php

namespace App\Http\Requests\stocks;

use App\Traits\Responser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StockIndexRequest extends FormRequest
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
            'page' =>  ['integer', 'numeric', 'gte:1'],
            'order_by' => [
                Rule::in(['latest', 'random', 'last_updated', 'desc']),
            ],
            'limit' =>  ['integer', 'numeric', 'gte:1'],
            'category_id' =>  ['exists:categories,id', 'numeric', 'gte:1'],
            'is_favorite' =>  ['boolean'],
            'id' =>  ['exists:stocks,id', 'numeric', 'gte:1'],
        ];;
        $isFavoriteBoolean = $this->is_favorite == 1 || $this->is_favorite == 0 || $this->is_favorite == "true" || $this->is_favorite == "TRUE" || $this->is_favorite == "false" || $this->is_favorite == "FALSE";
        $isFavoriteNull = $this->is_favorite == null;
        $isFavoriteId = (!$isFavoriteNull) && (!$isFavoriteBoolean);


        if ($isFavoriteId)  $this->replace(["is_favorite" => (int) str_replace("#", "", $this->is_favorite)]);;
        if ($isFavoriteId) $this->is_favorite_id = true;
        if ($isFavoriteId) $rules['is_favorite'] = [
            function ($attribute, $value, $fail) {
                $value = str_replace("#", "",  $value);
                if (!is_numeric($value)) $fail('The ' . $attribute . ' must be a number');
            }, function ($attribute, $value, $fail) {
                $value = str_replace("#", "",  $value);
                if ($value < 1) $fail('The ' . $attribute . ' must be a greater or equal 1');
            },
            function ($attribute, $value, $fail) {
                $value = str_replace("#", "",  $value);
                if (!(DB::table('users')->where('id', $value)->exists())) $fail('The ' . $attribute . ' with value of ' . $value . ' does not exists in users table');
            }
        ];


        return $rules;
    }
    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
