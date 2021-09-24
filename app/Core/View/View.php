<?php


namespace App\Core\View;



class View implements IView
{
    public string $title = '';

    protected function renderOnlyView($view, array $params = [])
    {
        extract($params, EXTR_OVERWRITE);
        ob_start();
        require BASE_DIR . DS . "resources/views/$view.php";
        return ob_get_clean();
    }

    public function renderView($layoutName, $view, array $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        ob_start();
        include_once BASE_DIR . DS . "resources/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
}