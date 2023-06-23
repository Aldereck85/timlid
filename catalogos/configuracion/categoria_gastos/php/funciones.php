<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_categoryTable':
            $idemp = $_REQUEST['data'];
            $json = $data->getCategoryTable($idemp);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "validar_categoriaGasto":
            $categoria = $_REQUEST['data'];
            $json = $data->validarCategoriaGasto($categoria);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionCatGasto":
            $fkcategoria = $_REQUEST['data'];
            $json = $data->validarRelacionCatGasto($fkcategoria);//Guardando el return de la función
            echo json_encode($json);
          break;
        }
    }
  }

?>