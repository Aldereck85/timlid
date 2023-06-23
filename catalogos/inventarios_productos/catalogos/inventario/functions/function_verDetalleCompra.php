<?php
  require_once('../../../include/db-conn.php');
  $id = $_GET['id']; /*as c LEFT JOIN productos as p ON c.FKProducto = p.PKProducto LEFT JOIN unidad_medida as u ON p.FKUnidadMedida = u.PKUnidadMedida LEFT JOIN orden_compra ON c.FKOrdenCompra = o.PKOrdenCompra LEFT JOIN proveedores as pr ON o.FKProveedor = pr.PKProveedor WHERE FKProducto = :id');*/
  $stmt = $conn->prepare('SELECT i.FKProducto, c.*, p.clave, p.FKUnidadMedida, p.Descripcion, cp.PKCompra, cp.Referencia, cp.Fecha_de_Emision, oc.FKProveedor, pr.Razon_Social, p.PKProducto,c.Cantidad_Recibida FROM inventario as i
    LEFT JOIN productos_cc as c ON i.FKProducto = c.FKProducto
    LEFT JOIN productos as p ON c.FKProducto = p.PKProducto
    LEFT JOIN compras_productos as cp ON c.FKCompra = cp.PKCompra
    LEFT JOIN orden_compra as oc ON cp.FKOrdenCompra = oc.PKOrdenCompra
    LEFT JOIN proveedores as pr ON oc.FKProveedor = pr.PKProveedor
    WHERE i.FKProducto = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $table="";
  $no = 1;
  $presentacion = "";
  while (($row = $stmt->fetch()) !== false) {
    $fecha = date('d/m/Y',strtotime($row['Fecha_de_Emision']));
      //$edit = '<a class=\"btn btn-primary\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Marca\" class=\"btn btn-primary\" onclick=\"obtenerIdMarcaEditar('.$row['PKMarca'].');\"><i class=\"fas fa-edit\"></i> Editar</a>&nbsp;&nbsp;';
  		//$delete ='<a class=\"btn btn-danger\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Marca\" class=\"btn btn-danger\" onclick=\"obtenerIdMarcaEliminar('.$row['PKMarca'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';

      $table.='{"No":"'.$no.'","Referencia":"'.$row['Referencia'].'","Fecha de compra":"'.$fecha.'","Proveedor":"'.$row['Razon_Social'].'","Precio unitario":"'.$row['Precio_Unitario'].'","Cantidad":"'.$row['Cantidad_Recibida'].'"},';
      $no++;
    }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
