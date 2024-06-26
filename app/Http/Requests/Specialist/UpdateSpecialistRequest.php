<?php

namespace App\Http\Requests\Specialist;

use App\Models\MasterData\Specialist;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use symfony\component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class UpdateSpecialistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        abort_if(Gate::denies('specialist_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            'name' => [
                'required', 'string', 'max:50', Rule::unique('specialist')->ignore($this->specialist),
                // rule unique only works for other record id
            ],
            'price' => [
                'required', 'string', 'max:255',
            ],
        ];
    }
}
