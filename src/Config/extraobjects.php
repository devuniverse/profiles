<?php
// print_r($ppuser);
// $fx

if(!function_exists('fallback_notifications')){
  function fallback_notifications($user){
    // isset($user['metas']['phone'])
    // return '
    // <div class="cell nexus--1-2 field-cont">
    //   <div class="thelabel cell nexus--1-3 hand--1-3">
    //     <label for="userinfo[meta][notify_tag_task]">'._i("Tagged in Task").'</label>
    //   </div><div class="theinput cell nexus--2-3 hand--2-3">
    //   <label class="switch">
    //     <input name="userinfo[meta][notify_tag_task]" type="checkbox" checked>
    //     <span class="sliderx round"></span>
    //   </label>
    //   </div>
    // </div>';
  }
}
if(!function_exists('fallback_billing')){
  function fallback_billing($user){
    return '';
  }
}
$extras = [
  "notifications"=>[
    "name" => _i("Notifications"),
    "slug" => "notifications",
    "callback" => fallback_notifications($ppuser),
    "iconclasses"=>"fa fa-bell text-success",
  ],
  "billing"=>[
    "name" => _i("Billing"),
    "slug" => "billing",
    "callback" => fallback_billing($ppuser),
    "iconclasses"=>"fa fa-money-check-alt text-success",
  ],
];
