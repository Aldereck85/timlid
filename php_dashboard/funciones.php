<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {
                case "get_numberTeams":
                    $value = $_REQUEST['user'];
                    $json = $data->getNumberTeams($value); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    break;
                case "get_projects":
                    $value = $_REQUEST['user'];
                    $json = $data->getProjects($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
                case "get_nota":
                    $json = $data->getNota(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
                case "get_ventas_anio":
                    $json = $data->getVentasAnio(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
                case "get_ventas_anio_empleados":
                    $json = $data->getVentasAnioEmpleados(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
                case "get_ventas_mes_empleados":
                    $mes = $_REQUEST['mes'];
                    $json = $data->getVentasPorMesEmpleados($mes); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;
            }
            break;
        case "set_data":
            $data = new set_data();
            switch ($_REQUEST['funcion']) {
                case "set_nota":
                    $nota = $_REQUEST['nota'];
                    $res = $data->setNota($nota); //Guardando el return de la función
                    echo $res; //Retornando el resultado al ajax
                    break;
            }
            break;
    }
}
