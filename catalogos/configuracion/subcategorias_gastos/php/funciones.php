<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){
    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_subcategoryTable':
            $json = $data->getSubcategoryTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "validar_subcategoriaGasto":
            $subcategoria = $_REQUEST['data'];
            $categoria = $_REQUEST['categoria'];
            $json = $data->validarSubcategoriaGasto($subcategoria, $categoria);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_existeRelacionSubCatGasto":
            $fksubcategoria = $_REQUEST['data'];
            $json = $data->validarRelacionSubCatGasto($fksubcategoria);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "validar_subcategoriaGastoU":
            $nomSubcategoria = $_REQUEST['data'];
            $fksubcategoria = $_REQUEST['data2'];
            $json = $data->validarSubcategoriaGastoU($nomSubcategoria, $fksubcategoria);//Guardando el return de la función
            echo json_encode($json);
          break;
          case "get_cmb_categorias_g":
            $idemp = $_REQUEST['data'];
            $json = $data->getCmbCategoriaG($idemp); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
        }
    }
  }

?>