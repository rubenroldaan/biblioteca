<?php
    class Persona {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost", "root", "", "biblioteca");
        }
    }

?>