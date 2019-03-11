@extends(Config::get('profiles.master_file_extend'))

@section(Config::get('profiles.yields.head'))
    @if(Config::get('profiles.includes.fontawesome'))
      <link rel="stylesheet" href="{{ url('/profiles/assets/fontawesome/css/all.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/dropzone.css') }}">
    @if(Config::get('profiles.includes.animate'))
      <link rel="stylesheet" href="{{ url('/profiles/assets/css/animate.css') }}">
    @endif
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/nexus.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/styled.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/lite-editor.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/tablet.css') }}">
    <link rel="stylesheet" href="{{ url('/profiles/assets/css/desktop.css') }}">
@endsection

@section(Config::get('profiles.yields.footer'))
  <script type="text/javascript">
    var profilePath = "/<?php echo \Config::get('profiles.profiles_url')?>";
  </script>
  @if( Config::get('profiles.includes.jquery'))
    <script src="{{ url('/profiles/assets/js/jquery.js') }}"></script>
  @endif
    <script src="{{ url('/profiles/assets/js/profile.js') }}"></script>
    <script src="{{ url('/profiles/assets/js/lite-editor.js') }}"></script>
    <script>
	window.addEventListener('DOMContentLoaded',function(){
        var editor = new LiteEditor('.js-lite-editor', {
           nl2br: true
        });
    });
	</script>
@endsection

@section(Config::get('profiles.yields.profiles-content'))

  <div class="full-profile">
    <!-- <div class="alert alert-success">

    </div> -->
    <div class="profile">
      <div class="cell nexus--1-6">
        <div class="profile-inner">
          <div class="photo">
            <input type="file" accept="image/*">
            <div class="photo__helper">
                <div class="photo__frame photo__frame--circle">
                  <canvas class="photo__canvas"></canvas>
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
            <div class="photo__options hide">
                <div class="photo__zoom">
                    <input type="range" class="zoom-handler">
                </div><a href="javascript:;" class="remove"><i class="fa fa-trash"></i></a>
            </div>
          </div>
          <form class="update-profile-form" action="/{{ \Config::get('profiles.profiles_url') }}/update" method="post">
            @csrf
            <input type="hidden" name="imageurldata" value="">
            <button type="submit" class="btn btn-primary" id="previewBtn"><?php echo _i('Preview'); ?></button>
          </form>
          <div class="nav-holder">
            <ul>
              <li class="selected" data-content="about"><i class="fa fa-info-circle" aria-hidden="true"></i> <span><?php echo _i('About Me'); ?></span></li>
              <li  data-content="security"><i class="fa fa-lock" aria-hidden="true"></i> <span><?php echo _i('Security'); ?></span></li>
              <li  data-content="other"><i class="fa fa-life-ring" aria-hidden="true"></i> <span><?php echo _i('Other'); ?></span></li>
            </ul>
          </div>
        </div>
      </div><div class="cell nexus--5-6 profile-sidemain">
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

                <h1>{{ _i("Biography") }}</h1>

                <textarea class='js-lite-editor fullwidth'></textarea>
              </div>
            </div>
          </div>
          <div class="hidden tab-content content-security">
            <div class="">
              <h1>{{ _i("UPdate Password") }}</h1>
              <div class="row-content">
                <div class="inputcontent row">
                  <label for="currentpassword"  class="col col-md-4">{{ _i("Current password") }}</label>
                  <input class="col col-md-8" type="password" name="currentpassword" value="">
                </div>
                <div class="inputcontent row">
                  <label for="newpassword" class="col col-md-4">{{ _i("New Password") }}</label>
                  <input type="password" class="col col-md-8" name="newpassword" value="">
                </div>
                <div class="inputcontent row">
                  <label for="newpassword" class="col col-md-4">{{ _i("Password confirm") }}</label>
                  <input type="password"  class="col col-md-8" name="passwordconfirm" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="hidden tab-content content-other">

          </div>
        </div>
      </div>
    </div>

    <div class="previews-container">
      <img src="" alt="" class="preview">
      <!-- <img src="" alt="" class="preview preview--rounded"> -->
    </div>
    <!-- <button type="button" class="btn btn-primary" id="uploadBtn">Upload Example</button> -->
  </div>

@endsection
