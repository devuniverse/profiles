<?php

namespace Devuniverse\Profiles\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use Intervention\Image\Facades\Image;
use Config;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
  public function loadIndex(){
    return view('profiles::profile');
  }
  public function updateAvatar(Request $request){
    $image = $request->imageurldata;
    $userid= \Auth::user()->id;
    $filePath = 'uploads/profiles/'. str_random(12) . '.jpg';
    $s3 = \Storage::disk('s3');
    $imageAmazoned = $s3->put($filePath, file_get_contents($image), 'public');
    if($imageAmazoned){

      $message = _i('Uploaded');
      $msgtype = 1;
      return redirect('/'.Config::get('profiles.profiles_url'))->with( ['theresponse'=>["message"=> $message, "msgtype"=>$msgtype]] );
    }else{
      $message = _i('Uploaded');
      $msgtype = 0;
    }
    return response()->json(['message'=>$message, 'msgtype' => $msgtype]);
  }
}
