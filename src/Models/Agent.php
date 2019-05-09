<?php

namespace AdrianBav\Traffic\Models;

class Agent extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'updated_at', 'created_at',
    ];

    /**
     * Get all the visits for the Agent.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
