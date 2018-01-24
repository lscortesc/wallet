<?php

namespace App\Providers;

use App\Customer;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::bind('customerTransfer', function ($value) {
            $customer = Customer::whereNotIn('id', [1])
                ->find($value);

            if (! $customer) {
                abort(404, 'Customer Not Found');
            }

            return $customer->id !== request()->user()->id ? $customer :
                abort(404, "You can't send money to yourself");
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->createMapApiRoutes($this->namespace, 'routes/api.php');
        $this->createMapApiRoutes('Oauth\\Http\\Controllers', 'routes/oauth.php');
    }

    /**
     * @param string $namespace
     * @param string $routesFile
     */
    protected function createMapApiRoutes(string $namespace, string $routesFile)
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($namespace)
            ->group(base_path($routesFile));
    }
}
