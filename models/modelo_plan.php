<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_Plan
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }



    public function listar_plannes()
    {
        $sql = "SELECT id_plan,nombre,descripcion,precio FROM planes ";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        return $respuesta; //retorna texto
    }


    public function crear_plan($nombre, $descripcion, $precio)
    {
        try {

            // Verificar si el plan  ya existe
            $sql_check = "SELECT COUNT(*) FROM planes WHERE nombre = :nombre";
            $stmt_check = $this->conn->conexion->prepare($sql_check);
            $stmt_check->bindParam(':nombre', $nombre);
            $stmt_check->execute();
            $existe = $stmt_check->fetchColumn();

            if ($existe > 0) {
                //array asociativo 
                return [
                    "status" => "existe",
                    "mensaje" => "El plan ya existe en el sistema."
                ];
            }

            // crear nuevo plan
            $sql = "INSERT INTO planes (nombre, descripcion, precio)
                VALUES (:nombre, :descripcion, :precio)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':precio', $precio);

            if ($stmt->execute()) {
                return [
                    "status" => "ok",
                    "mensaje" => "El plan fue creado correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al creado el plan."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Excepción: " . $e->getMessage()
            ];
        }
    }


    public function actualizar_planes($id, $nombre, $descripcion, $precio)
    {

        $sql = "UPDATE planes SET nombre=:Nom,descripcion=:Descri,precio=:precio WHERE id_plan=:Id";

        $stmt = $this->conn->conexion->prepare($sql);

        // Parámetros comunes
        $stmt->bindParam(':Nom', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':Descri', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
        $stmt->bindParam(':Id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensaje" => "El plan ha sido actualizado."
            ];
        } else {
            $errorInfo = $stmt->errorInfo();
            return [
                "status" => "error",
                "mensaje" => "No se pudo actualizar el plan.",
                "error" => $errorInfo
            ];
        }
    }
}
