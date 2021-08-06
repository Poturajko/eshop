<?php


namespace App;


class View
{
    private string $path;
    private string $layout = 'master';

    public function __construct(array $callback)
    {
        $this->path = $this->makePath($callback);
    }

    public function render(string $title, array $vars = []): void
    {
        extract($vars, EXTR_OVERWRITE);
        $path = __DIR__ . '/../resources/views/' . $this->path . '.php';
        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require __DIR__ . '/../resources/views/layouts/' . $this->layout . '.php';
        }
    }

    private function makePath(array $callback): string
    {
        $namespace = array_shift($callback);
        $controller = substr(strrchr($namespace, '\\'), 1);
        $cutController = lcfirst(strstr($controller, 'Controller', true));
        $actionName = array_shift($callback);

        return $cutController . '/' . $actionName;
    }
}