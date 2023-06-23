<?php
require_once('../../../../../include/db-conn.php');
try{
  $json = new \stdClass();
  $id =  $_REQUEST['id'];
  $stmt = $conn->prepare('call spc_InsumoUnico(:id);');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();
  $pkInsumoStock = $row['PKInsumosStock'];
  $identificador = $row['Identificador'];
  $nombre = $row['Nombre'];
  $tipoInsumo = $row['FKTipoInsumo'];
  $unidadMedida = $row['FKUnidadMedida'];
  $cantidadMin = $row['CantidadMinima'];
  $cantidadExi = $row['CantidadExistencia'];
  $descripcionBreve = $row['DescripcionBreve'];  
  $descripcionLarga = $row['Descripcion'];  
  $fechaActualizacion = $row['Fecha_Actualizacion'];
  $nomusuario = $row['Nombres'];
  $aPusuario = $row['PrimerApellido'];
  $aMusuario = $row['SegundoApellido'];
  $estatusInsumo = $row['FKEstatusInsumo'];
  $costo = $row['Costo'];

  $json->pkInsumoStock = $pkInsumoStock;
  $json->identificador = $identificador;
  $json->nombre = $nombre;
  $json->tipoInsumo = $tipoInsumo;
  $json->unidadMedida = $unidadMedida;
  $json->cantidadMin = $cantidadMin;
  $json->cantidadExi = $cantidadExi;
  $json->descripcionBreve = $descripcionBreve;
  $json->descripcionLarga = $descripcionLarga;
  $json->fechaActualizacion = $fechaActualizacion;
  $json->nomusuario = $nomusuario;
  $json->aPusuario = $aPusuario;
  $json->aMusuario = $aMusuario;
  $json->estatusInsumo = $estatusInsumo;
  $json->costo = $costo;
  $json = json_encode($json);
  echo $json;
  //header('Location:editar_puesto.php');
}catch(PDOException $ex){
  echo $ex->getMessage();
}
