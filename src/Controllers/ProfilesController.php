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
use Auth;
use Crypt;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
  public function loadIndex(){
    return view('profiles::profile');
  }
  public function updateAvatar(Request $request){
    $imageName = str_random(20).'.jpg';
    $filenametostore='uploads'.(env('APP_ENV') =='local' ? 'dev':( env('APP_ENV') =='staging' ? 'staging':'' )).'/profiles/'. $imageName;
    $image = base64_decode(str_replace('data:image/png;base64,','',$request->image));
    $s3 = Storage::disk('s3');
    $amazoned = $s3->put($filenametostore,$image , 'public');
    $avatarUrl = $s3->url($imageName);

    if($amazoned){
      $user = Auth::user();
      $user->profile_picture = $filenametostore;
      $user->profile_image = 'yes';
      $saved = $user->save();
      if($saved){
        $message = _i('Avatar changed successfully');
        $msgtype = 1;
      }else{
        $message = _i('Avatar may not be saved properly');
        $msgtype = 0;
      }
    }else{
      $message = _i('We encountered some ');
      $msgtype = 0;
    }
    return response()->json(['message'=>$message, "msgtype"=>$msgtype]);
  }

  public function display(Request $request){
    $requested = \Crypt::decryptString($request->ref);
    $user = \App\User::find($requested);
    if($user){
      $filePath = "https://".env('AWS_BUCKET').".s3.".env('AWS_DEFAULT_REGION').".amazonaws.com/".$user->profile_picture;
    }else{
      $filePath = "/images/user.png";
    }
    set_time_limit(0);
    // $songCode = $_REQUEST['c'];
    $bitrate = 128;
    $strContext=stream_context_create(
         array(
             'http'=>array(
             'method'=>'GET',
             'header'=>"Accept-language: en\r\n"
             )
         )
     );
     header('Content-type: image/jpeg');
     header ("Content-Transfer-Encoding: binary");
     header ("Pragma: no-cache");
     header ("icy-br: " . $bitrate);

     $fpOrigin=fopen($filePath, 'rb', false, $strContext);
     while(!feof($fpOrigin)){
       $buffer=fread($fpOrigin, 4096);
       echo $buffer;
       flush();
     }
     fclose($fpOrigin);
  }
}
