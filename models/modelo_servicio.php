<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_servicio
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function listar_Servicios_modelo()
    {
        $sql = "SELECT id_servicio,descripcion FROM servicios";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        return $respuesta; //retorna texto
    }



    public function crear_servicio_modelo($id_cliente, $id_plan, $id_tipo_conexion, $id_servicio, $direccion_referencia, $fecha_inicio)
    {
        try {
            $estado = "activo";

            // Insertar nuevo usuario
            $sql = "INSERT INTO detalles_servicios(id_cliente, id_plan, id_tipo_conexion, id_servicio, direccion_referencia, fecha_inicio, estado) 
                    VALUES (:id_cliente,:id_plan,:id_tipo_conexion,:id_servicio,:direccion_referencia,:fecha_inicio,:estado)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':id_servicio', $id_servicio);
            $stmt->bindParam(':id_tipo_conexion', $id_tipo_conexion);
            $stmt->bindParam(':direccion_referencia', $direccion_referencia);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':estado', $estado);

            if ($stmt->execute()) {
                return [
                    "status" => "ok",
                    "mensaje" => "Servicio creado  correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al crear servicio."
                ];
            }
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "mensaje" => "Excepción: " . $e->getMessage()
            ];
        }
    }




    public function cambiar_estatus_servicio($id, $estatus)
    {

        $sql = "update usuario set usuario_estatus= :estatus where id_usuario= :id";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam('estatus', $estatus, PDO::PARAM_STR);
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $respuesta = $stmt->execute();
        return $respuesta; //retorna texto
    }



    public function actualizar_datos_usuarios($id, $tel, $dir, $usu, $pwsd, $id_rol)
    {
        if (empty($pwsd)) {
            $sql = "UPDATE usuario 
                SET usuario_tel = :Tel,
                    usuario_direccion = :Dir,
                    usuario_usu = :Usu,
                    id_rol = :Id_rol
                WHERE id_usuario = :Id";
        } else {
            $sql = "UPDATE usuario 
                SET usuario_tel = :Tel,
                    usuario_direccion = :Dir,
                    usuario_usu = :Usu,
                    usuario_pass = :Pass,
                    id_rol = :Id_rol
                WHERE id_usuario = :Id";
        }

        $stmt = $this->conn->conexion->prepare($sql);

        // Parámetros comunes
        $stmt->bindParam('Tel', $tel, PDO::PARAM_STR);
        $stmt->bindParam('Dir', $dir, PDO::PARAM_STR);
        $stmt->bindParam('Usu', $usu, PDO::PARAM_STR);
        $stmt->bindParam('Id_rol', $id_rol, PDO::PARAM_INT);
        $stmt->bindParam('Id', $id, PDO::PARAM_INT);

        // Solo si hay contraseña nueva
        if (!empty($pwsd)) {
            $stmt->bindParam('Pass', $pwsd, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            return [
                "status" => "ok",
                "mensaje" => "Los datos del usuario han sido actualizados."
            ];
        } else {
            $errorInfo = $stmt->errorInfo();
            return [
                "status" => "error",
                "mensaje" => "No se pudo actualizar los datos del usuario.",
                "error" => $errorInfo
            ];
        }
    }


    public function listar_servicios()
    {
        $sql = "SELECT ds.id_detalle,c.nombre_completo AS nombre ,s.descripcion AS servicio,
                       p.nombre_plan AS plan,
                       p.velocidad,
                       p.precio,
                       tc.desc_conexion AS tipo_conexion,
                       ds.fecha_inicio,
                       ds.estado
                FROM 
                       detalles_servicios ds
                JOIN 
                       clientes c ON ds.id_cliente = c.id_cliente
                JOIN 
                        servicios s ON ds.id_servicio = s.id_servicio
                JOIN 
                        planes p ON ds.id_plan = p.id_plan
                JOIN 
                        tipo_conexion tc ON ds.id_tipo_conexion = tc.id_tipo_conexion";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
