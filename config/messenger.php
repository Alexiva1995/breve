<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Messenger Default User Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default User model.
    |
    */

    'user' => [
        'model' => 'App\Models\User'
    ],

    /*
    |--------------------------------------------------------------------------
    | Messenger Pusher Keys
    |--------------------------------------------------------------------------
    |
    | This option defines pusher keys.
    |
    */

    'pusher' => [
        'app_id'     => '1088227',
        'app_key'    => '70633ff8ae20c2f8780b',
        'app_secret' => '3acbc94cc2b0ea736916',
        'options' => [
            'cluster'   => 'mt1',
            'encrypted' => true
        ]
    ],
];
