<?php

return [
    \App\Models\User::class => Di\autowire(\App\Models\User::class),
    \App\Core\Application::class => DI\autowire(\App\Core\Application::class)
        ->constructorParameter('userClass', DI\get(\App\Models\User::class)),
];