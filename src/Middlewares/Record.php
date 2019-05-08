<?php

namespace AdrianBav\Traffic\Middlewares;

use Closure;
use Illuminate\Http\Request;
use AdrianBav\Traffic\Facades\Traffic;

class Record
{
    public function handle(Request $request, Closure $next)
    {
        Traffic::record(['visit1']);

        return $next($request);
    }
}
