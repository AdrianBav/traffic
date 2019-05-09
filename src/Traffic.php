<?php

namespace AdrianBav\Traffic;

use AdrianBav\Traffic\Models\Ip;
use AdrianBav\Traffic\Models\Site;
use AdrianBav\Traffic\Models\Agent;
use AdrianBav\Traffic\Models\Visit;

class Traffic
{
    /**
     * This sites identification.
     *
     * @var  string
     */
    protected $siteSlug;

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
        $this->siteSlug = $siteSlug;
        $this->singleVisitPerSession = $singleVisit;
    }

    /**
     * Record a visit.
     *
     * @param   string  $ipAddress
     * @param   string  $userAgent
     * @return  void
     */
    public function record($ipAddress, $userAgent)
    {
        if ($this->singleVisitPerSession && $this->alreadyRecorded()) {
            return;
        }

        $site = Site::firstOrCreate(['slug' => $this->siteSlug]);
        $ip = Ip::firstOrCreate(['address' => $ipAddress]);
        $agent = Agent::firstOrCreate(['name' => $userAgent]);

        Visit::create([
            'site_id' => $site->id,
            'ip_id' => $ip->id,
            'agent_id' => $agent->id,
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
     * @param   string  $siteSlug
     * @return  int
     */
    public function visits($siteSlug)
    {
        $site = Site::whereSlug($siteSlug)->first();

        if (is_null($site)) {
            return 0;
        }

        return $site->visits()->count();
    }
}
