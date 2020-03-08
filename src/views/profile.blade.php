@extends(Config::get('profiles.master_file_extend'))

@section(Config::get('profiles.yields.head'))
    @if(Config::get('profiles.includes.fontawesome'))
      <link rel="stylesheet" href="{{ url('/profiles/assets/fontawesome/css/all.css') }}">
    @endif
    @if(Config::get('profiles.includes.animate'))
      <link rel="stylesheet" href="{{ url('/profiles/assets/css/animate.css') }}">
    @endif
    @if(Config::get('profiles.includes.animate'))
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/nexus.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/styled.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/lite-editor.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/tablet.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/desktop.css') }}">
@endsection

<?php
  /* Depending on your route settings,remember to add this piece of code at the main layout file
    * profilePrefix is optional, but may be userful in dynamic settings
    * Define it before including the footer yields because the footer JS depends on it if it's defined
    *
    *
  <script type="text/javascript">
  var profilePrefix = "<?php echo '/'.\Request()->lang.'/manage' ?>";
  </script>

  @yield(Config::get('profiles.yields.footer'))
   */
?>
@section(Config::get('profiles.yields.footer'))
  <script type="text/javascript">
  if(profilePrefix!==""){
    var profilePath = profilePrefix + "<?php echo '/'.\Config::get('profiles.profiles_url'); ?>";
  }else{
    var profilePath = "<?php echo '/'.\Config::get('profiles.profiles_url'); ?>";
  }
  <?php
  $s3 = Storage::disk('s3');
  $exists = $s3->exists($user->profile_picture);
   if($exists){ ?>
     if(profilePrefix!==""){
       var userAvPath = profilePrefix +"/<?php echo Config::get("profiles.profiles_url"); ?>/display?ref=<?php echo \Crypt::encryptString(\Auth::user()->id); ?>";
     }else{
       var userAvPath = "/<?php echo Config::get("profiles.profiles_url"); ?>/display?ref=<?php echo \Crypt::encryptString(\Auth::user()->id); ?>";
     }
     var avPath = userAvPath;
   <?php }else{?>
     var userAvPath=null;
     var avPath = '/images/user.png';
   <?php } ?>
    var notMatched = "{{ _i('The passwords do not match') }} ";
  </script>
  @if( Config::get('profiles.includes.jquery'))
    <script src="{{ url('/profiles/assets/js/jquery.js') }}"></script>
  @endif
    <script src="{{ url('/profiles/assets/js/profile.js') }}"></script>
    <!-- <script src="{{ url('/profiles/assets/js/lite-editor.js') }}"></script>
    <script>
	window.addEventListener('DOMContentLoaded',function(){
        var editor = new LiteEditor('.js-lite-editor', {
           nl2br: true
        });
    });
	</script> -->
@endsection

