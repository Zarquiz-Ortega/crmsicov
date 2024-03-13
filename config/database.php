<?php

class Database
{

    private $hostname = "localhost";
    private $database = "sicov";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";

    function conectar()
    {

        try {
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . ";
         charset=" . $this->charset;

            $optons = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false

            ];

            $pdo = new PDO($conexion, $this->username, $this->password, $optons);

            return $pdo;
        } catch (PDOException $e) {
            echo 'Error conexion' . $e->getMessage();
            exit;
        }
    }
}
