<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;
use App\Models\Ajuste;

class AppServiceProvider extends ServiceProvider
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
        $mailconfig = Ajuste::first();        
        $data = [
            'driver'        =>  $mailconfig->driver,
            'host'          =>  $mailconfig->host,
            'port'          =>  $mailconfig->port,
            'encryption'    =>  $mailconfig->encryption,
            'username'      =>  $mailconfig->username,
            'password'      =>  $mailconfig->password,
            'from'          => [
                'address'   => $mailconfig->from,
                'name'      => $mailconfig->name
            ]

        ];
        Config::set('mail',$data);
    }
}
