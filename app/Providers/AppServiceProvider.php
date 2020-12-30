<?php

namespace App\Providers;

use App\Services\ChanelUser;
use App\Services\Goods\Info;
use App\Services\Goods\Price;
use App\Services\Logistics;
use App\Services\Orders\AfterCreate;
use App\Services\Queue;
use App\Services\Suppliers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    protected $defer = true;

    //所有的单例services容器
    public $singletons = [
        \App\Services\Test::class                           => \App\Services\Test::class,
        \App\Services\Ajax::class                           => \App\Services\Ajax::class,
        \App\Services\Common\Mongo::class                   =>  \App\Services\Common\Mongo::class,
        \App\Services\Outer\Erp\Dashboard::class            =>  \App\Services\Outer\Erp\Dashboard::class,
        \App\Services\Outer\Erp\Finance::class              =>  \App\Services\Outer\Erp\Finance::class,
        \App\Services\Backend\Basics::class                 =>  \App\Services\Backend\Basics::class,
        \App\Services\Outer\Erp\PrintsDeliver::class        =>  \App\Services\Outer\Erp\PrintsDeliver::class,
        \App\Services\Exception::class                      =>  \App\Services\Exception::class

    ];
    public $bindings = [
        Price::class            => Price::class,
        Suppliers::class        => Suppliers::class,
        Logistics::class        => Logistics::class,
        Info::class             => Info::class,
        ChanelUser::class       => ChanelUser::class,
        AfterCreate::class      => AfterCreate::class,
        Queue::class            => Queue::class
    ];


    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }
}
