<?php

namespace App\Models;

use App\Conn;
use PDO;
use PDOException;

class Pedidos extends Conn
{

    protected $pdo;
    private $tabela = "pedidos";
    private $attrib;

    public function __construct()
    {
        $this->pdo = Conn::getInstance();
    }

    public function __get($atributo)
    {
        return $this->attrib[$atributo];
    }

    public function __set($atributo, $valor)
    {
        $this->attrib[$atributo] = $valor;
    }

    public function registrarPedidos()
    {
        try {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_MULTI_STATEMENTS, true);
            $stmt = $this->pdo->prepare("
                INSERT INTO $this->tabela (Status,DataPedido,CPF,CodProduto,Quantidade)
                VALUES (:status,:data,:cpf,:cod,:qtd);
                UPDATE Produto SET EstoqueQtd = EstoqueQtd - :qtd WHERE CodProduto = :cod;
            ");

            $stmt->bindValue(":status", "Preparando para o envio", PDO::PARAM_STR);
            $stmt->bindValue(":data", $this->__get('data'), PDO::PARAM_STR);
            $stmt->bindValue(":cpf", $this->__get('cpf'), PDO::PARAM_STR);
            $stmt->bindValue(":cod", $this->__get('id'), PDO::PARAM_INT);
            $stmt->bindValue(":qtd", $this->__get('qtd'), PDO::PARAM_INT);

            if ($stmt->execute()) {
                $stmt->nextRowset(); // Mover para a próxima declaração

                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Pedido realizado com sucesso</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possível inserir registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com o código SQL");
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
        }
        return null;
    }

    public function meusPedidos()
    {
        try {

            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CPF = :cpf");
            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return $result;
                } else {
                    //throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        return null;
    }
}
