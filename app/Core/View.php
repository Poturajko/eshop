<?php


namespace App;


class View
{
    public string $title = '';

    protected function renderOnlyView($view, array $params = [])
    {
        extract($params, EXTR_OVERWRITE);
        ob_start();
        require __DIR__ . "/../resources/views/$view.php";
        return ob_get_clean();
    }

    public function renderView($layoutName, $view, array $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        ob_start();
        include_once __DIR__ ."/../resources/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
}