<?php
include_once("clases.php");
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

  switch ($_REQUEST['clase']) {
    case 'get_data':
      $data = new get_data();
      switch ($_REQUEST['funcion']) {
        case 'get_invoicesTable':
          $value = $_REQUEST['value'];
          $json = $data->getInvoicesTable($value); //Guardando el return de la función          
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_tableInvoiceFilterDate':
            $value = $_POST['value'];
            $dateini = $_POST['dateMin'];
            $datefin = $_POST['dateMax'];
            $json = $data->getTableInvoiceFilterDate($value,$dateini,$datefin);
            echo $json;
          break;
        case 'get_invoiceDetailTable':
          $value = $_REQUEST['value'];
          $json = $data->getInvoiceDetailTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_invoiceDetail':
          $value = $_REQUEST['value'];
          $json = $data->getInvoiceDetail($value); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'get_invoicesRelations':
          $value = $_REQUEST['value'];
          $json = $data->getInvoicesRelations($value); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'get_folioSerie':
          $json = $data->getFolioSerie(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
		    case 'get_folioSeriePreinvoice':
          $value = $_REQUEST['value'];
          $json = $data->getFolioSeriePreinvoice($value);
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_preinvoicePdf':
          $value = $_REQUEST['value'];
          $json = $data->getPreinvoicePdf($value);
          echo json_encode($json);
          break;
        case 'get_cfdiUse':
          $json = $data->getCfdiUse(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_cotizaciones':
          $value = $_REQUEST['value'];
          $json = $data->getCotizaciones($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_cotizacion':
          $value = $_REQUEST['value'];
          $json = $data->getCotizacion($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_formasPago':
          $json = $data->getFormasPago(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_monedas':
          $json = $data->getMonedas(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ventasDirectas':
          $value = $_REQUEST['value'];
          $json = $data->getVentasDirectas($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ventaDirecta':
          $value = $_REQUEST['value'];
          $json = $data->getVentaDirecta($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ordenesPedido':
          $value = $_REQUEST['value'];
          $json = $data->getOrdenesPedido($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ordenPedido':
          $value = $_REQUEST['value'];
          $json = $data->getOrdenPedido($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ventasOrigen':
          $value = $_REQUEST['value'];
          $json = $data->getVentasOrigen($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_ventasPagos':
          $value = $_REQUEST['value'];
          $json = $data->getVentasPagos($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_salidas':
          $value = $_REQUEST['value'];
          $json = $data->getSalidas($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_remisiones':
          $value = $_REQUEST['value'];
          $json = $data->getRemisiones($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_remision':
          $value = $_REQUEST['value'];
          $json = $data->getRemision($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_clienteCotizaciones':
          $json = $data->getClienteCotizaciones(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_clienteVentasDirectas':
          $json = $data->getClienteVentasDirectas(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_clientePedidos':
          $json = $data->getClientePedidos(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_clienteRemisiones':
          $json = $data->getClienteRemisiones(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_unidadesMedida':
          $json = $data->getUnidadesMedida(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_impuestos':
          $value = $_REQUEST['value'];
          $json = $data->getImpuestos($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_factorImpuestos':
          $value = $_REQUEST['value'];
          $json = $data->getFactorImpuestos($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_productosCotizacionTable':
          $value = $_REQUEST['value'];
          $json = $data->getProductosCotizacionTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_productosVentasTable':
          $value = $_REQUEST['value'];
          $json = $data->getProductosVentasTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_productosPedidoTable':
          $value = $_REQUEST['value'];
          $json = $data->getProductosPedidoTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_productosRemisionTable':
          $value = $_REQUEST['value'];
          $json = $data->getProductosRemisionTable($value); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_productosEditTable':
          $value = $_REQUEST['value'];
          $id_row = $_REQUEST['id_row'];
          $json = $data->getProductosEditTable($value, $id_row); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_impuestosProductosTable':
          $value = $_REQUEST['value'];
          $json = $data->getImpuestosProductosTable($value);
          echo $json;
          break;
        case 'get_impuestoTable':
          $producto = $_REQUEST['producto'];
          $id = $_REQUEST['id'];
          $json = $data->getImpuestoTable($producto, $id); //Guardando el return de la función
          echo $json; //Retornando el resultado al ajax
          break;
        case 'get_dataProduct':
          $id_row = $_REQUEST['id_row'];
          $json = $data->getDataProduct($id_row); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_claveSat':
          $value = $_REQUEST['value'];
          $json = $data->getClaveSat($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_unidadMedida':
          $value = $_REQUEST['value'];
          $json = $data->getUnidadMedida($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_claveSatTable':
          $json = $data->getClaveSatTable(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_claveSatTableSearch':
          $value = $_REQUEST['value'];
          $json = $data->getClaveSatTableSearch($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_claveUnidadMedidaTable':
          $json = $data->getClaveUnidadMedidaTable(); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_claveUnidadMedidaTableSearch':
          $value = $_REQUEST['value'];
          $json = $data->getClaveUnidadMedidaTableSearch($value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_productsAllSat':
          $value = $_REQUEST['value'];
          $tipo = $_REQUEST['tipo'];
          $ref = $_REQUEST['ref'];
          $json = $data->getProductsAllSat($value,$tipo,$ref); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'get_cancelInvoice':
          $value = $_REQUEST['value'];
          $client = $_REQUEST['email'];
          $motivo = $_REQUEST['motivo'];
          $factura_relacion = $_REQUEST['factura_relacion'];
          $json = $data->getCancelInvoice($value, $client, $motivo, $factura_relacion);
          echo json_encode($json);
          break;
        case 'get_statusCancelInvoice':
          $json = $data->getStatusCancelInvoice();
          echo json_encode($json);
          break;
        
        case 'get_productosSalidas':
          $value = $_REQUEST['value'];
          $json = $data->getProductosSalidas($value);
          echo $json;
          break;
        case 'get_totalSubtotalSalidas':
          $value = $_REQUEST['value'];
          $ref = $_REQUEST['ref'];
          $type = $_REQUEST['type'];
          $json = $data->getTotalSubtotalSalidas($value,$ref,$type);
          echo json_encode($json);
          break;
        case 'get_truncateTableProducts':
          $value = $_REQUEST['value'];
          $ref = $_REQUEST['ref'];
          $type = $_REQUEST['type'];
          $json = $data->getTruncateTableProducts($value,$ref,$type);
          echo json_encode($json);
          break;
        case 'get_clientes':
          $json = $data->getClientes();
          echo json_encode($json);
          break;
        case 'get_productos':
          $value = $_REQUEST['value'];
          $json = $data->getProductos($value);
          echo json_encode($json);
          break;
        case 'get_productosAll':
          $json = $data->getProductosAll();
          echo json_encode($json);
          break;
        case 'get_precio':
          $value = $_REQUEST['value'];
          $client = $_REQUEST['client'];
          $json = $data->getPrice($value,$client);
          echo json_encode($json);
          break;
        case 'get_precioAll':
          $value = $_REQUEST['value'];
          $json = $data->getPriceAll($value);
          echo json_encode($json);
          break;
        case 'get_lastUsoCFDI':
          $value = $_REQUEST['value'];
          $json = $data->getLastUsoCFDI($value);
          echo json_encode($json);
          break;
        case 'get_cuentasBancarias':
          $json = $data->getCuentasBancarias();
          echo json_encode($json);
          break;
        case 'get_vendedores':
          $json = $data->getVendedores();
          echo json_encode($json);
          break;
        case 'get_rfc':
          $idCliente = $_REQUEST['idCliente'];
          $json = $data->getRFCCliente($idCliente); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'get_clienteBilling':
          $idCliente = $_REQUEST['idCliente'];
          $json = $data->getClienteBilling($idCliente); //Guardando el return de la función
          echo json_encode($json);
          break;
        case 'get_creditNote':
          $value = $_REQUEST['value'];
          $json = $data->getCreditNote($value);
          echo json_encode($json);
          return;          
          break;
        case 'get_dataPreinvoice':
          $value = $_REQUEST['value'];
          $json = $data->getDataPreinvoice($value);
          echo json_encode($json);
        break;
        case 'get_productosPrefactura':
          $idPrefactura = $_REQUEST['value'];
          $json = $data->getProductosPrefactura($idPrefactura); //Guardando el return de la función
          echo $json;
          return;
          break;
        case 'get_com_pago':
          $value = $_POST['value'];
          $json = $data->getComPago($value); //Guardando el return de la función
          echo json_encode($json);
          break;

        case 'getAddressInvoiceClient':
          $client_id = $_POST['client_id'];
          $addres_invoice = $_POST['addres_invoice_id'];
          $json = $data->getAddressInvoiceClient($client_id,$addres_invoice);
          echo json_encode($json);
          return;
          break;

        case 'get_addressInvoiceCombo':
          $client_id = $_POST['value'];
          $json = $data->getAddressInvoiceCombo($client_id);
          echo json_encode($json);
          return;
          break;
        case 'get_dataClient':
          $client_id = $_POST['value'];
          $json = $data->getAddressInvoiceCombo($client_id);
          echo json_encode($json);
          return;
          break;
        case 'get_sucursales':
          $json = $data->getSucursales();
          echo json_encode($json);
          return;
        break;
        case 'get_stockProduct':
          $value = $_POST['value'];
          $value1 = $_POST['value1'];
          $json = $data->getStockProduct($value,$value1);
          echo json_encode($json);
          return;
        break;
        case 'get_ifHasQuotationOutputProduct':
          $value = $_POST['value'];
          $json = $data->getIfHasQuotationOutputProduct($value);
          echo json_encode($json);
        break;
        case 'get_ifHasSaleOutputProduct':
          $value = $_POST['value'];
          $json = $data->getIfHasSaleOutputProduct($value);
          echo json_encode($json);
        break;
        case 'get_stockProductAll':
          $value = $_POST['value'];
          $value1 = $_POST['value1'];
          $json = $data->getStockProductAll($value,$value1);
          echo json_encode($json);
        break;
        case 'get_ifProductHasStock':
          $value = $_POST['value'];
          $value1 = $_POST['value1'];
          $json = $data->getIfProductHasStock($value,$value1);
          echo json_encode($json);
        break;
        case 'get_countRelationSalesByTicket':
            $value = $_POST['value'];
            $json = $data->getCountRelationSalesByTicket($value);
            echo json_encode($json);
        break;
        case 'get_clientEmail':
            $value = $_POST['value'];
            $json = $data->getClientEmail($value);
            echo json_encode($json);
        break;
        case 'get_RfcClient':
            $value = $_POST['value'];
            $json = $data->getRfcClient($value);
            echo json_encode($json);
        break;
        case 'get_salesData':
            $initialDate = $_POST['initialDate'];
            $finalDate = $_POST['finalDate'];
            $json = $data->getSalesData($initialDate,$finalDate);
            echo $json;
        break;
        case 'get_taxesSales';
            $ids = $_POST['sales'];
            $json = $data->getTaxesSales($ids);
            echo json_encode($json);
        break;
        case 'get_taxSummary';
            $ids = $_POST['sales'];
            $json = $data->getTaxSummary($ids);
            echo json_encode($json);
        break;
        }
      break;
    case "save_data":
      $data = new save_data();
      switch ($_REQUEST['funcion']) {
        case 'save_factura':
          $datos = $_REQUEST['data'];
          $value = $_REQUEST['value'];
          $nota = $_REQUEST['data1'];
          $json = $data->saveFactura($datos,$nota, $value); //Guardando el return de la función
          echo json_encode($json); //Retornando el resultado al ajax
          break;
        case 'save_dataTaxes':
          $prod = $_REQUEST['producto'];
          $value = $_REQUEST['value'];
          $tasa = $_REQUEST['tasa'];
          $id = $_REQUEST['id'];
          $json = $data->saveDataTaxes($prod, $value, $tasa, $id);
          echo json_encode($json);
          break;
        case 'save_productoConcepto':
          $value = $_REQUEST['value'];
          $value1 = $_REQUEST['value1'];
          $json = $data->saveProductoConcepto($value,$value1);
          echo json_encode($json);
          break;
        case "save_personal":
            $nombre = $_REQUEST['nombre'];
            $apellido = $_REQUEST['apellido'];
            $genero = $_REQUEST['genero'];
            $roles = $_REQUEST['roles'];
            $estado = $_REQUEST['estado'];
            $json = $data->savePersonal($nombre, $apellido, $genero, $roles, $estado); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
        break;
        case "save_globalInvoice":
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->saveGlobalInvoice($value,$value1);
            echo json_encode($json);
        break;
      }
      break;
    case "delete_data":
      $data = new delete_data();
      switch ($_REQUEST['funcion']) {
        case 'delete_taxProducto':
          $ref = $_REQUEST['ref'];
          $prod = $_REQUEST['producto'];
          $value = $_REQUEST['value'];
          $id = $_REQUEST['id'];
          $tasa = $_REQUEST['tasa'];
          $tipo = $_REQUEST['tipo'];
          $json = $data->deleteTaxProducto($ref, $prod, $value, $tipo, $id, $tasa);
          echo json_encode($json);
          break;
        case 'delete_product':
          $value = $_REQUEST['value'];
          $json = $data->deleteProduct($value);
          echo json_encode($json);
          break;
        case 'delete_invoiceDataDoc':
            $value = $_REQUEST['value'];
            $json = $data->deleteInvoiceDataDoc($value);
            echo json_encode($json);
        break;
      }
      break;
    case "edit_data":
      $data = new edit_data();
      switch ($_REQUEST['funcion']) {
        case "edit_dataProducto":
          $value = $_REQUEST['value'];
          $json = $data->editDataProducto($value);
          echo json_encode($json);
          break;
        case "edit_claveSat":
          $value = $_REQUEST['value'];
          $prod = $_REQUEST['prod'];
          $json = $data->editClaveSat($value, $prod);
          echo json_encode($json);
          break;
        case "edit_claveSatUnidad":
          $value = $_REQUEST['value'];
          $prod = $_REQUEST['prod'];
          $json = $data->editClaveUnidadSat($value, $prod);
          echo json_encode($json);
          break;
        case "update_expiredDate":
          $value = $_REQUEST['value'];
          $expired_date = $_REQUEST['expired_date'];
          $json = $data->updateExpiredDate($value, $expired_date);
          echo json_encode($json);
          break;
        case "update_seller":
          $value = $_REQUEST['value'];
          $seller = $_REQUEST['seller'];
          $json = $data->updateSeller($value, $seller);
          echo json_encode($json);
        break;

        case "update_price":
            $value = $_REQUEST['value'];
            $json = $data->updateActionPrice($value);
        break;
      }
      break;
    case "send_data":
      $data = new send_data();
      switch ($_REQUEST['funcion']) {
        case "send_email":
          $value = $_REQUEST['value'];
          $arr = $_REQUEST['destinos'];
          $json = $data->sendEmail($value, $arr);
          echo json_encode($json);
          break;
      }
      break;
  }
}
