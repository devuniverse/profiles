<?php
return [

  /**
   *|
   */
  "profiles_url_protocol"      => "https",
  /**
   *|
   */
  "profiles_url"      => "profile",
  /**
   *|
   */
  "master_file_extend" => "profiles::main",

  // "default_file_system" => "public",
  /**
   *|
   */
  'yields' => [
      'head'   => 'css',
      'footer' => 'js',
      'profiles-content'=>'content'
  ],
  /**
   *|
   */
  'includes' => [
      'jquery'   => true,
      'animate' => true,
      'fontawesome' => true
  ],
  'override_default' => false
];
