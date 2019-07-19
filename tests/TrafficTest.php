<?php

namespace AdrianBav\Traffic\Tests;

use AdrianBav\Traffic\Models\Ip;
use Orchestra\Testbench\TestCase;
use AdrianBav\Traffic\Models\Site;
use AdrianBav\Traffic\Models\Agent;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use AdrianBav\Traffic\Facades\Traffic;
use AdrianBav\Traffic\TrafficServiceProvider;
use AdrianBav\Traffic\Contracts\RobotDetection;
use AdrianBav\Traffic\Middlewares\RecordVisits;

class TrafficTest extends TestCase
{
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
    }

    /**
     * Setup migrations.
     *
     * @return void
     */
    protected function setUpMigrations()
    {
        $this->artisan('migrate', [
            '--database' => 'traffic',
            '--path' => __DIR__.'/../database/migrations',
            '--realpath' => true,
        ]);
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
        })->middleware(RecordVisits::class);
    }

    /**
     * Setup middleware.
     *
     * @return void
     */
    protected function setUpGlobalMiddleware()
    {
        $this->app[Kernel::class]->pushMiddleware(RecordVisits::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Set the slug to use for testing this application
        $app['config']->set('traffic.site_slug', 'traffic_testing_slug');

        // Allow recording of multiple visits per session
        $app['config']->set('traffic.single_visit', false);

        // Allow all requests through for testing
        $app['config']->set('traffic.ignore_robots', false);

        // The service provider will clone this connection to 'traffic'
        $app['config']->set('traffic.database_default', 'testing');
    }

    /** @test  */
    public function the_site_slug_can_be_retrieved()
    {
        $siteSlug = Traffic::siteSlug();

        $this->assertEquals('traffic_testing_slug', $siteSlug);
    }

    /** @test */
    public function the_visit_count_starts_at_zero()
    {
        $this->assertEquals(0, Traffic::visits('traffic_testing_slug'));
    }

    /** @test */
    public function the_correct_visit_count_is_returned()
    {
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');

        $this->assertEquals(2, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function visits_are_persisted_between_requests_using_route_middleware()
    {
        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));

        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->assertEquals(3, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function visits_are_persisted_between_requests_using_global_middleware()
    {
        $this->setUpGlobalMiddleware();

        $this->get('/route-without-middleware');
        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));

        $this->get('/route-without-middleware');
        $this->get('/route-without-middleware');
        $this->assertEquals(3, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function visits_are_not_recorded_when_package_is_disabled()
    {
        config(['traffic.enabled' => false]);

        $this->get('/route-with-middleware');
        $this->assertEquals(0, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function package_can_be_configured_to_record_only_one_visit_per_session()
    {
        config(['traffic.single_visit' => true]);

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));

        $this->get('/route-with-middleware');
        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));

        session()->flush();

        $this->get('/route-with-middleware');
        $this->assertEquals(2, Traffic::visits('traffic_testing_slug'));

        $this->get('/route-with-middleware');
        $this->assertEquals(2, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function a_site_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $this->assertEquals(2, Site::first()->visits()->count());
    }

    /** @test  */
    public function an_ip_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $this->assertEquals(3, Ip::first()->visits()->count());
    }

    /** @test  */
    public function an_agent_is_related_to_a_visit()
    {
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');
        $this->get('/route-with-middleware');

        $this->assertEquals(4, Agent::first()->visits()->count());
    }

    /** @test  */
    public function robot_agents_can_be_blocked()
    {
        config(['traffic.ignore_robots' => true]);
        $this->app->bind(RobotDetection::class, FakeRobotDetection::class);

        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');

        $this->assertEquals(1, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function robot_agents_can_be_recorded()
    {
        config(['traffic.ignore_robots' => false]);
        $this->app->bind(RobotDetection::class, FakeRobotDetection::class);

        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');

        $this->assertEquals(3, Traffic::visits('traffic_testing_slug'));
    }

    /** @test  */
    public function robot_visit_counts_are_captured_seperatly()
    {
        config(['traffic.ignore_robots' => false]);
        $this->app->bind(RobotDetection::class, FakeRobotDetection::class);

        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');

        $this->assertEquals(3, Traffic::robots('traffic_testing_slug'));
    }

    /** @test  */
    public function robot_visit_counts_are_captured_seperatly_even_when_ignored_from_visits()
    {
        config(['traffic.ignore_robots' => true]);
        $this->app->bind(RobotDetection::class, FakeRobotDetection::class);

        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');
        Traffic::record($ip = '127.0.0.1', $agent = 'genuine');
        Traffic::record($ip = '127.0.0.1', $agent = 'robot');

        $this->assertEquals(3, Traffic::robots('traffic_testing_slug'));
    }

    /** @test  */
    public function ips_can_be_excluded()
    {
        config(['traffic.excluded_ips' => [
            '666.6.6.6',
            '999.9.9.9',
        ]]);

        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');
        Traffic::record($ip = '666.6.6.6', $agent = 'Symfony');
        Traffic::record($ip = '666.6.6.6', $agent = 'Symfony');
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');
        Traffic::record($ip = '999.9.9.9', $agent = 'Symfony');
        Traffic::record($ip = '127.0.0.1', $agent = 'Symfony');

        $this->assertEquals(3, Traffic::visits('traffic_testing_slug'));
    }
}
