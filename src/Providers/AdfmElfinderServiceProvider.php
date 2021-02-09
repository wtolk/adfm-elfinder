<?php

namespace Wtolk\AdfmElfinder\Providers;

use Illuminate\Support\ServiceProvider;


class AdfmElfinderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Объявляем один единственный роут
        $this->app['router']
            ->match(['get', 'post'], 'adfm/elfinder', 'Wtolk\AdfmElfinder\Controllers\ElfinderController@elfinder')
            ->name('adfm.elfinder.route');

        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/wtolk/crud/elfinder/'),
        ]);
    }


}
