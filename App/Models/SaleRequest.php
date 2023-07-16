<?php

namespace App\Models;

use App\Contracts\ModelContract;
use App\Conn;
use PDO;
use PDOException;

class SaleRequest extends Conn implements ModelContract
{
    private string $table = "sales";
    protected PDO $pdo;
    private array $attrib;

    public function __construct()
    {
        $this->pdo = Conn::getInstance();
    }

    /**
     * @inheritdoc
     */
    public function __get(string $attribute)
    {
        return $this->attrib[$attribute];
    }

    /**
     * @inheritdoc
     */
    public function __set(string $attribute, $value)
    {
        $this->attrib[$attribute] = $value;
    }

    /**
     * Register order
     *
     * @return Pedidos
     */
    public function registerOrder(): ?SaleRequest
    {
        try {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_MULTI_STATEMENTS, true);
            $stmt = $this->pdo->prepare("
                INSERT INTO $this->table (status, date_sale, CPF, product_id, items)
                VALUES (:status,:data,:cpf,:cod,:qtd);
                UPDATE Products SET stock = stock - :qtd WHERE id = :cod;
            ");
            $stmt->bindValue(":status", "Preparando para o envio", PDO::PARAM_STR);
            $stmt->bindValue(":data", $this->__get('data'), PDO::PARAM_STR);
            $stmt->bindValue(":cpf", $this->__get('cpf'), PDO::PARAM_STR);
            $stmt->bindValue(":cod", $this->__get('id'), PDO::PARAM_INT);
            $stmt->bindValue(":qtd", $this->__get('qtd'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                $stmt->nextRowset(); // Mover para a próxima declaração
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">
                        Pedido realizado com sucesso</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possível inserir registros na tabela $this->table");
                }
            } else {
                throw new PDOException("Houve um problema com o código SQL");
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
        }

        return null;
    }

    /**
     * Get my orders
     *
     * @return ?array
     */
    public function orders(): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE CPF = :cpf");
            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
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
