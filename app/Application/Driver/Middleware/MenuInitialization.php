<?php

namespace Application\Driver\Middleware;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Domain\MarketPlace\Repositories\Service\DriverServiceRepository;
use Closure;
use Domain\MarketPlace\Models\DriverService;
use Illuminate\Http\Request;

class MenuInitialization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next): mixed
    {
        app()->instance('menuInitialization', false);
        $menuRepo = new DriverServiceRepository(new DriverService());
        $menu = $menuRepo->first(resolve('_InitialDriver'));
        if ($menu)
        {
            app()->instance('menuInitialization', $menu);
        }

        return $next($request);
    }
}
