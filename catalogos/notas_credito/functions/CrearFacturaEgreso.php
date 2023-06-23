<?php
session_start();
$ruta_api = "../../../";
$empresa = $_SESSION["IDEmpresa"];
$userid = $_SESSION["PKUsuario"];
require_once($ruta_api.'include/db-conn.php');
require_once($ruta_api.'include/functions_api_facturation.php');
require_once $ruta_api . 'vendor/facturapi/facturapi-php/src/Facturapi.php';
$idsFacturas =  $_REQUEST["idsF"];
$PKCliente =  $_REQUEST["clienteId"];
$objConceptos =  $_REQUEST["objConceptos"];
$importeTotal =  $_REQUEST["importeTotal"];
$api = new API();
$FDePago = $_REQUEST["FDePago"];
$relacionf = $_REQUEST["Relacion"];
$dbh;
$sucursal;

$folioMax;
$strinfolio = "";

$stmt = $conn->prepare("SELECT total_facturado from facturacion where id in($idsFacturas) and empresa_id = $empresa and estatus != 4;");
$stmt->execute();
$facturaRes = $stmt->fetch();

$stmt = $conn->prepare("SELECT ifnull(sum(nc.importe),0) as importe from notas_cuentas_por_cobrar nc inner join notas_cuentas_por_cobrar_has_facturacion ncf on ncf.notas_cuentas_por_cobrar_id = nc.id where ncf.facturacion_id in($idsFacturas) and nc.empresa_id = $empresa and nc.estatus = 1;");
$stmt->execute();
$res = $stmt->fetch();

