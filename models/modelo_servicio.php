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
        $sql = "SELECT servicios.id_servicio,servicios.fecha_creacion,servicios.estado,
                       clientes.nombre_completo,
                       tipo_conexion.nombre_conexion,
                       planes.nombre

                from servicios INNER JOIN clientes on servicios.id_cliente=clientes.id_cliente
                INNER JOIN tipo_conexion on servicios.id_tipoConexion=tipo_conexion.id_tipoConexion
                INNER JOIN planes on servicios.id_plan=planes.id_plan";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }


    //este es el modelo(dale)
    public function crear_servicio_modelo($id_cliente, $id_plan, $referencia_direccion, $id_tipoConexion, $datos_conexion, $fecha_creacion)
    {
        try {
            $estatus = "activo";

            // Insertar nuevo usuario
            $sql = "INSERT INTO servicios (id_cliente, id_plan, referencia_direccion, id_tipoConexion,datos_conexion,fecha_creacion, estado) 
                            VALUES (:id_cliente,:id_plan,:referencia_direccion,:id_tipoConexion,:datos_conexion,:fecha_creacion,:estado)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':referencia_direccion', $referencia_direccion);
            $stmt->bindParam(':id_tipoConexion', $id_tipoConexion);
            $stmt->bindParam(':datos_conexion', $datos_conexion);
            $stmt->bindParam(':fecha_creacion', $fecha_creacion);
            $stmt->bindParam(':estado', $estatus);

            if ($stmt->execute()) {
                return [
                    "status" => "ok",
                    "mensaje" => "Usuario registrado correctamente."
                ];
            } else {
                return [
                    "status" => "error",
                    "mensaje" => "Error al insertar usuario."
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
}
