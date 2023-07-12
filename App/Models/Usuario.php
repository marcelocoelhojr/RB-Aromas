<?php

namespace App\Models;

use App\Conn;
use PDO;
use PDOException;

class Usuario extends Conn
{

    protected $pdo;
    private $tabela = "cliente";
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

    public function createUsuario()
    {
        try {

            $stmt = $this->pdo->prepare("INSERT INTO $this->tabela (NOME,EMAIL,SENHA,CPF,ENDERECO, UF, CIDADE, NUM_CASA,TELEFONE, SEXO, CEP, NASCIMENTO, RG) VALUE(:nome,:email,:senha,:cpf,:endereco,:uf, :cidade,:numero, :telefone, :sexo, :cep, :nascimento, :rg)");

            $stmt->bindvalue(":nome", $this->__get('nome', PDO::PARAM_STR));
            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
            $stmt->bindvalue(":email", $this->__get('email', PDO::PARAM_STR));
            $stmt->bindvalue(":senha", md5($this->__get('senha', PDO::PARAM_STR)));
            $stmt->bindvalue(":endereco", $this->__get('endereco', PDO::PARAM_STR));
            $stmt->bindvalue(":uf", $this->__get('uf', PDO::PARAM_STR));
            $stmt->bindvalue(":cidade", $this->__get('cidade', PDO::PARAM_STR));
            $stmt->bindvalue(":numero", $this->__get('numero', PDO::PARAM_STR));
            $stmt->bindvalue(":telefone", $this->__get('tel', PDO::PARAM_STR));
            $stmt->bindvalue(":sexo", $this->__get('sexo', PDO::PARAM_STR));
            $stmt->bindvalue(":cep", $this->__get('cep', PDO::PARAM_STR));
            $stmt->bindvalue(":nascimento", $this->__get('data', PDO::PARAM_STR));
            $stmt->bindvalue(":rg", $this->__get('rg', PDO::PARAM_STR));

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Usuário cadastrado com sucesso</div>";
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

    public function autenticar()
    {
        try {

            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CPF = :cpf AND SENHA = :senha");

            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
            $stmt->bindvalue(":senha", md5($this->__get('senha', PDO::PARAM_STR)));

            if ($stmt->execute()) {
                $result = $stmt->fetchALL(PDO::FETCH_OBJ);
                if ($stmt->rowCount() == 1) {
                    $this->__set('cpf', $result[0]->CPF);
                    $this->__set('nome', $result[0]->NOME);
                }
                return $result;
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\"> ERRO </div>";
        }
        return null;
    }

    public function validarCadastro()
    {
        if (strlen($this->__get('nome')) < 5) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Campo NOME inválido</div>";
            return false;
        }

        $email = filter_var($this->__get('email'), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Campo E-mail inválido!</div>";
            return false;
        }

        if (strlen($this->__get('senha')) < 6) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">Sua senha precisa conter mais de 6 caracteres</div>";
            return false;
        }

        if (($this->__get('senha')) != ($this->__get('confirmarSenha'))) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">As senhas não correspondem</div>";
            return false;
        }

        return true;
    }

    public function buscarUsuario()
    {
        try {

            $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CPF = :id");
            $stmt->bindvalue(":id", $this->__get('id', PDO::PARAM_STR));

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

    public function alterar($valor, $campo)
    {
        try {

            $id = $_SESSION['sId'];

            $stmt = $this->pdo->prepare("UPDATE $this->tabela SET $campo = '$valor' WHERE (CPF = '$id')");
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
