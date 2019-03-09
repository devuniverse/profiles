<?php
return [
  /**
   *|
   */
  "profiles_default_disk" => "public",
  /**
   *|
   */
  "files_upload_path"       => "uploads",
  /**
   *|
   */
  "files_upload_thumb_path" => "uploads/thumbnails",
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
  /**
   *|
   */
  "files_per_page" => 25,

  /**
  *|  #1  : defaults to public. Like Laravel
  *|  #2  : File systems in this setting MUST be mutually exclusive.
  *|        That is, Only one can have the default value to true
  *|
   */
  "profiles_storage_disk" => [

      "public"  => [

      ],
      "s3" => [
        "cname_path" => "", //  s3.amazonaws.com/bucket
        "cname_url"  => "",  //  bucket.s3.amazonaws.com
      ]
    ],

  // "default_file_system" => "public",
  /**
   *|
   */
  'yields' => [
      'head'   => 'css',
      'footer' => 'js',
      'profiles-content'=>'profiles-content'
  ],
  /**
   *|
   */
  'includes' => [
      'jquery'   => true,
      'animate' => true,
      'fontawesome' => true
  ],

  /**
   *|
   */
  'separate_uploads' => [

  ],
  'override_default' => false
];
