<?php

namespace AdrianBav\Traffic\Models;

class Visit extends BaseModel
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id', 'updated_at', 'created_at',
    ];
}
