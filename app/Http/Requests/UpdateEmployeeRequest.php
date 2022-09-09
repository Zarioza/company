<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'string|nullable',
            'position_id' => 'int|nullable|exists:positions,id',
            'superior_id' => 'int|nullable|exists:employees,id',
            'start_date' => 'date_format:"Y-m-d"|before:end_date|nullable',
            'end_date' => 'date_format:"Y-m-d"|nullable',
        ];
    }
}
