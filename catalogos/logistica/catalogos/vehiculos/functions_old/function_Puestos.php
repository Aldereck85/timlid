<?php
require_once('../../../include/db-conn.php');

$stmt = $conn->prepare('SELECT v.*, pa.Archivo FROM vehiculos as v LEFT JOIN poliza_autos as pa ON pa.FKVehiculo = v.PKVehiculo');
$stmt->execute();
$table="";
//href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\" class=\"btn btn-primary\" onclick=\"obtenerIdVehiculoEditar('.$row['PKVehiculo'].');\"><i class=\"fas fa-edit\"></i> Editar
//href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar('.$row['PKVehiculo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar
while (($row = $stmt->fetch()) !== false) {

    $descargar = "";
    if($row['Archivo'] != ""){
      $descargar = '<a class=\"dropdown-item\" href=\"functions/pdf/'.$row['Archivo'].'\" download=\"'.$row['Archivo'].'\"><i class=\"fas fa-download\"></i> Descargar poliza</a>';
    }

    $funciones = '<div class=\"dropdown\"><a style=\"margin-top: -20px;\" class=\"btn btnEdit\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i><img src=\"../../img/timdesk/edit.svg\" style=\"width: 17px;\"></i></a><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\" class=\"btn btn-primary\" onclick=\"obtenerIdVehiculoEditar(' . $row['PKVehiculo'] . ');\"><i class=\"fas fa-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/ver_Poliza.php?id=' . $row['PKVehiculo'] . '\"><i class=\"fas fa-file-signature\"></i> Poliza de seguro</a><a class=\"dropdown-item\" href=\"combustible.php?id=' . $row['PKVehiculo'] . '\"><i class=\"fas fa-gas-pump\"></i> Combustible</a><a class=\"dropdown-item\" href=\"servicios.php?id=' . $row['PKVehiculo'] . '\"><i class=\"fas fa-tachometer-alt\"></i> Servicios</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar(' . $row['PKVehiculo'] . ')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>' . $descargar . '</div></div>';  
    /*$funciones= '<i><img  href=\"#\" src=\"../../img/timdesk/edit.svg\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"></i>';*/

         /*$funciones= '<div class=\"dropdown\"><button class=\"btn btn-dark btnMargin dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><i class=\"fas fa-tools\"></i> Operaciones</button><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\" class=\"btn btn-primary\" onclick=\"obtenerIdVehiculoEditar('.$row['PKVehiculo'].');\"><i class=\"fas fa-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/ver_Poliza.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-file-signature\"></i> Poliza de seguro</a><a class=\"dropdown-item\" href=\"combustible.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-gas-pump\"></i> Combustible</a><a class=\"dropdown-item\" href=\"servicios.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-tachometer-alt\"></i> Servicios</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar('.$row['PKVehiculo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>'.$descargar.'</div></div>';*/

        /*$funciones= '<div class=\"dropdown\"><i><img class=\"btnEdit\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\"src=\"../../img/timdesk/edit.svg\"></i><div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\" class=\"btn btn-primary\" onclick=\"obtenerIdVehiculoEditar('.$row['PKVehiculo'].');\"><i class=\"fas fa-edit\"></i> Editar</a><a class=\"dropdown-item\" href=\"functions/ver_Poliza.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-file-signature\"></i> Poliza de seguro</a><a class=\"dropdown-item\" href=\"combustible.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-gas-pump\"></i> Combustible</a><a class=\"dropdown-item\" href=\"servicios.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-tachometer-alt\"></i> Servicios</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar('.$row['PKVehiculo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>'.$descargar.'</div></div>';*/
        
        /*$funciones='<div class=\"dropdown\"><i><img class=\"btnEdit\" href=\"#\" data-toggle=\"modal\" data-target=\"#editar_Vehiculo\"  onclick=\"obtenerIdVehiculoEditar('.$row['PKVehiculo'].');\"src=\"../../img/timdesk/edit.svg\"><div></i><a class=\"dropdown-item\" href=\"functions/ver_Poliza.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-file-signature\"></i> Poliza de seguro</a><a class=\"dropdown-item\" href=\"combustible.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-gas-pump\"></i> Combustible</a><a class=\"dropdown-item\" href=\"servicios.php?id='.$row['PKVehiculo'].'\"><i class=\"fas fa-tachometer-alt\"></i> Servicios</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#eliminar_Vehiculo\" class=\"btn btn-danger\" onclick=\"obtenerIdVehiculoEliminar('.$row['PKVehiculo'].')\"><i class=\"fas fa-trash-alt\"></i> Eliminar</a>'.$descargar.'</div>';*/
        
       

    $table.='{
      "Linea":"'.$row['Linea'].'",
      "Serie":"'.$row['Serie'].'",
      "Marca":"'.$row['Marca'].'",
      "Placas":"'.$row['Placas'].'",
      "Color":"'.$row['Color'].'",
      "Modelo":"'.$row['Modelo'].'",
      "Puertas":"'.$row['Puertas'].'",
      "Cilindros":"'.$row['Cilindros'].'",
      "Odometro":"'.$row['Odometro'].'",
      "Kilometraje para servicio":"'.$row['Kilometraje_para_Servicio'].'",
      "Motor":"'.$row['Motor'].'",
      "Combustible":"'.$row['Combustible'].'",
      "Acciones":"'.$funciones.'",
      "Transmision":"'.$row['Transmision'].'"
    },';
  }
  $table = substr($table,0,strlen($table)-1);
	echo '{"data":['.$table.']}';
 ?>
