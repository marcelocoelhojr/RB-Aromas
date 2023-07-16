<?php

namespace App\Services;

use App\Models\Product;
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
        $product = new Product();
        $this->dados = $product->listProduct();
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
        $productModel = new Product();
        $productModel->__set("nome", $_POST['nome']);
        $productModel->__set("categoria", $_POST['categoria']);
        $productModel->__set("descricao", $_POST['descricao']);
        $productModel->__set("img", 'produto.jpeg');
        $productModel->__set("EstoqueQtd", $_POST['EstoqueQtd']);
        $productModel->__set("preco", $_POST['preco']);
        if ($productModel->validate()) {
            $productModel->create();
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
                $productModel = new Product();
                $this->dados = $productModel->listProduct();
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
                $productModel = new Product();
                $productModel->__set('id', $_POST['id']);
                echo $_POST['id'];
                $productModel->delete();
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
                $productModel = new Product();
                $productModel->__set("id", $_POST['id']);
                $this->dados = $productModel->getProductById();
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
                $productModel = new Product();
                echo $_POST['id'];
                $productModel->update($_POST['alter'], $_POST['campo'], $_POST['id']);
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
        $productModel = new Product();
        $this->dados = $productModel->search($_GET['busca']);
        if ($this->dados == null) {
            header("location: /produtos");
        } else {
            $this->render("Produto/busca.phtml", "layoutAuth");
        }
    }
}
