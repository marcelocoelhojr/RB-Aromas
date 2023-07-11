<?php

namespace App\Controllers;

use App\Services\AppService;
use Config\Controller\Action;

class AppController extends Action
{

    /**
     * Checks if the user can access the restricted area
     *
     * @return void
     */
    public function restrito(): void
    {
        $appService = new AppService();
        $viewInfos = $appService->restricted();
        $this->render($viewInfos['file'], $viewInfos['layout']);
    }

     /**
     * Checks if the admin user can access the restricted area
     *
     * @return void
     */
    public function restritoadmin(): void
    {
        $appService = new AppService();
        $viewInfos = $appService->restrictedAdmin();
        $this->render($viewInfos['file'], $viewInfos['layout']);
    }
}
