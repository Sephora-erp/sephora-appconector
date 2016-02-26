<?php

class basicapp {

    //Basic activation data
    public $basic = [
        'name' => 'Basic app chat',
        'description' => 'Módulo de Gestión orientado a el app básica (Chat)',
        'version' => '1.0.1',
        'vendor' => 'Inforfenix',
        'package' => 'sephora.basic.app',
        'min-sephora' => '0.0.1',
        'max-sephora' => '0.0.1',
        'icon' => '',
        'has_triggers' => 0,
        'has_hooks' => 1
    ];
    //Routes data
    public $routes = [
        0 => [
            'type' => 'GET',
            'url' => '/chat',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@actionIndex'
        ],
        1 => [
            'type' => 'POST',
            'url' => '/chat/getThreads',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxGetThread'
        ],
        2 => [
            'type' => 'POST',
            'url' => '/chat/getCount',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxGetCount'
        ],
        3 => [
            'type' => 'POST',
            'url' => '/chat/getMessages',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxfetchMessages'
        ],
        4 => [
            'type' => 'POST',
            'url' => '/chat/sendMessage',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxSendMessage'
        ]
    ];
    //Unsafe  (out of the laravel logged in middleware security)
    public $unsafeRoutes = [
        1 => [
            'type' => 'GET',
            'url' => '/chat/app/getuuid',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxGetUUID'
        ],
        2 => [
            'type' => 'POST',
            'url' => '/chat/app/getMessages',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxAppFetchMessages'
        ],
        3 => [
            'type' => 'POST',
            'url' => '/chat/app/sendMessage',
            'action' => '\App\modules\basicapp\core\controllers\ChatController@ajaxAppSendMessage'
        ]
    ];
    //Menus
    public $menus = [
         0 => [
            'type' => 'top',
            'title' => 'Chat',
            'uuid' => 'basicapp_chat',
            'icon' => 'fa fa-whatsapp',
            'url' => '/chat',
            'package' => 'sephora.basic.app'
        ],
        1 => [
            'type' => 'top',
            'title' => 'Noticias',
            'uuid' => 'basicapp_posts',
            'icon' => 'fa fa-align-center',
            'url' => '/posts',
            'package' => 'sephora.basic.app'
        ],
    ];
    //Hooks declaration
    public $hooks = [
        0 => [
            'container' => 'afterCustomer',
        ],
        1 => [
            'container' => 'headerCss',
        ]
    ];

}
