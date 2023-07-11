<?php 

    namespace App\Models;
    
    use App\Conn;
    use PDO;
    use PDOException;

    class Pedidos extends Conn{

        protected $pdo;
        private $tabela = "pedidos";
        private $attrib;
        
        public function __construct(){
            $this->pdo = Conn::connection();
        }

        public function __get($atributo){
            return $this->attrib[$atributo];
        }

        public function __set($atributo, $valor){
            $this->attrib[$atributo] = $valor;
        }

        public function registrarPedidos(){
            try {

                $stmt = $this->pdo->prepare("INSERT INTO $this->tabela (Status,DataPedido,CPF,CodProduto,Quantidade) VALUE(:status,:data,:cpf,:cod,:qtd)");
                
                $stmt->bindvalue(":status", "Preparando para o envio", PDO::PARAM_STR);
                $stmt->bindvalue(":data", $this->__get('data', PDO::PARAM_STR));
                $stmt->bindvalue(":cpf", $this->__get('cpf', PDO::PARAM_STR));
                $stmt->bindvalue(":cod", $this->__get('id', PDO::PARAM_INT));
                $stmt->bindvalue(":qtd", $this->__get('qtd', PDO::PARAM_INT));
    
                if($stmt->execute()){
                    
                    if($stmt->rowCount() > 0){
                        $_SESSION['msg'] = "<div class=\"alert alert-success\" role=\"alert\">Pedido realizado com sucesso</div>";
                        return $this;
                    }
                    else{
                        throw new PDOException("Não foi possível inserir registros na tabela $this->tabela");
                    }
                }
                else{
                    throw new PDOException("Houve um problema com código SQL");
                }

            } catch (PDOException $e) {
                $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>" .$e->getMessage() ."</div>";
            }
            return null;
        }

        public function meusPedidos(){
            try {
   
                $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela WHERE CPF = :cpf");
                $stmt->bindvalue(":cpf", $this->__get('cpf',PDO::PARAM_STR));

                if($stmt->execute()){
                    if($stmt->rowCount() > 0){
                        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                        return $result;
                    }
                    else{
                        //throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
                    }
                }
                else{
                    throw new PDOException("Houve um problema com código SQL");
                }

            } catch (PDOException $e) {
                echo "Erro: " .$e->getMessage();
            }
            return null;

        }

        

    }



?>