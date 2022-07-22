<?php

namespace App\Http\Requests\email;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EmaildIndexRequest extends FormRequest
{
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
            // 'page' =>  ['integer', 'numeric', 'gte:1'],
            // 'limit' =>  ['integer', 'numeric', 'gte:1'],
        ];
    }
}
