<?php

namespace AdrianBav\Traffic;

use AdrianBav\Traffic\Models\Ip;
use AdrianBav\Traffic\Models\Site;
use AdrianBav\Traffic\Models\Agent;
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
        $site = Site::firstOrCreate(['slug' => $this->siteSlug]);

        if ($this->shouldRecord($site, $ipAddress, $userAgent) === false) {
            return;
        }

        $site->visits()->create([
            'ip_id' => Ip::firstOrCreate(['address' => $ipAddress])->id,
            'agent_id' => Agent::firstOrCreate(['name' => $userAgent])->id,
        ]);

        $this->markAsRecorded();
    }

    /**
     * Determine if the visit should be recorded.
     *
     * @param   string  $site
     * @param   string  $ipAddress
     * @param   string  $userAgent
     * @return  bool
     */
    private function shouldRecord($site, $ipAddress, $userAgent)
    {
        if ($this->robotDetector->isRobot($userAgent)) {
            $site->increment('robots');

            if ($this->ignoreRobots) {
                return false;
            }
        }

        if ($this->ipIsExcluded($ipAddress)) {
            return false;
        }

        if ($this->singleVisitPerSession && $this->alreadyRecorded()) {
            return false;
        }

        return true;
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
        if (is_null($site = Site::whereSlug($siteSlug)->first())) {
            return 0;
        }

        return $site->visits()->count();
    }

    /**
     * Return the number of robot visits.
     *
     * @param   string  $siteSlug
     * @return  int
     */
    public function robots($siteSlug)
    {
        if (is_null($site = Site::whereSlug($siteSlug)->first())) {
            return 0;
        }

        return $site->robots;
    }
}
