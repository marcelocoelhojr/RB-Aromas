<?php

namespace App;

use PDO;
use PDOException;

class Conn
{

    protected $host = "127.0.0.1";
    protected $user = "root";
    protected $pass = "";
    protected $dbname = "rbaromas";
    protected $pdo = "false";

    public function connection()
    {
        try {
            #conectando ao banco de dados
            $this->pdo = new PDO("mysql:host=$this->host; dbname=$this->dbname", $this->user, $this->pass);
            #definindo a utilização de report de erros do tipo Exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #definindo o padrão de condificação
            $this->pdo->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        return $this->pdo;
    }
}
