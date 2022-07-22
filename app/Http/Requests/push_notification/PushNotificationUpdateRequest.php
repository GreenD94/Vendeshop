<?php

namespace App\Http\Requests\push_notification;

use App\Models\User;
use App\Traits\Responser;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PushNotificationUpdateRequest extends FormRequest
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
            'id' =>  ['required', 'exists:push_notifications,id', 'numeric', 'gte:1', 'integer'],
            'user_id' =>  ['exists:users,id', 'numeric', 'gte:1', 'integer'],
            'push_notification_event_id' =>  ['exists:push_notification_events,id', 'numeric', 'gte:1', 'integer'],
            'is_new' =>  ['boolean'],
        ];
    }

    protected  function failedValidation(Validator $validator)
    {
        $this->errorResponse($validator->errors(), "Validation Error", 422);
    }
}
