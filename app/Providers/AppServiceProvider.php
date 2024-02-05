<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Config;

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
        $data = [
            'driver'        =>'smtp',
            'host'          =>'sandbox.smtp.mailtrap.io',
            'port'          =>'2525',
            'encryption'    =>'tls',
            'username'      =>'4c4c778cabb850',
            'password'      =>'88aeee2bffa71b',
            'from'          => [
                'address'   => 'luisfernandomedinallorenti@gmail.com',
                'name'      => 'Farmacia_Laufer'
            ]

        ];
        Config::set('mail',$data);
    }
}
