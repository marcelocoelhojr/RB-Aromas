<?php

namespace App;

use PDO;
use PDOException;

class Conn
{
    private static ?PDO $instance = null;

    private function __construct()
    {
        //private construct
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = self::createConnection();
        }

        return self::$instance;
    }

    private static function createConnection(): PDO
    {
        $envFile = __DIR__ . '/../.env';
        $envVars = parse_ini_file($envFile);
        $host = $envVars["DB_HOST"];
        $user = $envVars["DB_USER"];
        $pass = $envVars["DB_PASSWORD"];
        $dbName = $envVars["DB_DATABASE"];
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            exit();
        }

        return $pdo;
    }
}