if(($res['importe'] + $importeTotal) > $facturaRes['total_facturado']){
  $data['status']="warning";
  $data['result']="las NC se exceden del importe de la factura";
}else{
  //Seleccionar la clave de la forma de pago
    $query = sprintf("SELECT clave FROM formas_pago_sat where id=$FDePago;");
      $stmt = $conn->prepare($query);
      $stmt->execute();
    $FDePago = $stmt->fetchAll();
    $FDePago = $FDePago[0]["clave"];
    $stmt->closeCursor();

    $query = sprintf("SELECT id FROM sucursales where empresa_id = $empresa limit 1;");
    $stmt = $conn->prepare($query);
    $stmt->execute();
      $sucursal = $stmt->fetchAll();
      $sucursal = $sucursal[0]["id"];
      $stmt->closeCursor();


    //recuperación de los datos necesarios para facturapi
    //se recupera la key de la empresa
    $query = sprintf("select key_company_api key_company,key_user_company_api key_user from empresas where PKEmpresa = :id");
      $stmt = $conn->prepare($query);
      $stmt->bindValue(":id",$empresa);
      $stmt->execute();
    $key_company_api = $stmt->fetchAll();
    $stmt->closeCursor();

    //Select array clientes para la API
    $query = sprintf("SELECT cl.PKCliente id,cl.razon_social,cl.RFC rfc,cl.email, codigo_postal, crf.clave FROM clientes cl
    LEFT JOIN claves_regimen_fiscal crf on cl.regimen_fiscal_id = crf.id
    WHERE cl.PKCLiente = :id");
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":id",$PKCliente);
  $stmt->execute();

  $cliente = $stmt->fetchAll();
  $clave = 0;
  if($cliente[0]['clave'] == null || $cliente[0]['clave'] == ""){
    $clave = "616";
    /* echo("Cliente Clave2222: ");
    echo($clave); */
  }else{
    $clave = $cliente[0]['clave'];
    /* echo("Cliente Clave: ");
    echo($clave); */
  }

  $cliente_api = [
    "legal_name" => $cliente[0]['razon_social'],
    "tax_id" => $cliente[0]['rfc'],
    "tax_system" => $clave,
    "address" => array(
      "zip" => strval($cliente[0]['codigo_postal']),
      "country"=>"MEX"
    ),
    "email" => $cliente[0]['email']
  ];

    //Recupera el cliente y su id de la API
    $query = sprintf("SELECT PKCliente, id_api from clientes where PKCliente = $PKCliente;");
      $stmt = $conn->prepare($query);
      $stmt->execute();
    $cliente = $stmt->fetchAll();
    $stmt->closeCursor();


    ///Unused Ya no rellenamos con 0000
      /* if($folioMax != 1){
          $intFolio = intval($folioMax);
          $tamaño = (strlen($intFolio));
          $count = 1;
          while($count<=(6-$tamaño)){
              $strinfolio .="0";
              $count++;
          }
          $strinfolio .= $folioMax + 1;
        //  echo($strinfolio);
      }else{
          $strinfolio = "000001";
      } */

    $facturasRelacionadas = [];
    $related_documents = [];
    $FolioInternoF = "<h3>Folios internos de facturas relacionadas</h3>";
    $IdsFacturas = [];
    //Saca UUIDS de las facturas seleccionadas.
    $all = $conn->prepare("SELECT id, uuid, serie, folio from facturacion where ((estatus = 1) or (estatus = 2) or (estatus = 3)) and id in($idsFacturas) and empresa_id = $empresa");
      $all->execute();
      
      while (($row = $all->fetch()) !== false) {
        
        //// V3 ////
        array_push($facturasRelacionadas,$row["uuid"] );
        array_push($IdsFacturas,$row["id"]);
        $FolioInternoF .= "<h5>" . $row["serie"] ." ". $row["folio"] . "</h5>";
        //$facturasRelacionadas[]=["uuid"=>$row["uuid"]];
      }

      /// V4 ///
      $related_documents[] = array(
        "relationship" => $relacionf,
        "documents" => $facturasRelacionadas
      );
      //Variable bolanea ¿Incuye impuestos?
      $tax_included;
      $includLocaltaxe = false;
      
      //Array de conceptos
      $items;
    /* $dbh = $conn->beginTransaction();
  try{
  $conn->exec("INSERT INTO notas_cuentas_por_cobrar (folion_nota,num_serie_nota,importe,subtotal,sucursal_id,usuario_creo_id,uso_cfdi_id,metodo_pago_id,sat_tipo_ralcion_id,sat_moneda_id,factura_id,cliente_id) values ('tes001','test001',1,1,1,$userid,02,$FDePago,02,484,1,$PKCliente)");
  $foreinKey = $conn->lastInsertId();
  $conn->commit(); */
  /// Array de productos
  $items = [];
      //Recorre el array de conceptos, en valor estan los arrays hijos
      foreach ($objConceptos as $clave => $valor) {

        ///Guardara el producto actual al finalizar el algoritmo foreach
        $products = [];

        $clave_dePS = "84111506";
        $unit_key = $valor["C_Unidad"];
        $descriptionn = $valor["Descripcion"];
        $price = $valor["Importe"];
        $tax_included = $valor["TaxesInclud"];
      //  $conn->exec("INSERT INTO conceptos_notasCredit (notas_cuentas_por_cobrar_id,clave_dePS,unit_key,descriptionn,price) VALUES ($foreinKey,$clave_dePS,)");
        $tax_included = true;
        
        //Array de impuestos
        $taxes = [];
        $localtaxes =[];
        $AuxArrImpuestosGen = (($valor["taxesGen"]) ? ($valor["taxesGen"]) : 0);
        if($AuxArrImpuestosGen != 0){
          foreach($AuxArrImpuestosGen as $key => $value){
            $keysplode = explode(" ",$key);
            
            ///Si no es impuesto Local
            if(strpos($key, "Local") === false){
              $type=""; //Guardara el tipo TasaCuotaExento
              //Si es tipo tasa, convierte el valor entero a decimal (Porcentaje)
              if(strpos($key, "Tasa") != false){
                $type = "Tasa";
                $value = $value/100;
              }elseif(strpos($key, "Cuota") != false){
                $type = "Cuota";
              }elseif(strpos($key,"Exento") != false){
                $type = "Exento";
              } 
              /// Buscamos en la cadena key si es trasladado
              if(strpos($key, "Trasladado") == false){
                $TrasladadoRetenido = true;
              }else{
                ///Si trae "trasladado" es igual a true
                $TrasladadoRetenido = false;
              }
              //Busca si es retenido
              if(strpos($key, "Retenido") !== false){
                $TrasladadoRetenido = true;
              }
              /* print_r($TrasladadoRetenido); */
              //Construye array taxes
              $taxes[] = array(
                "type" => $keysplode[0],
                "rate" => $value,
                "factor" => $type,
                "withholding" => $TrasladadoRetenido
                //$conn->exec("INSERT into impuestos_conceptosNC()")
              );      
            }else{
              //Incluye impuestos locales a true
              $includLocaltaxe = true;
              $TrasladadoRetenido = false;
              /// Buscamos en la cadena key si es trasladado
              if(strpos($key, "Trasladado") == false){

              }else{
                ///Si trae "trasladado" es igual a true
                $TrasladadoRetenido = true;
              }
              //Busca si es retenido
              if(strpos($key, "Retenido") !== false){
                $TrasladadoRetenido = false;
              }
                //Convierte a tasa
                $value = $value/100;
              /* if(count($keysplode) >2 ){
                $TrasladadoRetenido = ($keysplode[2]=="Trasladado") ? false:true;
              } */

              //Borramos Local de Key, dejamos solo el nombre del impuesto
              $key = str_replace("Local", '',$key);
              $localtaxes[] = array(
                "rate" => $value,
                "type" => $key,
                "withholding" => $TrasladadoRetenido
              );
            }
            
          };
        }
        
      // print("Cantidad ".$valor["Cantidad"]." Descuento: ".$valor["Descuento"]);

        //Si trae impuestos mete el concepto con el array de impuestos 
        if($tax_included){
          if($includLocaltaxe){
            /// V4 ///
            $products[] = array(
              "product_key" => $valor["C_Producto_Servicio"],
              "unit_key" => $valor["C_Unidad"],
              "description" => $valor["Descripcion"],
              "price" => $valor["Importe"],
              "tax_included"=> $valor["TaxesInclud"],
              "taxes"=>$taxes,
              "local_taxes"=> $localtaxes
            );
          }else{
            /// V4 ///
            $products[] = array(
              "product_key" => $valor["C_Producto_Servicio"],
              "unit_key" => $valor["C_Unidad"],
              "description" => $valor["Descripcion"],
              "price" => $valor["Importe"],
              "tax_included"=> $valor["TaxesInclud"],
              "taxes"=>$taxes
            );
          }
          
          //Si no trae Impuestos mete el concepto sin impuestos
        }else{
          $products[] = array(
            "product_key" => $valor["C_Producto_Servicio"],
            "unit_key" => $valor["C_Unidad"],
            "description" => $valor["Descripcion"],
            "price" => $valor["Importe"]
          );
        }
          /// V4 /// 
        ///Guarda en Items El producto actual con su cantidad
        foreach($products as $arrayProduct){
          $items[] = [
            "product" => $arrayProduct,
            "quantity" => $valor["Cantidad_p"]
          ];
        }
        
      }
      //print_r($items);
      $folioMax;
    //Genera el Folio que se incertará a la NC
      $query = sprintf("SELECT folion_nota as idMax FROM notas_cuentas_por_cobrar where empresa_id = $empresa and tipo_nc = 1 ORDER BY fecha_captura desc limit 1;");
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $folioMax = $stmt->fetchAll();
      if($folioMax){
          $folioMax = $folioMax[0]["idMax"] + 1;
      }else{
      $folioMax = 1;
      }
      //Crea el array para la peticion.
      $invoice = array(
        "type" => \Facturapi\InvoiceType::EGRESO,
        "customer" => $cliente_api,
        "payment_form" => $FDePago,
        "related_documents" => $related_documents,
        "series" => "NC",
        "folio_number" => $folioMax,
        //"relation" => $relacionf,
        "pdf_custom_section" => $FolioInternoF,
        //"related" => $facturasRelacionadas,
        "items" => $items
      );
  /*   }catch (Exception $e){
      $conn->rollBack();
      echo "Failed: " . $e->getMessage();
    } */
    

      //Crear FActura
      $mensaje = $api->createInvoice($key_company_api[0]['key_company'],$invoice);
      /* print_r($mensaje); */
      $data['estatus']="err";
      //Si se creo correctamente se guardan los datos en la BD
      if(isset($mensaje->id)){
        if($mensaje->id !== "" && $mensaje->id !== null){
          //Abre una nueva transaccion.
          $dbh = $conn->beginTransaction();
        try{
          //Crea la query
          $query = sprintf("INSERT INTO notas_cuentas_por_cobrar (
            id_Nota_Facturapi,
            folion_nota,
            num_serie_nota,
            importe,
            subtotal,
            sucursal_id,
            usuario_creo_id,
            uso_cfdi_id,
            metodo_pago_id,
            sat_tipo_ralcion_id,
            sat_moneda_id,
            estatus,
            empresa_id,
            cliente_id,
            tipo_nc) values (
              :id_Nota_Facturapi,
              :folion_nota,
              :num_serie_nota,
              :importe,
              :subtotal,
              :sucursal_id,
              :usuario_creo_id,
              :uso_cfdi_id,
              :metodo_pago_id,
              :sat_tipo_ralcion_id,
              :sat_moneda_id,
              1,
              :empresa_id,
              :cliente_id,
              :tipoNC
            )");
            //Prepara la query
            $stmt = $conn->prepare($query);
            //Pasa los valores a la query
            $stmt->bindValue(":id_Nota_Facturapi",$mensaje->id);
            $stmt->bindValue(":folion_nota",$mensaje->folio_number);
            $stmt->bindValue(":num_serie_nota",'NC');
            $stmt->bindValue(":importe",$mensaje->total);
            $stmt->bindValue(":subtotal",$mensaje->total);
            $stmt->bindValue(":sucursal_id",$sucursal);
            $stmt->bindValue(":usuario_creo_id",$userid);
            $stmt->bindValue(":uso_cfdi_id",02);
            $stmt->bindValue(":metodo_pago_id",$mensaje->payment_form);
            $stmt->bindValue(":sat_tipo_ralcion_id",02);
            $stmt->bindValue(":sat_moneda_id",484);
            $stmt->bindValue(":empresa_id",$empresa);
            $stmt->bindValue(":cliente_id",$PKCliente);
            $stmt->bindValue(":tipoNC",1);

          //Ejecuta la query
          $stmt->execute();
          //optiene el id de el registro recien insertado.
          $foreinKey = $conn->lastInsertId();

          foreach($IdsFacturas as $k => $val){
            $query5 = sprintf("INSERT INTO notas_cuentas_por_cobrar_has_facturacion (
              notas_cuentas_por_cobrar_id,
              facturacion_id) values (
                :notas_cuentas_por_cobrar_id,
                :facturacion_id)");
              $stmt = $conn->prepare($query5);
              $stmt->bindValue(":notas_cuentas_por_cobrar_id",$foreinKey);
              $stmt->bindValue(":facturacion_id",$val);
              $stmt->execute();
          }
          
          $item = $mensaje->items;
          foreach ($item as $clave => $valor){
            $query2 = sprintf("INSERT INTO conceptos_notasCredit (
              notas_cuentas_por_cobrar_id,
              clave_dePS,
              clave_Uni,
              cantidad,
              unidad,
              descripcion,
              valor_unitario,
              importe) values (
                :notas_cuentas_por_cobrar_id,
                :clave_dePS,
                :clave_Uni,
                :cantidad,
                :unidad,
                :descripcion,
                :valor_unitario,
                :importe
              )");
              $stmt = $conn->prepare($query2);
            //Optiene el concepto
            $AUXOBJProduct = $valor->product;
            $stmt->bindValue(":notas_cuentas_por_cobrar_id",$foreinKey);
            $stmt->bindValue(":clave_dePS",$AUXOBJProduct->product_key);
            $stmt->bindValue(":clave_Uni",$AUXOBJProduct->unit_key);
            $stmt->bindValue(":cantidad",$valor->quantity);
            $stmt->bindValue(":unidad",$AUXOBJProduct->unit_name);
            $stmt->bindValue(":descripcion",$AUXOBJProduct->description);
            $stmt->bindValue(":valor_unitario",$AUXOBJProduct->price);
            $Importe = (($AUXOBJProduct->price) * ($valor->quantity));
            $stmt->bindValue(":importe",$Importe);
            //Ejecuta la query
            $stmt->execute();
            $foreinKeyConcept = $conn->lastInsertId();
            //guarda el objeto de 
            if(isset($AUXOBJProduct->taxes)){
              $AUXTaxes = $AUXOBJProduct->taxes;
            //Recorre los Taxes del concepto
            foreach ($AUXTaxes as $key => $property){
              $type = $property->type;
              $query3 = sprintf("INSERT INTO impuestos_conceptosnc (
                tipo,
                rate,
                factor,
                withholding,
                conceptos_notasCredit_iddetalle_notasCredito) values (
                  :tipo,
                  :rate,
                  :factor,
                  :withholding,
                  :conceptos_notasCredit_iddetalle_notasCredito
                )");
                  $stmt = $conn->prepare($query3);
                $stmt->bindValue(":tipo",$property->type);
                $stmt->bindValue(":rate",$property->rate);
                $stmt->bindValue(":factor",$property->factor);
                if($property->withholding!=null || $property->withholding!=""){
                  $stmt->bindValue(":withholding",1);    
                }else{
                  $stmt->bindValue(":withholding",0);    
                }
                $stmt->bindValue(":conceptos_notasCredit_iddetalle_notasCredito",$foreinKeyConcept);    
                $stmt->execute();
            }
            }
            

            ///Guarda los Impiuestos locales.
            if(isset($AUXOBJProduct->local_taxes)){
              $AUXTaxesLocal = $AUXOBJProduct->local_taxes;
              //Recorre los Taxes del concepto
              foreach ($AUXTaxesLocal as $key => $property){
                $type = $property->type;
                $query4 = sprintf("INSERT INTO impuestos_conceptosnc (
                  tipo,
                  rate,
                  factor,
                  withholding,
                  conceptos_notasCredit_iddetalle_notasCredito) values (
                    :tipo,
                    :rate,
                    :factor,
                    :withholding,
                    :conceptos_notasCredit_iddetalle_notasCredito
                  )");
                    $stmt = $conn->prepare($query4);
                  $stmt->bindValue(":tipo",$property->type);
                  $stmt->bindValue(":rate",$property->rate);
                  $stmt->bindValue(":factor","Tasa");
                  if($property->withholding!=null || $property->withholding!=""){
                    $stmt->bindValue(":withholding",1);    
                  }else{
                    $stmt->bindValue(":withholding",0);    
                  }
                  $stmt->bindValue(":conceptos_notasCredit_iddetalle_notasCredito",$foreinKeyConcept);    
                  $stmt->execute();
              }
            }
            
          }
          $data['status']="ok";
          //Aplica los cambios 
          $conn->commit();
        }catch (Exception $e){
          $conn->rollBack();
          $data['status']="error";
          $data['result']=$e->getMessage();
        }
        echo($mensaje->id);
        $_SESSION["FacEgreso"] = "1";
        }else{
          $data['status']="error";
          $data['result']=$mensaje;
        }
        
      }else{
        $data['status']="error";
        $data['result']=$clave. ' : ' .$mensaje;
      }
}
echo json_encode($data);
    
?>