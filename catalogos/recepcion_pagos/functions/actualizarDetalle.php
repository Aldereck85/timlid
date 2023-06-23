<?php
session_start();
$empresa = $_SESSION["IDEmpresa"];
// Editar actualizar los datos de la pantalla edit
require_once('../../../include/db-conn.php');

$_PKREsponsable = $_SESSION["PKUsuario"];
$idpagos = $_POST['idpagos'];
$txtFecha = $_POST['txtFecha'];
$cuenta = $_POST['cuenta'];
$Referencia = trim($_POST['Referencia']);
$txtComentarios = $_POST['txtComentarios'];
$total =number_format($_REQUEST['txtTotal'], 6, '.', '');
$formaPago=$_POST['formaPago'];
$tipoCuenta=$_POST['tipoCuenta'];
$greenFlag=true; //bandera para saber si se puede continuar o no con el guardado de datos 

//validación de tamaño de inputs
if(strlen($_REQUEST['txtComentarios'])>400 || strlen($_REQUEST['Referencia'])>1000){
    $greenFlag=false;
    $data['estatus']='err-v';
    $data['result']="Tamaño de caracteres excedido";
}

//validación para forma de pago diferente a "por definir"
if($formaPago==22){
    $greenFlag=false;
    $data['estatus']='err-v';
    $data['result']='Forma de pago no puede ser "Por definir"';
}

if($tipoCuenta != 1 && $tipoCuenta != 2){
    $greenFlag=false;
    $data['estatus']='err-v';
    $data['result']='Tipo de cuenta por cobrar inexistente';
}

$stringToInsert =  $_POST['stringToInsert'];
    if($stringToInsert!=null){
        $array_stringToInsert = explode(",", $stringToInsert);
        $_count_cadena_insert = count($array_stringToInsert);

        //valida que se hayan ingresado numeros solamente
        for($i=0; $i<$_count_cadena_insert; $i++){
            $aux = explode('-',$array_stringToInsert[$i]);
            if(floatval($aux[1])==0){
                $greenFlag=false;
                $data['estatus']='err-v';
                $data['result']="Se han intentado ingresar carácteres no válidos al insertar";
            }
        }
    }else{
        $_count_cadena_insert = 0;
    }
$stringToDelete =  $_POST['stringToDelete'];
    if($stringToDelete!=null){
        $array_stringToDelete = explode(",", $stringToDelete);
        $_count_cadena_delete = count($array_stringToDelete);

        //valida que se hayan ingresado numeros solamente
        for($i=0; $i<$_count_cadena_delete; $i++){
            $aux = explode('-',$array_stringToDelete[$i]);
            if(floatval($aux[1])==0){
                $greenFlag=false;
                $data['estatus']='err-v';
                $data['result']="Se han intentado ingresar carácteres no válidos al eliminar";
            }
        }
    }else{
        $_count_cadena_delete = 0;
    }
$stringToUpdate= $_POST['stringToUpdate'];
    if($stringToUpdate!=null){
        $array_stringToUpdate = explode(",", $stringToUpdate);
        $_count_cadena_update = count($array_stringToUpdate);

        //valida que se hayan ingresado numeros solamente
        for($i=0; $i<$_count_cadena_update; $i++){
            $aux = explode('-',$array_stringToUpdate[$i]);
            if(floatval($aux[1])==0){
                $greenFlag=false;
                $data['estatus']='err-v';
                $data['result']="Se han intentado ingresar carácteres no válidos al editar";
            }
        }
    }else{
        $_count_cadena_update = 0;
    }

    $array_stringToUpdate = explode(",", $stringToUpdate);
    $_count_cadena_update = count($array_stringToUpdate);
    if($stringToUpdate==null){
        $_count_cadena_update = 0;
    }
$cuentaOrigen=300;
$_tipo_movimiento = 0;

if($greenFlag){
    $query = sprintf('call spu_tablaDetalle_pagos_cpc(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
    try{
        $stmt = $conn->prepare($query);
        $stmt->execute(array($idpagos,$total, $txtFecha,$Referencia,$_PKREsponsable,$stringToInsert, $_count_cadena_insert, $stringToDelete, $_count_cadena_delete, $stringToUpdate, $_count_cadena_update,$txtComentarios,$cuentaOrigen, $cuenta,$_tipo_movimiento,$empresa,$formaPago, $tipoCuenta));
        $data['estatus'] = "ok";
        $_SESSION["actualizadoCPC"]=2;
    } catch (PDOException $e) {
        $data['estatus'] = "err";
        $data['result'] = $e;
    } 
}

echo json_encode($data);       
?>