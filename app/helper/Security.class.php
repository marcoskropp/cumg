<?php

/**
 * Esta classe é responsável por realizar os métodos de segurança referentes 
 * ao login
 *  */
class Security {

    private $location;
    private $nivel;

    public function __construct($nivel) {
        $this->nivel = $nivel;
    }

    public function retorno() {
        $this->verificarSessao();
        return $this->location;
    }

    private function verificarSessao() {
        if ($_SESSION['usuario'] == null) {
            session_destroy();
            $this->location = "../produto/homeController.php";
        } else {
            
            $nivel = $this->nivel;
            switch ($nivel) {
                case "admin":
                    $this->location = "../admin/adminController.php";
                    break;
                case "cliente":
                    $this->location = "../cliente/clienteController.php";
                    break;
                default:
                    $this->location = "../produto/homeController.php";
            }
        }
    }
}
