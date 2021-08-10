<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

use DevCoder\DotEnv;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

(new DotEnv(__DIR__ . '/../.env'))->load();

$container = require __DIR__ . '/../bootstrap/container.php';

require __DIR__ . '/../routes/web.php';