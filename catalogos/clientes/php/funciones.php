<?php
include_once("clases.php");
$array = "";
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

  switch($_REQUEST['clase']){
    case "get_data":
      $data = new get_data();
      switch ($_REQUEST['funcion']){
        //JAVIER RAMIREZ

        /////////////////////////TABLAS//////////////////////////////
        case "get_clientesTable":
          $json = $data->getClientesTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_razonSocial_clientesTable":
          $pkCliente = $_REQUEST['data'];
          $json = $data->getRazonSocialClientesTable($pkCliente);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_contacto_clientesTable":
          $pkCliente = $_REQUEST['data'];
          $permissionEdit = $_REQUEST['data2'];
          $json = $data->getContactoClientesTable($pkCliente, $permissionEdit);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_banco_clientesTable":
          $pkCliente = $_REQUEST['data'];
          $permissionEdit = $_REQUEST['data2'];
          $json = $data->getBancoClientesTable($pkCliente, $permissionEdit);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_productosTable":
          $pkCliente = $_REQUEST['data'];
          $permissionEdit = $_REQUEST['data2'];
          $json = $data->getProductosTable($pkCliente, $permissionEdit);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_direccionEnvio_clientesTable":
          $pkCliente = $_REQUEST['data'];
          $permissionEdit = $_REQUEST['data2'];
          $json = $data->getDireccionEnvioClientesTable($pkCliente, $permissionEdit);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        /////////////////////////COMBOS//////////////////////////////
        case "get_cmb_estatusGral":
          $json = $data->getCmbEstatusGral();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_mediosContacto":
          $json = $data->getCmbMediosContacto($_SESSION['IDEmpresa']);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_vendedor":
          $json = $data->getCmbVendedor();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        case "get_cmb_CategoriaCliente":
          $json = $data->getCmbCategoriaCliente();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        case "get_cmb_regimen":
          $json = $data->getCmbRegimen();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_paises":
          $json = $data->getCmbPaises();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_estados":
          $pkPais = $_REQUEST['data'];
          $json = $data->getCmbEstados($pkPais);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_banco":
          $json = $data->getCmbBanco();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_producto_cliente":
          $json = $data->getCmbProductoCliente();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_costouni_venta":
          $json = $data->getCmbCostouniVenta();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_costouni_ventaEsp":
          $json = $data->getCmbCostouniVentaEsp();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        /////////////////////////VALIDACIONES//////////////////////////////
        case "validar_medioContactoCliente":
          $medioContacto = $_REQUEST['data'];
          $json = $data->validarMedioContactoCliente($medioContacto);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_nombreComercial":
          $nombreComercial = $_REQUEST['data'];
          $json = $data->validarNombreComercial($nombreComercial);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_estado":
          $estado = $_REQUEST['data'];
          $PKPais = $_REQUEST['data2'];
          $json = $data->validarEstado($estado, $PKPais);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_razonSocial_Cliente":
          $razonSocial = $_REQUEST['data'];
          $json = $data->validarRazonSocialCliente($razonSocial);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_rfc_Cliente":
          $rfc = $_REQUEST['data'];
          $json = $data->validarRfcCliente($rfc);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_contacto_cliente":
          $nombreContacto = $_REQUEST['data'];
          $apellidoContacto = $_REQUEST['data2'];
          /*$puesto = $_REQUEST['data3'];
          $email = $_REQUEST['data4'];*/
          $PKCliente = $_REQUEST['data5'];
          $json = $data->validarContactoCliente($nombreContacto, $apellidoContacto, $PKCliente);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_noCuenta":
          $noCuenta = $_REQUEST['data'];
          $json = $data->validarNoCuenta($noCuenta);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_CLABE":
          $clabe = $_REQUEST['data'];
          $json = $data->validarCLABE($clabe);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_datosBanarios_cliente":
          $pkBanco = $_REQUEST['data'];
          $noCuenta = $_REQUEST['data2'];
          $clabe = $_REQUEST['data3'];
          $pkCliente = $_REQUEST['data4'];
          $json = $data->validarDatosBanariosCliente($pkBanco, $noCuenta, $clabe, $pkCliente);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_producto_Cliente":
          $pkProducto = $_REQUEST['data'];
          $pkCliente = $_REQUEST['data2'];
          $json = $data->validarProductoCliente($pkProducto, $pkCliente);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_sucursal_Cliente":
          $sucursal = $_REQUEST['data'];
          $pkCliente = $_REQUEST['data2'];
          $json = $data->validarSucursalCliente($sucursal, $pkCliente);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_EmpresaCliente":
          $pkCliente = $_REQUEST['data'];
          $json = $data->validarEmpresaCliente($pkCliente); //Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_Permisos":
          $pkPantalla = $_REQUEST['data'];
          $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
          echo json_encode($json);
        break;
        case "valid_cp":
          $CP = $_REQUEST['data'];
          $json = $data->validarCP($CP); //Guardando el return de la función
          echo json_encode($json);
        break;
        case "get_cp":
          $colonia = $_REQUEST['data'];
          $json = $data->validarCP($colonia); //Guardando el return de la función
          echo json_encode($json);
        break;
        /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
        case "get_datos_fiscal_cliente":
          $pkRazonSocial = $_REQUEST['datos'];
          $json = $data->getDatosFiscalCliente($pkRazonSocial);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_contacto_cliente":
          $pkContacto = $_REQUEST['datos'];
          $json = $data->getDatosContactoCliente($pkContacto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_cuentaBancaria_cliente":
          $pkCuentaBancaria = $_REQUEST['datos'];
          $json = $data->getDatosCuentaBancariaCliente($pkCuentaBancaria);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_costoGral_producto":
          $pkProducto = $_REQUEST['datos'];
          $json = $data->getDatosCostoGralProducto($pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_generales_cliente":
          $pkCliente = $_REQUEST['datos'];
          $json = $data->getDatosGeneralesCliente($pkCliente);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_direccionEnvio_cliente":
          $pkDireccionEnvio = $_REQUEST['datos'];
          $json = $data->getDatosDireccionEnvioCliente($pkDireccionEnvio);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_ventas_cliente":
          $permisoRead = $_REQUEST['read'];
          $clienteId = $_REQUEST['clienteId'];
          $json = $data->getDatosVentaCliente($permisoRead, $clienteId);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_cotizaciones_cliente":
          $permisoRead = $_REQUEST['read'];
          $clienteId = $_REQUEST['clienteId'];
          $json = $data->getDatosCotizacionCliente($permisoRead, $clienteId);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_pedidos_cliente":
          $permisoRead = $_REQUEST['read'];
          $clienteId = $_REQUEST['clienteId'];
          $json = $data->getDatosPedidoCliente($permisoRead, $clienteId);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_pagos_cliente":
          $permisoRead = $_REQUEST['read'];
          $clienteId = $_REQUEST['clienteId'];
          $isfiltered=0;
          $Fdesde="no";
          $Fhasta="no";
          if(isset($_REQUEST['isfiltered'])){
            $isfiltered=$_REQUEST['isfiltered'];
            $Fdesde=$_REQUEST['fecha_desde'];
            $Fhasta=$_REQUEST['fecha_hasta'];
          }
          $json = $data->getDatosPagosCliente($permisoRead, $clienteId, $isfiltered, $Fdesde, $Fhasta);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        /////////////////////////COLUMNAS AJUSTABLES//////////////////////////////
        case "lista_columnas"://dato del proyecto que se eligió
					$json = $data->listaColumnas();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
        case "info_columnas"://dato del proyecto que se eligió
          if(isset($_REQUEST["array"]))
          $array = $_REQUEST["array"];
          $json = $data->infoColumnas($array);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
				break;
        case "orden_columnas"://dato del proyecto que se eligió
					$json = $data->ordenColumnas();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
        case "obtener_ids":
					$json = $data->obtenerIds();//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
          return;
				break;
        case "orden_datos":
					$sort = $_REQUEST["sort"];
          $indice = $_REQUEST["indice"];
          $search = $_REQUEST["search"];
					$json = $data->ordenDatos($sort,$indice,$search);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
        //END JAVIER RAMIREZ
      }
    break;

    case "data_order":
			$order = new data_order();
			switch ($_REQUEST['funcion']){
        #JAVIER RAMIREZ
				case "column_order"://dato del proyecto que se eligió
					if(isset($_REQUEST["ordenArray"]))
						$array = $_REQUEST["ordenArray"];
					$json = $order->columnOrder($array);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
        #JAVIER RAMIREZ
			}
		break;

    case "buscar_data"://Buscar info en ddbb
			$buscar = new buscar_data();//creando un nuevo objeto que referencia a la clase buscar data
			switch ($_REQUEST['funcion']){
        #JAVIER RAMIREZ
				case "buscar_cliente":
					$inputValue = $_REQUEST['data'];
					$array = $_REQUEST['array'];
					$json = $buscar->buscarCliente($inputValue,$array);
					echo json_encode($json);
					return;
        break;
        #END JAVIER RAMIREZ
        case "get_Costoproducto_Cliente":
					$pkRegistro = $_REQUEST['data'];
					$json = $buscar->getCostoProducto_Cliente($pkRegistro);
					echo json_encode($json);
					return;
        break;
			}
		break;

    case "save_data":
      $data = new save_data();
      switch ($_REQUEST['funcion']){
        //JAVIER RAMIREZ
        case "save_medioContactoCliente":
          $medioContacto = $_REQUEST['datos'];
          $json = $data->saveMedioContactoCliente($medioContacto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosCliente":
          $array = $_REQUEST['datos'];
          $nombreComercial = $_REQUEST['datos2'];
          $medioContactoCliente = $_REQUEST['datos3'];
          $vendedor = $_REQUEST['datos4'];
          $montoCredito = $_REQUEST['datos5'];
          $diasCredito = $_REQUEST['datos6'];
          $telefono = $_REQUEST['datos7'];
          $email = $_REQUEST['datos8'];
          $estatus = $_REQUEST['datos9'];
          $categoria = $_REQUEST['datos10'];
          /* $razonSocial = $_REQUEST['datos10'];
          $rfc = $_REQUEST['datos11'];
          $calle = $_REQUEST['datos12'];
          $numExt = $_REQUEST['datos13'];
          $numInt = $_REQUEST['datos14'];
          $colonia = $_REQUEST['datos15'];
          $municipio = $_REQUEST['datos16'];
          $pais = $_REQUEST['datos17'];
          $estado = $_REQUEST['datos18'];
          $cp = $_REQUEST['datos19'];
          $pkRazon = $_REQUEST['datos20'];
          $regimen = $_REQUEST['datos21']; */
          $contacto = $_REQUEST['datos22'];
          $json = $data->saveDatosCliente($array, $nombreComercial, $medioContactoCliente, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $contacto, $categoria);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_razonSocial_Cliente":
          $razonSocial = $_REQUEST['datos'];
          $rfc = $_REQUEST['datos2'];
          $calle = $_REQUEST['datos4'];
          $numExt = $_REQUEST['datos5'];
          $numInt = $_REQUEST['datos6'];
          $colonia = $_REQUEST['datos7'];
          $municipio = $_REQUEST['datos8'];
          $pais = $_REQUEST['datos9'];
          $estado = $_REQUEST['datos10'];
          $cp = $_REQUEST['datos11'];
          $pkCliente = $_REQUEST['datos12'];
          $pkRazonSocial = $_REQUEST['datos13'];
          $regimenFiscal = $_REQUEST['datos14'];
          $json = $data->saveRazonSocialCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $regimenFiscal);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_estado_pais":
          $estado = $_REQUEST['data'];
          $pais = $_REQUEST['data2'];
          $json = $data->saveEstadoPais($estado, $pais);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_contactoCliente":
          $nombreContacto = $_REQUEST['datos'];
          $apellidoContacto = $_REQUEST['datos2'];
          $puesto = $_REQUEST['datos3'];
          $telefonoFijo = $_REQUEST['datos4'];
          $celular = $_REQUEST['datos5'];
          $email = $_REQUEST['datos6'];  
          $pkCliente = $_REQUEST['datos7'];  
          $pkContacto = $_REQUEST['datos8']; 
          
          $isFacturacion = $_REQUEST['datos9']; 
          $isComplementoPago = $_REQUEST['datos10']; 
          $isAvisosEnvio = $_REQUEST['datos11']; 
          $isPagos = $_REQUEST['datos12']; 

          $json = $data->saveContactoCliente($nombreContacto, $apellidoContacto, $puesto, $telefonoFijo, $celular, $email, $pkCliente, $pkContacto, $isFacturacion, $isComplementoPago, $isAvisosEnvio, $isPagos);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_bancoCliente":
          $pkBanco = $_REQUEST['datos'];
          $noCuenta = $_REQUEST['datos2'];
          $clabe = $_REQUEST['datos3'];
          $pkCliente = $_REQUEST['datos4'];
          $pkCuentaBancaria = $_REQUEST['datos5'];
          $moneda = $_REQUEST['datos6'];
          $json = $data->saveBancoCliente($pkBanco, $noCuenta, $clabe, $pkCliente, $pkCuentaBancaria, $moneda);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_producto_Cliente":
          $pkProducto = $_REQUEST['datos'];
          $costoEsp = $_REQUEST['datos2'];
          $moneda = $_REQUEST['datos3'];
          $pkCliente = $_REQUEST['datos4'];
          $json = $data->saveProductoCliente($pkProducto, $costoEsp, $moneda, $pkCliente);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_direccionEnvio_Cliente":
          $sucursal = $_REQUEST['datos'];
          $email = $_REQUEST['datos3'];
          $calle = $_REQUEST['datos4'];
          $numExt = $_REQUEST['datos5'];
          $numInt = $_REQUEST['datos6'];
          $colonia = $_REQUEST['datos7'];
          $municipio = $_REQUEST['datos8'];
          $pais = $_REQUEST['datos9'];
          $estado = $_REQUEST['datos10'];
          $cp = $_REQUEST['datos11'];
          $pkCliente = $_REQUEST['datos12'];
          $pkDireccion = $_REQUEST['datos13'];
          $contacto = $_REQUEST['datos14'];
          $telefono = $_REQUEST['datos15'];
          $json = $data->saveDireccionEnvioCliente($sucursal, $email, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkDireccion, $contacto, $telefono);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        //END JAVIER RAMIREZ
      }
    break;

    case "edit_data":
      $data = new edit_data();
      switch ($_REQUEST['funcion']){
        case "edit_entry":
          $value = $_REQUEST['data'];
          $json = $data->editEntry($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        //JAVIER RAMIREZ
        case "edit_razonSocial_Cliente":
          $razonSocial = $_REQUEST['datos'];
          $rfc = $_REQUEST['datos2'];
          $calle = $_REQUEST['datos4'];
          $numExt = $_REQUEST['datos5'];
          $numInt = $_REQUEST['datos6'];
          $colonia = $_REQUEST['datos7'];
          $municipio = $_REQUEST['datos8'];
          $pais = $_REQUEST['datos9'];
          $estado = $_REQUEST['datos10'];
          $cp = $_REQUEST['datos11'];
          $pkCliente = $_REQUEST['datos12'];
          $pkRazonSocial = $_REQUEST['datos13'];
          $regimenFiscal = $_REQUEST['datos14'];
          $json = $data->editRazonSocialCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkCliente, $pkRazonSocial, $regimenFiscal);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_datosCliente":
          $nombreComercial = $_REQUEST['datos2'];
          $medioContactoCliente = $_REQUEST['datos3'];
          $vendedor = $_REQUEST['datos4'];
          $montoCredito = $_REQUEST['datos5'];
          $diasCredito = $_REQUEST['datos6'];
          $telefono = $_REQUEST['datos7'];
          $email = $_REQUEST['datos8'];
          $estatus = $_REQUEST['datos9'];
          $pkCliente = $_REQUEST['datos10'];
          $categoria = $_REQUEST['datos11'];
          $contacto = $_REQUEST['datos24'];
          $json = $data->editDatosCliente($nombreComercial, $medioContactoCliente, $vendedor, $montoCredito, $diasCredito, $telefono, $email, $estatus, $pkCliente, $categoria, $contacto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_datosFiscalesCliente":
          $razonSocial = $_REQUEST['datos12'];
          $rfc = $_REQUEST['datos13'];
          $calle = $_REQUEST['datos14'];
          $numExt = $_REQUEST['datos15'];
          $numInt = $_REQUEST['datos16'];
          $colonia = $_REQUEST['datos17'];
          $municipio = $_REQUEST['datos18'];
          $pais = $_REQUEST['datos19'];
          $estado = $_REQUEST['datos20'];
          $cp = $_REQUEST['datos21'];
          $pkRazon = $_REQUEST['datos22'];
          $regimen = $_REQUEST['datos23'];
          $json = $data->editDatosFiscalesCliente($razonSocial, $rfc, $calle, $numExt, $numInt, $colonia, $municipio, $pais, $estado, $cp, $pkRazon, $regimen);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_datosClientePredeterminado":
          $idContactoCliente = $_REQUEST['datos'];
          $json = $data->editDatosClientePredeterminado($idContactoCliente);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "update_costo_cliente":
          $pkRegistro = $_REQUEST['datos'];
          $Costo = $_REQUEST['datos2'];
          $moneda = $_REQUEST['datos3'];
          $json = $data->updateCostoCliente($pkRegistro, $Costo, $moneda);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        /////////////////////////COLUMNAS AJUSTABLES//////////////////////////////
        case "update_check_column"://dato del proyecto que se eligió
					$pkColumnaCliente = $_REQUEST["data"];
					$flag = $_REQUEST["flag"];
					$json = $data->updateCheckColumn($pkColumnaCliente,$flag);//Guardando el return de la función
					echo json_encode($json); //Retornando el resultado al ajax
					return;
				break;
        //END JAVIER RAMIREZ
      }
    break;

    case "delete_data":
      $data = new delete_data();
      switch ($_REQUEST['funcion']){
        //JAVIER RAMIREZ
        case "delete_razonSocial_Cliente":
          $value = $_REQUEST['datos'];
          $json = $data->deleteRazonSocialCliente($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_contacto_Cliente":
          $value = $_REQUEST['datos'];
          $json = $data->deleteContactoCliente($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_cuentaBancaria_Cliente":
          $value = $_REQUEST['datos'];
          $json = $data->deleteCuentaBancariaCliente($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_cliente_producto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteClienteProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_Cliente":
          $value = $_REQUEST['data'];
          $json = $data->deleteCliente($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_direccionEnvio_Cliente":
          $value = $_REQUEST['datos'];
          $json = $data->deleteDireccionEnvioCliente($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        //END JAVIER RAMIREZ
      }
    break;


    /*
    case "upload_file":
      $data = new upload_file();
      switch ($_REQUEST['funcion']) {
        case 'upload_xml_entry':
          $value = $_REQUEST['data'];
          //$file = $_FILES['data'];
          print_r($_REQUEST['data']);
          $json = $data->uploadXmlEntries($value);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
      }
    break;
    */

    case "delete_file":
      $data = new delete_file();
      switch ($_REQUEST['funcion']) {
        case 'delete_xml':
          $value = $_REQUEST['data'];
          $json = $data->deleteXml($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;

      }
    break;
  }


}


?>
