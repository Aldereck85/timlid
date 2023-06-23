<?php
include_once "modelo.php";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {
    //echo($_REQUEST['clase']+ " " + $_REQUEST['funcion']);
    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new Get_datas;
            switch ($_REQUEST['funcion']) {
                case "get_proveedorCombo":
                    $json = $data->loadCmbProviders(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_sucursalCombo":
                    $json = $data->loadCmbsucursal(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "validate_seriefolio":
                    $serie = $_REQUEST["_serie"];
                    $folio = $_REQUEST["_folio"];
                    $idProveedor = $_REQUEST["_idProveedor"];
                    $json = $data->validate_seriefolio($idProveedor,$serie,$folio); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "validate_seriefolio_toupdate":
                    $serie = $_REQUEST["_serie"];
                    $folio = $_REQUEST["_folio"];
                    $idProveedor = $_REQUEST["_idProveedor"];
                    $cuenta = $_REQUEST["_cuenta"];
                    $json = $data->validate_seriefolio_toUpdate($idProveedor,$serie,$folio,$cuenta); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_categorias":
                    $json = $data->loadCmbCategorias(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_subcategorias":
                    $subCat = $_REQUEST['subCat'];
                    $json = $data->loadCmbSubcategorias($subCat); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
            }
        case "save_datas":
            $data = new Save_data;
            switch ($_REQUEST['funcion']){
                case "insert_all":
                    $_PKUsuario = $_SESSION["PKUsuario"];
                    $_proveedor = $_REQUEST['_proveedor'];
                    $_sucursal = $_REQUEST['_sucursal']; 
                    $_txtNoDocumento = $_REQUEST['_txtNoDocumento'];
                    $_txtSerie = $_REQUEST['_txtSerie']; 
                    $_txtSubtotal = floatval($_REQUEST['_txtSubtotal']);
                    $_txtIva = $_REQUEST['_txtIva']; 
                    $_txtIEPS = $_REQUEST['_txtIEPS'];
                    $_txtImporte = floatval($_REQUEST['_txtImporte']); 
                    $_txtDescuento = $_REQUEST['_txtDescuento']; 
                    $_fecha = $_REQUEST['_fecha'];
                    $_fechavenci = $_REQUEST['_fechavenci'];
                    $_radiodoc = $_REQUEST['_radiodoc'];
                    $_cat = $_REQUEST['_cat'];
                    $_subcat = $_REQUEST['_subcat'];
                    $_comentarios = $_REQUEST['_comentarios'];
                    $json = $data->insertcuenta($_PKUsuario,$_proveedor,$_sucursal,$_txtNoDocumento,$_txtSerie,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_fechavenci,$_radiodoc,$_cat,$_subcat,$_comentarios); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "update":
                    $_cuenta = $_REQUEST["_cuenta"];
                    $_PKUsuario = $_SESSION["PKUsuario"];
                    $_sucursal = $_REQUEST['_sucursal']; 
                    $_txtSubtotal = $_REQUEST['_txtSubtotal'];
                    $_txtIva = $_REQUEST['_txtIva']; 
                    $_txtIEPS = $_REQUEST['_txtIEPS'];
                    $_txtImporte = $_REQUEST['_txtImporte']; 
                    $_txtDescuento = $_REQUEST['_txtDescuento']; 
                    $_fecha = $_REQUEST['_fecha'];
                    $_radiodoc = $_REQUEST['_radiodoc']; 
                    $json = $data->updatecuenta($_cuenta,$_PKUsuario,$_sucursal,$_txtSubtotal,$_txtIva,$_txtIEPS,$_txtImporte,$_txtDescuento,$_fecha,$_radiodoc); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    break;

            }
        case "update_data":
            $data = new update_data();
            switch($_REQUEST['funcion'])
            {
                case 'update_category':
                    $id = $_REQUEST['id'];
                    $value = $_REQUEST['value'];
                    $value1 = $_REQUEST['value1'];
                    $json = $data->updateCategory($id,$value,$value1);
                    echo json_encode($json);
                break;
                case 'update_subcategory':
                    $id = $_REQUEST['id'];
                    $value = $_REQUEST['value'];
                    $json = $data->updateSubcategory($id,$value);
                    echo json_encode($json);
                break;
            }
        break;
        case "delete_data":
            $data = new delete_data();
            switch($_REQUEST['funcion'])
            {
                case 'delete_cuentaPagar':
                    $id = $_REQUEST['id'];
                    $json = $data->deleteCuentaPagar($id);
                    echo json_encode($json);
                break;
            }
        break;
    }
}