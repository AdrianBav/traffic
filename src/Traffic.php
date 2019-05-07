<?php

namespace AdrianBav\Traffic;

class Traffic
{
    /**
     * An array of site visits.
     *
     * @var  Collection
     */
    protected $visits;

    /**
     * Instantiate a new Traffic instance.
     */
    public function __construct()
    {
        $this->visits = collect();
    }

    /**
     * Record a visit.
     *
     * @param   string  $site
     * @param   array   $payload
     * @return  void
     */
    public function record($site, $payload)
    {
        $this->visits->push([
            'site' => $site,
            'payload' => $payload,
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
        return $this->visits
            ->filter(function($visit) use ($site) {
                return $visit['site'] == $site;
            })->count();
    }
}
