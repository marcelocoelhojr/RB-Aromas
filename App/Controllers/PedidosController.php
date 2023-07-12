<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\PurchaseService;
use Config\Controller\Action;

class PedidosController extends Action
{

    protected $dados = null;


    public function carrinho()
    {
        $cartService = new CartService();
        $cartService->carrinho();
    }

    public function finalizar()
    {
        $purchaseService = new PurchaseService();
        $purchaseService->checkoutView();
    }

    public function pedidos()
    {
        $purchaseService = new PurchaseService();
        $purchaseService->listProducts();
    }

    public function execFinalizar()
    {
        $purchaseService = new PurchaseService();
        $purchaseService->checkout();
    }
}
