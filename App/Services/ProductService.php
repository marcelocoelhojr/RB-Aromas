<?php

namespace App\Services;

use App\Models\Produto;
use Config\Controller\Action;

class ProductService extends Action
{
    const PERMITION_MESSAGE = "<div class=\"alert alert-danger\" role=\"alert\">
        Você não possui permissão para acessar está página!</div>";

    protected $dados = null;
    protected $cad = null;

    /**
     * List products view
     *
     * @return void
     */
    public function productsView(): void
    {
        $product = new Produto();
        $this->dados = $product->readProduto();
        $this->render("Produto/index.phtml", "layoutAuth");
    }

    /**
     * Register view product
     *
     * @return void
     */
    public function registerView(): void
    {
        if ($_SESSION['sId'] == "admin") {
            $this->render("Produto/cadastro.phtml", "layoutAdmin");
        } else {
            $_SESSION['msg'] = self::PERMITION_MESSAGE;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Register product
     *
     * @return void
     */
    public function register(): void
    {
        $productModel = new Produto();
        $productModel->__set("nome", $_POST['nome']);
        $productModel->__set("categoria", $_POST['categoria']);
        $productModel->__set("descricao", $_POST['descricao']);
        $productModel->__set("img", 'produto.jpeg');
        $productModel->__set("EstoqueQtd", $_POST['EstoqueQtd']);
        $productModel->__set("preco", $_POST['preco']);
        if ($productModel->validarCadastro()) {
            $productModel->createProduto();
        } else {
            $this->cad['formRetorno'] = $_POST;
        }
        $this->render("Produto/cadastro.phtml", "layoutAdmin");
    }

    /**
     * List products
     *
     * @return void
     */
    public function listProducts(): void
    {
        if (validateUser()) {
            if ($_SESSION['sId'] == "admin") {
                $productModel = new Produto();
                $this->dados = $productModel->readProduto();
                $this->render("Produto/listaProduto.phtml", "layoutAdmin");
            } else {
                $_SESSION['msg'] = self::PERMITION_MESSAGE;
                $this->render("Auth/login.phtml", "layoutAuth");
            }
        } else {
            $_SESSION['msg'] = self::PERMITION_MESSAGE;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Delete product by id
     *
     * @return void
     */
    public function delete(): void
    {
        if (validateUser()) {
            if (isset($_POST['id'])) {
                $productModel = new Produto();
                $productModel->__set('CodProduto', $_POST['id']);
                echo $_POST['id'];
                $productModel->excluirProduto();
                header("listaprodutos");
                $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">
                    Produto excluido com sucesso!</div>";
            } else {
                $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                    ID do produto não informado!</div>";
            }
            header("location: /listaprodutos");
        } else {
            $_SESSION['msg'] = self::PERMITION_MESSAGE;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Update product view admin
     *
     * @return void
     */
    public function updateViewAdmin(): void
    {
        if (validateUser()) {
            if ($_SESSION['sId'] == "admin") {
                $productModel = new Produto();
                $productModel->__set("id", $_POST['id']);
                $this->dados = $productModel->selecionarProduto();
                $this->render("Produto/atualizar.phtml", "layoutAdmin");
            }
        } else {
            $_SESSION['msg'] = self::PERMITION_MESSAGE;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Update product
     *
     * @return void
     */
    public function update(): void
    {
        if (validateUser()) {
            if ($_SESSION['sId'] == "admin") {
                $productModel = new Produto();
                echo $_POST['id'];
                $productModel->alterar($_POST['alter'], $_POST['campo'], $_POST['id']);
                $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Alterado com sucesso!</div>";
                header("location: /listaprodutos");
            }
        } else {
            $_SESSION['msg'] = self::PERMITION_MESSAGE;
            $this->render("Auth/login.phtml", "layoutAuth");
        }
    }

    /**
     * Search product
     *
     * @return void
     */
    public function search(): void
    {
        $productModel = new Produto();
        $this->dados = $productModel->buscar($_GET['busca']);
        if ($this->dados == null) {
            header("location: /produtos");
        } else {
            $this->render("Produto/busca.phtml", "layoutAuth");
        }
    }
}
