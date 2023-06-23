<?php
  require_once('../../../include/db-conn.php');
  if(isset($_GET['id'])){
    $id =  $_GET['id'];
  }
  $stmt = $conn->prepare('SELECT clientes.PKCliente, clientes.Nombre_comercial, clientes.FechaAlta, estatus_cliente.Estatus, empleados.Primer_Nombre, empleados.Apellido_Paterno FROM clientes INNER JOIN estatus_cliente ON clientes.FKEstatus = estatus_cliente.PKEstatusCliente INNER JOIN usuarios on clientes.FKUser = usuarios.PKUsuario INNER JOIN empleados on usuarios.FKEmpleado = empleados.PKEmpleado WHERE clientes.FKEstatus = 4 AND clientes.FKUser = :id');
  $stmt->execute(array(':id'=>$id));

  $table="";
  while (($row = $stmt->fetch()) !== false) {
    //href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Cliente\" class=\"btn btn-primary\" onclick=\"obtenerIdClienteEditar('.$row['PKCliente'].');\"><i class=\"fas fa-edit\"></i> Editar
    //href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Cliente\" class=\"btn btn-danger\" onclick=\"obtenerIdClienteEliminar('.$row['PKCliente'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
    $fecha = new DateTime($row['FechaAlta']);
    $fechaAlt =  date_format($fecha, 'd/m/Y');
    $vendedor = $row['Primer_Nombre']." ".$row['Apellido_Paterno'];
    $funciones= '<div class=\"dropdown\"><button class=\"btn btn-outline-dark btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Cliente\" class=\"btn btn-primary\" onclick=\"obtenerIdClienteEditar('.$row['PKCliente'].');\"><i class=\"fas fa-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"directorio/cuentas/index.php?id='.$row['PKCliente'].'\"><i class=\"far fa-credit-card\"></i> Cuentas bancarias</a><a class=\"dropdown-item\" href=\"directorio/datos_contacto/index.php?id='.$row['PKCliente'].'\"><i class=\"far fa-address-card\"></i> Datos de contacto</a><a class=\"dropdown-item\" href=\"directorio/domicilios_fiscales/index.php?id='.$row['PKCliente'].'\"><i class=\"fas fa-file-invoice-dollar\"></i> Datos fiscales</a><a class=\"dropdown-item\" href=\"directorio/domicilios_envio/index.php?id='.$row['PKCliente'].'\"><i class=\"fas fa-shipping-fast\"></i> Domicilios de envio</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Cliente\" class=\"btn btn-danger\" onclick=\"obtenerIdClienteEliminar('.$row['PKCliente'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a> </div></div>';
    $table.='{"Id cliente":"'.$row['PKCliente'].'","Nombre comercial":"'.$row['Nombre_comercial'].'","Fecha de alta":"'.$fechaAlt.'","Acciones":"'.$funciones.'"},';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';


 ?>
