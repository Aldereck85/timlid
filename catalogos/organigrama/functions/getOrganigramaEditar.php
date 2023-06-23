<?php
session_start();
if (isset($_POST['id'])) {
    require_once '../../../include/db-conn.php';
    $id = $_POST['id'];
    /* QUERY PARA TRAEL EL ORGANIGRAMA A EDITAR */
    $getOrganigrama = $conn->prepare("SELECT o.PKOrganigrama, o.ParentNode, o.FKEmpleado, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido, puestos.puesto,
    o2.FKEmpleado as idempleado
    FROM organigrama as o
    INNER JOIN empleados ON empleados.PKEmpleado  = o.FKEmpleado
    LEFT JOIN datos_laborales_empleado ON datos_laborales_empleado.FKEmpleado = empleados.PKEmpleado
    LEFT JOIN puestos  ON puestos.id = datos_laborales_empleado.FKPuesto
    LEFT JOIN  organigrama as o2 ON o.ParentNode = o2.PKOrganigrama
    WHERE o.PKOrganigrama  = :id");
    $getOrganigrama->execute(array(':id' => $id));
    $organigrama = $getOrganigrama->fetch(PDO::FETCH_ASSOC);

    /* QUERY PARA TRAER TODOS LOS EMPLEADOS*/
    $stmt = $conn->prepare('SELECT e.PKEmpleado, e.Nombres, e.PrimerApellido, e.SegundoApellido FROM empleados as e
                                                 LEFT JOIN datos_laborales_empleado as de ON de.FKEmpleado = e.PKEmpleado
                                        WHERE e.estatus = 1 AND e.empresa_id = :idempresa');
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* QUERY PARA TRAER TODOS LOS EMPLEADOS EN EL ORGANIGRAMA*/
    $stmt = $conn->prepare("SELECT * FROM empleados as e INNER JOIN organigrama as o ON e.PKEmpleado = o.FKEmpleado WHERE e.empresa_id = :idempresa");
    $stmt->bindValue(':idempresa', $_SESSION['IDEmpresa']);
    $stmt->execute();
    $jefes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $res = array('status' => 'success', 'organigrama' => $organigrama, 'empleados' => $empleados,'jefes' => $jefes);
    echo json_encode($res);

} else {
    echo json_encode(array('status' => 'fail', 'message' => 'No se enviaron los datos correctamente'));
}