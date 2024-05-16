<?php

namespace App\Http\Requests\DetailUser;

use App\Models\ManagementAccess\DetailUser;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use symfony\component\HttpFoundation\Response;

class UpdateDetailUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('detail_user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'user_id' => [
                'required', 'integer',
            ],
            'type_user_id' => [
                'required', 'integer',
            ],
            'contact' => [
                'nullable', 'string', 'max:255',
            ],
            'address' => [
                'nullable', 'string', 'max:255',
            ],
            'photo' => [
                'nullable', 'string', 'max:10000',
            ],
            'gender' => [
                'nullable', 'string', 'max:10000',
            ],
        ];
    }
}
