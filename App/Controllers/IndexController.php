<?php

namespace App\Controllers;

use Config\Controller\Action;

class IndexController extends Action
{

    public function index()
    {
        $this->render("Index/index.phtml", "layoutPadrao3");
    }

    public function default()
    {
        $this->render("Erro/index.phtml", "layoutPadrao2");
    }
}
