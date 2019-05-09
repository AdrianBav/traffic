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
     */
    public function __construct()
    {
        $this->site = config('traffic.site_slug');
    }

    /**
     * Record a visit.
     *
     * @param   string  $site
     * @param   string  $ip
     * @param   string  $agent
     * @return  void
     */
    public function record($ip, $agent)
    {
        Visit::create([
            'site' => $this->site,
            'ip' => $ip,
            'agent' => $agent,
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
        return Visit::where('site', $this->site)
            ->count();
    }
}
