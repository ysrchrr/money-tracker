<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappNotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reminder_setting_id',
        'whatsapp_number',
        'message',
        'payload',
        'response_body',
        'status',
        'error_message',
        'trigger_source',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reminderSetting(): BelongsTo
    {
        return $this->belongsTo(ReminderSetting::class);
    }
}
