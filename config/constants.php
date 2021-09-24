<?php
return [
    'const' => [
        define('DS', DIRECTORY_SEPARATOR),
        define('BASE_DIR', dirname(__DIR__)),
        define('CORE_DIR', BASE_DIR . DS . 'app/Core'),
        define('HELPERS_DIR', CORE_DIR . DS . 'Helpers'),
        define('STORAGE_DIR', BASE_DIR . DS . 'storage'),
        define('ENV', BASE_DIR . DS . '.env'),
    ],
];
