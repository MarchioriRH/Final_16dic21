<?php

include_once 'View/clientesView.php';
include_once 'Model/clientesModel.php'; 


class ClientesController {
    private $clientesView;
    private $clientesModel;
    function __construct() {
        $this->clientesView = new clientesView();
        $this->clientesModel = new clientesModel();
    }

    public function altaNuevoUsuario(){
        if($_SESSION['rol'] == 'admin'){
            $body = file_get_contents('php://input');
            $cliente = json_decode($body);
            if($cliente->nombre == null || $cliente->dni == null || $cliente->telefono == null || $cliente->direccion == null || $cliente->ejecutivo == null){
                $this->clientesView->mostrarError("Faltan datos");
            } else {
                $cliente = $this->tarjetasModel->getCliente($cliente->dni);
                if(!$cliente){
                    $idCliente = $this->clientesModel->altaNuevoCliente($cliente->nombre, $cliente->dni, $cliente->telefono, $cliente->direccion, $cliente->ejecutivo);
                    $this->clientesModel->registroActividad(200, $_POST['fechaAlta'], $_POST['tipo_operacion'], $idCliente);
                    if($cliente->ejecutivo == true){
                        $bussinesCard = $this->cardHelper->getBussinesCard();
                        $this->clientesModel->altaTarjetaBussines($bussinesCard->fechaAlta, $bussinesCard->numeroTarjeta, $bussinesCard->fechaCaducidad, $bussinesCard->tipoTarjeta,  $idCliente);
                    }
                    $this->clientesView->mostrarExito("cliente creado correctamente");
                } else {
                    $this->clientesView->mostrarError("El cliente ya existe");
                }
            }
        } else {
            $this->clientesView->mostrarError("No tienes permisos para realizar el alta de un nuevo cliente");
        }
    }

    
    public function resumenDeCuenta(){
        if($_SESSION['rol'] = 'admin'){
            if(isset($_POST['idCliente'])){
                $idCliente = $_POST['idCliente'];
                $cliente = $this->clientesModel->getCliente($idCliente);
                if ($cliente){
                    $tarjetasAsociadas = $this->clientesModel->getTarjetasAsociadas($idCliente);
                    $actividad = $this->clientesModel->getActividad($idCliente);
                    // supongo que cuando se cargan los puntos, dependiento el tipo de operacion, el numero va a ser
                    // positivo o negativo.
                    $kmsAcumulados = 0;
                    foreach($actividad as $actividad){
                        $kmsAcumulados += $actividad->kms;
                    }
                    $this->clientesView->mostrarResumenDeCuenta($cliente, $tarjetasAsociadas, $actividad, $kmsAcumulados);
                } else {
                    $this->clientesView->mostrarError("El cliente no existe");
                }
            } else {
                $this->clientesView->mostrarError("Faltan datos");
            }
        } else {
            $this->clientesView->mostrarError("No tienes permisos para consultar el resumen de cuenta de un cliente");
        }
    }

    public function transferenciaRapida(){
        if(isset($_SESSION['user'])){
            if(isset($_POST['idClienteDestinatario'])){
                $clienteDestinatario = $this->clientesModel->getCliente($_POST['idClienteDestinatario']);
                if($clienteDestinatario){
                    $actividadClienteDonante = $this->clientesModel->getActividad($_SESSION['idCliente']);
                    $kmsAcumuladosDonante = 0;
                    foreach($actividadClienteDonante as $actividad){
                        $kmsAcumuladosDonante += $actividad->kms;
                    }
                    if($kmsAcumuladosDonante->kms < $_POST['kmsDonados']){
                        $this->clientesView->mostrarError("No tienes suficientes kms para realizar la transferencia");
                    } else {
                        $this->clientesModel->registroActividad($_POST['kmsDonados'], $_POST['fechaAlta'], $_POST['tipo_operacion'], $_SESSION['idClienteDestinatario']);
                        $kmsDonados = $_POST['kmsDonados'];
                        $this->clientesModel->registroActividad(-$kmsDonados, $_POST['fechaAlta'], $_POST['tipo_operacion'], $_POST['idClienteDestinatario']);
                        $this->clientesView->mostrarExito("Transferencia realizada correctamente");
                    }
                } else {
                    $this->clientesView->mostrarError("El cliente destinatario no existe");
                }
            } else {
                $this->clientesView->mostrarError("Faltan datos");
            }
        } else {
            $this->clientesView->mostrarError("Debe estar logueado para poder realizar la transferencia");
        }
    }
}