<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'name' => 'string|required',
            'position_id' => 'int|required|exists:positions,id',
            'superior_id' => 'int|nullable|exists:employees,id',
            'start_date' => 'date_format:"Y-m-d"|before:end_date|required',
            'end_date' => 'date_format:"Y-m-d"|required',
        ];
    }
}
