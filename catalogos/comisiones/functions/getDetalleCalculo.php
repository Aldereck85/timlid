<?php
session_start();
require_once('../../../include/db-conn.php');
/*Recupera los datos de un cálculo*/ 

if(isset($_REQUEST['idComision']) && !empty($_REQUEST['idComision'])) {
    $idComision = $_REQUEST['idComision'];
    
    $stmt = $conn->prepare('SELECT concat(e.Nombres," ",e.PrimerApellido," ",e.SegundoApellido) as nombre_vendedor, 
                                    DATE_FORMAT(c.fecha_registro,"%d-%m-%Y") as fecha_registro,
                                    c.fecha_ini as fecha_ini, 
                                    c.fecha_fin as fecha_fin, 
                                    c.porcentaje_comision as porcentaje, 
                                    c.monto_calculado as monto_calculado,
                                    c.monto_ingresado as monto_comisionado, 
                                    c.saldo_insoluto as saldo_insoluto, 
                                    c.estatus as estatus
                            FROM comisiones c 
                              INNER JOIN empleados e ON c.id_empleado=e.PKEmpleado
                            WHERE id=:idComision');
    
    $stmt->bindValue(":idComision",$idComision);
    $stmt->execute();
    
    $comision=$stmt-> fetch(PDO::FETCH_ASSOC);
    echo json_encode($comision);
  }
?>