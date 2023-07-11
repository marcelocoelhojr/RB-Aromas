<?php

namespace App\Models;

use App\Conn;
use PDO;
use PDOException;

class Produto extends Conn
{

    protected $pdo;
    private $tabela = "produto";
    private $attrib;

    public function __construct()
    {
        $this->pdo = Conn::connection();
    }

    public function __get($atributo)
    {
        return $this->attrib[$atributo];
    }

    public function __set($atributo, $valor)
    {
        $this->attrib[$atributo] = $valor;
    }

    public function readProduto()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela");
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return $result;
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        return null;
    }

    public function listaCarrinho($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CodProduto = :id");
            $stmt->bindvalue(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return $result;
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    public function createProduto()
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO $this->tabela (Nome,Preco,Descricao,Categoria, EstoqueQtd,Imagem) VALUE(:nome,:preco,:descricao,:categoria,:estoque,:img)");
            $stmt->bindvalue(":nome", $this->__get('nome', PDO::PARAM_STR));
            $stmt->bindvalue(":preco", $this->__get('preco', PDO::PARAM_STR));
            $stmt->bindvalue(":descricao", $this->__get('descricao', PDO::PARAM_STR));
            $stmt->bindvalue(":categoria", $this->__get('categoria', PDO::PARAM_STR));
            $stmt->bindvalue(":estoque", $this->__get('EstoqueQtd', PDO::PARAM_INT));
            $stmt->bindvalue(":img", $this->__get('img', PDO::PARAM_INT));
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Produto cadastrado com sucesso</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possível inserir registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
        }
        return null;
    }

    public function validarCadastro()
    {
        if (strlen($this->__get('nome')) < 5) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Campo NOME inválido!</div>";
            return false;
        }

        return true;
    }

    public function pesquisarProdutoById()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CodProduto=:CodProduto");
            $stmt->bindvalue(":CodProduto", $this->__get('CodProduto'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo "execute";
                $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $result;
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    public function excluirProduto()
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM $this->tabela WHERE CodProduto=:CodProduto");
            $stmt->bindvalue(":CodProduto", $this->__get('CodProduto'), PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">Produto excluido com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a exclusão na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
        return null;
    }

    public function admAtualizarProduto()
    {
        try {
            foreach ($this->attrib as $key => $value) {
                $values[] = $key . '=:' . $key;
            }
            $values = implode(',', $values);
            $stmt = $this->pdo->prepare("UPDATE $this->tabela SET $values WHERE CodProduto=:CodProduto");
            $stmt->bindvalue(':CodProduto', $this->__get('CodProduto', PDO::PARAM_INT));
            if ($stmt->execute($this->attrib)) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">Produto alterado com sucesso!</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possivel realizar a exclusão na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema no código SQL");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return null;
    }

    public function selecionarProduto()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CodProduto = :cod");
            $stmt->bindvalue(":cod", $this->__get('id', PDO::PARAM_INT));

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                    return $result;
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
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
                    $_SESSION['msg'] = "<div class=\"alert alert-succes\" role=\"alert\">$campo alterado com sucesso!</div>";
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
}
