<?php 

    namespace App\Controllers;

    use App\Models\Usuario;
    use Config\Controller\Action;

    class AuthController extends Action{

        protected $dados = null;

        public function autenticar(){
            $this->render("Auth/login.phtml","layoutAuth");
        }

        public function execAutenticar(){

            $usuario = new Usuario();

            $usuario->__set('cpf', $_POST['cpf']);
            $usuario->__set('senha', $_POST['senha']);

            if(count($usuario->autenticar()) == 1){
                $_SESSION['sId'] = $usuario->__get('cpf');
                $_SESSION['sNome'] = $usuario->__get('nome');
                if($_SESSION['sId'] == "admin"){
                    header("location: /restritoadmin");
                }
                else{
                    header("location: /restrito");
                }
               
            }
            else{
                $_SESSION['msg'] = "<div class=\"alert alert-danger\" role=\"alert\">CPF e/ou Senha incorreto(s)!</div>";
                $this->dados['formRetorno'] = $_POST;
                $this->render("Auth/login.phtml","layoutAuth");
            }

        }

        public function logout(){
            session_destroy();
            header("location: /");
        }

    }

?>