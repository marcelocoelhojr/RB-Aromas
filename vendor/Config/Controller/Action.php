<?php

namespace Config\Controller;

abstract class Action
{

    protected $view = null;

    protected function render($view, $layout)
    {
        $this->view = $view;
        if (file_exists("../App/Views/$layout.phtml")) {
            require_once("../App/Views/$layout.phtml");
        } else {
            $this->content();
        }
    }

    protected function content()
    {
        require_once("../App/Views/$this->view");
    }
}
