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

            // Verificar si el plan  ya existe
            $sql_check = "SELECT COUNT(*) as total FROM empresa ";
            $stmt_check = $this->conn->conexion->prepare($sql_check);
            $stmt_check->execute();
            $existe = $stmt_check->fetchColumn();

            if ($existe['total'] > 0) {
                //array asociativo 
                return [
                    "status" => "existe",
                    "mensaje" => "Ya existe una empresa."
                ];
            }


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


    public function actualizar_datos_empresa($direccion, $telefono, $id)
    {

        $sql = "UPDATE empresa SET direccion=:dir,telefono=:tel WHERE id_empresa=:id";

        $stmt = $this->conn->conexion->prepare($sql);

        // Parámetros comunes
        $stmt->bindParam(':dir', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':velocidad', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensaje" => "Los datos se han actualizado correctamente."
            ];
        } else {
            $errorInfo = $stmt->errorInfo();
            return [
                "status" => "error",
                "mensaje" => "No se pudo actualizar los datos.",
                "error" => $errorInfo
            ];
        }
    }
}
