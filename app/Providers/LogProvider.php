<?php

namespace App\Providers;

use App\Services\Common\Log\Mongo;
use Illuminate\Support\ServiceProvider;

class LogProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Services\Common\Log\LogInterface',function(){
            //此处可切换记录日志的方式。
            return new Mongo();
        });
    }
}
