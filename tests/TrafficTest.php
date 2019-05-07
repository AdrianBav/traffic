<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Traffic;
use PHPUnit\Framework\TestCase;

class TrafficTest extends TestCase
{
    /**
     * An instance of the traffic package.
     *
     * @var  Traffic
     */
    protected $traffic;

    /**
     * Common setup.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->traffic = new Traffic;
    }

    /** @test */
    public function the_visit_count_is_returned()
    {
        $this->traffic->record('site1');
        $this->traffic->record('site2');

        $this->assertEquals(2, $this->traffic->visits());
    }
}
