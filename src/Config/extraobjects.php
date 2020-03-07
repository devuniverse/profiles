<?php
// print_r($ppuser);
// $fx


if(!function_exists('fallback_notifications')){
  function fallback_notifications($user){
  $html = '<div class="cell nexus--1-2 field-cont">
            <div class="thelabel cell nexus--1-3 hand--1-3">
              <label for="userinfo[meta][notify_tag_task]">'._i("Tagged in Task").'</label>
            </div><div class="theinput cell nexus--2-3 hand--2-3">
            <div class="container">
              <label class="switch" for="checkbox">
                <input type="checkbox" name="userinfo[meta][notify_tag_task]" id="meta-notify_tag_task" '.($user->metas["notify_tag_task"]==1 ? "checked":"").' value="1" />
                <div class="slider round"></div>
              </label>
            </div>
            </div>
          </div>';
    return $html;
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
