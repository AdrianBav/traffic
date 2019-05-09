<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Models\Ip;
use Orchestra\Testbench\TestCase;
use AdrianBav\Traffic\Models\Site;
use AdrianBav\Traffic\Models\Agent;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use AdrianBav\Traffic\Facades\Traffic;
use AdrianBav\Traffic\Middlewares\Record;
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
            'Traffic' => Traffic::class,
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

        $this->setUpMigrations();
        $this->setUpRoutes();

        $this->trafficSiteSlug = getenv('TRAFFIC_SITE_SLUG');
    }

    /**
     * Setup migrations.
     *
     * @return void
     */
    protected function setUpMigrations()
    {
        $this->artisan('migrate', ['--database' => 'traffic']);
    }

    /**
     * Setup routes.
     *
     * @return void
     */
    protected function setUpRoutes()
    {
        Route::get('/route-without-middleware', function () {
            return response();
        });

        Route::get('/route-with-middleware', function () {
            return response();
        })->middleware(Record::class);
    }

    /**
     * Setup middleware.
     *
     * @return void
     */
    protected function setUpGlobalMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(Record::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Allow recording of multiple visits per session
        $app['config']->set('traffic.single_visit', false);

        // The service provider will clone this connection to 'traffic'
        $app['config']->set('traffic.database_default', 'testing');
    }

    /** @test */
    public function the_visit_count_starts_at_zero()
    {
        $this->assertEquals(0, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test */
    public function the_correct_visit_count_is_returned()
    {
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');

        $this->assertEquals(2, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function visits_are_persisted_between_requests_using_route_middleware()
    {
        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits($this->trafficSiteSlug));

        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->assertEquals(3, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function visits_are_persisted_between_requests_using_global_middleware()
    {
        $this->setUpGlobalMiddleware();

        $this->get('/route-without-middleware');
        $this->assertEquals(1, Traffic::visits($this->trafficSiteSlug));

        $this->get('/route-without-middleware');
        $this->get('/route-without-middleware');
        $this->assertEquals(3, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function visits_are_not_recorded_when_package_is_disabled()
    {
        config(['traffic.enabled' => false]);

        $this->get('/route-with-middleware');
        $this->assertEquals(0, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function package_can_be_configured_to_record_only_one_visit_per_session()
    {
        config(['traffic.single_visit' => true]);

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits($this->trafficSiteSlug));

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits($this->trafficSiteSlug));

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits($this->trafficSiteSlug));

        session()->flush();

        $this->get('/route-with-middleware');
        $this->assertEquals(2, Traffic::visits($this->trafficSiteSlug));

        $this->get('/route-with-middleware');
        $this->assertEquals(2, Traffic::visits($this->trafficSiteSlug));
    }

    /** @test  */
    public function a_site_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $site = Site::where('slug', $this->trafficSiteSlug)->first();
        $this->assertEquals(2, $site->visits()->count());
    }

    /** @test  */
    public function an_ip_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $ip = Ip::where('address', '127.0.0.1')->first();
        $this->assertEquals(3, $ip->visits()->count());
    }

    /** @test  */
    public function an_agent_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $agent = Agent::where('name', 'Symfony')->first();
        $this->assertEquals(4, $agent->visits()->count());
    }
}
