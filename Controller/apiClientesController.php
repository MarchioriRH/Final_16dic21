<?php

include_once 'View/clientesView.php';
include_once 'Model/apiClientesModel.php';

class ApiClientesController {
    private $apiClientesView;
    private $clientesModel;

    function __construct() {
        $this->apiClientesView = new apiClientesView();
        $this->apiClientesModel = new apiClientesModel();
    }

    public function obtenerTarjetasAsociadas($params = []){
        $idCliente = $params[':ID'];
        $tarjetasAsociadas = $this->apiClientesModel->getTarjetasAsociadas($idCliente);
        if($tarjetasAsociadas)
            $this->apiClientesView->mostrarTarjetasAsociadas($tarjetasAsociadas);
        else
            $this->apiClientesView->mostrarError("El cliente no tiene tarjetas asociadas");
    }

    public function obtenerEstadoDeCuentaEntreFechas($params = []){
        $idCliente = $params[':ID'];
        $fechaInicio = $_GET[':fechaInicio'];
        $fechaFin = $_GET[':fechaFin'];
        $actividad = $this->apiClientesModel->getActividad($idCliente, $fechaInicio, $fechaFin);
        if($actividad)
            $this->apiClientesView->mostrarEstadoDeCuentaEntreFechas($actividad);
        else
            $this->apiClientesView->mostrarError("El cliente no tiene actividad en esas fechas");
    }

    public function eliminarTarjetaAsociada($params = []){
        $nro_tarjeta = $params[':ID'];
        $this->apiClientesModel->eliminarTarjetaAsociada($nro_tarjeta);
        $this->apiClientesView->mostrarMsje("Tarjeta eliminada correctamente");
    }

}