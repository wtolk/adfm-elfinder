<?php

namespace Wtolk\AdfmElfinder\Providers;

use App\Adfm\Helpers\ImageCache;
use App\Helpers\Dev;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Wtolk\Adfm\Commands\CheckDBCommand;
use Wtolk\Adfm\Commands\CreateDBCommand;
use Wtolk\Adfm\Commands\CreateUserCommand;
use Wtolk\Adfm\Commands\InstallSetUpCommand;
use Wtolk\Adfm\Commands\SetEnvCommand;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Aws\S3\S3Client;
use Aws\Laravel\AwsServiceProvider;

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
            ->get('adfm/elfinder', 'Wtolk\AdfmElfinder\Controllers\ElfinderController@elfinder')
            ->name('adfm.elfinder.route');
    }


}
