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
use Hash;
use Crypt;
use Illuminate\Support\Facades\Storage;
use Devuniverse\Profiles\Models\Usermeta;

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
        $other = ['avatar_url'=> url($request->profilepref.'/'.Config::get("profiles.profiles_url").'/display?ref='.\Crypt::encryptString($user->id)) ];
      }else{
        $message = _i('Avatar may not be saved properly');
        $msgtype = 0;
        $other = [];
      }
    }else{
      $message = _i('We encountered some ');
      $msgtype = 0;
      $other = [];
    }
    return response()->json(['message'=>$message, "msgtype"=>$msgtype, 'other'=>$other]);
  }

  public function display(Request $request){
    $requested = \Crypt::decryptString($request->ref);
    $user = \App\User::find($requested);
    if($user->id != Auth::user()->id){
      $message = _i('You are not viewing your own account');
      $msgtype = 0;
    }else{
      $s3 = Storage::disk('s3');
      $exists = $s3->exists($user->profile_picture);
      if($exists){
        $filePath = "https://".env('AWS_BUCKET').".s3.".env('AWS_DEFAULT_REGION').".amazonaws.com/".$user->profile_picture;
      }else{
        $filePath = public_path("/images/user.png");
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
  /**
   * @method updateUserInfo
   * @param $request
   * @description : Update user information
   * @return binary / redirect
   */
  public function updateUserInfo(Request $request){
    $user = Auth::user();
    $useri = $request->userinfo;

    $lastname = $useri['user']['lastname'];
    $currentpass = !empty($useri['user']['currentpassword']) ? $useri['user']['currentpassword'] : "";
    $newpass = !empty($useri['user']['newpassword']) ? $useri['user']['newpassword'] : "";
    $confirmpass = !empty($useri['user']['passwordconfirm']) ? $useri['user']['passwordconfirm'] : "";
    /**
     * If the password is being changed
     */
    if(!empty($currentpass) && !empty($newpass) && !empty($confirmpass)){
      if (!\Hash::check($currentpass, $user->password)) {
        $message = _i("Current password is not correct");
        $msgtype = 0;
      }else{
        if($newpass != $confirmpass){
          $message = _i("Your passwords do not match");
          $msgtype = 0;
        }else{
          $user->password = Hash::make($newpass);
          $user->save();
          $message = _i("Password updated successfully");
          $msgtype = 1;
          Auth::logout();
        }
      } 
    }
    /**
     * The password hasn't changed
     */
    else
    {
      $userinfox = $useri['user'];
      $usermeta = $useri['meta'];
      foreach($userinfox as $u => $x){
        $user->$u = $x;
        $user->save();
      }

      foreach($usermeta as $x => $meta){
        if(!empty($meta)){
          $umeta = Usermeta::where("user_id",$user->id)->where("meta_key",$x)->first();
          if($umeta){
            $umeta->meta_value = $meta;
            $saved = $umeta->save();
          }else{
            $umetaNew = new Usermeta();
            $umetaNew->user_id     = $user->id;
            $umetaNew->meta_key    = $x;
            $umetaNew->meta_value  = $meta;
            $saved = $umetaNew->save();
          }
        }
      }
      $message = _i("Account saved successfully");
      $msgtype = 1;
    }

    return redirect()->back()->with('theresponse', ["message"=>$message, "msgtype"=>$msgtype]);

  }
}
