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
    public function the_visit_count_starts_at_zero()
    {
        $this->assertEquals(0, $this->traffic->visits('site1'));
    }

    /** @test */
    public function the_correct_visit_count_is_returned()
    {
        $this->traffic->record('site1', ['visit1']);
        $this->traffic->record('site1', ['visit2']);

        $this->assertEquals(2, $this->traffic->visits('site1'));
    }

    /** @test  */
    public function multiple_sites_can_be_counted_indipendently()
    {
        $this->traffic->record('site1', ['visit1']);
        $this->traffic->record('site1', ['visit2']);
        $this->traffic->record('site2', ['visit1']);

        $this->assertEquals(2, $this->traffic->visits('site1'));
        $this->assertEquals(1, $this->traffic->visits('site2'));
    }
}
