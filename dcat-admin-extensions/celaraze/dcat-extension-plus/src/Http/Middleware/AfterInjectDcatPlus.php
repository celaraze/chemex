<?php

namespace Celaraze\DcatPlus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class AfterInjectDcatPlus.
 */
class AfterInjectDcatPlus
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
