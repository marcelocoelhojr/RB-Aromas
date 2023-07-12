<?php

namespace App\Services;

use App\Models\Usuario;
use Config\Controller\Action;

class AuthService extends Action
{
    protected array $dados;

    /**
     * Authentication view
     *
     * @return void
     */
    public function autenticate(): void
    {
        $this->render("Auth/login.phtml", "layoutAuth");
    }

    /**
     * Authentication
     *
     * @return void
     */
    public function login(): void
    {
        $userModel = new Usuario();
        $userModel->__set('cpf', $_POST['cpf']);
        $userModel->__set('senha', $_POST['senha']);
        if (count($userModel->autenticar()) == 1) {
            $_SESSION['sId'] = $userModel->__get('cpf');
            $_SESSION['sNome'] = $userModel->__get('nome');
            $this->loginRedirect();
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">CPF e/ou Senha incorreto(s)!</div>";
            $this->dados['formRetorno'] = $_POST;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Login redirect
     *
     * @void
     */
    private function loginRedirect(): void
    {
        if ($_SESSION['sId'] == "admin") {
            header("location: /restritoadmin");
        } else {
            header("location: /restrito");
        }
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout(): void
    {
        session_destroy();
        header("location: /");
    }
}
