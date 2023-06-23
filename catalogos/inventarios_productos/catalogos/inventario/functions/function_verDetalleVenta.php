<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id'];
  $stmt = $conn->prepare('SELECT c.Precio_Unitario, c.Cantidad, f.PKFactura, f.Fecha_de_Emision, cl.Nombre_comercial FROM inventario as i INNER JOIN ventas as c ON i.FKProducto = c.FKProducto INNER JOIN productos as p ON c.FKProducto = p.PKProducto INNER JOIN facturas as f ON c.FKFactura = f.PKFactura INNER JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal = f.FKDomicilioFiscal INNER JOIN clientes as cl ON df.FKCliente = cl.PKCliente WHERE i.FKProducto = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $table="";
  $no = 1;
  $presentacion = "";
  $fecha = "";
  while (($row = $stmt->fetch()) !== false) {
    if(isset($row['Fecha_de_Emision'])){
      $fecha = date('d/m/Y',strtotime($row['Fecha_de_Emision']));
    }else{
      $fecha = $row['Fecha_de_Emision'];
    }

      $table.='{"No":"'.$no.'","Factura":"'.$row['PKFactura'].'","Fecha de venta":"'.$fecha.'","Cliente":"'.$row['Nombre_comercial'].'","Precio unitario":"'.$row['Precio_Unitario'].'","Cantidad":"'.$row['Cantidad'].'"},';
      $no++;
    }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
