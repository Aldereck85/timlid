<?php
include_once("clases.php");
$array = "";
if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

  switch($_REQUEST['clase']){
    case "get_data":
      $data = new get_data();
      switch ($_REQUEST['funcion']){
        case 'get_entriesTable':
          $json = $data->getEntriesTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case 'get_entriesSelect':
          $json = $data->getEntriesSelect();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        break;
        case "get_brands":
          $json = $data->getBrands();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_Cotizaciones":
          $json = $data->getCotizaciones();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_accionProductoTable":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getAccionProductoTable($pkProducto);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_accionProductoTableTemp":
          $pkUsuario = $_REQUEST['data'];
          $json = $data->getAccionProductoTableTemp($pkUsuario);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_categoriasTable":
          $json = $data->getCategoriasTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_marcasTable":
          $json = $data->getMarcasTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_tipoProductoTable":
          $json = $data->getTipoProductoTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_clientesTable":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getClientesTable($pkProducto);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_estatusGral":
          $json = $data->getCmbEstatusGral();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_categoria":
          $json = $data->getCmbCategoria();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_marca":
          $json = $data->getCmbMarca();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_tipo":
          $json = $data->getCmbTipo();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_tipo_orden":
          $json = $data->getCmbTipoOrden();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_costouni_compra":
          $json = $data->getCmbCostouniCompra();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_costouni_venta":
          $json = $data->getCmbCostouniVenta();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_impuestos":
          $json = $data->getCmbImpuestos();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_tasa_impuestos":
          $pkImpuesto = $_REQUEST['data'];
          $json = $data->getCmbTasaImpuestos($pkImpuesto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_acciones_producto":
          $json = $data->getCmbAccionesProducto();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_proveedor":
          $json = $data->getCmbProveedor();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_condicionPago":
          $json = $data->getCmbCondicionPago();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_unidadM_proveedor":
          $pkProveedor = $_REQUEST['data'];
          $json = $data->getCmbUnidadMProveedor($pkProveedor);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_regimen":
          $json = $data->getCmbRegimen();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;   
        case "get_cmb_vendedor":
          $json = $data->getCmbVendedor();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_mediosContacto":
          $json = $data->getCmbMedioContacto();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_categoria":
          $pkCategoria = $_REQUEST['datos'];
          $json = $data->getDatosCategoria($pkCategoria);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_CotizacionAdd":
          $PKCotizacion = $_REQUEST['data'];
          $json = $data->getDatosCotizacion($PKCotizacion);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_ListadoProductosCotizacionAdd":
          $PKCotizacion = $_REQUEST['data'];
          $json = $data->getDatosListadoProductosCotizacionAdd($PKCotizacion);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_ListadoImpuestosProductos":
          $PKProducto = $_REQUEST['data'];
          $PKCotizacion = $_REQUEST['data2'];
          $json = $data->getdatosListadoImpuestosProductos($PKProducto, $PKCotizacion);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_clientes_producto":
          $json = $data->getCmbClientesProducto();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_cmb_direccionesEnvio":
          $pkCliente = $_REQUEST['data'];
          $json = $data->getCmbDireccionesEnvio($pkCliente); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_clienteCombo":
          $json = $data->getClienteCombo(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;  
        case "get_sucursalCombo":
          $json = $data->getSucursalCombo(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break; 
        case "get_productoCombo":
          $value = $_REQUEST['value'];
          $json = $data->getProductoCombo($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_vendedorCombo":
          $json = $data->getCmbVendedor(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DireccionesEnviosCliente":
          $pkCliente = $_REQUEST['data'];
          $json = $data->getDireccionesEnviosCliente($pkCliente); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_marca":
          $pkMarca = $_REQUEST['datos'];
          $json = $data->getDatosMarca($pkMarca);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_tipoProducto":
          $pkTipoProducto = $_REQUEST['datos'];
          $json = $data->getDatosTipoProducto($pkTipoProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_tipoOrdenInventario":
          $pkTipoOrdenInventario = $_REQUEST['datos'];
          $json = $data->getDatosTipoOrdenInventario($pkTipoOrdenInventario);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DataDatosProducto":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getDataDatosProducto($pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DataDatosProductoCompuesto":
          $pkProducto = $_REQUEST['data'];
          $pkUsuario = $_REQUEST['data2'];
          $json = $data->getDataDatosProductoCompuesto($pkProducto, $pkUsuario);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DataFiscalProducto":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getDataFiscalProducto($pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_datos_proveedor_producto":
          $PKdatosProveedor = $_REQUEST['datos'];
          $json = $data->getDatosProveedorProducto($PKdatosProveedor);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DataInventarioProducto":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getDataInventarioProducto($pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_DataVentaProducto":
          $pkProducto = $_REQUEST['data'];
          $json = $data->getDataVentaProducto($pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_InventarioSucursal":
          $pkSucursal = $_REQUEST['data'];
          $pkProducto = $_REQUEST['data2'];
          $json = $data->getInventarioSucursal($pkSucursal, $pkProducto); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        case "get_vendedor":
            $json = $data->getVendedor(); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
        break;
        case "validar_claveInterna":
          $clave = $_REQUEST['data'];
          $json = $data->validarClaveInterna($clave);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_nombre":
          $clave = $_REQUEST['data'];
          $json = $data->validarNombre($clave);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_codigoBarras":
          $codigo = $_REQUEST['data'];
          $json = $data->validarCodigoBarras($codigo);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_impuestoProducto":
          $pkProducto = $_REQUEST['data'];
          $pkImpuesto = $_REQUEST['data2'];
          $json = $data->validarImpuestoProducto($pkProducto, $pkImpuesto);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_accionProducto":
          $pkAccion = $_REQUEST['data'];
          $pkProducto = $_REQUEST['data2'];
          $json = $data->validarAccionProducto($pkAccion,$pkProducto);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_accionProducto_temp":
          $pkAccion = $_REQUEST['data'];
          $pkUsuario = $_REQUEST['data2'];
          $json = $data->validarAccionProductoTemp($pkAccion,$pkUsuario);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_categoriaProducto":
          $categoria = $_REQUEST['data'];
          $json = $data->validarCategoriaProducto($categoria);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_marcaProducto":
          $marca = $_REQUEST['data'];
          $json = $data->validarMarcaProducto($marca);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_tipoProducto":
          $tipo = $_REQUEST['data'];
          $json = $data->validarTipoProducto($tipo);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_tipoOrdenInventario":
          $tipo = $_REQUEST['data'];
          $json = $data->validarTipoOrdenInventario($tipo);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_producto_compuesto_temp":
          $pkUsuario = $_REQUEST['data'];
          $pkProducto = $_REQUEST['data2'];
          $json = $data->validarProductoCompuestoTemp($pkUsuario, $pkProducto);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_proveedorProducto":
          $pkProducto = $_REQUEST['data'];
          $pkProveedor = $_REQUEST['data2'];
          $json = $data->validarProveedorProducto($pkProveedor, $pkProducto);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_claveProveedorProducto":
          $pkProveedor = $_REQUEST['data'];
          $clave = $_REQUEST['data2'];
          $json = $data->validarClaveProveedorProducto($pkProveedor, $clave);//Guardando el return de la función
          echo json_encode($json);
        break;
        case "validar_clienteProducto":
          $pkCliente = $_REQUEST['data'];
          $pkProducto = $_REQUEST['data2'];
          $json = $data->validarClienteProducto($pkCliente, $pkProducto);//Guardando el return de la función
          echo json_encode($json);
        break;
        //END JAVIER RAMIREZ
        case "get_typeEntries":
          $json = $data->getTypeEntries();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_users":
          $json = $data->getUsers();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
          case "get_productsEntries":
          $json = $data->getProductsEntries();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_products":
          $json = $data->getProducts();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_productsOther":
          $value = $_REQUEST['id'];
          $value1 = $_REQUEST['tipo'];
          $json = $data->getProductsOther($value,$value1);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
        break;
        case "get_idEntries":
          $json = $data->getIdEntries();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_warehouses":
          $json = $data->getWarehouses();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_outputsSelect":
          $json = $data->getOutputsSelect();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_outputsTable":
          $json = $data->getOutputsTable();//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
        case "get_provider":
          $json = $data->getProvider();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_clientsSelect":
          $json = $data->getClientsSelect();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_outputsClient":
          $value = $_REQUEST['data'];
          $json = $data->getOutputClient($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_manufacturingInput":
          $json = $data->getManufacturingInput();//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_typeEntry":
          $value = $_REQUEST['data'];
          $json = $data->getTypeEntry($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "get_productsEntriesTable":
          $value = $_REQUEST['id'];
          $json = $data->getProductsEntriesTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          return;
        break;
      }
    break;

    case "save_data":
      $data = new save_data();
      switch ($_REQUEST['funcion']){
        case "save_typeEntries":
          $value = $_REQUEST['data'];
          $json = $data->saveTypeEntries($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_entry":
          $value = $_REQUEST['data'];
          $json = $data->saveEntry($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosImpuesto":
          $array = $_REQUEST['datos'];
          $json = $data->saveDatosImpuesto($array);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosFiscales":
          $fkClave = $_REQUEST['datos'];
          $fkUnidad = $_REQUEST['datos2'];
          $fkProducto = $_REQUEST['datos3'];
          $json = $data->saveDatosFiscales($fkClave, $fkUnidad, $fkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
       /* case "save_datosProveedorProducto":
          $array = $_REQUEST['datos'];
          $json = $data->saveDatosProveedorProducto($array);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;*/
        case "save_datosInventario":
          $array = $_REQUEST['datos'];
          $json = $data->saveDatosInventario($array);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosTipoProducto":
          $array = $_REQUEST['datos'];
          $json = $data->saveDatosTipoProducto($array);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosTipoProducto_Temp":
          $pkAccion = $_REQUEST['data'];
          $pkUsuario = $_REQUEST['data2'];
          $json = $data->saveDatosTipoProductoTemp($pkAccion,$pkUsuario);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_categoria":
          $value = $_REQUEST['datos'];
          $json = $data->saveCategoria($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_marca":
          $value = $_REQUEST['datos'];
          $json = $data->saveMarca($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_tipoProducto":
          $value = $_REQUEST['datos'];
          $json = $data->saveTipoProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosProveedor":
          $value = $_REQUEST['datos'];
          $json = $data->saveDatosProveedor($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break; 
        case "save_clienteProducto":
          $cliente = $_REQUEST['datos'];
          $costoEsp = $_REQUEST['datos2'];
          $moneda = $_REQUEST['datos3'];
          $pkProducto = $_REQUEST['datos4'];
          $costoGral = $_REQUEST['datos5'];
          $monedaGral = $_REQUEST['datos6'];
          $json = $data->saveClienteProducto($cliente, $costoEsp, $moneda, $pkProducto, $costoGral, $monedaGral);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break; 
        case "save_datosVenta":
          $costoGral = $_REQUEST['datos'];
          $monedaGral = $_REQUEST['datos2'];
          $pkProducto = $_REQUEST['datos3'];
          $json = $data->saveDatosVenta($costoGral, $monedaGral, $pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "save_datosClienteCotizacion":
          $nombreComercial = $_REQUEST['datos'];
          $medioContactoCliente = $_REQUEST['datos2'];
          $vendedor = $_REQUEST['datos3'];
          $email = $_REQUEST['datos4'];
          $razonSocial = $_REQUEST['datos5'];
          $rfc = $_REQUEST['datos6'];
          $pais = $_REQUEST['datos7'];
          $estado = $_REQUEST['datos8'];
          $cp = $_REQUEST['datos9'];
          $regimen = $_REQUEST['datos10'];
          $telefono = $_REQUEST['datos11'];
          $json = $data->saveDatosClienteCotizacion($nombreComercial, $medioContactoCliente, $vendedor, $telefono, $email, $razonSocial, $rfc, $pais, $estado, $cp, $regimen);//Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
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
        case "edit_categoria":
          $estatus = $_REQUEST['datos'];
          $categoria = $_REQUEST['datos2'];
          $id = $_REQUEST['datos3'];
          $json = $data->editCategoria($estatus,$categoria, $id);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_marca":
          $estatus = $_REQUEST['datos'];
          $marca = $_REQUEST['datos2'];
          $id = $_REQUEST['datos3'];
          $json = $data->editMarca($estatus,$marca, $id);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_tipoProducto":
          $estatus = $_REQUEST['datos'];
          $tipoProducto = $_REQUEST['datos2'];
          $id = $_REQUEST['datos3'];
          $json = $data->editTipoProducto($estatus,$tipoProducto, $id);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_tipoOrdenInventario":
          $estatus = $_REQUEST['datos'];
          $tipoOrdenInventario = $_REQUEST['datos2'];
          $id = $_REQUEST['datos3'];
          $json = $data->editTipoOrdenInventario($estatus,$tipoOrdenInventario, $id);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "edit_datosInventario":
          $array = $_REQUEST['datos'];
          $json = $data->editDatosInventario($array);//Guardando el return de la función
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
        case "delete_impuesto_producto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteImpuestoProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_accion_producto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteAccionProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_accion_producto_temp":
          $value = $_REQUEST['datos'];
          $json = $data->deleteAccionProductoTemp($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_categoria":
          $value = $_REQUEST['datos'];
          $json = $data->deleteCategoria($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_marca":
          $value = $_REQUEST['datos'];
          $json = $data->deleteMarca($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_tipoProducto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteTipoProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_tipoOrdenInventario":
          $value = $_REQUEST['datos'];
          $json = $data->deleteTipoOrdenInventario($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_datosProductoCompTempAll":
          $value = $_REQUEST['datos'];
          $json = $data->deleteDatosProductoCompTempAll($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_datosProductoCompTemp":
          $pkUsuario = $_REQUEST['datos'];
          $pkProducto = $_REQUEST['datos2'];
          $json = $data->deleteDatosProductoCompTemp($pkUsuario, $pkProducto);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_proveedor_producto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteProveedorProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_cliente_producto":
          $value = $_REQUEST['datos'];
          $json = $data->deleteClienteProducto($value);//Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          return;
        break;
        case "delete_Producto":
          $value = $_REQUEST['data'];
          $json = $data->deleteProducto($value);//Guardando el return de la función
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
