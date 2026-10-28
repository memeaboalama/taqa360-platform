<?php

namespace Application\Marketplace\Middleware;

use App\Domain\MarketPlace\Actions\Dashboard\Service\FetchServiceAction;
use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Closure;
use Domain\MarketPlace\Models\MerchantService;
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
        $menuRepo = new MerchantServiceRepository(new MerchantService());
        $menu = $menuRepo->first(resolve('_InitialMerchant'));
        if ($menu)
        {
            app()->instance('menuInitialization', $menu);
        }

        return $next($request);
    }
}
