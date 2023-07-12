<?php

namespace App\Controllers;

use App\Models\Usuario;
use Config\Controller\Action;

class UsuariosController extends Action
{

    protected $dados = null;

    public function cadastro()
    {
        $this->render("Usuario/cadastro.phtml", "layoutAuth");
    }

    public function registrar()
    {

        $usuario = new Usuario();

        $usuario->__set("nome", $_POST['nome']);
        $usuario->__set("cpf", $_POST['cpf']);
        $usuario->__set("email", $_POST['email']);
        $usuario->__set("senha", $_POST['senha']);
        $usuario->__set("endereco", $_POST['endereco']);
        $usuario->__set("uf", $_POST['uf']);
        $usuario->__set("cidade", $_POST['cidade']);
        $usuario->__set("numero", $_POST['numero']);
        $usuario->__set("tel", $_POST['tel']);
        $usuario->__set("cep", $_POST['cep']);
        $usuario->__set("sexo", $_POST['sexo']);
        $usuario->__set("data", $_POST['data']);
        $usuario->__set("rg", $_POST['rg']);
        $usuario->__set("confirmarSenha", $_POST['confirmarSenha']);


        if ($usuario->validarCadastro()) {
            $usuario->createusuario();
        } else {
            $this->dados['formRetorno'] = $_POST;
        }


        $this->render("Usuario/cadastro.phtml", "layoutPadrao3");
    }

    public function dados()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] != "admin") {
                $user = new Usuario();
                $user->__set("id", $_SESSION['sId']);
                $this->dados = $user->buscarUsuario();
                $this->render("Usuario/meusDados.phtml", "layoutAuth");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function alterar()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] != "admin") {
                $user = new Usuario();
                $user->__set("id", $_SESSION['sId']);
                $this->dados = $user->buscarUsuario();
                $this->render("Usuario/alterarDados.phtml", "layoutAuth");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function execAlterarDados()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] != "admin") {
                $user = new Usuario();
                $user->alterar($_POST['alter'], $_POST['campo']);
                $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Alterado com sucesso!</div>";
                header("location: /alterarDados");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }
}
