<?php
  require_once('../../../include/db-conn.php');

  if(isset($_POST['idCotizacion'])){
    $id =  $_POST['idCotizacion'];
    $idMensajeFinal = $_POST['idMensajeFinal'];

        try{
          $stmt = $conn->prepare('SELECT mc.PKMensajes_Cotizacion ,mc.TipoUsuario, mc.Mensaje, DATE_FORMAT(mc.FechaAgregado, "%d/%m/%Y %H:%i:%s") as Fecha ,cl.NombreComercial,IFNULL(CONCAT(e.Nombres," ", e.PrimerApellido) ,u.Nombre) as Nombre_Empleado  FROM mensajes_cotizacion as mc INNER JOIN cotizacion as c ON c.PKCotizacion = mc.FKCotizacion INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente INNER JOIN usuarios as u ON u.id = c.FKUsuarioCreacion LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.id LEFT JOIN empleados as e ON e.PKEmpleado = eu.FKEmpleado WHERE mc.FKCotizacion = :id AND mc.PKMensajes_Cotizacion > :idMensajeFinal ORDER BY  mc.PKMensajes_Cotizacion ASC');
          $stmt->execute(array(':id'=>$id,':idMensajeFinal'=> $idMensajeFinal ));
          
          $row = $stmt->fetchAll();
          
          echo json_encode($row);

        }catch(Exception $e){
          echo $e->getMessage();
          exit;
        }
    }
   ?>
