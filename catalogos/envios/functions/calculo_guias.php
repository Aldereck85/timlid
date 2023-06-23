<?php
  require_once('../../../include/db-conn.php');

  $id = $_POST['id'];
  $stmt = $conn->prepare('SELECT Cajas_por_enviar, Piezas_por_enviar, Piezas_por_Caja  FROM productos_en_envio as pe 
                                    LEFT JOIN productos as p ON p.PKProducto = pe.FKProducto
                                    LEFT JOIN unidad_medida as um ON um.PKUnidadMedida = p.FKUnidadMedida
                                    LEFT JOIN inventario as i ON i.FKProducto = pe.FKProducto 
                                        WHERE pe.FKEnvio = :id GROUP BY pe.FKProducto ORDER BY p.FKCategoria');
  $stmt->execute(array(':id' => $id));
  $row = $stmt->fetchAll();

  $cajas = 0;
  $cajas_piezas_sueltas = 0;
  for($x = 0; $x < count($row);$x++) {
    $cajas = $cajas + $row[$x]['Cajas_por_enviar'];

    if($row[$x]['Piezas_por_enviar'] > 0)
      $cajas_piezas_sueltas = $cajas_piezas_sueltas + (($row[$x]['Piezas_por_enviar'] / $row[$x]['Piezas_por_Caja']) + 0.10 );
  }

  $cajastotales = $cajas + ceil($cajas_piezas_sueltas);
  
  echo $cajastotales;
?>
