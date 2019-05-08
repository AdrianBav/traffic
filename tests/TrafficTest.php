<?php

namespace AdrianBav\Traffic\Tests;

use Orchestra\Testbench\TestCase;
use AdrianBav\Traffic\Facades\Traffic;
use AdrianBav\Traffic\TrafficServiceProvider;

class TrafficTest extends TestCase
{
    /**
     * The site slug used for testing.
     *
     * @var  string
     */
    protected $trafficSiteSlug;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->trafficSiteSlug = getenv('TRAFFIC_SITE_SLUG');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TrafficServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Traffic' => Traffic::class,
        ];
    }

    /** @test */
    public function the_visit_count_starts_at_zero()
    {
        $this->assertEquals(0, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test */
    public function the_correct_visit_count_is_returned()
    {
        Traffic::record(['visit1']);
        Traffic::record(['visit2']);

        $this->assertEquals(2, Traffic::visits($this->trafficSiteSlug));
    }
}
