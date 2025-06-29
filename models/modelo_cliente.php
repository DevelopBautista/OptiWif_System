<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_cliente
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function listar_clientes()
    {
        $sql = "SELECT * FROM clientes ";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }


    public function insertar_cliente($nombres, $ced, $dir, $tel)
    {
        try {
            // Verificar si el cliente ya existe
            $sql_check = "SELECT COUNT(*) FROM clientes WHERE numero_cedula = :numero_cedula";
            $stmt_check = $this->conn->conexion->prepare($sql_check);
            $stmt_check->bindParam(':numero_cedula', $ced);
            $stmt_check->execute();
            $existe = $stmt_check->fetchColumn();

            if ($existe > 0) {
                //array asociativo 
                return [
                    "status" => "existe",
                    "mensaje" => "El cliente ya existe en el sistema."
                ];
            }

            // Insertar nuevo cliente
            $sql = "INSERT INTO clientes (nombre_completo, numero_cedula,direccion,telefono)
                VALUES (:nombre_completo, :numero_cedula,:direccion,:telefono)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':nombre_completo', $nombres);
            $stmt->bindParam(':numero_cedula', $ced);
            $stmt->bindParam(':direccion', $dir);
            $stmt->bindParam(':telefono', $tel);


            if ($stmt->execute()) {
                return [
                    "status" => "ok",
                    "mensaje" => "Cliente registrado correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al insertar cliente."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Excepción: " . $e->getMessage()
            ];
        }
    }



    public function actualizar_datos_cliente($id, $nombre_completo, $numero_cedula, $dir, $tel)
    {

        $sql = "UPDATE clientes 
                SET nombre_completo=:nombre,
                    numero_cedula=:cedula, 
                    direccion = :Dir,
                    telefono = :Tel
                WHERE id_cliente = :Id";

        $stmt = $this->conn->conexion->prepare($sql);

        // Parámetros comunes
        $stmt->bindParam(':nombre', $nombre_completo, PDO::PARAM_STR);
        $stmt->bindParam(':cedula', $numero_cedula, PDO::PARAM_STR);
        $stmt->bindParam(':Dir', $dir, PDO::PARAM_STR);
        $stmt->bindParam(':Tel', $tel, PDO::PARAM_STR);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);


        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensaje" => "Los datos del cliente han sido actualizados."
            ];
        } else {
            $errorInfo = $stmt->errorInfo();
            return [
                "status" => "error",
                "mensaje" => "No se pudo actualizar los datos del cliente.",
                "error" => $errorInfo
            ];
        }
    }
}
