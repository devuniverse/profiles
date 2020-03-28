<?php

namespace Devuniverse\Profiles;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;

class ProfilesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      include __DIR__.'/Routes/web.php';
      $this->publishes([
        __DIR__.'/Config/profiles.php' => config_path('profiles.php'),
        __DIR__.'/public' => public_path('profiles/assets'),
        __DIR__.'/config/extraobjects.php' => base_path('bootstrap/extraobjects.php'),
        __DIR__.'/views' => base_path('resources/views/vendor/profiles'),

      ]);
      // $this->publishes([
      //     __DIR__.'/database/' => database_path(),
      // ], 'profiles');
      $this->loadMigrationsFrom(__DIR__.'/database/migrations');

      /************************  TO VIEWS ***************************/

      view()->composer('*', function ($view){
       $request =  Request();
       if(\Auth::check()){
         $user = Auth::user();
         $metas =[];
         foreach (\Devuniverse\Profiles\Models\Usermeta::where('user_id', $user->id)->get() as $key => $meta) {
           $metas[$meta->meta_key] = $meta->meta_value;
         }
         $user['metas'] = $metas;
         $view->with('ppuser', $user);
       };
      });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      // register our controller
      $this->app->make('Devuniverse\Profiles\Controllers\ProfilesController');
      $this->loadViewsFrom(__DIR__.'/views', 'profiles');

      $this->mergeConfigFrom(
          __DIR__.'/Config/profiles.php', 'profiles'
      );
    }
}
