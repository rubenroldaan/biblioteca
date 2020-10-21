<?php
    class Usuario {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost", "root", "", "biblioteca");
        }

        public function buscarUsuario($usr, $pass) {
            if ($result = $this->db->query("SELECT * FROM users WHERE nombre = '$usr' AND passwd = '$pass'")) {
                if ($result->num_rows == 1) {
                    $usuario = $result->fetch_object();
                    $_SESSION["id_usuario"] = $usuario->id_user;
                    $_SESSION["nombre_usuario"] = $usuario->nombre;
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

?>