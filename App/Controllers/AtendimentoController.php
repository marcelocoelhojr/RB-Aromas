<?php

namespace App\Controllers;

use App\Models\Atendimento;
use Config\Controller\Action;

class AtendimentoController extends Action
{

    protected $dados = null;

    public function atendimento()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] == "admin") {
                $atendimento = new Atendimento();
                $this->dados = $atendimento->mensagem();
                $this->render("Admin/atendimento.phtml", "layoutAdmin");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function responderMensagem()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] == "admin") {
                $atendimento = new Atendimento();
                $this->dados = $atendimento->mensagem();
                $this->render("Admin/responder.phtml", "layoutAdmin");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }
}
