<?php

namespace App\Services;

use App\Models\Produto;
use Config\Controller\Action;

class CartService extends Action
{
    /**
     * @var array The cart data.
     */
    protected $dados;

    /**
     * Initializes the CartService.
     */
    public function __construct()
    {
        if (!isset($_SESSION['kart'])) {
            $_SESSION['kart'] = [];
        }
    }

    /**
     * Handles the 'carrinho' action.
     *
     * @return void
     */
    public function carrinho(): void
    {
        if (isset($_GET['acao'])) {
            $this->cartAction();
        }
        $productModel = new Produto();
        if (!empty($_SESSION['kart'])) {
            $this->dados = $this->getCartData($productModel);
        }
        $this->renderCartPage();
    }

    /**
     * Cart action
     *
     * @return void
     */
    private function cartAction(): void
    {
        switch ($_GET['acao']) {
            case 'add':
                $this->addToCart();
                $this->redirect("/produtos");
                break;
            case 'del':
                $this->deleteFromCart();
                break;
            case 'contar1':
                $this->decreaseCartItemQuantity();
                break;
            case 'contar2':
                $this->increaseCartItemQuantity();
                break;
            default:
                break;
        }
    }

    /**
     * Adds a product to the cart.
     *
     * @return void
     */
    private function addToCart(): void
    {
        $id = intval($_GET['id']);
        if (!isset($_SESSION['kart'][$id])) {
            $_SESSION['kart'][$id] = 1;
        } else {
            $_SESSION['kart'][$id] += 1;
        }
    }

    /**
     * Deletes a product from the cart.
     *
     * @return void
     */
    private function deleteFromCart(): void
    {
        $id = intval($_GET['id']);
        if (isset($_SESSION['kart'][$id])) {
            unset($_SESSION['kart'][$id]);
            $_SESSION['count'][$id] = 1;
            $this->redirect("/carrinho");
        }
    }

    /**
     * Decreases the quantity of a product in the cart.
     *
     *  @return void
     */
    private function decreaseCartItemQuantity(): void
    {
        if (isset($_SESSION['count'])) {
            $id = $_GET['id'];
            $_SESSION['count'][$id] = max(0, $_SESSION['count'][$id] - 1);

            if ($_SESSION['count'][$id] == 0) {
                unset($_SESSION['kart'][$id]);
                $_SESSION['count'][$id] = 1;
                $this->redirect("/carrinho");
            }
        }
    }

    /**
     * Increases the quantity of a product in the cart.
     *
     * @return void
     */
    private function increaseCartItemQuantity(): void
    {
        if (isset($_SESSION['count'])) {
            $id = $_GET['id'];
            $_SESSION['count'][$id] += 1;
        }
    }

    /**
     * Renders the cart page.
     *
     * @return void
     */
    private function renderCartPage(): void
    {
        $this->render("Pedidos/carrinho.phtml", "layoutAuth");
    }

    /**
     * Redirects the user to the specified location.
     *
     * @param string $location The URL to redirect to.
     * @return void
     */
    private function redirect(string $location): void
    {
        header("Location: $location");
        exit;
    }

    /**
     * Retrieves the cart data.
     *
     * @param Produto $productModel The product model.
     * @return array The cart data.
     */
    private function getCartData(Produto $productModel): array
    {
        $cartData = [];
        foreach ($_SESSION['kart'] as $id => $qtd) {
            $cartData[] = $productModel->listaCarrinho($id);
        }

        return $cartData;
    }
}
