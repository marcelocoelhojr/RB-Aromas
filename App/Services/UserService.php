<?php

namespace App\Services;

use App\Models\User;
use Config\Controller\Action;

class UserService extends Action
{
    public array $dados;

    /**
     * Register view
     */
    public function registerView(): void
    {
        $this->render("Usuario/cadastro.phtml", "layoutAuth");
    }

    /**
     * Register user
     *
     * @return void
     */
    public function register(): void
    {
        $userModel = new User();
        $userModel->__set("nome", $_POST['nome']);
        $userModel->__set("cpf", $_POST['cpf']);
        $userModel->__set("email", $_POST['email']);
        $userModel->__set("senha", $_POST['senha']);
        $userModel->__set("endereco", $_POST['endereco']);
        $userModel->__set("uf", $_POST['uf']);
        $userModel->__set("cidade", $_POST['cidade']);
        $userModel->__set("numero", $_POST['numero']);
        $userModel->__set("tel", $_POST['tel']);
        $userModel->__set("cep", $_POST['cep']);
        $userModel->__set("sexo", $_POST['sexo']);
        $userModel->__set("data", $_POST['data']);
        $userModel->__set("rg", $_POST['rg']);
        $userModel->__set("confirmarSenha", $_POST['confirmarSenha']);
        if ($userModel->validarCadastro()) {
            $userModel->create();
        } else {
            $this->dados['formRetorno'] = $_POST;
        }
        $this->render("Usuario/cadastro.phtml", "layoutPadrao3");
    }

    /**
     * Get user data
     *
     * @return void
     */
    public function getUserData(): void
    {
        if ($_SESSION['sId'] == "admin") {
            return;
        }

        if (validateUser()) {
            $userModel = new User();
            $userModel->__set("id", $_SESSION['sId']);
            $this->dados = $userModel->buscarUsuario();
            $this->render("Usuario/meusDados.phtml", "layoutAuth");
        } else {
            $this->loginRedirect();
        }
    }

    /**
     * Update view
     *
     * @return void
     */
    public function updateView(): void
    {
        if ($_SESSION['sId'] == "admin") {
            return;
        }

        if (validateUser()) {
            $userModel = new User();
            $userModel->__set("id", $_SESSION['sId']);
            $this->dados = $userModel->buscarUsuario();
            $this->render("Usuario/alterarDados.phtml", "layoutAuth");
        } else {
            $this->loginRedirect();
        }
    }

    /**
     * Update user data
     *
     * @return void
     */
    public function updateUser(): void
    {
        if ($_SESSION['sId'] == "admin") {
            return;
        }

        if (validateUser()) {
            $user = new User();
            $user->alterar($_POST['alter'], $_POST['campo']);
            $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Alterado com sucesso!</div>";
            header("location: /alterarDados");
        } else {
            $this->loginRedirect();
        }
    }

    /**
     * Redirect user without permission
     *
     * @return void
     */
    private function loginRedirect(): void
    {
        $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
            Você não possui permissão para acessar está página!</div>";
        $this->render("Auth/login.phtml", "layoutAuth");
    }
}
