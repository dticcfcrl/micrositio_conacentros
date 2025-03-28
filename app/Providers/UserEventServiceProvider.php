<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Statamic\Facades\User;
use App\Models\User as UserDB;
use Statamic\Auth\File\User as UserCMS;

class UserEventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(Registered::class, function ($event) {
            $user = $event->user;
            if($user instanceof UserDB) {
                $user->__set('password_changed_at', Carbon::now()->addYear());
            } else if($user instanceof UserCMS) {
                $user->set('expiration_date', Carbon::now()->addYear());
            } else {
                abort(403, 'Acceso denegado.');
            }
            $user->save();
        });

        Event::listen(PasswordReset::class, function ($event) {
            $user = $event->user;

            if($user instanceof UserDB) {
                $user->__set('password_changed_at', Carbon::now()->addYear());
            } else if($user instanceof UserCMS) {
                $user->set('expiration_date', Carbon::now()->addYear());
            } else {
                abort(403, 'Acceso denegado.');
            }

            $user->save();
        });
    }
}
