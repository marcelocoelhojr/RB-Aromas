<?php

//helper file

if (!function_exists('validateUser')) {
    
    /**
     * Validate user
     *
     * @return bool
     */
    function validateUser(): bool
    {
        return isset($_SESSION['sId']) && isset($_SESSION['sNome']) ? true : false;
    }
    
}
