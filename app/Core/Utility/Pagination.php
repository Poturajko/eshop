<?php

namespace App\Core\Utility;

use App\Core\Request;

class Pagination
{
    private $max = 10;

    private $current_page;

    private $total;

    private $limit;

    public function __construct(Request $request, $total, $limit)
    {
        $this->total = $total;

        $this->limit = $limit;

        $this->amount = $this->amount();

        $currentPage = $request->getBody()['page'] ?: 1;

        $this->setCurrentPage($currentPage);
    }

    public function get()
    {
        $links = null;

        $limits = $this->limits();

        $html = '<nav>' . '<ul class="pagination">';
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
            if ($page == $this->current_page) {
                $links .= '<li class="active"><a href="/">' . $page . '</a></li>';
            } else {
                $links .= $this->generateHtml($page);
            }
        }

        if (!is_null($links)) {
            if ($this->current_page > 1) {
                $links = $this->generateHtml(1, '&lt;') . $links;
            }

            if ($this->current_page < $this->amount) {
                $links .= $this->generateHtml($this->amount, '&gt;');
            }
        }

        $html .= $links . '</ul>' . '</nav>';

        return $html;
    }

    private function generateHtml($page, $text = null)
    {
        if (!$text) {
            $text = $page;
        }

        if ($_SERVER['REQUEST_URI'] === '/') {
            $currentURI = $_SERVER['REQUEST_URI'] . '?page=' . $page;
        } else {
            $currentURI = '/';
        }

        return '<li><a href="' . $currentURI . '">' . $text . '</a></li>';
    }

    private function limits()
    {
        $left = $this->current_page - round($this->max / 2);

        $start = $left > 0 ? $left : 1;

        if ($start + $this->max <= $this->amount) {
            $end = $start > 1 ? $start + $this->max : $this->max;
        } else {
            $end = $this->amount;
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }

        return array($start, $end);
    }

    private function setCurrentPage($currentPage)
    {
        $this->current_page = $currentPage;

        if ($this->current_page > 0) {
            if ($this->current_page > $this->amount) {
                $this->current_page = $this->amount;
            }
        } else {
            $this->current_page = 1;
        }
    }

    private function amount()
    {
        return ceil($this->total / $this->limit);
    }

}
