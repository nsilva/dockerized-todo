<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Support\Enums\TodoStatusEnum;
use Illuminate\Validation\Rule;

class UpdateTodoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'     => 'sometimes',
            'status'    => [
                'sometimes',
                'nullable',
                Rule::in(array_column(TodoStatusEnum::cases(), 'value'))
            ],
        ];
    }
}
