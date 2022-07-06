<?php

namespace Dunkul\LogEx;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Dunkul\LogEx\LogExClass;

class LogExServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('LogEx', function () {
      return new LogExClass(new Request);
    });
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
