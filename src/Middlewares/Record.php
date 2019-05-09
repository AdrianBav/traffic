<?php

namespace AdrianBav\Traffic\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AdrianBav\Traffic\Facades\Traffic;

class Record
{
    public function handle(Request $request, Closure $next)
    {
        if (config('traffic.enabled')) {
            Traffic::record(
                $request->ip(),
                $request->userAgent()
            );
        }

        return $next($request);
    }
}
