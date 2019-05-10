<?php

namespace AdrianBav\Traffic\Contracts;

interface RobotDetection
{
    /**
     * Detect spider/crawlers/bots from the user agent.
     *
     * @param   string  $userAgent
     * @return  bool
     */
    public function isRobot($userAgent);
}
