<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Event;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'relationship', 
        'fk', 
        'start',
        'sent',
    ];

    // Relacion con tabla events a partir de reminders.fk = events.id
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'fk');
    }
}