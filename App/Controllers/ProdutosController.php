<?php

namespace App\Controllers;

use App\Models\Produto;
use App\Service\ProductService;
use Config\Controller\Action;

class ProdutosController extends Action
{

    protected $dados = null;
    protected $cad = null;

    public function index()
    {
        $productService = new ProductService();
        $productService->productsView();
    }


    public function cadastroproduto()
    {
        $productService = new ProductService();
        $productService->registerView();
    }

    public function registrar()
    {
        $productService = new ProductService();
        $productService->register();
    }

    public function listaProdutos()
    {
        $productService = new ProductService();
        $productService->listProducts();
    }

    public function delete()
    {
        $productService = new ProductService();
        $productService->delete();
    }


    public function admExecAtualizarProduto()
    {
        $productService = new ProductService();
        $productService->updateViewAdmin();
    }

    public function execAlterarProduto()
    {
        $productService = new ProductService();
        $productService->update();
    }

    public function buscar()
    {
        $productService = new ProductService();
        $productService->search();
    }
}
