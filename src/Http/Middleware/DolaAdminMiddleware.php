<?php

namespace DFZ\Dola\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DFZ\Dola\Facades\Dola;

class DolaAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $user = Dola::model('User')->find(Auth::id());

            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        return redirect(route('dola.login'));
    }
}
