<?php

namespace App;

use Config\Init\Boot;

class Routes extends Boot
{

    protected function initRoutes()
    {
        $routes['home'] = array(
            'route' => '/',
            'controller' => 'IndexController',
            'method' => 'index'
        );

        $routes['produtos'] = array(
            'route' => '/produtos',
            'controller' => 'ProdutosController',
            'method' => 'index'
        );

        $routes['cadastro'] = array(
            'route' => '/cadastro',
            'controller' => 'UsuariosController',
            'method' => 'cadastro'
        );

        $routes['registrarusuario'] = array(
            'route' => '/registrarusuario',
            'controller' => 'UsuariosController',
            'method' => 'registrar'
        );

        $routes['autenticar'] = array(
            'route' => '/autenticar',
            'controller' => 'AuthController',
            'method' => 'autenticar'
        );

        $routes['execAutenticar'] = array(
            'route' => '/execAutenticar',
            'controller' => 'AuthController',
            'method' => 'execAutenticar'
        );

        $routes['logout'] = array(
            'route' => '/logout',
            'controller' => 'AuthController',
            'method' => 'logout'
        );

        $routes['restrito'] = array(
            'route' => '/restrito',
            'controller' => 'AppController',
            'method' => 'restrito'
        );

        $routes['meusDados'] = array(
            'route' => '/meusDados',
            'controller' => 'UsuariosController',
            'method' => 'dados'
        );

        $routes['alterarDados'] = array(
            'route' => '/alterarDados',
            'controller' => 'UsuariosController',
            'method' => 'alterar'
        );

        $routes['execAlterarDados'] = array(
            'route' => '/execAlterarDados',
            'controller' => 'UsuariosController',
            'method' => 'execAlterarDados'
        );

        $routes['restritoadmin'] = array(
            'route' => '/restritoadmin',
            'controller' => 'AppController',
            'method' => 'restritoadmin'
        );

        $routes['cadastroproduto'] = array(
            'route' => '/cadastroproduto',
            'controller' => 'produtosController',
            'method' => 'cadastroproduto'
        );

        $routes['registrarproduto'] = array(
            'route' => '/registrarproduto',
            'controller' => 'ProdutosController',
            'method' => 'registrar'
        );

        $routes['listaprodutos'] = array(
            'route' => '/listaprodutos',
            'controller' => 'ProdutosController',
            'method' => 'listaProdutos'
        );

        $routes['delete'] = array(
            'route' => '/delete',
            'controller' => 'ProdutosController',
            'method' => 'delete'
        );

        $routes['execAlterarProduto'] = array(
            'route' => '/execAlterarProduto',
            'controller' => 'ProdutosController',
            'method' => 'execAlterarProduto'
        );

        $routes['admExecAtualizarProduto'] = array(
            'route' => '/admExecAtualizarProduto',
            'controller' => 'ProdutosController',
            'method' => 'admExecAtualizarProduto'
        );

        $routes['politicas'] = array(
            'route' => '/politicas',
            'controller' => 'SuporteController',
            'method' => 'politicas'
        );

        $routes['termos'] = array(
            'route' => '/termos',
            'controller' => 'SuporteController',
            'method' => 'termos'
        );

        $routes['trocas'] = array(
            'route' => '/trocas',
            'controller' => 'SuporteController',
            'method' => 'trocas'
        );

        $routes['sobre'] = array(
            'route' => '/sobre',
            'controller' => 'SuporteController',
            'method' => 'sobre'
        );


        $routes['carrinho'] = array(
            'route' => '/carrinho',
            'controller' => 'PedidosController',
            'method' => 'carrinho'
        );

        $routes['contagem'] = array(
            'route' => '/contagem',
            'controller' => 'PedidosController',
            'method' => 'contagem'
        );

        $routes['finalizar'] = array(
            'route' => '/finalizar',
            'controller' => 'PedidosController',
            'method' => 'finalizar'
        );

        $routes['execFinalizar'] = array(
            'route' => '/execFinalizar',
            'controller' => 'PedidosController',
            'method' => 'execFinalizar'
        );

        $routes['pedidos'] = array(
            'route' => '/pedidos',
            'controller' => 'PedidosController',
            'method' => 'pedidos'
        );

        $routes['buscar'] = array(
            'route' => '/buscar',
            'controller' => 'ProdutosController',
            'method' => 'buscar'
        );

        parent::setRoutes($routes);
    }
}
