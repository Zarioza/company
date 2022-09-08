<?php

namespace App\Http\Requests;

use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
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
            'name' => 'string|sometimes|unique:positions',
            'type' => 'string|sometimes|in:'
                      . sprintf('%s,%s', Position::POSITION_REGULAR, Position::POSITION_MANAGEMENT),
        ];
    }
}
