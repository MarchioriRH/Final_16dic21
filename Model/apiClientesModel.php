<?php

class ApiCientesModel{

    private $db;

    function __construct(){
        $this->db = new PDO('mysql:host=localhost;dbname=api_clientesPFY;charset=utf8', 'root', '');
    }

    public function getTarjetasAsociadas($idCliente){
        $sentencia = $this->db->prepare("SELECT * FROM tarjeta WHERE id_cliente = :id_cliente");
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->execute();
        $tarjetas = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $tarjetas;
    }

    public function getActividad($idCliente, $fechaInicio, $fechaFin){
        $sentencia = $this->db->prepare("SELECT * FROM actividad WHERE id_cliente = :id_cliente AND fecha BETWEEN :fechaInicio AND :fechaFin");
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $sentencia->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);
        $sentencia->execute();
        $actividad = $sentencia->fetchAll(PDO::FETCH_OBJ);
        return $actividad;
    }

    public function eliminarTarjetaAsociada($nro_tarjeta){
        $sentencia = $this->db->prepare("DELETE FROM tarjeta WHERE nro_tarjeta = :nro_tarjeta");
        $sentencia->bindParam(":id_cliente", $idCliente, PDO::PARAM_INT);
        $sentencia->bindParam(":nro_tarjeta", $nro_tarjeta, PDO::PARAM_STR);
        $sentencia->execute();
    }


}