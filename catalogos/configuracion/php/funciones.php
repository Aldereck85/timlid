<?php
  include_once("clases.php");
  $array = "";
  if(isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])){

    switch($_REQUEST['clase']){
      case "get_data":
        $data = new get_data();
        switch ($_REQUEST['funcion']){
          case 'get_NoUsuarios':
            $json = $data->getNoUsuarios();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "get_cmb_estatusGral":
            $json = $data->getCmbEstatusGral(); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_userTable':
            $value = $_REQUEST['value'];
            $session_user = $_REQUEST['session_user'];
            $screen = $_REQUEST['screen'];
            $json = $data->getUserTable($value,$session_user,$screen);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case 'get_users_by_widget':
            $json = $data->getUsersByWidget($_SESSION['IDEmpresa']);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case 'get_rols':
            $json = $data->getRols();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_user':
            $value = $_REQUEST['value'];
            $json = $data->getUser($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case "validar_categoriaProducto":
            $categoria = $_REQUEST['data'];
            $json = $data->validarCategoriaProducto($categoria); //Guardando el return de la función
            echo json_encode($json);
            break;
          case "validar_categoriaCliente":
            $categoria = $_REQUEST['data'];
            $json = $data->validarCategoriaCliente($categoria); //Guardando el return de la función
            echo json_encode($json);
            break;
          case "get_datos_categoria":
            $pkCategoria = $_REQUEST['datos'];
            $json = $data->getDatosCategoria($pkCategoria); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "get_datos_categoria_clientes":
            $pkCategoria = $_REQUEST['datos'];
            $json = $data->getDatosCategoriaClientes($pkCategoria); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case 'get_marcasP':
            $value = $_SESSION["IDEmpresa"];
            $json = $data->get_marcasP($value);//Guardando el return de la función
            echo ($json); //Retornando el resultado al ajax
          break;
          case 'get_categoriasClientes':
            $json = $data->get_categoriasClientes();//Guardando el return de la función
            echo ($json); //Retornando el resultado al ajax
          break;
          case "get_datos_marca":
            $pkMarca = $_REQUEST['datos'];
            $json = $data->getDatosMarca($pkMarca); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "get_marcasTable":
            $json = $data->getMarcasTable(); //Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
            break;
          case "validar_marcaProducto":
            $marca = $_REQUEST['data'];
            $json = $data->validarMarcaProducto($marca); //Guardando el return de la función
            echo json_encode($json);
            break;
          case 'get_employer':
            //$value = $_REQUEST['value'];
            $json = $data->getEmployer();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case 'get_profiles':
              $json = $data->getProfiles();//Guardando el return de la función
              echo json_encode($json); //Retornando el resultado al ajax
              return;
          break;
          case 'get_screens':
            $value = $_REQUEST['data'];
            $json = $data->getScreen($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'get_permission_screen':
            $usuario = $_REQUEST['usuario'];
            $pantalla = $_REQUEST['pantalla'];
            $json = $data->getPermissionScreen($usuario,$pantalla);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_profilesTable":
            //$value = $_REQUEST['value'];
            //$json = $data->getProfilesTable($value);//Guardando el return de la función
            $json = $data->getProfilesTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "get_companyDataTable":
            $json = $data->getCompanyDataTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          case "get_personalDataTable":
            $json = $data->getPersonalDataTable();//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "get_companyData":
            $value = $_REQUEST['data'];
            $json = $data->getCompanyData($value);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "get_regimenFiscal":
            $json = $data->getRegimenFiscal();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case "get_stateCompany":
            $json = $data->getStateCompany();//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
          case "get_personalU":
            $idEmpleado = $_REQUEST['id'];
            $json = $data->getPersonalU($idEmpleado);//Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
          break;
        }
      break;
      case "save_data":
        $save = new save_data();
        switch ($_REQUEST['funcion']){
          case "save_user":
            $user = $_REQUEST['user'];
            $name = $_REQUEST['name'];
            $primerApp = $_REQUEST['primerApp'];
            $segundoApp = $_REQUEST['segundoApp'];
            $employer = $_REQUEST['employer'];
            $role = $_REQUEST['role'];
            $profile = $_REQUEST['profile'];
            $empresa = $_REQUEST['empresa'];
            $json = $save->saveUser($user, $name, $primerApp, $segundoApp, $employer,$role, $profile,$empresa);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
            return;
          break;
          case "save_categoria":
            $value = $_REQUEST['datos'];
            $json = $save->saveCategoria($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "save_categoriaClientes":
            $value = $_REQUEST['datos'];
            $json = $save->saveCategoriaClientes($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "save_marca":
            $value = $_REQUEST['datos'];
            $json = $save->saveMarca($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          case "save_personal":
            $nombre = $_REQUEST['nombre'];
            $apellido = $_REQUEST['apellido'];
            $genero = $_REQUEST['genero'];
            $roles = $_REQUEST['roles'];
            $estado = $_REQUEST['estado'];
            $json = $save->savePersonal($nombre, $apellido, $genero, $roles, $estado); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            break;
          /*case "save_companyData":
            $value = $_REQUEST['value'];
            $json = $save->saveCompanyData($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;*/
          case "save_widgets":
            $json = $save->saveWidgets($_REQUEST['widgets'], $_SESSION['IDEmpresa']);
            echo $json;
            return;
          break;
        }
      break;
      case "update_data":
        $update = new update_data();
        switch($_REQUEST['funcion']){
          case "update_user":
            $idUser = $_REQUEST['value'];
            $user = $_REQUEST['user'];
            $name = $_REQUEST['name'];
            $role = $_REQUEST['role'];
            $profile = $_REQUEST['profile'];
            $json = $update->updateUser($idUser, $user, $name, $role, $profile);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case "edit_categoria":
            $estatus = $_REQUEST['datos'];
            $categoria = $_REQUEST['datos2'];
            $id = $_REQUEST['datos3'];
            $json = $update->editCategoria($estatus, $categoria, $id); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "edit_categoria_clientes":
            $estatus = $_REQUEST['datos'];
            $categoria = $_REQUEST['datos2'];
            $id = $_REQUEST['datos3'];
            $json = $update->editCategoriaClientes($estatus, $categoria, $id); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
            break;
          case "edit_marca":
            $estatus = $_REQUEST['datos'];
            $marca = $_REQUEST['datos2'];
            $id = $_REQUEST['datos3'];
            $json = $update->editMarca($estatus, $marca, $id); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "activate_user":
            $idUser = $_REQUEST['value'];
            $json = $update->activateUser($idUser);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case "resend_email":
            $idUser = $_REQUEST['idUsuario'];
            $res = $update->resendEmail($idUser);
            echo json_encode($res);
          break;
          case "update_personal":
            $nombre = $_REQUEST['nombreU'];
            $apellido = $_REQUEST['apellidoU'];
            $genero = $_REQUEST['generoU'];
            $roles = $_REQUEST['rolesU'];
            $estado = $_REQUEST['estadoU'];
            $idEmpleado = $_REQUEST['idEmpleado'];
            $res = $update->updatePersonal($nombre, $apellido, $genero, $roles, $estado, $idEmpleado);
            echo json_encode($res);
          break;
        }
      break;
      case "delete_data":
        $delete = new delete_data();
        switch ($_REQUEST['funcion']) {
          case 'delete_user':
            $value = $_REQUEST['value'];
            $json = $delete->deleteUser($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case "delete_categoria":
            $value = $_REQUEST['datos'];
            $json = $delete->deleteCategoria($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          case "delete_categoriaCliente":
            $value = $_REQUEST['datos'];
            $json = $delete->deleteCategoriaCliente($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case "delete_marca":
            $value = $_REQUEST['datos'];
            $json = $delete->deleteMarca($value); //Guardando el return de la función
            echo json_encode($json); //Retornando el resultado al ajax
            return;
          break;
          case 'delete_companyData':
            $value = $_REQUEST['data'];
            $json = $delete->deleteCompanyData($value);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
          case 'delete_personal':
            $idEmpleado = $_REQUEST['idEmpleado'];
            $json = $delete->deletePersonal($idEmpleado);//Guardando el return de la función
            echo $json; //Retornando el resultado al ajax
          break;
        }
      break;
    }
  }
?>