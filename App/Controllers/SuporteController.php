<?php

namespace App\Controllers;

use Config\Controller\Action;

class SuporteController extends Action
{

    public function politicas()
    {
        $this->render("Suporte/privacidade.phtml", "layoutPadrao3");
    }

    public function termos()
    {
        $this->render("Suporte/termos.phtml", "layoutPadrao3");
    }

    public function trocas()
    {
        $this->render("Suporte/trocas.phtml", "layoutPadrao3");
    }

    public function sobre()
    {
        $this->render("Suporte/sobre.phtml", "layoutPadrao3");
    }
}
