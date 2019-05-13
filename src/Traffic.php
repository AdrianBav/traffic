<?php

namespace AdrianBav\Traffic;

use AdrianBav\Traffic\Models\Ip;
use AdrianBav\Traffic\Models\Site;
use AdrianBav\Traffic\Models\Agent;
use AdrianBav\Traffic\Models\Visit;
use AdrianBav\Traffic\Contracts\RobotDetection;

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
     * @var  bool
     */
    protected $singleVisitPerSession;

    /**
     * Detect bots/crawlers/spiders from the user agent.
     *
     * @var  RobotDetection
     */
    protected $robotDetector;

    /**
     * Disable the recording of the visits of robots.
     *
     * @var  bool
     */
    protected $ignoreRobots;

    /**
     * Disable the recording of the visits from excluded IPs.
     *
     * @var  array
     */
    protected $excludedIps;

    /**
     * Instantiate a new Traffic instance.
     *
     * @param   array  $config
     * @param   RobotDetection  $robotDetector
     * @return  void
     */
    public function __construct($config, RobotDetection $robotDetector)
    {
        $this->siteSlug = $config['site_slug'];
        $this->singleVisitPerSession = $config['single_visit'];
        $this->ignoreRobots = $config['ignore_robots'];
        $this->excludedIps = $config['excluded_ips'];

        $this->robotDetector = $robotDetector;
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

        if ($this->ignoreRobots && $this->robotDetector->isRobot($userAgent)) {
            return;
        }

        if ($this->ipIsExcluded($ipAddress)) {
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
     * @return  bool
     */
    private function alreadyRecorded()
    {
        return session('recorded', false) === true;
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
     * Determine if the IP address should be excluded.
     *
     * @param   string  $ipAddress
     * @return  bool
     */
    private function ipIsExcluded($ipAddress)
    {
        return in_array($ipAddress, $this->excludedIps);
    }

    /**
     * Return the number of visits.
     *
     * @param   string  $siteSlug
     * @return  int
     */
    public function visits($siteSlug)
    {
        $count = optional(Site::whereSlug($siteSlug)->first(), function ($site) {
            return $site->visits()->count();
        });

        return $count ?? 0;
    }
}
