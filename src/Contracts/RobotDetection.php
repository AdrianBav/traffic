<?php

namespace AdrianBav\Traffic\Contracts;

interface RobotDetection
{
    /**
     * Detect spider/crawlers/bots from the user agent.
     *
     * @param   string  $userAgent
     * @return  boolean
     */
    public function isRobot($userAgent);
}
