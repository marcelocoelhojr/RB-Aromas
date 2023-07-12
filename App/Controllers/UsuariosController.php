<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Services\UserService;

class UsuariosController
{

    /**
     * Register view
     *
     * @return void
     */
    public function cadastro(): void
    {
        $userService = new UserService();
        $userService->registerView();
    }

    /**
     * Register user
     *
     * @return void
     */
    public function registrar(): void
    {
        $userService = new UserService();
        $userService->register();
    }

    /**
     * Get user data
     *
     * @return void
     */
    public function dados(): void
    {
        $userService = new UserService();
        $userService->getUserData();
    }

    /**
     * Update view
     *
     * @return void
     */
    public function alterar(): void
    {
        $userService = new UserService();
        $userService->updateView();
    }

    /**
     * Update user data
     *
     * @return void
     */
    public function execAlterarDados(): void
    {
        $userService = new UserService();
        $userService->updateUser();
    }
}
