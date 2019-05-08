<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Traffic;
use Orchestra\Testbench\TestCase;
use AdrianBav\Traffic\Facades\Traffic as TrafficFacade;
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
            'Traffic' => TrafficFacade::class,
        ];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->trafficSiteSlug = getenv('TRAFFIC_SITE_SLUG');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /** @test */
    public function the_visit_count_starts_at_zero()
    {
        $this->assertEquals(0, TrafficFacade::visits($this->trafficSiteSlug));
    }

    /** @test */
    public function the_correct_visit_count_is_returned()
    {
        TrafficFacade::record(['visit1']);
        TrafficFacade::record(['visit2']);

        $this->assertEquals(2, TrafficFacade::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function visits_are_persisted_between_requests()
    {
        $traffic1 = new Traffic;
        $traffic2 = new Traffic;

        $traffic1->record(['visit1']);
        $traffic2->record(['visit2']);

        $this->assertEquals(2, TrafficFacade::visits($this->trafficSiteSlug));
    }
}
