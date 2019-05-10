<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Contracts\RobotDetection;

class FakeRobotDetection implements RobotDetection
{
    /**
     * {@inheritdoc}
     */
    public function isRobot($userAgent)
    {
        return ($userAgent === 'robot') ? true : false;
    }
}
