<?php
  include_once("clases.php");
  $array = "";

  if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    
    switch ($_REQUEST['clase']) {
      case 'get_data':
        $data = new get_data();

        switch ($_REQUEST['funcion']) {
          case 'get_countCashRegisterAccounts':
            $json = $data->getCountCashRegisterAccounts(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_branchOffices':
            $json = $data->getBranchOffices(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_responsible':
            $json = $data->getResponsible(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_money':
            $json = $data->getMoney(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_cash_register':
            $value = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";
            $json = $data->getCashRegister($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_cash_registers':
            $value = $_POST['value'];
            $json = $data->getCashRegisters($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;

          case 'get_product':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $value2 = $_REQUEST['value2'];
            $json = $data->getProduct($value,$value1,$value2);
            //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_productsFormatTable':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $value2 = $_REQUEST['value2'];
            $value3 = $_REQUEST['value3'];
            $value4 = $_REQUEST['value4'];
            $json = $data->getProductsFormatTable($value,$value1,$value2,$value3,$value4);
            echo json_encode($json);
          break;

          case 'get_productsDatatable':
            $value = $_REQUEST['value'];
            $json = $data->getFormatDatatableSearchProducts($value); //Guardando el return de la función          
            echo $json; //Retornando el resultado al ajax
            break;

          case 'get_clientSelect':
            //$value = $_REQUEST['value'];
            $json = $data->getClientsSelect(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_taxSelect':
            $json = $data->getTaxSelect(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_rateOrFeeSelect':
            $value = $_REQUEST['value'];
            $json = $data->getRateOrFeeSelect($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_productCategories':
            $json = $data->getProductCategories(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_productTradeMark':
            $json = $data->getProductTradeMark(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;

          case 'get_clvProductServ':
            $json = $data->getClvProductServ(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_clvProductServSearch':
            $value = $_REQUEST['value'];
            $json = $data->getClvProductServSearch($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;

          case 'get_clvProductUnit':
            $json = $data->getClvProductUnit(); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          
          case 'get_clvProductUnitSearch':
            $value = $_REQUEST['value'];
            $json = $data->getClvProductUnidSearch($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_productTaxes':
            $value = $_REQUEST['value'];
            $json = $data->getProductTaxes($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_productTaxesTable':
            $value = $_REQUEST['value'];
            $json = $data->getProductTaxesTable($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            break;
          case 'get_subtotalsTableTemp':
            $value = $_REQUEST['value'];
            $json = $data->getSubtotalsTicket($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_peddingSales':
            $value = $_REQUEST['value'];
            $json = $data->getPeddingSales($value); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'get_peddingProductData':
            $value = $_REQUEST['value'];
            $json = $data->getPeddingProductData($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_productPrice':
            $value = $_REQUEST['value'];
            $json = $data->getProductPrice($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'get_countCashRegisterAccountsStatus':
            $value = $_REQUEST['value'];
            $json = $data->getCountCashRegisterAccountsStatus($value); 
            echo json_encode($json);
            break;
          case 'get_generalDataCashRegisterCut':
            $value = $_REQUEST['value'];
            $json = $data->getGeneralDataCashRegisterCut($value); 
            echo json_encode($json);
            break;
          case 'get_balacenPerPeriodDataCashRegisterCut':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->getBalacenPerPeriodDataCashRegisterCut($value,$value1); 
            echo json_encode($json);
            break;
          case 'get_totalsCountCashClosing':
            $value = $_REQUEST['value'];
            $json = $data->getTotalsCountCashClosing($value); 
            echo json_encode($json);
            break;
          case 'get_cashRegisterClosing':
            $value = $_REQUEST['value'];
            $json = $data->getCashRegisterClosing($value); 
            echo json_encode($json);
            break;
          case 'get_ifProductoKeyExist':
            $value = $_REQUEST['value'];
            $json = $data->getIfProductoKeyExist($value); 
            echo json_encode($json);
            break;
          case "get_claveReferencia":
            $json = $data->getClaveReferencia(); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "get_claveReferenciaEdit":
            $pkProducto = $_REQUEST['datos'];
            $json = $data->getClaveReferenciaEdit($pkProducto); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_ifProductoPrescription':
            $value = $_REQUEST['value'];
            $json = $data->getIfProductoPrescription($value);
            echo $json; //Retornando el resultado al ajax
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
          case "get_cmb_estados":
            $PKPais = $_REQUEST['pais'];
            $json = $data->getCmbEstados($PKPais);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          case "get_checkedIfExistNameCashRegister":
            $value = $_POST['value'];
            $json = $data->getCheckedIfExistNameCashRegister($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "get_currentBalance":
            $value = $_POST['value'];
            $json = $data->getCurrentBalance($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "get_allTickets":
            $value = $_POST['value'];
            $json = $data->getAllTickets($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
            break;
          case 'get_countBranchOffice':
            $json = $data->getCountBranchOffice();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
            break;
          case 'get_lastTicket':
            $json = $data->getLastTicket();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_productsTicketTaxUnique':
            $value = $_POST['value'];
            $date = $_POST['date'];
            $date1 = $_POST['date1'];
            $json = $data->getProductsTicketTaxUnique($value,$date,$date1);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_formatProductsInvoice':
            $value = $_POST['value'];
            $json = $data->getFormatProductsInvoice($value,$date,$date1);
            echo json_encode($json);
            break;
          case "get_formatInvoice":
            $value = $_POST['value'];
            $json = $data->getFormatInvoice($value);
            print_r($json);
            break;
          case 'get_generalInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getGeneralInvoice($value,$value1);
            echo $json;
            break;
          case 'get_taxGeneralInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getTaxGeneralInvoice($value,$value1);
            echo json_encode($json);
            break;
          case 'get_dataTicket':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getDataTicket("",$value,$value1);
            echo json_encode($json);
            break;
          case 'get_totalGeneralInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getTotalGeneralInvoice($value,$value1);
            echo json_encode($json);
            break;
          case 'get_productsGeneralInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getProductsGeneralInvoice($value,$value1);
            echo $json;
          break;

          case 'get_cfdiUse':
            $json = $data->getCfdiUse();
            echo json_encode($json);;
          break;

          case 'get_paidType':
            $json = $data->getPaidType();
            echo json_encode($json);
          break;

          case 'get_formatClientGeneralInvoice':
            $json = $data->getFormatClientGeneralInvoice();
            echo json_encode($json);
          break;

          case 'get_formatInvoiceGeneral':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getFormatInvoiceGeneral($value,$value1);
            echo json_encode($json);
          break;
          
          case 'get_idTicketGeneralInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getIdTicketGeneralInvoice($value,$value1);
            echo json_encode($json);
          break;

          case 'get_formatSaveDetailGeneralInvoice':
            // getFormatSaveDetailGeneralInvoice

            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getFormatSaveDetailGeneralInvoice($value,$value1);
            echo json_encode($json);
          break; 
         
          case 'get_productsTicketTax':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getProductsTicketTax("",$value,$value1);
            echo json_encode($json);
          break; 

          // getProductsTicketTaxUniqueTicketGeneral($value,$date,$date1)

            case 'get_formatInvoiceGeneral':
                $value = $_POST['value'];
                $value1 = $_POST['date'];
                $value2 = $_POST['date1'];
                $json = $data->getFormatInvoiceGeneral($value,$value1,$value2);
                echo json_encode($json);
            break; 

            case 'get_detailsTicket':
                $value = $_POST['value'];
                $json = $data->getDetailsTicket($value);
                echo $json;
            break;

            case 'get_ifPrinterNameExist':
                $value = $_POST['value'];
                $json = $data->getIfPrinterNameExist($value);
                echo $json; //Retornando el resultado al aja
            break;
            case 'check_priceZero':
                $value = $_POST['value'];
                $json = $data->checkPriceZero($value);
                echo $json; //Retornando el resultado al aja
            break;

            case 'get_passwordAdmin':
                $value = $_POST['value'];
                $json = $data->getPasswordAdmin($value);
                echo json_encode($json);//Retornando el resultado al aja
            break;

            case 'get_cajeros':
                $json = $data->getCajeros();
                echo json_encode($json);//Retornando el resultado al aja
            break;

            case 'get_admin':
                $json = $data->getEmployerNameFounder();
                echo json_encode($json);//Retornando el resultado al aja
            break;
        }
      break; 
      case 'save_data':
        $data = new save_data();
        switch($_REQUEST['funcion']){
          case 'save_cashRegisterAccount':
            $value = $_REQUEST['value'];
            $json = $data->saveCashRegisterAccount($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          
          case 'save_productCategory':
            $value = $_REQUEST['value'];
            $json = $data->saveProductCategory($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'save_productTradeMark':
            $value = $_REQUEST['value'];
            $json = $data->saveProductTradeMark($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'save_product':
            $value = $_REQUEST['value'];
            $json = $data->saveProduct($value); //Guardando el return de la función          
            echo json_encode($json);
            return;
          break;
          case 'save_productsTblTemp':
            $value = $_REQUEST['value'];
            $json = $data->saveProductsTblTemp($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'save_productsPedding':
            $value = $_REQUEST['value'];
            $json = $data->saveProductsPedding($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'save_peddingProductData':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->savePeddingProductData($value,$value1); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'save_ticketData':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $value2 = $_REQUEST['value2'];
            $json = $data->saveAllDataTicket($value,$value1,$value2); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;

          case 'save_accountMovementData':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->saveAccountMovementData($value,$value1); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;

          case 'save_allDataCashRegisterCut':
            $value = $_REQUEST['value'];
            $json = $data->saveAllDataCashRegisterCut($value);
            echo json_encode($json);
          break;

          case 'save_detailsGeneralInvoice':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->saveDetailsGeneralInvoice("",$value,$value1);
            print_r($json);
          break;

          case 'save_allDataGeneralInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $value2 = $_POST['value2'];
            $json = $data->saveAllDataGeneralInvoice($value,$value1,$value2);
            echo json_encode($json);
          break;
          
          
        }
      break;

      case 'delete_data':
        $data = new delete_data();
        switch($_REQUEST['funcion']){
          case 'delete_allProductsTableTemp':
            $value = $_REQUEST['value'];
            $json = $data->deleteAllProductsTableTemp($value); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'delete_productTableTemp':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->deleteProductTableTemp($value,$value1); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
      break;

      case 'update_data':
        $data = new update_data();
        switch ($_REQUEST['funcion']) {
          case 'update_product':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $json = $data->updateProduct($value,$value1); //Guardando el return de la función          
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'update_pendingSale':
            $value = $_REQUEST['value'];
            $json = $data->updatePendingSale($value);
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'update_productTicket':
            $value = $_REQUEST['value'];
            $json = $data->updateProductTicket($value);
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'update_currentBalance':
            $value = $_REQUEST['value'];
            $json = $data->updateCurrentBalance($value);
            echo json_encode($json); //Retornando el resultado al ajax
            break;

        //   case 'update_productStock':
        //     $value = $_REQUEST['value'];
        //     $json = $data->updateProductStock($value);
        //     echo json_encode($json); //Retornando el resultado al ajax
        //     break;

        //   case 'update_statusTicket':
        //     $value = $_REQUEST['value'];
        //     $value1 = $_REQUEST['value1'];
        //     $json = $data->updateStatusTicket($value,$value1);
        //     echo json_encode($json); //Retornando el resultado al ajax
        //     break;
          case 'update_printer':
            $value = $_REQUEST['value'];
            $json = $data->updatePrinter($value);
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case 'update_cancelTicketData':
            $value = $_REQUEST['value'];
            $value1 = $_REQUEST['value1'];
            $value2 = $_REQUEST['value2'];
            $json = $data->updateCancelTicketData($value,$value1,$value2);
            echo json_encode($json); //Retornando el resultado al ajax
          break;
        }
      break;
      
      case 'get_print':
        $data = new get_print();
        switch($_REQUEST['funcion']){
          case 'get_printTicket':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $value2 = $_POST['value2'];
            $date = $_POST['date'];
            $date1 = $_POST['date1'];
            $value3 = $_POST['value3'];
            $json = $data->getPrintTicket($value,$value1,$value2,$date,$date1,$value3);
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_formatTicketTax':
            $value = $_POST['value'];
            $date = $_POST['date'];
            $date1 = $_POST['date1'];
            $json = $data->getFormatTicketTax($value,$date,$date1);
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
        }
      break;

      case 'get_invoice':
        $data = new get_invoice();
        switch ($_REQUEST['funcion']) {
          // case 'create_invoice':
          //   $value = $_POST['value'];
          //   $json = $data->createInvoice($value);
          //   echo json_encode($json); //Retornando el resultado al ajax
          //   return;
          //   break;
          case 'create_generalInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $value2 = $_POST['value2'];
            $json = $data->createGeneralInvoice($value,$value1,$value2);
            echo json_encode($json);
          break;

          case 'get_pdfInvoice':
            $value = $_POST['value'];
            $value1 = $_POST['value1'];
            $json = $data->getPdfInvoice($value,$value1);
            echo $json;
          break;
        }
      break;

    }

  } else {
    echo "esta vacio";
  }

?>