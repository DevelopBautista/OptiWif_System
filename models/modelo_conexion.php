<?php
class conexion
{
    /*variables para las coenxion*/
    private $serverUrl;
    private $usuarioDb;
    private $passDb;
    private $dbName;
    public $conexion;

    /*Constructor para inicializar las variables*/
    public function __construct()
    {   $this->dbName = "sistema_wisper";
        $this->serverUrl = "mysql:host=localhost;dbname=" . $this->dbName . ";charset=utf8";
        $this->usuarioDb = "root";
        $this->passDb = "Admin@132081";
    }
    /*Funcion para crear la conexion a la db*/
    public function conectar()
    {
        try {
            $this->conexion = new PDO($this->serverUrl, $this->usuarioDb, $this->passDb);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /*Funcion para cerrar la conexion a la db*/
    public function cerrar_conexion()
    {

        $this->conexion = null;
    }
}
