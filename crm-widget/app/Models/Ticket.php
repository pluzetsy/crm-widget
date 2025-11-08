<?php

namespace App\Models;

use App\TicketStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'manager_id',
        'subject',
        'text',
        'status',
        'handled_at',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
        'status' => TicketStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')->useDisk('public');
    }
}
