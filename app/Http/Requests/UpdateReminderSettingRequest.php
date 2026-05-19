<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'is_enabled' => ['nullable', 'boolean'],
            'send_time' => ['required', 'date_format:H:i'],
            'message_template' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