@section(Config::get('profiles.yields.profiles-content'))
<?php
$profiles = new \Devuniverse\Profiles\Models\Profiles();
$extraMenus = $profiles::extras();
 ?>
  <div class="full-profile">
    <!-- <div class="alert alert-success">

    </div> -->
    <div class="profile">
      <form class="update-profileinfo" action="{{ route('profiles.update.info', \Request()->route()->parameters() ) }}" method="post">
        @csrf
        <input type="hidden" name="backto" value="{{ url()->current() }}"/>
        <div class="profile-left cell nexus--1-4">
          <div class="profile-inner">
            <div class="photo">
              <input type="file" accept="image/*">
              <div class="photo__helper">
                <i class="fa fa-pen editimage"></i>
                <div class="photo__frame photo__frame--circle">
                  <canvas class="photo__canvas" id="photo__canvas"></canvas>
                  <div class="message is-empty">
                    <p class="message--desktop">{{ _i('Drop your photo here or browse your computer') }}.</p>
                    <p class="message--mobile">{{ _i('Tap here to select your picture') }}.</p>
                  </div>
                  <div class="message is-loading">
                    <i class="fa fa-2x fa-spin fa-spinner"></i>
                  </div>
                  <div class="message is-dragover">
                    <i class="fa fa-2x fa-cloud-upload"></i>
                    <p>{{ _i('Drop your photo') }}</p>
                  </div>
                  <div class="message is-wrong-file-type">
                    <p>{{ _i('Only images allowed') }}.</p>
                    <p class="message--desktop">{{ _i('Drop your photo here or browse your computer') }}.</p>
                    <p class="message--mobile">{{ _i('Tap here to select your picture') }}.</p>
                  </div>
                  <div class="message is-wrong-image-size">
                    <p>{{ _i('Your photo must be larger than 350px') }}.</p>
                  </div>
                </div>
              </div>
              <div class="photo__options animated slideInUp hidden">
                <div class="photo__zoom">
                  <input type="range" class="zoom-handler">
                </div><a href="javascript:;" class="remove"><i class="fa fa-trash"></i></a>
                <button type="submit" class="btn btn-primary animated slideInUp" id="previewBtn">{{ _i('Update Avatar') }}</button>
              </div>
            </div>
            <div class="nav-holder">
              <ul>
                <li class="selected" data-content="about"><div class="the-left"><i class="fa fa-info-circle text-primary" aria-hidden="true"></i></div><div class="the-right"><span><?php echo _i('About Me'); ?></span></div></li>
                <li  data-content="security"><div class="the-left"><i class="fa fa-lock text-danger" aria-hidden="true"></i></div><div class="the-right"><span><?php echo _i('Security'); ?></span></div></li>
                <li  data-content="socialnetworks"><div class="the-left"><i class="fa fa-life-ring text-info" aria-hidden="true"></i></div><div class="the-right"><span><?php echo _i('Social Network'); ?></span></div></li>
                @foreach($extraMenus as $ex => $obj)
                <li  data-content="{{ $obj['slug'] }}"><div class="the-left"><i class="{{ $obj['iconclasses'] }}" aria-hidden="true"></i></div><div class="the-right"><span><?php echo $obj['name']; ?></span></div></li>
                @endforeach
              </ul>
            </div>
          </div>
        </div><div class="cell nexus--3-4 profile-sidemain">
          <div class="profile-inner">
            <div class="messages-alerts">
              @if( !empty(Session::get('theresponse')) )
              @if(Session::get('theresponse')["msgtype"]==1)
              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Well done!</strong> {{ Session::get('theresponse')["message"] }}
              </div>
              @else
              <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Error!</strong> {{ Session::get('theresponse')["message"] }}
              </div>
              @endif
              @endif
            </div>
            <div class="tab-content content-about">
              <div class="row-content">
                <div class="inputcontent row">
                  <h1>{{ _i("Basic Information") }}</h1>
                  <div class="infosection basic-inner">
                    <div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][email]">{{ _i("Email") }}</label>
                      </div><div class="theinput cell nexus--2-3 hand--2-3">
                        <input type="text" disabled name="userinfo[user][email]" value="{{ $ppuser->email }}">
                      </div>
                    </div><div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][firstname]">{{ _i("Firstname") }}</label>
                      </div><div class="theinput cell nexus--2-3 hand--2-3">
                        <input type="text" required name="userinfo[user][firstname]" value="{{ $ppuser->name }}">
                      </div>
                    </div><div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][lastname]">{{ _i("Lastname") }}</label>
                      </div><div class="theinput cell nexus--2-3 hand--2-3">
                        <input type="text" name="userinfo[user][lastname]" value="{{ $ppuser->lastname }}">
                      </div>
                    </div><div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][phone]">{{ _i("Telephone") }}</label>
                      </div><div class="theinput cell nexus--2-3 hand--2-3">
                        <input type="number" required name="userinfo[meta][phone]" value="{{ isset($ppuser->metas['phone']) ? $ppuser->metas['phone']:'' }}">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="hidden tab-content content-security">
              <div class="row-content">
                <div class="inputcontent row">
                  <h1>{{ _i("Update password") }}</h1>
                  <div class="infosection security-inner">
                    <div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][email]">{{ _i("Current password") }}</label>
                      </div><div class="theinput xpass cell nexus--2-3 hand--2-3">
                        <input type="password" disabled name="userinfo[user][currentpassword]" value="">
                      </div>
                    </div><div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][email]">{{ _i("New Password") }}</label>
                      </div><div class="theinput xpass cell nexus--2-3 hand--2-3">
                        <input type="password" disabled name="userinfo[user][newpassword]" value="">
                      </div>
                    </div><div class="cell nexus--1-2 field-cont">
                      <div class="thelabel cell nexus--1-3 hand--1-3">
                        <label for="userinfo[user][email]">{{ _i("Confirm password") }}</label>
                      </div><div class="theinput xpass cell nexus--2-3 hand--2-3">
                        <input type="password" disabled name="userinfo[user][passwordconfirm]" value="">
                      </div>
                    </div>
                  </div>
                  <div class="action knowledge">
                    <div class="password messages hidden">
                      <div class="alert ">

                        <div class="alert-content"></div>
                      </div>
                    </div>
                    <p>{{ _i("Once you change your password, you will be logged out.") }}</p>
                    <p>{{ _i("You will need to log back in.") }}</p>
                  </div>
                </div>
              </div>
              </div>
              <div class="hidden tab-content content-socialnetworks">
                <div class="row-content">
                  <div class="inputcontent row">
                    <h1>{{ _i("Social Networks") }}</h1>
                    <div class="infosection socialnetworks-inner">
                      <div class="cell nexus--1-2 field-cont">
                        <div class="thelabel cell nexus--1-3 hand--1-3">
                          <label for="userinfo[meta][facebook]">{{ _i("Facebook") }}</label>
                        </div><div class="theinput cell nexus--2-3 hand--2-3">
                          <input type="text" name="userinfo[meta][facebook]" value="{{ isset($ppuser->metas['facebook']) ? $ppuser->metas['facebook']:'' }}">
                        </div>
                      </div><div class="cell nexus--1-2 field-cont">
                        <div class="thelabel cell nexus--1-3 hand--1-3">
                          <label for="userinfo[meta][twitter]">{{ _i("Twitter") }}</label>
                        </div><div class="theinput cell nexus--2-3 hand--2-3">
                          <input type="text" name="userinfo[meta][twitter]" value="{{ isset($ppuser->metas['twitter']) ? $ppuser->metas['twitter']:'' }}">
                        </div>
                      </div><div class="cell nexus--1-2 field-cont">
                        <div class="thelabel cell nexus--1-3 hand--1-3">
                          <label for="userinfo[meta][youtube]">{{ _i("Youtube") }}</label>
                        </div><div class="theinput cell nexus--2-3 hand--2-3">
                          <input type="text" name="userinfo[meta][youtube]" value="{{ isset($ppuser->metas['youtube']) ? $ppuser->metas['youtube']:'' }}">
                        </div>
                      </div><div class="cell nexus--1-2 field-cont">
                        <div class="thelabel cell nexus--1-3 hand--1-3">
                          <label for="userinfo[meta][github]">{{ _i("Github") }}</label>
                        </div><div class="theinput cell nexus--2-3 hand--2-3">
                          <input type="text" name="userinfo[meta][github]" value="{{ isset($ppuser->metas['github']) ? $ppuser->metas['github']:'' }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @foreach($extraMenus as $k=>$v)
              <div class="hidden tab-content content-{{ $k }}">
                <div class="row-content">
                  <div class="inputcontent row">
                    <?php
                      echo $v['callback'];
                    ?>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        <div class="updatecta">
          <button class="btn btn-primary" type="submit">{{ _i("Update") }}</button>
        </div>
        </div>
      </form>
    </div>

    <div class="previews-container">
    </div>
  </div>

@endsection
