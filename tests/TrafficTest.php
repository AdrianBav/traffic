<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Traffic;
use PHPUnit\Framework\TestCase;

class TrafficTest extends TestCase
{
    /** @test */
    public function the_visit_count_is_returned()
    {
        $traffic = new Traffic;

        $traffic->record('site1');
        $traffic->record('site2');

        $this->assertEquals(2, $traffic->visits());
    }
}
