<?php
include_once("class.php");
$array = "";

if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    switch ($_REQUEST['clase']) {
        case 'get_data':
            switch ($_REQUEST['funcion']) {
                case 'get_charts':
                    $json = get_data::getChart(); //Guardando el return de la función          
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case 'get_chartsFilter':
                    $initialDate = $_POST['initialDate'];
                    $finalDate = $_POST['finalDate'];
                    $json = get_data::getChartFilter($initialDate,$finalDate); //Guardando el return de la función          
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                case 'get_generalFilterData':
                    $initialDate = $_POST['initialDate'];
                    $finalDate = $_POST['finalDate'];
                    $json = get_data::getGeneralFilterData($initialDate,$finalDate); //Guardando el return de la función          
                    echo json_encode($json); //Retornando el resultado al ajax
                break;

                case 'get_generateExpenseReport':
                    $year = $_POST['year'];
                    $month = $_POST['month'];
                    $initialDate = $_POST['initialDate'];
                    $finalDate = $_POST['finalDate'];
                    $json = get_data::getGenerateExpenseReport($year,$month, $initialDate, $finalDate); //Guardando el return de la función          
                    echo json_encode($json); //Retornando el resultado al ajax
                break;
            }
    }
}