<?php
session_start();

$jwt_ruta = "../../../";
require_once '../../jwt.php';

function generateRandomString($length = 12) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
} 

$json = new \stdClass();

//echo "<pre>",print_r($_POST),"</pre>";
require_once('../../../include/db-conn.php');
include('simple_html_dom.php');
$html = str_get_html($_POST['tabla_cotizacion']);
$idCliente = $_POST['cmbCliente']; 
$moneda = $_POST['cmbMoneda']; 
$razonsocial = "";//$_POST['cmbRazon']; 
$FechaIngreso = $_POST['txtFechaGeneracion'];
$FechaModificacion = $_POST['txtFechaGeneracion'];
$FechaVencimiento = $_POST['txtFechaVencimiento'];
$Subtotal = $_POST['Subtotal'];
$Importe = $_POST['Total'];
$NotaCliente = trim($_POST['NotasClientes']);
$NotaInterna = trim($_POST['NotasInternas']);
$FKUsuario = $_SESSION["PKUsuario"];
$idSucursal = $_POST['cmbSucursal']; 
$codigo = generateRandomString();
$idVendedor = $_POST['cmbVendedor']; 
$idDireccionEnvio = $_POST['cmbDireccionEntrega']; 
$condicionPago = $_POST['cmbCondicionPago']; 
$token = $_POST["csr_token_7ALF1"];
$facturacion_directa = 1;
$flujo_almacen = 0;
$tipoProducto = $_POST['TipoProductoGeneral'];
$modificar = 0; //si se va apoder modificar si es facturacion directa
$subtotal_general = 0.00; $total_final = 0.00;

if(strlen($NotaCliente) > 500){
  $json->estatus = "error-notacliente";
  $json = json_encode($json);
  echo $json;
  return;
}

if(strlen($NotaInterna) > 500){
  $json->estatus = "error-notainterna";
  $json = json_encode($json);
  echo $json;
  return;
}

$stmt = $conn->prepare("SELECT activar_inventario FROM sucursales WHERE empresa_id = :empresa_id AND id = :idSucursal");
$stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
$stmt->bindValue(':idSucursal',$idSucursal);
$stmt->execute();
$sucursal = $stmt->fetch();

