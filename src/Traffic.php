<?php

namespace AdrianBav\Traffic;

use AdrianBav\Traffic\Models\Visit;

class Traffic
{
    /**
     * This sites identification.
     *
     * @var  string
     */
    protected $site;

    /**
     * Instantiate a new Traffic instance.
     *
     * @param   string  $site
     * @return  void
     */
    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * Record a visit.
     *
     * @param   array   $payload
     * @return  void
     */
    public function record($payload)
    {
        Visit::create([
            'site' => $this->site,
            'payload' => $payload[0],
        ]);
    }

    /**
     * Return the number of visits.
     *
     * @param   string  $site
     * @return  int
     */
    public function visits($site)
    {
        return Visit::where('site', $this->site)->count();
    }
}
