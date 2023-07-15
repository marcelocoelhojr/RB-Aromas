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
    private $tabela = "product";
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
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
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
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CodProduto = :id");
            $stmt->bindvalue(":id", $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
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
                "INSERT INTO $this->tabela (Nome,Preco,Descricao,Categoria, EstoqueQtd,Imagem)
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
                    throw new PDOException("Não foi possível inserir registros na tabela $this->tabela");
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
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CodProduto=:id");
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
            $stmt = $this->pdo->prepare("DELETE FROM $this->tabela WHERE CodProduto=:CodProduto");
            $stmt->bindvalue(":CodProduto", $this->__get('CodProduto'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">
                        Produto excluido com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a exclusão na tabela $this->tabela");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    public function alterar($valor, $campo, $id)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE $this->tabela SET $campo = '$valor' WHERE (CodProduto = '$id')");
            echo var_dump($stmt);
            if ($stmt->execute($this->attrib)) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">
                        $campo alterado com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a alteração na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema no código SQL");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public function produtoQtd($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT EstoqueQtd, Nome FROM $this->tabela WHERE CodProduto = $id");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException(self::EXCEPTION_MESSAGE);
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    public function alterQtd($id, $qtd)
    {
        try {

            $stmt = $this->pdo->prepare("UPDATE $this->tabela SET EstoqueQtd = '$qtd' WHERE (CodProduto = '$id')");
            echo var_dump($stmt);
            if ($stmt->execute($this->attrib)) {
                if ($stmt->rowCount() > 0) {
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a alteração na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema no código SQL");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return null;
    }

    public function buscar($nome)
    {
        try {

            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE Nome LIKE :nome");
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
