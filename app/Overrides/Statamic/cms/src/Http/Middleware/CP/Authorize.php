<?php

namespace Statamic\Http\Middleware\CP;

use Closure;
use Statamic\Exceptions\AuthenticationException;
use Statamic\Exceptions\AuthorizationException;
use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Authorize
{
    public function handle($request, Closure $next)
    {
        $user = User::current();
       

        if (! $user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        if ($user->cant('access cp')) {
            throw new AuthorizationException('Unauthorized.');
        }

        if ($user) {
            if ($user->get('super')) {
                return $next($request);
            }

            $expirationDate = $user->get('expiration_date');
            if ($expirationDate && Carbon::parse($expirationDate)->isPast()) {
                Auth::logout();
                throw new AuthorizationException('Unauthorized.');
            }
        }


        return $next($request);
    }
}
