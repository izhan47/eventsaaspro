<?php

namespace Eventsaaspro\Models;

use Eventsaaspro\Models\User;
use Illuminate\Database\Eloquent\Model;

class ComedianEvent extends Model
{

    protected $guarded = [];
    protected $hidden = [];
    protected $with = ['user'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function setFixedValueAttribute($value)
    {
        $this->attributes['fixed_value'] = json_encode($value);
    }

    public function setPercentValueAttribute($value)
    {
        $this->attributes['percent_value'] = json_encode($value);
    }

    public function getFixedValueAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getPercentValueAttribute($value)
    {
        return json_decode($value, true);
    }
}
