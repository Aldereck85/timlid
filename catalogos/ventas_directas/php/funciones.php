<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {

                /////////////////////////TABLAS//////////////////////////////
                case "get_reportes_Table":
                  $vendedor = $_REQUEST['vendedor'];
                  $cliente = $_REQUEST['cliente'];
                  $estado = $_REQUEST['estado'];
                  $fechaInic = $_REQUEST['fechaInic'];
                  $fechaFin = $_REQUEST['fechaFin'];
                  $mes = $_REQUEST['mes'];
                  $res = $data->getReportesTable($vendedor, $cliente, $estado, $fechaInic, $fechaFin,$mes); //Guardando el return de la función
                  echo json_encode($res); //Retornando el resultado al ajax
                  return;
                break;
                case "get_totals_Table":
                    $vendedor = $_REQUEST['vendedor'];
                    $cliente = $_REQUEST['cliente'];
                    $estado = $_REQUEST['estado'];
                    $fechaInic = $_REQUEST['fechaInic'];
                    $fechaFin = $_REQUEST['fechaFin'];
                    $mes = $_REQUEST['mes'];
                    $res = $data->getTotalsReporting($vendedor, $cliente, $estado, $fechaInic, $fechaFin,$mes); //Guardando el return de la función
                    echo json_encode($res); //Retornando el resultado al ajax
                    return;
                  break;
                //JAVIER RAMIREZ
                case "get_ventaDirecta_Table":
                  $isPermissionsEdit = $_REQUEST['data'];
                  $isPermissionsDelete = $_REQUEST['data2'];
                  $json = $data->getVentaDirectaTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                  echo $json; //Retornando el resultado al ajax
                  return;
                break;
                case "get_ventasDirectasTempTable":
                  $pkUsuario = $_REQUEST['data'];
                  $pkSucursal = $_REQUEST['data2'];
                  $json = $data->getVentasDirectasTempTable($pkUsuario, $pkSucursal); //Guardando el return de la función
                  echo $json; //Retornando el resultado al ajax
                  return;
                break;
                case "get_ventaDirectaTableEdit":
                  $pkVenta = $_REQUEST['data'];
                  $rmdID = $_REQUEST['rmdID'];
                  $json = $data->getVentaDirectaTableEdit($pkVenta,$rmdID); //Guardando el return de la función
                  echo $json; //Retornando el resultado al ajax
                  return;
                break;
                case "get_ventaDirectaTableVer":
                  $pkVenta = $_REQUEST['data'];
                  $json = $data->getVentaDirectaTableVer($pkVenta); //Guardando el return de la función
                  echo $json; //Retornando el resultado al ajax
                  return;
                break;
                /////////////////////////COMBOS//////////////////////////////
                case "get_clienteCombo":
                  $json = $data->getClienteCombo(); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_productoCombo":
                  $value = $_REQUEST['value'];
                  $json = $data->getProductoCombo($value); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_productoComboEdit":
                  $value = $_REQUEST['value'];
                  $pkVenta = $_REQUEST['value2'];
                  $json = $data->getProductoComboEdit($value, $pkVenta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_todoProductoCombo":
                  $value = $_REQUEST['value'];
                  $json = $data->getTodosProductosCombo($value); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;  
                case "get_todoProductoComboEdit":
                  $value = $_REQUEST['value'];
                  $pkVenta = $_REQUEST['value2'];
                  $json = $data->getTodosProductosComboEdit($value, $pkVenta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;  
                case "get_sucursalCombo":
                  $json = $data->getSucursalCombo(); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;   
                case "get_cmb_vendedor":
                  $json = $data->getCmbVendedor();//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_cmb_regimen":
                  $json = $data->getCmbRegimen();//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_cmb_estados":
                  $PKPais = $_REQUEST['pais'];
                  $json = $data->getCmbEstados($PKPais);//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_cmb_mediosContacto":
                  $json = $data->getCmbMedioContacto();//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "resetProducts":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $json = $data->resetProducts($PKVentaDirecta);//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "clearEdicionestemp":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $json = $data->clearEdicionestemp($PKVentaDirecta);//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_cmb_condicionPago":
                  $json = $data->getCmbCondicionPago();//Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_cmb_direccionesEnvio":
                  $pkCliente = $_REQUEST['data'];
                  $json = $data->getCmbDireccionesEnvio($pkCliente); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
                case "get_datos_VentaDirectaEdit":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $json = $data->getDatosVentaDirectaEdit($PKVentaDirecta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_datos_VentaDirectaPDF":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $PKUsuario = $_REQUEST['data2'];
                  $json = $data->getDatosVentaDirectaPDF($PKVentaDirecta, $PKUsuario); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_datosImpu_VentaDirectaPDF":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $json = $data->getDatosImpuVentaDirectaPDF($PKVentaDirecta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_datosProd_VentaDirectaPDF":
                  $PKVentaDirecta = $_REQUEST['data'];
                  $json = $data->getDatosProdVentaDirectaPDF($PKVentaDirecta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                /////////////////////////VALIDACIONES//////////////////////////////
                case "validar_productoVentaDirecta":
                  $pkProducto = $_REQUEST['data'];
                  $pkUsuario = $_REQUEST['data2'];
                  $pkCliente = $_REQUEST['data3'];
                  $json = $data->validarProductoVentaDirecta($pkProducto, $pkUsuario, $pkCliente); //Guardando el return de la función
                  echo json_encode($json);
                break;
                case "validar_productoVentaDirectaEdit":
                  $pkProducto = $_REQUEST['data'];
                  $pkVentaDirecta = $_REQUEST['data2'];
                  $pkCliente = $_REQUEST['data3'];
                  $json = $data->validarProductoVentaDirectaEdit($pkProducto, $pkVentaDirecta, $pkCliente); //Guardando el return de la función
                  echo json_encode($json);
                break;
                case "validar_SucursalInventario":
                  $pkSucursal = $_REQUEST['data'];
                  $json = $data->validarSucursalInventario($pkSucursal); //Guardando el return de la función
                  echo json_encode($json);
                break;
                case "validar_estadoVentaDirecta":
                  $pkVenta = $_REQUEST['data'];
                  $json = $data->validarEstadoVentaDirecta($pkVenta); //Guardando el return de la función
                  echo json_encode($json);
                break;
                case "validar_Permisos":
                  $pkPantalla = $_REQUEST['data'];
                  $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
                  echo json_encode($json);
                break;
                /////////////////////////INFO//////////////////////////////
                case "get_referencia":
                  $json = $data->getReferencia(); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_fechaEmision":
                  $json = $data->getFechaEmision(); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_fechaVencimientoMin":
                  $json = $data->getFechaVencimientoMin(); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_subTotalVentaDirectaTemp":
                  $pkUsuario = $_REQUEST['datos'];
                  $json = $data->getSubTotalVentaDirectaTemp($pkUsuario); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_subTotalVentaDirectaEdit":
                  $pkVenta = $_REQUEST['datos'];
                  $json = $data->getSubTotalVentaDirectaEdit($pkVenta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_subTotalVentaDirectaVer":
                  $pkVenta = $_REQUEST['datos'];
                  $json = $data->getSubTotalVentaDirectaVer($pkVenta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_impuestoVentaDirectaTemp":
                  $pkUsuario = $_REQUEST['datos'];
                  $json = $data->getImpuestoVentaDirectaTemp($pkUsuario); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                ///Nueva version, IEPS grava IVA
                case "get_impuestoVentaDirectaTemp_v2":
                  $pkUsuario = $_REQUEST['datos'];
                  $json = $data->getImpuestoVentaDirectaEdit_v2(0,1); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_impuestoVentaDirectaEdit":
                  $pkVenta = $_REQUEST['datos'];
                  $isEdit = $_REQUEST['datos2'];
                  $json = $data->getImpuestoVentaDirectaEdit($pkVenta, $isEdit); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_impuestoVentaDirectaEdit_v2":
                  $pkVenta = $_REQUEST['datos'];
                  $isEdit = $_REQUEST['datos2'];
                  $json = $data->getImpuestoVentaDirectaEdit_v2($pkVenta, $isEdit); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_totalVentaDirectaTemp":
                  $pkUsuario = $_REQUEST['datos'];
                  $json = $data->getTotalVentaDirectaTemp($pkUsuario); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_totalVentaDirectaEdit":
                  $pkVenta = $_REQUEST['datos'];
                  $isEdit = $_REQUEST['datos2'];
                  $json = $data->getTotalVentaDirectaEdit($pkVenta, $isEdit); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_precioCliente":
                  $value = $_REQUEST['value'];
                  $value1 = $_REQUEST['value1'];
                  $value2 = $_REQUEST['value2'];
                  $json = $data->getPrecioCliente($value, $value1, $value2); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_InventarioSucursal":
                  $pkSucursal = $_REQUEST['data'];
                  $pkProducto = $_REQUEST['data2'];
                  $json = $data->getInventarioSucursal($pkSucursal, $pkProducto); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_VendedorCliente":
                  $pkCliente = $_REQUEST['data'];
                  $json = $data->getVendedorCliente($pkCliente); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "get_DireccionesEnviosCliente":
                  $pkCliente = $_REQUEST['data'];
                  $json = $data->getDireccionesEnviosCliente($pkCliente); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                /* END JAVIER RAMIREZ */
            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                case "save_venta_directaTemp":
                  $idproducto = $_REQUEST['datos'];
                  $cantidad = $_REQUEST['datos2'];
                  $pkUsuario = $_REQUEST['datos3'];
                  $pkProveedor = $_REQUEST['datos4'];
                  $precio = $_REQUEST['datos5'];
                  $precioEsp = $_REQUEST['datos6'];
                  $randomID = $_REQUEST['randomId'];
                  $json = $data->saveVentaDirectaTemp($idproducto, $cantidad, $pkUsuario, $pkProveedor, $precio, $precioEsp,$randomID); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "save_venta_directaEdit":
                  $idproducto = $_REQUEST['datos'];
                  $cantidad = $_REQUEST['datos2'];
                  $pkVentaDirecta = $_REQUEST['datos3'];
                  $pkProveedor = $_REQUEST['datos4'];
                  $precio = $_REQUEST['datos5'];
                  $precioEsp = $_REQUEST['datos6'];
                  $randomID = $_REQUEST['random'];
                  $json = $data->saveVentaDirectaEdit($idproducto, $cantidad, $pkVentaDirecta, $pkProveedor, $precio, $precioEsp,$randomID); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case 'save_VentaDirecta':
                  $referencia = $_REQUEST['datos'];
                  $fechaEmision = $_REQUEST['datos2'];
                  $fechaVencimiento = $_REQUEST['datos3'];
                  $cliente = $_REQUEST['datos4'];
                  $sucursal = $_REQUEST['datos5'];
                  $importe = $_REQUEST['datos6'];
                  $pkUsuario = $_REQUEST['datos7'];
                  $notasInternas = $_REQUEST['datos8'];
                  $notasCliente = $_REQUEST['datos9'];
                  $vendedor = $_REQUEST['datos10'];
                  $moneda = $_REQUEST['moneda'];
                  $subtotal = $_REQUEST['datos11'];
                  $direccionEnvioCliente = $_REQUEST['datos12'];
                  $condicionPago = $_REQUEST['datos13'];
                  $idrandom = $_REQUEST['randomID'];
                  $afectar_inventario = $_REQUEST['afectar_inventario'];
                  $json = $data->saveVentaDirecta($referencia, $fechaEmision,
                      $fechaVencimiento,
                      $cliente,
                      $sucursal,
                      $importe,
                      $pkUsuario,
                      $notasInternas,
                      $notasCliente,
                      $vendedor,
                      $moneda,
                      $subtotal,
                      $direccionEnvioCliente,
                      $condicionPago,
                      $idrandom,
                      $afectar_inventario
                    ); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case 'save_datosCliente':
                  $nombreComercial = $_REQUEST['datos2'];
                  $medioContactoCliente = $_REQUEST['datos3'];
                  $vendedor = $_REQUEST['datos4'];
                  $montoCredito = $_REQUEST['datos5'];
                  $diasCredito = $_REQUEST['datos6'];
                  $telefono = $_REQUEST['datos7'];
                  $email = $_REQUEST['datos8'];
                  $estatus = $_REQUEST['datos9'];
                  $razonSocial = $_REQUEST['datos10'];
                  $rfc = $_REQUEST['datos11'];
                  $pais = $_REQUEST['datos17'];
                  $estado = $_REQUEST['datos18'];
                  $cp = $_REQUEST['datos19'];
                  $pkRazon = $_REQUEST['datos20'];
                  $regimen = $_REQUEST['datos21'];
                  $json = $data->saveCliente_VentaDirecta($nombreComercial, $medioContactoCliente,
                      $vendedor,
                      $montoCredito,
                      $diasCredito,
                      $telefono,
                      $email,
                      $estatus,
                      $razonSocial,
                      $rfc,
                      $pais,
                      $estado,
                      $cp,
                      $pkRazon,
                      $regimen
                    ); 
                    echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case 'save_datosProd':

                  $is_from_Facturacion = $_REQUEST['is_from_Facturacion'];
                  $claveSat = 0;
                  $nombre = $_REQUEST['nombre'];
                  $clave = $_REQUEST['clave'];
                  $tipo = $_REQUEST['tipo'];
                  $cliente = $_REQUEST['cliente'];
                  $existencia = $_REQUEST['existenciaFabricacion'];
                  $unidadSat = $_REQUEST['unidadSat'];
                  $idSucursal = $_REQUEST['idSucursal'];

                  if(isset($_REQUEST['idImpuestosArray'])){
                    $idImpuestosArray = $_REQUEST['idImpuestosArray'];
                  }
                  else{
                    $idImpuestosArray = array();
                  }

                  if(isset($_REQUEST['tasaImpuestosArray'])){
                    $tasaImpuestosArray = $_REQUEST['tasaImpuestosArray'];
                  }
                  else{
                    $tasaImpuestosArray = array();
                  }

                  if($is_from_Facturacion == 1){
                    $claveSat = $_REQUEST['claveSat'];
                    $unidadSat = $_REQUEST['unidadSat']; 
                  }
                  $json = $data->saveProd_VentaDirecta($nombre, $clave, $tipo, $cliente, $is_from_Facturacion, $claveSat, $unidadSat, $existencia, $idSucursal, $idImpuestosArray, $tasaImpuestosArray); 
                    echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "save_VentaCopy_TableTemp":
                  $pkVenta = $_REQUEST['data'];
                  $randomID = $_REQUEST['rdmID'];
                  $json = $data->saveVentaCopyTableTemp($pkVenta,$randomID); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                //END JAVIER RAMIREZ
            }
            break;
            

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                case "edit_venta_directaTemp": //dato del proyecto que se eligió
                  $idproducto = $_REQUEST["datos"];
                  $cantidad = $_REQUEST["datos2"];
                  $pkUsuario = $_REQUEST["datos3"];
                  $pkCliente = $_REQUEST["datos4"];
                  $newPrecio = $_REQUEST["newprecio"];
                  $json = $data->editVentaDirectaTemp($idproducto, $cantidad, $pkUsuario, $pkCliente,$newPrecio); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "edit_venta_directaEdit": //dato del proyecto que se eligió
                  $idproducto = $_REQUEST["datos"];
                  $cantidad = $_REQUEST["datos2"];
                  $pkVentaDirecta = $_REQUEST["datos3"];
                  $pkCliente = $_REQUEST["datos4"];
                  $json = $data->editVentaDirectaEdit($idproducto, $cantidad, $pkVentaDirecta, $pkCliente); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "edit_VentaDirectaTemp_Cantidad": //dato del proyecto que se eligió
                  $idVentaTemp = $_REQUEST["datos"];
                  $cantidad = $_REQUEST["datos2"];
                  $precio = $_REQUEST["precio"];
                  
                  $json = $data->editVentaDirectaTempCantidad($idVentaTemp, $cantidad,$precio); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "edit_VentaDirectaEdit_Cantidad": //dato del proyecto que se eligió
                  $idVentaDetalle = $_REQUEST["datos"];
                  $cantidad = $_REQUEST["datos2"];
                  $precio = $_REQUEST["precio"];
                  $json = $data->editVentaDirectaEditCantidad($idVentaDetalle, $cantidad, $precio); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case 'edit_VentaDirecta':
                  $referencia = $_REQUEST['datos'];
                  $fechaEmision = $_REQUEST['datos2'];
                  $fechaVencimiento = $_REQUEST['datos3'];
                  $cliente = $_REQUEST['datos4'];
                  $sucursal = $_REQUEST['datos5'];
                  $importe = $_REQUEST['datos6'];
                  $pkVentaDirecta = $_REQUEST['datos7'];
                  $notasInternas = $_REQUEST['datos8'];
                  $notasCliente = $_REQUEST['datos9'];
                  $vendedor = $_REQUEST['datos10'];
                  $vendedor = $_REQUEST['datos10'];
                  $moneda = $_REQUEST['monedas'];
                  $subtotal = $_REQUEST['datos11'];
                  $direccionEnvioCliente = $_REQUEST['datos12'];
                  $condicionPago = $_REQUEST['datos13'];
                  $IdOprdm = $_REQUEST['rdm'];
                  $json = $data->editVentaDirecta($referencia, $fechaEmision,
                      $fechaVencimiento,
                      $cliente,
                      $sucursal,
                      $importe,
                      $pkVentaDirecta,
                      $notasInternas,
                      $notasCliente,
                      $vendedor,
                      $moneda,
                      $subtotal,
                      $direccionEnvioCliente,
                      $condicionPago,
                      $IdOprdm
                      ); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "edit_EstatusVentaDirecta": //dato del proyecto que se eligió
                  $PKVentaDirecta = $_REQUEST["datos"];
                  $Estado = $_REQUEST["datos2"];
                  $json = $data->editEstatusVentaDirecta($PKVentaDirecta, $Estado); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "edit_ventaDirectaFD": //dato del proyecto que se eligió
                  $PKVentaDirecta = $_REQUEST["data"];
                  $isFD = $_REQUEST["data2"];
                  $json = $data->editVentaDirectaFD($PKVentaDirecta, $isFD); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                //END JAVIER RAMIREZ
            }
            break;

        case "delete_data":
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                case "delete_VentaDirectaTempAll":
                  $value = $_REQUEST['data'];
                  $json = $data->deleteVentaDirectaTempAll($value); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "delete_VentaDirectaTemp":
                  $value = $_REQUEST['data'];
                  $json = $data->deleteVentaDirectaTemp($value); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "delete_VentaDirectaEdit":
                  $value = $_REQUEST['data'];
                  $PKVenta = $_REQUEST['Venta'];
                  $json = $data->deleteVentaDirectaEdit($value,$PKVenta); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                case "delete_ventaDirecta":
                  $value = $_REQUEST['data'];
                  $json = $data->deleteVentaDirecta($value); //Guardando el return de la función
                  echo json_encode($json); //Retornando el resultado al ajax
                  return;
                break;
                //END JAVIER RAMIREZ
            }
            break;
    }

}