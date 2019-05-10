<?php

namespace AdrianBav\Traffic;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use AdrianBav\Traffic\Contracts\RobotDetection;

class CrawlerRobotDetection implements RobotDetection
{
    /**
     * Holds an instance of the crawler detector.
     *
     * @var  \Jaybizzle\CrawlerDetect\CrawlerDetect
     */
    protected $crawlerDetect;

    /**
     * Instantiate a new CrawlerRobotDetection instance.
     */
    public function __construct()
    {
        $this->crawlerDetect = new CrawlerDetect;
    }

    /**
     * {@inheritdoc}
     */
    public function isRobot($userAgent)
    {
        return $this->crawlerDetect->isCrawler($userAgent);
    }
}
