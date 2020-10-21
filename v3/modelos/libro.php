<?php
    class Libro {
        private $db;
        public function __construct() {
            $this->db = new mysqli("localhost", "root", "", "biblioteca");
        }

        public function getAll() {
            $arrayResult = array();
            if ($result = $this->db->query("SELECT * FROM libros")) {
                while ($fila = $result->fetch_object()) {
                    $arrayResult[] = $fila;
                }
            } else {
                $arrayResult = null;
            }
            return $arrayResult;
        }

    }