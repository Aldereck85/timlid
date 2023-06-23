
<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT PKOrdenCompra,Estatus, Referencia, PKProveedor, Razon_Social, Importe, Fecha_de_Emision, Fecha_Deseada_Entrega, Fecha_Entrega, Observaciones,Dias_Credito,Limite_Credito FROM orden_compra
  LEFT JOIN proveedores ON FKProveedor = PKProveedor');
$stmt->execute();
$cambiarEstatus = "";
$productos = "";
$table="";
$no = 1;
$barra ="";
while (($row = $stmt->fetch()) !== false) {
  $canTotal = 0;
  $canCompra = 0;
  $cantidad = 0;
  $stmt1 = $conn->prepare('SELECT * FROM compras_productos WHERE FKOrdenCompra = :id');
  $stmt1->bindValue(':id', $row['PKOrdenCompra'], PDO::PARAM_INT);
  $stmt1->execute();
  $rowCount = $stmt1->rowCount();

  $importe = "$".number_format($row['Importe'],2);
  $fechaEmision = date("d/m/Y", strtotime($row['Fecha_de_Emision']));

  if($row['Fecha_Deseada_Entrega'] == '0000-00-00' || !isset($row['Fecha_Deseada_Entrega'])){
    $fechaDeseada = '';
  }else{
    $fechaDeseada = date("d/m/Y", strtotime($row['Fecha_Deseada_Entrega']));
  }
  if($row['Fecha_Entrega'] == '0000-00-00' || !isset($row['Fecha_Entrega'])){
    $fechaEntrega = 'No entregado';
  }else{
    $fechaEntrega = date("d/m/Y", strtotime($row['Fecha_Entrega']));
  }
  /*
  $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=1\"><i class=\"fas fa-times\"></i> No pagado y No Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=2\"><i class=\"fas fa-hourglass-start\"></i> Pagado y no Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=3\"><i class=\"fas fa-hourglass-half\"></i> No Pagado y Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=4\"><i class=\"fas fa-check\"></i> Pagado y Entregado</div></div>';
  if($row['Estatus'] == 2){
    $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\"><i class=\"fas fa-times\"></i> No pagado y No Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=2\"><i class=\"fas fa-hourglass-start\"></i> Pagado y no Entregado</a><a class=\"dropdown-item\"><i class=\"fas fa-hourglass-half\"></i> No Pagado y Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=4\"><i class=\"fas fa-check\"></i> Pagado y Entregado</div></div>';
  }else if($row['Estatus'] == 3){
    $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\"><i class=\"fas fa-times\"></i> No pagado y No Entregado</a><a class=\"dropdown-item\"><i class=\"fas fa-hourglass-start\"></i> Pagado y no Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=3\"><i class=\"fas fa-hourglass-half\"></i> No Pagado y Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=4\"><i class=\"fas fa-check\"></i> Pagado y Entregado</div></div>';
  }else if($row['Estatus'] == 4){
    $cambiarEstatus = '<div class=\"dropdown\"><button class=\"btn btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" disabled> Estatus</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\"><i class=\"fas fa-times\"></i> No pagado y No Entregado</a><a class=\"dropdown-item\"><i class=\"fas fa-hourglass-start\"></i> Pagado y no Entregado</a><a class=\"dropdown-item\"><i class=\"fas fa-hourglass-half\"></i> No Pagado y Entregado</a><a class=\"dropdown-item\" href=\"functions/cambiar_Estatus.php?id='.$row['PKOrdenCompra'].'&estatus=4\"><i class=\"fas fa-check\"></i> Pagado y Entregado</div></div>';
  }
  */
  $stmt2 = $conn->prepare('SELECT Cantidad FROM productos_oc WHERE FKOrdenCompra = :id');
  $stmt2->bindValue(':id',$row['PKOrdenCompra']);
  $stmt2->execute();
  while($row2 = $stmt2->fetch()){
    $canTotal += $row2['Cantidad'];
  }

  $stmt2 = $conn->prepare('SELECT Cantidad_Recibida FROM productos_cc AS pc
    LEFT JOIN compras_productos AS cp ON pc.FKCompra = cp.PKCompra
    WHERE cp.FKOrdenCompra = :id');
  $stmt2->bindValue(':id',$row['PKOrdenCompra']);
  $stmt2->execute();
  while($row2 = $stmt2->fetch()){
    $canCompra += $row2['Cantidad_Recibida'];
  }

  if($canTotal > 0){
    $cantidad = ($canCompra/$canTotal)*100;
  }
  $estatus ="";
  if($row['Estatus'] == 1){
    if($cantidad == 0){
      $barra = '<div><span class=\"badge badge-info\">En espera<br>de entrega<span></div>';
      $estatus = '<div><span class=\"badge badge-info\">Aceptada<span></div>';
    }else if($cantidad > 0 && $cantidad < 25){
      $barra = '<div class=\"progress\" style=\"position:relative;\"><div class=\"progress-bar progress-bar-striped progress-bar-animated bg-danger\" role=\"progressbar\" style=\"width: '.$cantidad.'%;\" aria-valuenow=\"'.$cantidad.'\" aria-valuemin=\"0\" aria-valuemax=\"100\"><span style=\"text-align: center;color:black;font-weight:bold;position:absolute;left:0;right:0\" tooltip=\"Productos:\">'.number_format($cantidad,0).'%</span></div></div>';
      $estatus = '<div class=\"text-danger\"><i class=\"fas fa-hourglass-start\"></i> Entrega parcial</div>';
    }else if($cantidad < 75){
      $barra = '<div class=\"progress\" style=\"position:relative;\"><div class=\"progress-bar progress-bar-striped progress-bar-animated bg-warning\" role=\"progressbar\" style=\"width: '.$cantidad.'%;\" aria-valuenow=\"'.$cantidad.'\" aria-valuemin=\"0\" aria-valuemax=\"100\"><span style=\"text-align: center;color:black;font-weight:bold;position:absolute;left:0;right:0\">'.number_format($cantidad,0).'%</span></div></div>';
      $estatus = '<div class=\"text-warning\"><i class=\"fas fa-hourglass-half\"></i> Entrega parcial</div>';
    }else if($cantidad >= 75 && $cantidad < 100){
      $barra = '<div class=\"progress\" style=\"position:relative;\"><div class=\"progress-bar progress-bar-striped progress-bar-animated bg-primary\" role=\"progressbar\" style=\"width: '.$cantidad.'%;\" aria-valuenow=\"'.$cantidad.'\" aria-valuemin=\"0\" aria-valuemax=\"100\"><span style=\"text-align: center;color:white;font-weight:bold;position:absolute;left:0;right:0\">'.number_format($cantidad,0).'%</span></div></div>';
      $estatus = '<div class=\"text-primary\"><i class=\"fas fa-hourglass-end\"></i> Entrega parcial</div>';
    }else{
      $barra = '<div class=\"progress\" style=\"position:relative;\"><div class=\"progress-bar progress-bar-striped bg-success\" role=\"progressbar\" style=\"width: '.$cantidad.'%;\" aria-valuenow=\"'.$cantidad.'\" aria-valuemin=\"0\" aria-valuemax=\"100\"><span style=\"text-align: center;color:white;font-weight:bold;position:absolute;left:0;right:0\">'.number_format($cantidad,0).'%</span></div></div>';
      $estatus = '<div class=\"text-success\"><i class=\"fas fa-check\"></i> Entrega Completa</div>';
    }
  }else if($row['Estatus'] == 2){
    $barra = '<div class=\"progress\" style=\"position:relative;\"><div class=\"progress-bar progress-bar-striped bg-success\" role=\"progressbar\" style=\"width: '.$cantidad.'%;\" aria-valuenow=\"'.$cantidad.'\" aria-valuemin=\"0\" aria-valuemax=\"100\"><span style=\"text-align: center;color:white;font-weight:bold;position:absolute;left:0;right:0\">Completo</span></div></div>';
    $estatus = '<div class=\"text-success\"><i class=\"fas fa-hourglass-end\"></i> Entrega completa</div>';
  }else if($row['Estatus'] == 3){
    $estatus = '<div><span class=\"badge badge-danger text-white\">Cancelada</span></div>';
    $barra = '<div><span class=\"badge badge-danger text-white\">Cancelada</span></div>';
  }else{
      $estatus = '<div><span class=\"badge badge-secondary text-white\">En espera<br>de aceptación</span></div>';
      $barra = '<div><span class=\"badge badge-secondary text-white\">En espera<br>de aceptación</span></div>';
    }
  $observaciones = "No hay observaciones";
  if($row['Observaciones'] != "" || $row['Observaciones'] != null)
    $observaciones = $row['Observaciones'];

  $edit = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_OrdenCompra\" onclick=\"obtenerIdOrdenCompraEditar('.$row['PKOrdenCompra'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
	$delete ='<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_OrdenCompra\" onclick=\"obtenerIdOrdenCompraEliminar('.$row['PKOrdenCompra'].')\"><i class=\"fas fa-trash\"></i> Eliminar</a>';
  $cancelar = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#cancelar_OrdenCompra\" onclick=\"obtenerIdOrdenCompraCancelar('.$row['PKOrdenCompra'].')\"><i class=\"fas fa-ban\"></i> Cancelar</a>';
  $productos = '<a class=\"dropdown-item\" href=\"functions/agregar_Productos.php?id='.$row['PKOrdenCompra'].'\"><i class=\"fas fa-plus-square\"></i> Agregar productos</a>';
  $ver = '<a class=\"btn btn-success\" href=\"functions/ver_OrdenCompra.php?id='.$row['PKOrdenCompra'].'\"><i class=\"fas fa-eye\"></i> Ver compra</a>';
/*
    switch ($row['Estatus']) {
      case 1:

          break;
      case 2:
          $estatus = '<div style=\"color:#4e73df;\"><i class=\"fas fa-hourglass-start\"></i> Pagado y no Entregado</div>';
          break;
      case 3:
          $estatus = '<div style=\"color:#f6c23e;\"><i class=\"fas fa-hourglass-half\"></i> No Pagado y Entregado</div>';
          break;
      case 4:
          $estatus = '<div style=\"color:#1cc88a;\"><i class=\"fas fa-check\"></i> Pagado y Entregado<p></div>';
          break;
      }
*/
      $dias = 'P0D';
      $diasCredito = 0;
      $d = "";
      $aux = new DateTime($row['Fecha_Entrega']);
      if(isset($row['Dias_Credito'])){
        $d = $row['Dias_Credito'] +1;
        $dias = 'P'.$d.'D';
        $diasCredito = $row['Dias_Credito'];
      }
      $fechaLimite = $aux->add(new DateInterval($dias));
      $fechaActual = new DateTime(date('Y-m-d'));
      $dif = $fechaActual->diff($fechaLimite);
      $diasContinuos = $dif->format('%a')-1;

      if($fechaActual > $fechaLimite){
        $diasContinuos = $diasContinuos * -1;
      }

      $fechaFormateada = "";
      if($row['Estatus'] == 1 || $row['Estatus'] == 3){
        if($diasContinuos > ($diasCredito*.2)){
          $fechaFormateada = '<h5><span class=\"badge badge-success\">'.$fechaLimite->format('d/m/Y').'<span></h5>';
        }else if($diasContinuos > ($diasCredito*0.1)){
          $fechaFormateada = '<h5> <span class=\"badge badge-warning\">'.$fechaLimite->format('d/m/Y').'<span></h5>';
        }else{
          $fechaFormateada = '<h5><span class=\"badge badge-danger\">'.$fechaLimite->format('d/m/Y').'<span></h5>';
        }
      }else{
        $fechaFormateada = '<h5><span class=\"badge badge-secondary\">'.$fechaLimite->format('d/m/Y').'<span></h5>';
      }

      if(is_null($row['Fecha_Entrega']) || $row['Fecha_Entrega'] == '0000-00-00'){
        $fechaFormateada = '<h6><span class=\"badge badge-light\">No se puede calcular.<br>Fecha de entrega<br>no registrada<span></h6>';
      }

  if($row['Estatus'] != 0){
    $acciones = $ver;
  }else{
    $ver = '<a class=\"dropdown-item\" href=\"functions/ver_OrdenCompra.php?id='.$row['PKOrdenCompra'].'\"><i class=\"fas fa-eye\"></i> Ver compra</a>';
    $acciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">'.$ver.$cancelar;

  }
  $proveedor = '<a style=\"text-decoration:none;color:gray;font-weight:bold;\" href=\"../../catalogos/proveedores/functions/ver_Proveedor.php?id='.$row['PKProveedor'].'\">'.$row['Razon_Social'].'<a>';
  //$table.='{"No":"'.$no.'","Cambiar estatus":"'.$cambiarEstatus.'","Referencia":"'.$row['Referencia'].'","Fecha de emision":"'.$fechaEmision.'","Fecha estimada de entrega":"'.$fechaDeseada.'","Estatus":"'.$estatus.'","Fecha de entrega":"'.$fechaEntrega.'","Proveedor":"'.$proveedor.'","Importe":"'.$importe.'","Fecha Limite de Pago":"'.$fechaFormateada.'","Observaciones":"'.$row['Observaciones'].'","Acciones":"'.$acciones.'"},';
  $table.='{"No":"'.$no.'","Progreso":"'.$barra.'","Referencia":"'.$row['Referencia'].'","Fecha de emision":"'.$fechaEmision.'","Fecha estimada de entrega":"'.$fechaDeseada.'","Estatus":"'.$estatus.'","Fecha de entrega":"'.$fechaEntrega.'","Proveedor":"'.$proveedor.'","Importe":"'.$importe.'","Fecha Limite de Pago":"'.$fechaFormateada.'","Observaciones":"'.$observaciones.'","Acciones":"'.$acciones.'"},';
  $no++;
}
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
