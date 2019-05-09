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
     * Only record a single visit per session.
     *
     * @var  boolean
     */
    protected $singleVisitPerSession;

    /**
     * Instantiate a new Traffic instance.
     *
     * @param   string  $siteSlug
     * @param   boolean  $singleVisit
     * @return  void
     */
    public function __construct($siteSlug, $singleVisit)
    {
        $this->site = $siteSlug;
        $this->singleVisitPerSession = $singleVisit;
    }

    /**
     * Record a visit.
     *
     * @param   string  $ip
     * @param   string  $agent
     * @return  void
     */
    public function record($ip, $agent)
    {
        if ($this->singleVisitPerSession && $this->alreadyRecorded()) {
            return;
        }

        Visit::create([
            'site' => $this->site,
            'ip' => $ip,
            'agent' => $agent,
        ]);

        $this->markAsRecorded();
    }

    /**
     * Check if this visit has already been recorded.
     *
     * @return  boolean
     */
    private function alreadyRecorded()
    {
        return (session('recorded', false) === true);
    }

    /**
     * Mark this visit as recorded.
     *
     * @return  void
     */
    private function markAsRecorded()
    {
        session(['recorded' => true]);
    }

    /**
     * Return the number of visits.
     *
     * @param   string  $site
     * @return  int
     */
    public function visits($site)
    {
        return Visit::where('site', $site)
            ->count();
    }
}
