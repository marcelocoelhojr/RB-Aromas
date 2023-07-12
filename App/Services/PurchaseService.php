<?php

namespace App\Services;

use App\Models\Pedidos;
use App\Models\Produto;
use Config\Controller\Action;

class PurchaseService extends Action
{
    protected $dados = null;
    protected array $prod;

    /**
     * Checkout view
     *
     * @return void
     */
    public function checkoutView(): void
    {
        if (isset($_GET['fim'])) {
            $productModel = new Produto();
            $i = 0;
            foreach ($_SESSION['kart'] as $id => $qtd) {
                $this->dados[$i] = $productModel->listaCarrinho($id);
                $i++;
            }
            $this->render("Pedidos/finalizar.phtml", "layoutAuth");
        } else {
            header("location: /");
        }
    }

    /**
     * List products
     *
     * @return void
     */
    public function listProducts(): void
    {
        if (isset($_SESSION['sId'])) {
            if ($_SESSION['sId'] != "admin") {
                $purchaseModel = new Pedidos();
                $productModel = new Produto();
                $prod = array();
                $purchaseModel->__set("cpf", $_SESSION['sId']);
                $this->dados = $purchaseModel->meusPedidos();
                $i = 0;
                if (isset($this->dados)) {
                    foreach ($this->dados as $valor) {
                        $productModel->__set('id', $valor->CodProduto);
                        $prod[$i] = $productModel->selecionarProduto();
                        $i++;
                    }
                }
                $this->prod = $prod;
                $this->render("Usuario/pedidos.phtml", "layoutUser");
            }
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                É necessário está logado para proseguir!</div>";
            header("location: /autenticar");
        }
    }

    /**
     * Checkout
     *
     * @return void
     */
    public function checkout(): void
    {
        if (isset($_SESSION['sId'])) {
            $purchaseModel = new Pedidos();
            $tamanho = $_POST['tamanho'];
            for ($i = 0; $i < $tamanho; $i++) {
                $purchaseModel->__set("id", $_SESSION['produtos'][$i]);
                $purchaseModel->__set("cpf", $_SESSION['sId']);
                $purchaseModel->__set("data", date('d/m/Y'));
                $purchaseModel->__set("qtd", $_SESSION['qtd'][$i]);
                $purchaseModel->registrarPedidos();
            }
            unset($_SESSION['kart']);
            header("location: /pedidos");
        } else {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                É necessário está logado para proseguir!</div>";
            header("location: /autenticar");
        }
    }
}
