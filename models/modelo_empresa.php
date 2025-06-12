<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_empresa
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function insertar_datos_empresa($nombre, $direccion, $telefono, $rnc, $logo)
    {
        try {
            $sql = "INSERT INTO empresa(nombre, direccion, telefono,rnc ,logo) 
            VALUES (:nombre,:direccion,:telefono,:rnc,:logo)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rnc', $rnc);
            $stmt->bindParam(':logo', $logo);


            if ($stmt->execute()) {
                return [
                    "status" => "ok",
                    "mensaje" => "Datos de la empresa han sido  registrado correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al registrar empresa."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Excepción: " . $e->getMessage()
            ];
        }
    }
}
