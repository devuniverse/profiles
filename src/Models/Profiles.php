<?php

namespace Devuniverse\Profiles\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelGettext;
use Auth;

class Profiles extends Model
{
    public function __construct(){

    }
    static public function extras(){
      $user = Auth::user();
      $metas =[];
      foreach (\Devuniverse\Profiles\Models\Usermeta::where('user_id', $user->id)->get() as $key => $meta) {
        $metas[$meta->meta_key] = $meta->meta_value;
      }
      $user['metas'] = $metas;
      $ppuser = $user;
      if (file_exists(base_path('bootstrap/extraobjects.php'))) {
        require(base_path('bootstrap/extraobjects.php'));
        return $extras;
      }else{
        return [];
      }
    }

}
