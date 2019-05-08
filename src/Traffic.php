<?php

namespace AdrianBav\Traffic;

class Traffic
{
    /**
     * This sites identification.
     *
     * @var  String
     */
    protected $site;

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
        $this->site = config('traffic.site_slug');
        $this->visits = collect();
    }

    /**
     * Record a visit.
     *
     * @param   string  $site
     * @param   array   $payload
     * @return  void
     */
    public function record($payload)
    {
        $this->visits->push([
            'site' => $this->site,
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
            ->filter(function ($visit) use ($site) {
                return $visit['site'] == $site;
            })->count();
    }
}
