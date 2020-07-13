<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;

class AdminOperation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->isSuperAdmin()) {
            return $next($request);
        } else {
            throw new CustomException('您无权进行此操作');
        }

    }
}
