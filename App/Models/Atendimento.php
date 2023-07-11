<?php 

    namespace App\Models;
    
    use App\Conn;
    use PDO;
    use PDOException;

    class Atendimento extends Conn{

        protected $pdo;
        private $tabela = "atendimento";
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

        public function mensagem(){
           
            try {

                $stmt = $this->pdo->prepare("SELECT * FROM $this->tabela");
                if($stmt->execute()){
                    if($stmt->rowCount() > 0){
                        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                        return $result;
                    }
                    else{
                        throw new PDOException("Não foram encontrados registros na tabela $this->tabela");
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