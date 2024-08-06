<?php

namespace Eventsaaspro\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'additional_information',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class);
    }
}
