<?php
  session_start();
  require_once('../../../include/db-conn.php');
  $id = $_POST['idProducto'];
  $idCliente = $_POST['idCliente'];

  $json = new \stdClass();

  if(isset($_POST['idProducto'])){
    try{
      $stmt = $conn->prepare("SELECT cvp.CostoGeneral as PUGeneral, '1' as tipo
                                  FROM costo_venta_producto AS cvp 
                                        WHERE cvp.FKProducto = :idProducto
                                        UNION
                                        SELECT cepc.CostoEspecial as PUGeneral , '2' as tipo
                                  FROM costo_especial_producto_cliente as cepc
                                        WHERE cepc.FKCliente = :idCliente AND cepc.FKProducto = :idProducto2
                                        UNION
                                        SELECT cepv.costo_especial as PUGeneral , '3' as tipo
                                  FROM costo_especial_producto_vendedor as cepv
                                        WHERE cepv.vendedor_id = :vendedor_id AND cepv.producto_id = :idProducto3");
      $stmt->bindValue(":idProducto" , $id);
      $stmt->bindValue(":idCliente" , $idCliente);
      $stmt->bindValue(":idProducto2" , $id);
      $stmt->bindValue(":vendedor_id" , $_SESSION['PKUsuario']);
      $stmt->bindValue(":idProducto3" , $id);
      $stmt->execute();
      $row = $stmt->fetchAll();
      $cuenta = $stmt->rowCount();

      if($cuenta > 0){
        
        if($cuenta == 1){
            $json->Precio = number_format($row[0]['PUGeneral'],2);
        }

        if($cuenta == 2){
            $json->Precio = number_format($row[1]['PUGeneral'],2);
        }

        if($cuenta == 3){
            $json->Precio = number_format($row[2]['PUGeneral'],2);
        }
        
        
      }else{
        $json->Precio = "";
      }

      $stmt = $conn->prepare('SELECT i.PKImpuesto, ip.Tasa, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte, i.Operacion FROM info_fiscal_productos as ifp INNER JOIN impuestos_productos as ip ON ifp.PKInfoFiscalProducto = ip.FKInfoFiscalProducto INNER JOIN impuesto as i ON i.PKImpuesto = ip.FKImpuesto WHERE ifp.FKProducto= :id ORDER BY i.PKImpuesto ASC');
      $stmt->bindValue(":id" , $id);
      $stmt->execute();
      $row = $stmt->fetchAll();

      $tas = "";
      $impuestos = "<span class='impuestos_".$id."'>"; 
      $cant = count($row);
      $cont = 1;

      foreach($row as $r){
          $ImpuestoNombre = $r['Nombre'];
          $IniImpuesto = explode(" ", $ImpuestoNombre);
          $Identificador = $IniImpuesto[0]."_".$r['TipoImpuesto']."_".$r['TipoImporte']."_".$r['PKImpuesto']."_".$id;

          if($r['TipoImpuesto'] == 1){
            $tas = "%";
           }
          if($r['TipoImporte'] == 2 || $r['TipoImporte'] == 3){
            $tas = "";
          }

          $impuestos.= "<span id='".$Identificador."'>".$r['Nombre']." ".$r['Tasa'].$tas." <input name='valImp_".$r['Tasa']."' type='hidden' id='impAgregado_".$id."_".$r['PKImpuesto']."' value='".$r['Tasa']."' /><input type='hidden' id='OperacionUnica_".$id."_".$r['PKImpuesto']."' value='".$r['Operacion']."' /><input type='hidden' id='ImpuestoUnico_".$id."_".$r['PKImpuesto']."' value='".$r['Nombre']."' /></span>";

          if($cont < $cant)
            $impuestos.= "<br>";
          
          $cont++;
      }
      
      $impuestos.= "</span>";
                            

      $json->Impuestos = $impuestos;

      $stmt = $conn->prepare('SELECT csu.Descripcion FROM info_fiscal_productos as ifp INNER JOIN claves_sat_unidades as csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad WHERE ifp.FKProducto= :id');
      $stmt->bindValue(":id" , $id);
      $stmt->execute();
      $rowUnidad = $stmt->fetch();

      if($stmt->rowCount() > 0){
        $json->ClaveUnidad = $rowUnidad['Descripcion'];
      }
      else{
        $json->ClaveUnidad = 'Sin unidad';
      }

      $stmt = $conn->prepare('SELECT FKTipoProducto FROM productos WHERE PKProducto = :id');
      $stmt->bindValue(":id" , $id);
      $stmt->execute();
      $tipoProducto = $stmt->fetch();
      $json->tipoProducto = $tipoProducto['FKTipoProducto'];
      
      $json = json_encode($json);
      echo $json;

    }catch(PDOException $ex){
      echo $ex->getMessage();
    }
  }
?>