if(/* $tipoProducto == 2 || */ $sucursal['activar_inventario'] == 0){
  $facturacion_directa = 1;
  $modificar = 1;
}
/*echo $tipoProducto." - ".$sucursal['activar_inventario'];
return;*/
if(!empty($_SESSION['token_ld10d'])) {
            if (!hash_equals($_SESSION['token_ld10d'], $token)) {
                $json->estatus = "error-general";
                $json = json_encode($json);
                echo $json;
            }
            else{

                try{
                  $conn->beginTransaction();

                  //obtener el ultimo id generado por empresa
                  $stmt = $conn->prepare("SELECT id_cotizacion_empresa FROM cotizacion WHERE empresa_id = :empresa_id ORDER BY id_cotizacion_empresa DESC LIMIT 1");
                  $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
                  $stmt->execute();
                  $rowidcotizacion = $stmt->fetch();
                  $idcotizacionempresa = $rowidcotizacion['id_cotizacion_empresa'] + 1;

                  date_default_timezone_set('America/Mexico_City');
                  $fecha_generacion = date("Y-m-d H:i:s");
                  $stmt = $conn->prepare("INSERT INTO cotizacion (id_cotizacion_empresa, Subtotal, ImporteTotal, FechaIngreso, FechaVencimiento, NotaCliente, NotaInterna, CodigoCotizacion, estatus_factura_id, estatus_cotizacion_id, FKCliente, empleado_id, FKUsuarioCreacion , FKUsuarioEdicion, FKSucursal ,empresa_id , created_at, updated_at, facturacion_directa, flujo_almacen, modificar, direccion_entrega_id, condicion_Pago,FkMoneda_id) VALUES (:idcotizacionempresa, :subtotal, :importe, :fechaingreso, :fechavencimiento, :notacliente, :notainterna, :CodigoCotizacion, :estatus_factura_id, :estatus_cotizacion_id, :clienteid, :empleado_id, :fkusuario, :fkusuarioMod, :sucursalID, :empresa_id, :created, :updated, :facturacion_directa, :flujo_almacen, :modificar, :direccion_entrega_id, :condicion_Pago,:moneda_id)");
                  $stmt->bindValue(':idcotizacionempresa',$idcotizacionempresa);
                  $stmt->bindValue(':subtotal',$Subtotal);
                  $stmt->bindValue(':importe',$Importe);
                  $stmt->bindValue(':fechaingreso',$FechaIngreso);
                  $stmt->bindValue(':fechavencimiento',$FechaVencimiento);
                  $stmt->bindValue(':notacliente',$NotaCliente);
                  $stmt->bindValue(':notainterna',$NotaInterna);
                  $stmt->bindValue(':CodigoCotizacion',$codigo);
                  $stmt->bindValue(':estatus_factura_id', 4); //5 pendiente de facturar directo
                  $stmt->bindValue(':estatus_cotizacion_id', 5);
                  $stmt->bindValue(':clienteid',$idCliente);
                  $stmt->bindValue(':empleado_id',$idVendedor);
                  $stmt->bindValue(':fkusuario',$FKUsuario);
                  $stmt->bindValue(':fkusuarioMod',$FKUsuario);
                  $stmt->bindValue(':sucursalID',$idSucursal);
                  $stmt->bindValue(':empresa_id',$_SESSION['IDEmpresa']);
                  $stmt->bindValue(':created',$fecha_generacion);
                  $stmt->bindValue(':updated',$fecha_generacion);
                  $stmt->bindValue(':facturacion_directa',$facturacion_directa);
                  $stmt->bindValue(':flujo_almacen',$flujo_almacen);
                  $stmt->bindValue(':modificar',$modificar);
                  $stmt->bindValue(':direccion_entrega_id',$idDireccionEnvio);
                  $stmt->bindValue(':condicion_Pago',$condicionPago);
                  $stmt->bindValue(':moneda_id',$moneda);
                  $stmt->execute();

                  $idCotizacion = $conn->lastInsertId();

                  $stmt = $conn->prepare("INSERT INTO bitacora_cotizaciones (FKUsuario, Fecha_Movimiento, FKMensaje, FKCotizacion) VALUES (:fkusuario, :fechamovimiento, :fkmensaje, :fkcotizacion)");
                  $stmt->bindValue(':fkusuario',$FKUsuario);
                  $stmt->bindValue(':fechamovimiento',$FechaIngreso);
                  $stmt->bindValue(':fkmensaje', 6);
                  $stmt->bindValue(':fkcotizacion',$idCotizacion);
                  $stmt->execute(); 

                  $cuenta = count($_POST['inp_productos']);

                  $descuento_array = $_POST['inp_precio'];
                  $piezas_array = $_POST['inp_piezas'];
                  $precio_array = $_POST['inp_precio'];
                  $producto_array = $_POST['inp_productos'];
                  $calcular_impuestos = array();
                  $impuestos_determinados = array();
                  $cantidadAdicionalIVA = 0;

                  for($x = 0 ; $x < $cuenta; $x++){

                    $stmt = $conn->prepare("INSERT INTO detalle_cotizacion (Precio, Cantidad, FKProducto, FKCotizacion) VALUES (:precio, :cantidad, :idproducto, :idcotizacion)");
                    $stmt->bindValue(':precio',$precio_array[$x]);
                    $stmt->bindValue(':cantidad',$piezas_array[$x]);
                    $stmt->bindValue(':idproducto',$producto_array[$x]);
                    $stmt->bindValue(':idcotizacion',$idCotizacion);
                    $stmt->execute();

                    $total_producto = $precio_array[$x] * $piezas_array[$x];
                    //echo "producto id: ".$producto_array[$x]."<br>";
                    //echo "precio: ".$precio_array[$x]." cantidad: ".$piezas_array[$x]." total: ".$total_producto."<br>";
                    $calcular_impuestos[$x][0] = $producto_array[$x];
                    $calcular_impuestos[$x][1] = $total_producto;
                    $calcular_impuestos[$x][2] = $piezas_array[$x];
                    $calcular_impuestos[$x][3] = $precio_array[$x];
                    $subtotal_general = $subtotal_general + $total_producto;

                  }

                  //una vez insertados los productos comprueba si la cotizacion tiene solo servicios, si es así se indica que será por facturación directa
                  //comprueba si la cotizacion tiene solo servicios, si es así ésta no genera pedido
                  $stmt = $conn->prepare("SELECT p.FKTipoProducto from productos p 
                                            inner join detalle_cotizacion dc on p.PKProducto = dc.FKProducto
                                          where dc.FKCotizacion = :numero_cotizacion 
                                          group by FKTipoProducto;");
                  $stmt->bindValue(':numero_cotizacion', $idCotizacion);
                  $stmt->execute();
                  $tiposProdRes = $stmt->rowCount();
                  $tipoProdObtenido = $stmt->fetch()['FKTipoProducto'];

                  //actualiza el facturar directo en base de datos
                  if($tiposProdRes == 1 && $tipoProdObtenido == 5){
                    $stmt = $conn->prepare("UPDATE cotizacion as c set c.estatus_factura_id = 4, c.estatus_factura_id_old = 4 ,c.facturacion_directa = 1, c.modificar = 1 where c.PKCotizacion = :numero_cotizacion;");
                    $stmt->bindValue(':numero_cotizacion', $idCotizacion);
                    $stmt->execute();
                  }
                  
                    $rows = $html->find('tr'); // Find all rows in the table

                    $contador = 0;
                    $columnas = 0;
                    foreach($html->find('tr') as $tr) {
                      //echo $tr;
                        if($contador > 0){

                          foreach($tr->find('td') as $th){
                                  if($columnas == 4){
                                    $impuestos = explode("<br>", $th->innertext);
                                    //print_r($impuestos); echo "/////////////////////////////////////////////";
                                    foreach ($impuestos as $imp) {

                                      preg_match_all('/(?<=id=).*?(?=")/',
                                      $imp,
                                      $out, PREG_PATTERN_ORDER);
                                      //print_r($out);
                                      //echo " SEPARADOR". $out[0][0]." <br>";

                                      if(isset($out[0][1])){
                                          if(trim($out[0][1]) != ""){

                                              $cadena = str_replace('"', "", $out[0]);
                                              $final = explode("_", $cadena[1]);

                                              $nombreImpuesto = $final[0];
                                              $tipoImpuesto = $final[1];
                                              $tipoImporte = $final[2];
                                              $idImpuesto = $final[3];
                                              $idProducto = $final[4];

                                              preg_match_all("/(?<=valImp_).*?(?= )/",
                                              $imp,
                                              $tasa, PREG_PATTERN_ORDER);
                                              //print_r($tasa);
                                              $impuesto_str = $tasa[0];
                                              $num_imp = substr_count($impuesto_str[0], '"');

                                              if($num_imp > 0){

                                                  $impuestoTasa = str_replace('"', "", $tasa[0]);

                                                  $stmt = $conn->prepare("INSERT INTO detalleimpuesto (FKCotizacion, FKProducto, FKImpuesto, Tasa) VALUES (:idcotizacion, :idproducto, :idimpuesto, :tasa)");
                                                  $stmt->bindValue(':idcotizacion',$idCotizacion);
                                                  $stmt->bindValue(':idproducto',$idProducto);
                                                  $stmt->bindValue(':idimpuesto',$idImpuesto);
                                                  $stmt->bindValue(':tasa',$impuestoTasa[0]);
                                                  $stmt->execute();

                                                  $found_key = array_search($idProducto, array_column($calcular_impuestos, 0));

                                                  //echo "llave ".$found_key." total: ".$calcular_impuestos[$found_key][1]."<br>";

                                                  $stmt = $conn->prepare("SELECT FKTipoImporte FROM impuesto WHERE PKImpuesto = :idImpuesto");
                                                  $stmt->bindValue(':idImpuesto',$idImpuesto);
                                                  $stmt->execute();
                                                  $row_imp = $stmt->fetch();

                                                  if($idImpuesto == 1){

                                                      foreach ($impuestos as $impIVA) {

                                                          preg_match_all('/(?<=id=).*?(?=")/',
                                                          $impIVA,
                                                          $outIVA, PREG_PATTERN_ORDER);

                                                          if(isset($out[0][1])){
                                                              if(trim($out[0][1]) != ""){

                                                                  $cadenaIVA = str_replace('"', "", $outIVA[0]);
                                                                  $finalIVA = explode("_", $cadenaIVA[1]);

                                                                  $nombreImpuestoIVA = $finalIVA[0];
                                                                  $tipoImpuestoIVA = $finalIVA[1];
                                                                  $tipoImporteIVA = $finalIVA[2];
                                                                  $idImpuestoIVA = $finalIVA[3]; //uso
                                                                  $idProductoIVA = $finalIVA[4];

                                                                  preg_match_all("/(?<=valImp_).*?(?= )/",
                                                                  $impIVA,
                                                                  $tasaIVA, PREG_PATTERN_ORDER);
                                                                  
                                                                  $impuestoTasaIVA = str_replace('"', "", $tasaIVA[0]);

                                                                  if($idImpuestoIVA == 2){
                                                                    $cantidadAdicionalIVA = $cantidadAdicionalIVA + (($calcular_impuestos[$found_key][2]  * $calcular_impuestos[$found_key][3] ) * ($impuestoTasaIVA[0] / 100));
                                                                  }
                                                                  if($idImpuestoIVA == 3){
                                                                    $cantidadAdicionalIVA = $cantidadAdicionalIVA + ($calcular_impuestos[$found_key][2]  * $impuestoTasaIVA[0]);
                                                                  }
                                                          
                                                            }
                                                          }
                                                      }

                                                  }

                                                  if(!isset($impuestos_determinados[$idImpuesto])){

                                                      if($row_imp['FKTipoImporte'] == 1){
                                                        $impuestos_determinados[$idImpuesto] = ($calcular_impuestos[$found_key][1] + $cantidadAdicionalIVA) * ($impuestoTasa[0] / 100);
                                                      }
                                                      if($row_imp['FKTipoImporte'] == 2){
                                                        $impuestos_determinados[$idImpuesto] = $calcular_impuestos[$found_key][2] * $impuestoTasa[0];
                                                      }
                                                      
                                                  }
                                                  else{
                                                      if($row_imp['FKTipoImporte'] == 1){
                                                        $impuestos_determinados[$idImpuesto] = $impuestos_determinados[$idImpuesto] + (($calcular_impuestos[$found_key][1] + $cantidadAdicionalIVA) * ($impuestoTasa[0] / 100));
                                                      }
                                                      if($row_imp['FKTipoImporte'] == 2){
                                                        $impuestos_determinados[$idImpuesto] = $impuestos_determinados[$idImpuesto] + ($calcular_impuestos[$found_key][2]  * $impuestoTasa[0]);
                                                      }
                                                  }
                                                  
                                                  $cantidadAdicionalIVA = 0;
                                                  

                                              }

                                          }
                                      }

                                    }
                        
                                  }
                                  $columnas++;
                          }
                        }
                        $columnas = 0;
                        $contador++;
                    }

                  //RECALCULO FINAL DEL TOTAL, SE ACTUALIZAR EN CASO DE NO COINCIDIR
                  $total_final = $subtotal_general;

                  foreach($impuestos_determinados as $id => $valor){

                    $stmt = $conn->prepare("SELECT FKTipoImpuesto, Operacion FROM impuesto WHERE PKImpuesto = :idImpuesto");
                    $stmt->bindValue(':idImpuesto',$id);
                    $stmt->execute();
                    $row_imp = $stmt->fetch();

                    if($row_imp['FKTipoImpuesto'] == 1){
                      $total_final = $total_final + $valor;
                    }
                    if($row_imp['FKTipoImpuesto'] == 2){
                      $total_final = $total_final - $valor;
                    }
                    if($row_imp['FKTipoImpuesto'] == 3){
                      if($row_imp['Operacion'] == 1){
                        $total_final = $total_final + $valor;
                      }
                      if($row_imp['Operacion'] == 2){
                        $total_final = $total_final - $valor;
                      }
                      
                    }

                  }

                  $diferencia_subtotal = abs($Subtotal - $subtotal_general);
                  $diferencia_total = abs($Importe - $total_final);

                  if($diferencia_subtotal > 1 || $diferencia_total > 1){
                      $stmt = $conn->prepare("UPDATE cotizacion SET Subtotal = :subtotal, ImporteTotal = :importe WHERE PKCotizacion = :idCotizacion ");
                      $stmt->bindValue(':subtotal',$subtotal_general);
                      $stmt->bindValue(':importe',$total_final);
                      $stmt->bindValue(':idCotizacion',$idCotizacion);
                      $stmt->execute();
                  }

                  if($conn->commit()){

                    /*
                    print_r($impuestos_determinados);
                    echo "subtotal:  ".$subtotal_general."<br>";
                    echo "total:  ".$total_final."<br>";*/

                    $idcotizacionempresazero = str_pad($idcotizacionempresa, 10, "0", STR_PAD_LEFT);
                    $json->estatus = "exito";
                    $json->idcotizacion = $idCotizacion;
                    $json->idcotizacionempresa = $idcotizacionempresazero;
                    $json = json_encode($json);
                    echo $json;
                  }
                }catch(PDOException $ex){
                  //echo $ex->getMessage();
                  $json->estatus = "error-general";
                  $json = json_encode($json);
                  echo $json;
                  $conn->rollBack(); 
                }
            }
}
else{
    $json->estatus = "error-general";
    $json = json_encode($json);
    echo $json;
}

?>
