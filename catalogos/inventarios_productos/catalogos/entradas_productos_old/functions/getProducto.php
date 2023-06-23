<?php
  require_once('../../../include/db-conn.php');
  $id = $_POST['idOrdenCompra'];

  $stmt = $conn->prepare('SELECT oc.FKProducto, pr.Clave, pr.Descripcion,u.Unidad_de_Medida,u.Piezas_por_Caja,od.Estatus FROM productos_oc AS oc
    LEFT JOIN productos AS pr ON oc.FKProducto = pr.PKProducto
    LEFT JOIN orden_compra AS od ON oc.FKOrdenCompra = od.PKOrdenCompra
    LEFT JOIN unidad_medida AS u ON pr.FKUnidadMedida = u.PKUnidadMedida
    WHERE od.PKOrdenCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $x = 0;
  $html = '<option value="">Seleccione una opcion...</option>';
  $unidad = "";

  while($row = $stmt->fetch()){
    if($row['Estatus'] == 1){
      if(strtoupper($row['Unidad_de_Medida']) == 'PIEZA' || strtoupper($row['Unidad_de_Medida']) == 'PAR'){
        $unidad = $row['Unidad_de_Medida'];
      }else{
        $unidad = $row['Unidad_de_Medida']." c/".$row['Piezas_por_Caja'];
      }
      $html .= '<option value="'.$row['FKProducto'].'">'.$row['Clave'].' '.$row['Descripcion'].' '.$unidad.'</option>';
    }else if($row['Estatus'] == 2){
      $html = '<option value="">La orden de compra ingresada se ha completado.</option>';
    }else{
      $html = '<option value="">No se a aceptado la orden de compra</option>';
    }
    $x++;
  }
  if($x == 0){
    $html .= '<option style="background: red;color:white">No hay productos para esta orden de compra</option>';
  }

  echo $html;

?>
