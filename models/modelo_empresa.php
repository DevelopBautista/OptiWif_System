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

            // Verificar si ya existe una empresa registrada
            $sql_check = "SELECT COUNT(*) as total FROM empresa";
            $stmt_check = $this->conn->conexion->prepare($sql_check);
            $stmt_check->execute();
            $existe = $stmt_check->fetchColumn();

            if ($existe > 0) {
                return [
                    "status" => "existe",
                    "mensaje" => "Ya existe una empresa registrada. No se puede registrar otra."
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

    public function update_Empresa($id,$direccion, $telefono,$logo = null)
    {
        // Buscar logo anterior
        $sql_get_logo = "SELECT logo FROM empresa WHERE id_empresa = ?";
        $stmt = $this->conn->conexion->prepare($sql_get_logo);
        $stmt->execute([$id]);
        $empresa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($empresa && $logo && !empty($empresa['logo'])) {
            $rutaLogoAnterior = __DIR__ . "/../views/logos/" . $empresa['logo'];
            if (file_exists($rutaLogoAnterior)) {
                unlink($rutaLogoAnterior); // elimina el archivo anterior
            }
        }

        // Actualizar
        $sql = "UPDATE empresa SET  direccion=?, telefono=?" . ($logo ? ", logo=?" : "") . " WHERE id_empresa=?";
        $params = [$direccion, $telefono];
        if ($logo) $params[] = $logo;
        $params[] = $id;

        $stmt = $this->conn->conexion->prepare($sql);
        if ($stmt->execute($params)) {
            return ["status" => "ok", "mensaje" => "Datos actualizados correctamente."];
        } else {
            return ["status" => "error", "mensaje" => "Error al actualizar."];
        }
    }


    public function obtener_empresa()
    {
        $sql = "SELECT * FROM empresa LIMIT 1";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // devuelve false si no hay
    }
}
