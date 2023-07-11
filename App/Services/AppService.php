<?php

namespace App\Services;

class AppService
{

    const AUTH_FILE = 'Auth/login.phtml';
    const MESSAGE = "<div class=\"alert alert-danger\" role=\"alert\">
        Você não possui permissão para acessar está página!</div>";

    protected $dados = null;

    /**
     * Checks if the user can access the restricted area
     *
     * @return array
     */
    public function restricted(): array
    {
        if ($this->verifySession()) {
            $view['file'] = 'App/restrito.phtml';
            $view['layout'] = 'layoutUser';
        } else {
            $_SESSION['msg'] = self::MESSAGE;
            $view['file'] = self::AUTH_FILE;
            $view['layout'] = 'layoutAuth';
        }

        return $view;
    }

    /**
     * Checks if the admin user can access the restricted area
     *
     * @return array
     */
    public function restrictedAdmin(): array
    {
        if ($this->verifySession()) {
            if ($_SESSION['sId'] == "admin") {
                $view['file'] = 'App/restritoAdmin.phtml';
                $view['layout'] = 'layoutAdmin';
            } else {
                $_SESSION['msg'] = self::MESSAGE;
                $view['file'] = self::AUTH_FILE;
                $view['layout'] = 'layoutAuth';
            }
        } else {
            $_SESSION['msg'] = self::MESSAGE;
            $view['file'] = self::AUTH_FILE;
            $view['layout'] = 'layoutAuth';
        }

        return $view;
    }

    /**
     * Verify session
     *
     * @return bool
     */
    private function verifySession(): bool
    {
        return isset($_SESSION['sId']) && isset($_SESSION['sNome']) ? true : false;
    }
}
