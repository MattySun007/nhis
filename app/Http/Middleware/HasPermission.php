<?php
namespace App\Http\Middleware;

use Closure;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $permissions)
    {
        if (count($permissions))
        {
            // Check if the user has any of the listed permissions
            $hasAnyPermission = array_reduce($permissions, function ($can, $perm) use ($request) {
                return $can || $request->user()->can($perm);
            });

            if(!$hasAnyPermission)
            {
              if ($request->ajax())
              {
                return response()->json(['error' => 'Unauthorized Access: ensure you have the permissions for this route!'], 403);
              }
            }
        }

        return $next($request);
    }
}
