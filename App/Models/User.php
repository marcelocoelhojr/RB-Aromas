<?php

namespace App\Models;

use App\Conn;
use App\Contracts\ModelContract;
use PDO;
use PDOException;

class User extends Conn implements ModelContract
{

    protected $pdo;
    private $table = "users";
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

    public function create()
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO $this->table (name, email, password, cpf, address, uf, city, number_address, phone, sex, cep, date_birth, rg)
                VALUE(:name, :email, :password, :cpf, :address, :uf, :city, :number_address, :phone, :sex, :cep, :date_birth, :rg)"
            );
            $stmt->bindvalue(":name", $this->__get('name', PDO::PARAM_STR));
            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
            $stmt->bindvalue(":email", $this->__get('email', PDO::PARAM_STR));
            $stmt->bindvalue(":password", md5($this->__get('password', PDO::PARAM_STR)));
            $stmt->bindvalue(":address", $this->__get('address', PDO::PARAM_STR));
            $stmt->bindvalue(":uf", $this->__get('uf', PDO::PARAM_STR));
            $stmt->bindvalue(":city", $this->__get('city', PDO::PARAM_STR));
            $stmt->bindvalue(":number_address", 202);
            $stmt->bindvalue(":phone", $this->__get('phone', PDO::PARAM_STR));
            $stmt->bindvalue(":sex", $this->__get('sex', PDO::PARAM_STR));
            $stmt->bindvalue(":cep", $this->__get('cep', PDO::PARAM_STR));
            $stmt->bindvalue(":date_birth", $this->__get('date_birth', PDO::PARAM_STR));
            $stmt->bindvalue(":rg", $this->__get('rg', PDO::PARAM_STR));
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">
                        Usuário cadastrado com sucesso</div>";
                    return $this;
                } else {
                    throw new PDOException("Não foi possível inserir registros na tabela $this->table");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            print_r(json_encode([$e]));
            exit;
            $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" . $e->getMessage() . "</div>";
        }

        return null;
    }

    /**
     * Autenticate user
     */
    public function autenticate()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE cpf = :cpf AND password = :pass");
            $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
            $stmt->bindvalue(":pass", md5($this->__get('pass', PDO::PARAM_STR)));
            if ($stmt->execute()) {
                $result = $stmt->fetchALL(PDO::FETCH_OBJ);
                if ($stmt->rowCount() == 1) {
                    $this->__set('cpf', $result[0]->cpf);
                    $this->__set('nome', $result[0]->name);
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

    /**
     * Validate user creation
     */
    public function validate(): bool
    {
        if (strlen($this->__get('name')) < 5) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                Campo NOME inválido</div>";
            return false;
        }
        $email = filter_var($this->__get('email'), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                Campo E-mail inválido!</div>";
            return false;
        }
        if (strlen($this->__get('password')) < 6) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                Sua senha precisa conter mais de 6 caracteres</div>";
            return false;
        }
        if (($this->__get('password')) != ($this->__get('confirmarSenha'))) {
            $_SESSION['msg'] = $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">
                As senhas não correspondem</div>";
            return false;
        }

        return true;
    }

    /**
     * Search user by id
     */
    public function searchUser()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE cpf = :id");
            $stmt->bindvalue(":id", $this->__get('id', PDO::PARAM_STR));
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_OBJ);
                } else {
                    throw new PDOException("Não foram encontrados registros na tabela $this->table");
                }
            } else {
                throw new PDOException("Houve um problema com código SQL");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }

        return null;
    }

    /**
     * Update user
     */
    public function update($campo, $value): ?User
    {
        try {
            $id = $_SESSION['sId'];
            $stmt = $this->pdo->prepare("UPDATE $this->table SET $campo = '$value' WHERE (cpf = '$id')");
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
            echo $e->getMessage();
        }

        return null;
    }
}
