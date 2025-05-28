<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class modelo_Usuario
{
    private $conn;

    public function __construct()
    {
        require_once("modelo_conexion.php");
        $this->conn = new Conexion();
        $this->conn->conectar();
    }

    public function Verificar_usuario($usuario, $password)
    {
        $sql = "SELECT usuario.id_usuario, usuario.usuario_nombres, usuario.usuario_tel, 
                       usuario.usuario_direccion, usuario.usuario_usu, usuario.usuario_pass, 
                       usuario.id_rol, usuario.usuario_estatus, rol.rol_nombre
                FROM usuario 
                INNER JOIN rol ON usuario.id_rol = rol.id_rol
                WHERE BINARY usuario.usuario_usu = :USUARIO";

        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam(':USUARIO', $usuario);
        $stmt->execute();

        $arreglo = [];

        while ($row = $stmt->fetch()) {
            if (password_verify($password, $row['usuario_pass'])) {
                $arreglo[] = $row;
            }
        }

        $this->conn->cerrar_conexion();
        return $arreglo; //retorna array
    }



    public function listar_usuarios()
    {
        $sql = "SELECT usuario.id_usuario, usuario.usuario_nombres, usuario.usuario_ced,usuario.usuario_tel,
                       usuario.usuario_direccion,usuario.usuario_usu, 
                       usuario.id_rol, usuario.usuario_estatus, rol.rol_nombre
                FROM usuario 
                INNER JOIN rol ON usuario.id_rol = rol.id_rol";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $respuesta; //retorna texto
    }



    public function listar_roles()
    {
        $sql = "SELECT rol.id_rol, rol.rol_nombre FROM  rol ";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->execute();
        $respuesta = $stmt->fetchAll();
        return $respuesta; //retorna texto
    }


    public function insertar_usuario($nombres, $dir, $tel, $user, $pswd, $ced, $id_rol)
    {
        try {
            $estatus = "activo";

            // Verificar si el usuario ya existe
            $sql_check = "SELECT COUNT(*) FROM usuario WHERE usuario_usu = :usuario_usu";
            $stmt_check = $this->conn->conexion->prepare($sql_check);
            $stmt_check->bindParam(':usuario_usu', $user);
            $stmt_check->execute();
            $existe = $stmt_check->fetchColumn();

            if ($existe > 0) {
                //array asociativo 
                return [
                    "status" => "existe",
                    "mensaje" => "El nombre de usuario ya existe en el sistema."
                ];
            }

            // Insertar nuevo usuario
            $sql = "INSERT INTO usuario (usuario_nombres, usuario_ced, usuario_tel, usuario_direccion, usuario_usu, usuario_pass, id_rol, usuario_estatus)
                VALUES (:usuario_nombres, :usuario_ced, :usuario_tel, :usuario_direccion, :usuario_usu, :usuario_pass, :id_rol, :usuario_estatus)";
            $stmt = $this->conn->conexion->prepare($sql);

            $stmt->bindParam(':usuario_nombres', $nombres);
            $stmt->bindParam(':usuario_ced', $ced);
            $stmt->bindParam(':usuario_tel', $tel);
            $stmt->bindParam(':usuario_direccion', $dir);
            $stmt->bindParam(':usuario_usu', $user);
            $stmt->bindParam(':usuario_pass', $pswd);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->bindParam(':usuario_estatus', $estatus);

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



    public function eliminar_usuarios($id)
    {
        $sql = "DELETE FROM usuario where id_usuario= :id";
        $stmt = $this->conn->conexion->prepare($sql);
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $respuesta = $stmt->execute();
        return $respuesta; //retorna texto
    }



    public function cambiar_estatus_usuario($id, $estatus)
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
