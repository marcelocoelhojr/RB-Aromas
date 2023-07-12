<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    /**
     * Authentication view
     *
     * @return void
     */
    public function autenticar(): void
    {
        $authService = new AuthService();
        $authService->autenticate();
    }

     /**
     * Authentication
     *
     * @return void
     */
    public function execAutenticar(): void
    {
        $authService = new AuthService();
        $authService->login();
    }

     /**
     * Logout
     *
     * @return void
     */
    public function logout(): void
    {
        $authService = new AuthService();
        $authService->logout();
    }
}
