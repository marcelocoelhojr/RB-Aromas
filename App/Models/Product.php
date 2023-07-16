<?php

namespace App\Models;

use App\Conn;
use App\Contracts\ModelContract;
use PDO;
use PDOException;

class Product extends Conn implements ModelContract
{
    const EXCEPTION_MESSAGE = 'Houve um problema com código SQL';

    protected $pdo;
    private $table = "products";
    private $attrib;

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
     * List product without paginate
     */
    public function listProduct()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->table");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    /**
     * List cart
     */
    public function listCart($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindvalue(":id", $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->table");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    /**
     * Create product
     *
     * @return ?Product
     */
    public function create(): ?Product
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO $this->table (name, price, description, category, stock, image)
                VALUE(:nome,:preco,:descricao,:categoria,:estoque,:img)"
            );
            $stmt->bindvalue(":nome", $this->__get('nome', PDO::PARAM_STR));
            $stmt->bindvalue(":preco", $this->__get('preco', PDO::PARAM_STR));
            $stmt->bindvalue(":descricao", $this->__get('descricao', PDO::PARAM_STR));
            $stmt->bindvalue(":categoria", $this->__get('categoria', PDO::PARAM_STR));
            $stmt->bindvalue(":estoque", $this->__get('EstoqueQtd', PDO::PARAM_INT));
            $stmt->bindvalue(":img", $this->__get('img', PDO::PARAM_INT));
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">
                        Produto cadastrado com sucesso</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possível inserir registros na tabela $this->table");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
        }

        return null;
    }

    public function validate()
    {
        if (strlen($this->__get('nome')) < 5) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                Campo NOME inválido!</div>";
            return false;
        }

        return true;
    }

    /**
     * Get product by id
     */
    public function getProductById()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindvalue(":id", $this->__get('id'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    /**
     * Delete product by id
     *
     * @return ?Product
     */
    public function delete(): ?Product
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM $this->table WHERE id = :id");
            $stmt->bindvalue(":id", $this->__get('id'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">
                        Produto excluido com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a exclusão na tabela $this->table");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    /**
     * Update product by id
     *
     * @return ?Product
     */
    public function update($valor, $campo, $id): ?Product
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE $this->table SET $campo = '$valor' WHERE (id = '$id')");
            if ($stmt->execute($this->attrib)) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">
                        $campo alterado com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a alteração na tabela $this->table");
                }
            } else {
                throw new PDOException("Houve um problema no código SQL");
            }
        } catch (PDOException $e) {
            print_r(json_encode([$e])); exit;
            echo $e->getMessage();
        }

        return null;
    }

    /**
     * Search product
     */
    public function search($nome)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE name LIKE :nome");
            $stmt->bindvalue(":nome", "%" . $nome . "%", PDO::PARAM_STR);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Produto não encontrado!</div>";
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }
}
