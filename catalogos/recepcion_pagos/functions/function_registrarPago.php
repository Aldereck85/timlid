<?php
session_start();
require_once('../../../include/db-conn.php');

    $usuario=$_SESSION['PKUsuario'];
    $empresa=$_SESSION['IDEmpresa'];
    $_proveedor = $_REQUEST['_proveedor'];
    $_referencia = trim($_REQUEST['_referencia']);
    $_cuentaCobrar = $_REQUEST['_cuentaCobrar'];
    $_FormaPago=$_REQUEST['_FormaPago'];
    $_cadena_CP = "";//cadena a utilizar en el caso de una cuenta por pagar 
    $greenFlag=true; //bandera para saber si se puede continuar o no con el guardado de datos  
    $_cuentasTipos = $_REQUEST['_cuentasTipos'];

    //se convierte en un array la cadena para contar las facturas a pagar.
    $array_cadena = explode(",", $_cuentaCobrar);
    $count_cadena = count($array_cadena);

    //comprueba que se pueda convertir a decimal
    for($i=0; $i<$count_cadena; $i++){
        $aux = explode('-',$array_cadena[$i]);
        if(floatval($aux[1])==0){
            $greenFlag=false;
            $data['estatus']='err-v';
            $data['result']="Se han intentado ingresar carácteres no válidos en el Importe";
        }
    }

    //validación de tamaño de inputs
    if(strlen($_REQUEST['Comentarios'])>400 || strlen($_REQUEST['_referencia'])>1000){
        $greenFlag=false;
        $data['estatus']='err-v';
        $data['result']="Tamaño de caracteres excedido";
    }

    //validación para forma de pago diferente a "por definir"
    if($_FormaPago==22){
        $greenFlag=false;
        $data['estatus']='err-v';
        $data['result']='Forma de pago no puede ser "Por definir"';
    }

    if($greenFlag){
        $_comentarios = $_REQUEST['Comentarios'];
        $_total = number_format($_REQUEST['total'], 6, '.', '');
        $tipo_movi = $_REQUEST['tipo_movi'];
        $_origenCE = $_REQUEST['_origenCE'];
        $_cuentaDest=$_REQUEST['_cuentaDest'];
        $_fechaPago=$_REQUEST['_fechaPago'];
        $_tipoPago=$_REQUEST['_tipoPago'];

        try{
            //inserciones a la base de datos
            $query=sprintf('CALL spc_tablaDetalle_cuentasCobrar(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt = $conn->prepare($query);
            $stmt->execute(array($usuario,$_proveedor,$_referencia,$_cuentaCobrar, $_cadena_CP, $count_cadena,$_tipoPago,$_comentarios,$_total, $tipo_movi, $_origenCE, $_cuentaDest, $_fechaPago, $empresa, $_FormaPago, $_cuentasTipos, 1, 1)); 
            $folio = $stmt->fetchAll();
            $data['estatus']='ok';
            $data['folio']=$folio[0]['codigo'];
            $_SESSION["actualizadoCPC"]=1;
        }catch(Exception $e){
            $data['estatus']='err';
            $data['result']=$e;
        }
    }
    
    echo json_encode($data);
?>