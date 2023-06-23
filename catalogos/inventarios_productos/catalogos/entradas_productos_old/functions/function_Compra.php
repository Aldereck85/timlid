<?php
  require_once('../../../../../include/db-rm.php');
  $table = "";
  $no = 1;
  $stmt = $conn_rm->prepare('SELECT * FROM entradas_inventarios
                          LEFT JOIN tipos_entradas_inventarios ON entradas_inventarios.FKTipoEntrada = tipos_entradas_inventarios.PKTipoEntrada
                          LEFT JOIN almacenes ON entradas_inventarios.FKAlmacen = almacenes.PKAlmacen
                          LEFT JOIN prueba_rh.usuarios AS usuarios ON entradas_inventarios.FKUsuario = usuarios.PKUsuario
                          LEFT JOIN prueba_rh.empleados AS empleados ON usuarios.FKEmpleado = empleados.PKEmpleado');
  $stmt->execute();
  while($row = $stmt->fetch()){
    $usuario = $row['Nombres']." ". $row['PrimerApellido'];
    $fechaHora = date("d/m/Y H:i:s",strtotime($row['Fecha']));
    /*
    $estatus ="";
    if($row['Estatus'] == 1){
      $estatus = '<div class=\"text-danger\"><i class=\"fas fa-hourglass-start\"></i> No pagado</div>';
    }else if($row['Estatus'] == 2){
      $estatus = '<div class=\"text-warning\"><i class=\"fas fa-hourglass-half\"></i> Pago Parcial</div>';
    }else if($row['Estatus'] == 3){
      $estatus = '<div class=\"text-success\"><i class=\"fas fa-hourglass-end\"></i> Pagado</div>';
    }
    $edit = '<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_OrdenCompra\" onclick=\"obtenerIdOrdenCompraEditar('.$row['PKCompra'].');\"><i class=\"fas fa-edit\"></i> Editar</a>';
  	$delete ='<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_OrdenCompra\" onclick=\"obtenerIdOrdenCompraEliminar('.$row['PKCompra'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>';
    $ver = '<a class=\"dropdown-item\" href=\"functions/ver_Compra.php?id='.$row['PKCompra'].'&compra='.$row['FKOrdenCompra'].'\"><i class=\"fas fa-eye\"></i> Ver compra</a>';
    $acciones = '<div class=\"dropdown\"><button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">'.$ver.$delete;
    $importe = "$ ".number_format($row['Importe'],2);
    $fechaEntrega = date("d/m/Y", strtotime($row['Fecha_de_Emision']));
    */
    $table.='{"Folio":"'.$row['PKEntradaInventario'].'","Fecha":"'.$fechaHora.'","Tipo de entrada":"'.$row['TipoEntrada'].'","Usuario":"'.$usuario.'"},';
    $no++;
  }

  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
