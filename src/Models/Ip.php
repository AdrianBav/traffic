<?php

namespace AdrianBav\Traffic\Models;

class Ip extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get all the visits for the IP.
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}
