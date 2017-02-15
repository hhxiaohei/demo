<?php

namespace App\Http\Middleware;

use App\Models\OperationLog;
use Closure;

class OperationLogAdd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() != "GET") {
            OperationLog::create([
                "route" => $request->route()->getPath(),
                "path" => $request->getPathInfo(),
                "method" => OperationLog::MAP[$request->method()],
                "data" => $request->except("_method", "_token"),
                "ip"=> $request->ip(),
                "admin_id"=>$request->user()->id
            ]);
        }
        return $next($request);

    }
}
