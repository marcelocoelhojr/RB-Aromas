<?php

namespace App\Controllers;

use App\Models\Produto;
use Config\Controller\Action;

class ProdutosController extends Action
{

    protected $dados = null;
    protected $cad = null;

    public function index()
    {
        $produto = new Produto();
        $this->dados = $produto->readProduto();
        $this->render("Produto/index.phtml", "layoutAuth");
    }


    public function cadastroproduto()
    {
        if ($_SESSION['sId'] == "admin") {
            $this->render("Produto/cadastro.phtml", "layoutAdmin");
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function registrar()
    {

        $produto = new Produto();
        $produto->__set("nome", $_POST['nome']);
        $produto->__set("categoria", $_POST['categoria']);
        $produto->__set("descricao", $_POST['descricao']);
        $produto->__set("img", $_POST['img']);
        $produto->__set("EstoqueQtd", $_POST['EstoqueQtd']);
        $produto->__set("preco", $_POST['preco']);


        if ($produto->validarCadastro()) {
            $produto->createProduto();
        } else {
            $this->cad['formRetorno'] = $_POST;
        }

        $this->render("Produto/cadastro.phtml", "layoutAdmin");
    }

    public function listaProdutos()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] == "admin") {
                $produto = new Produto();
                $this->dados = $produto->readProduto();
                $this->render("Produto/listaProduto.phtml", "layoutAdmin");
            } else {
                $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
                $this->render("Auth/login.phtml", "layoutAuth");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function delete()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if (isset($_POST['id'])) {
                $produto = new Produto();
                $produto->__set('CodProduto', $_POST['id']);
                echo $_POST['id'];
                $produto->excluirProduto();
                header("listaprodutos");
                $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Produto excluido com sucesso!</div>";
            } else {
                $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">ID do produto não informado!</div>";
            }
            header("location: /listaprodutos");
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }


    public function admExecAtualizarProduto()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] == "admin") {
                $produto = new Produto();
                $produto->__set("id", $_POST['id']);
                $this->dados = $produto->selecionarProduto();
                $this->render("Produto/atualizar.phtml", "layoutAdmin");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function execAlterarProduto()
    {
        if (isset($_SESSION['sId']) && isset($_SESSION['sNome'])) {
            if ($_SESSION['sId'] == "admin") {
                $prod = new Produto();
                echo $_POST['id'];
                $prod->alterar($_POST['alter'], $_POST['campo'], $_POST['id']);
                $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Alterado com sucesso!</div>";
                header("location: /listaprodutos");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Você não possui permissão para acessar está página!</div>";
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    public function buscar()
    {
        $produto = new Produto();
        $this->dados = $produto->buscar($_GET['busca']);
        if ($this->dados == null) {
            header("location: /produtos");
        } else {
            $this->render("Produto/busca.phtml", "layoutAuth");
        }
    }
}
