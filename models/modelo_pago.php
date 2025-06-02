<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_pago
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }


    public function pago_servicio($id_cliente, $id_plan, $fecha_contrato)
    {
        try {

            // Insertar nuevo pago
            $sql = "INSERT INTO contratos_servicio(id_cliente, id_plan, id_servicio, id_tipo_conexion, fecha_contrato, estado, acceso_cliente, observaciones) 
                    VALUES (:id_cliente,:id_plan,:id_servicio,:id_tipo_conexion,:fecha_contrato,:estado,:acceso_cliente,:observaciones)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':id_plan', $id_plan);
            $stmt->bindParam(':fecha_contrato', $fecha_contrato);

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


    public function listar_pagos()
    {
        $sql = "SELECT ps.id_pago_servicio,c.nombre_completo AS cliente,
                       p.nombre_plan,
                       p.precio,
                       ps.estado_pago,
                       ps.fecha_pago
                FROM pago_servicio ps
                INNER JOIN clientes c ON ps.id_cliente = c.id_cliente
                INNER JOIN planes p ON ps.id_plan = p.id_plan
                INNER JOIN contratos_servicio cs ON ps.id_contrato = cs.id_contrato;
";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }
}
