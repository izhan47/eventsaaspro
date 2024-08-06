<?php

namespace Eventsaaspro\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'title',
        'amount',
        'type',
        'start_date',
        'expire_date',
        'ticket',
        'quantity',
        'status',
    ];
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getTicketAttribute($value)
    {
        return json_decode($value, true);
    }
}
