<?php
session_start();

if(isset($_SESSION["Usuario"])) {

    require_once '../../../include/db-conn.php';

    /* QUERY PARA TRAER TODOS LOS EMPLEADOS*/
    $stmt = $conn->prepare('SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido FROM empleados as e
                                                 LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                                        WHERE NOT EXISTS (SELECT * FROM organigrama WHERE e.PKEmpleado = organigrama.FKEmpleado) AND e.estatus = 1
                                        AND e.empresa_id = :idempresa ');
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* QUERY PARA TRAER LOS JEFES DEL ORGANIGRAMA */
    $stmt = $conn->prepare("SELECT * FROM empleados as e INNER JOIN organigrama as o ON e.PKEmpleado = o.FKEmpleado WHERE e.empresa_id = :idempresa");
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $jefes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $cantidad_jefes = $stmt->rowCount();

    $res = array('status' => 'success', 'jefes' => $jefes, 'empleados' => $empleados,'cantidad_jefes' => $cantidad_jefes);
    echo json_encode($res);
}