<?php

class ClientesModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;'.'dbname=db_PFY;charset=utf8', 'root', '');
    }

    public function getCliente($dni){
        $sentencia = $this->db->prepare("SELECT * FROM cliente WHERE dni = :dni");
        $sentencia->bindParam(":dni", $dni, PDO::PARAM_INT);
        $sentencia->execute();
        $cliente = $sentencia->fetch(PDO::FETCH_OBJ);
        return $cliente;
    }
    
    public function altaNuevoCliente($nombre, $dni, $telefono, $direccion, $ejecutivo){
        $sentencia = $this->db->prepare("INSERT INTO cliente (nombre, dni, telefono, direccion, ejecutivo) VALUES (:nombre, :dni, :telefono, :direccion, :ejecutivo)");
        $sentencia->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $sentencia->bindParam(":dni", $dni, PDO::PARAM_INT);
        $sentencia->bindParam(":telefono", $telefono, PDO::PARAM_INT);
        $sentencia->bindParam(":direccion", $direccion, PDO::PARAM_STR);
        $sentencia->bindParam(":ejecutivo", $ejecutivo, PDO::PARAM_BOOL);
        $sentencia->execute();
        return $this->db->lastInsertId();
    }

    public function registroActividad($kms, $fechaAlta, $tipo_operacion, $idCliente){
        $sentencia = $this->db->prepare("INSERT INTO actividad (kms, fecha, tipo_operacion, id_cliente) VALUES (:kms, :fecha, :tipo_operacion, :id_cliente)");
        $sentencia->bindParam(":kms", $kms, PDO::PARAM_INT);
        $sentencia->bindParam(":fecha", $fechaAlta, PDO::PARAM_STR);
        $sentencia->bindParam(":tipo_operacion", $tipo_operacion, PDO::PARAM_STR);
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->execute();
    }

    public function altaTarjetaBussines($fechaAlta, $numeroTarjeta, $fechaCaducidad, $tipoTarjeta,  $idCliente){
        $sentencia = $this->db->prepare("INSERT INTO tarjeta (fecha_alta, nro_tarjeta, fecha_vencimiento, tipo_tarjeta, id_cliente) VALUES (:fecha_alta, :numero_tarjeta, :fecha_caducidad, :tipo_tarjeta, :id_cliente)");
        $sentencia->bindParam(":fecha_alta", $fechaAlta, PDO::PARAM_STR);
        $sentencia->bindParam(":numero_tarjeta", $numeroTarjeta, PDO::PARAM_INT);
        $sentencia->bindParam(":fecha_caducidad", $fechaCaducidad, PDO::PARAM_STR);
        $sentencia->bindParam(":tipo_tarjeta", $tipoTarjeta, PDO::PARAM_STR);
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->execute();
    }

    public function getTarjetasAsociadas($idCliente){
        $sentencia = $this->db->prepare("SELECT * FROM tarjeta WHERE id_cliente = :id_cliente");
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->execute();
        $tarjetas = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $tarjetas;
    }

    public function getActividad($idCliente){
        $sentencia = $this->db->prepare("SELECT * FROM actividad WHERE id_cliente = :id_cliente");
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->execute();
        $actividad = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $actividad;
    }

}