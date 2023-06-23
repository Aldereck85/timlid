<?php
include_once "clases.php";
$array = "";
if (isset($_REQUEST['clase']) && isset($_REQUEST['funcion'])) {

    switch ($_REQUEST['clase']) {
        case "get_data":
            $data = new get_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                /////////////////////////TABLAS//////////////////////////////
                
                case "get_vehiculosTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getVehiculosTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_cargasCombustibleVehiculoTable":
                    $pkVehiculo = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getCargasCombustibleVehiculoTable($pkVehiculo, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_serviciosVehiculoTable":
                    $pkVehiculo = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getServiciosVehiculoTable($pkVehiculo, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_prestamosVehiculoTable":
                    $pkVehiculo = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getPrestamosVehiculoTable($pkVehiculo, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_prestamosVehiculoTableFiltered":
                    $pkVehiculo = $_REQUEST['data']['pkVehiculo'];
                    $empleado_id = $_REQUEST['data']['empleado'];
                    $fromDate = $_REQUEST['data']['from'];
                    $toDate = $_REQUEST['data']['to'];
                    $permissionEdit = $_REQUEST['data']['data2'];
                    $permissionDelete = $_REQUEST['data']['data3'];
                    $json = $data->getPrestamosVehiculoTableFiltered($pkVehiculo, $empleado_id, $fromDate, $toDate, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_guiasTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getGuiasTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_paqueteriasTable":
                    $isPermissionsEdit = $_REQUEST['data'];
                    $isPermissionsDelete = $_REQUEST['data2'];
                    $json = $data->getPaqueteriasTable($isPermissionsEdit,$isPermissionsDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_contactoPaqueteriaTable":
                    $pkPaqueteria = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getContactoPaqueteriaTable($pkPaqueteria, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_cuentaBancariaPaqueteriaTable":
                    $pkPaqueteria = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getCuentaBancariaPaqueteriaTable($pkPaqueteria, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;
                case "get_sucursalesPaqueteriaTable":
                    $pkPaqueteria = $_REQUEST['data'];
                    $permissionEdit = $_REQUEST['data2'];
                    $permissionDelete = $_REQUEST['data3'];
                    $json = $data->getSucursalesPaqueteriaTable($pkPaqueteria, $permissionEdit, $permissionDelete); //Guardando el return de la función
                    echo $json; //Retornando el resultado al ajax
                    return;
                break;

                /////////////////////////COMBOS//////////////////////////////

                case "get_cmb_vehiculo_responsable":
                    $json = $data->getCmbVehiculoResponsable(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_vehiculo_unidadMedida":
                    $json = $data->getCmbVehiculoUnidadMedida(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_vehiculo_monedaCU":
                    $json = $data->getCmbVehiculoMonedaCU(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_vehiculo_tipoServicio":
                    $json = $data->getCmbVehiculoTipoServicio(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_vehiculo_empleados":
                    $json = $data->getCmbVehiculoEmpleados(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_guia_tipoPago":
                    $json = $data->getCmbGuiaTipoPago(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_guia_paqueteria":
                    $json = $data->getCmbGuiaPaqueteria(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_paises":
                    $json = $data->getCmbPaises(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_estados":
                    $pkPais = $_REQUEST['data'];
                    $json = $data->getCmbEstados($pkPais); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_cmb_banco":
                    $json = $data->getCmbBanco(); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                /////////////////////////DATOS PARA EDICIÓN//////////////////////////////
                
                case "get_datos_vehiculo_cargaCombustible":
                    $PKCargaCombustible = $_REQUEST['datos'];
                    $json = $data->getDatosVehiculoCargaCombustible($PKCargaCombustible); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_vehiculo_servicio":
                    $PKServicio = $_REQUEST['datos'];
                    $json = $data->getDatosVehiculoServicio($PKServicio); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_vehiculo_prestamo":
                    $PKPrestamo = $_REQUEST['datos'];
                    $json = $data->getDatosVehiculoPrestamo($PKPrestamo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_vehiculo_generales":
                    $PKVehiculo = $_REQUEST['datos'];
                    $json = $data->getDatosVehiculoGenerales($PKVehiculo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_vehiculo_poliza":
                    $PKVehiculo = $_REQUEST['datos'];
                    $json = $data->getDatosVehiculoPoliza($PKVehiculo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_guia_general":
                    $PKGuia = $_REQUEST['datos'];
                    $json = $data->getDatosGuiaGeneral($PKGuia); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_paqueteria_contacto":
                    $pkContacto = $_REQUEST['datos'];
                    $json = $data->getDatosPaqueteriaContacto($pkContacto); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_paqueteria_cuentaBancaria":
                    $pkCuentaBancaria = $_REQUEST['datos'];
                    $json = $data->getDatosPaqueteriaCuentaBancaria($pkCuentaBancaria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_paqueteria_sucursal":
                    $pkSucursal = $_REQUEST['datos'];
                    $json = $data->getDatosPaqueteriaSucursal($pkSucursal);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_datos_paqueteria_generales":
                    $pkPaqueteria = $_REQUEST['datos'];
                    $json = $data->getDatosPaqueteriaGenerales($pkPaqueteria);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "get_Pdf_Prestamo":
                    $id_prestamo=$_REQUEST['idPrestamo'];
                    $json = $data->getPdfPrestamo($id_prestamo);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                /////////////////////////VALIDACIONES//////////////////////////////
                
                case "validar_Permisos":
                    $pkPantalla = $_REQUEST['data'];
                    $json = $data->validarPermisos($pkPantalla); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_EmpresaVehiculo":
                    $pkVehiculo = $_REQUEST['data'];
                    $json = $data->validarEmpresaVehiculo($pkVehiculo); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_nombreComercial":
                    $nombreComercial = $_REQUEST['data'];
                    $json = $data->validarPaqueteriaNombreComercial($nombreComercial); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_razonSocial":
                    $razonSocial = $_REQUEST['data'];
                    $PKProveedor = $_REQUEST['data2'];
                    $json = $data->validarPaqueteriaRazonSocial($razonSocial, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_rfc":
                    $rfc = $_REQUEST['data'];
                    $PKProveedor = $_REQUEST['data2'];
                    $json = $data->validarPaqueteriaRfc($rfc, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_estado":
                    $estado = $_REQUEST['data'];
                    $PKPais = $_REQUEST['data2'];
                    $json = $data->validarEstado($estado, $PKPais); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_EmpresaPaqueteria":
                    $pkPaqueteria = $_REQUEST['data'];
                    $json = $data->validarEmpresaPaqueteria($pkPaqueteria); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_contacto":
                    $email = $_REQUEST['data'];
                    $PKProveedor = $_REQUEST['data2'];
                    $json = $data->validarPaqueteriaContacto($email, $PKProveedor); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_noCuenta":
                    $noCuenta = $_REQUEST['data'];
                    $json = $data->validarPaqueteriaNoCuenta($noCuenta); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_CLABE":
                    $clabe = $_REQUEST['data'];
                    $json = $data->validarPaqueteriaCLABE($clabe); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_datosBancarios":
                    $pkBanco = $_REQUEST['data'];
                    $noCuenta = $_REQUEST['data2'];
                    $clabe = $_REQUEST['data3'];
                    $pkPaqueteria = $_REQUEST['data4'];
                    $json = $data->validarPaqueteriaDatosBancarios($pkBanco, $noCuenta, $clabe, $pkPaqueteria); //Guardando el return de la función
                    echo json_encode($json);
                break;
                case "validar_Paqueteria_sucursal":
                    $sucursal = $_REQUEST['data'];
                    $pkPaqueteria = $_REQUEST['data2'];
                    $json = $data->validarPaqueteriaSucursal($sucursal, $pkPaqueteria);//Guardando el return de la función
                    echo json_encode($json);
                  break;

                /////////////////////////INFO//////////////////////////////
                case "get_PKEmpresa":
                    $json = $data->getPKEmpresa();//Guardando el return de la función
                    echo json_encode($json);
                break;

                /* END JAVIER RAMIREZ */
            }
            break;

        case "save_data":
            $data = new save_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
                case "save_datosVehiculo":
                    $array = $_REQUEST['datos'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $linea = $_REQUEST['datos']['linea'];
                    $marca = $_REQUEST['datos']['marca'];
                    $serie = $_REQUEST['datos']['serie'];
                    $placas  = $_REQUEST['datos']['placas'];
                    $modelo  = $_REQUEST['datos']['modelo'];
                    $puertas  = $_REQUEST['datos']['puertas'];
                    $cilindros  = $_REQUEST['datos']['cilindros'];
                    $odometro  = $_REQUEST['datos']['odometro'];
                    $kilometros  = $_REQUEST['datos']['kilometros'];
                    $motor  = $_REQUEST['datos']['motor'];
                    $color  = $_REQUEST['datos']['color'];
                    $combustible  = $_REQUEST['datos']['combustible'];
                    $transmision  = $_REQUEST['datos']['transmision'];
                    $responsable  = $_REQUEST['datos']['responsable'];
                $json = $data->saveDatosVehiculo(
                        $array, 
                        $estatus,
                        $linea, 
                        $marca, 
                        $serie, 
                        $placas, 
                        $modelo, 
                        $puertas, 
                        $cilindros, 
                        $odometro, 
                        $kilometros, 
                        $motor, 
                        $color, 
                        $combustible, 
                        $transmision, 
                        $responsable
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosVehiculoCargaCombustible":
                    $array = $_REQUEST['datos'];
                    $fechaCarga = $_REQUEST['datos']['fechaCarga'];
                    $cantidad = $_REQUEST['datos']['cantidad'];
                    $unidadMedida = $_REQUEST['datos']['unidadMedida'];
                    $costoUnitario = $_REQUEST['datos']['costoUnitario'];
                    $moneda = $_REQUEST['datos']['moneda'];
                    $odometro = $_REQUEST['datos']['odometro'];
                    $tanqueLleno = $_REQUEST['datos']['tanqueLleno']['active'];
                    $pkVehiculo = $_REQUEST['datos']['pkVehiculo'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosVehiculoCargaCombustible(
                        $array, 
                        $fechaCarga, 
                        $cantidad, 
                        $unidadMedida, 
                        $costoUnitario, 
                        $moneda, 
                        $odometro, 
                        $tanqueLleno, 
                        $pkVehiculo, 
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosVehiculoPolizaSeguro":
                    $array = $_REQUEST['datos'];
                    $noPoliza = $_REQUEST['datos']['noPoliza'];
                    $aseguradora = $_REQUEST['datos']['aseguradora'];
                    $fechaInicio = $_REQUEST['datos']['fechaInicio'];
                    $fechaTermino = $_REQUEST['datos']['fechaTermino'];
                    $inciso = $_REQUEST['datos']['inciso'];
                    $importePoliza = $_REQUEST['datos']['importePoliza'];
                    $monedaPoliza = $_REQUEST['datos']['monedaPoliza'];
                    $agenteSeguros = $_REQUEST['datos']['agenteSeguros'];
                    $telefonoAgente = $_REQUEST['datos']['telefonoAgente'];
                    $telefonoSiniestros = $_REQUEST['datos']['telefonoSiniestros'];
                    $archivo = $_REQUEST['datos']['archivo'];
                    $pkVehiculo = $_REQUEST['datos']['pkVehiculo'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosVehiculoPolizaSeguro(
                        $array, 
                        $noPoliza, 
                        $aseguradora, 
                        $fechaInicio, 
                        $fechaTermino, 
                        $inciso, 
                        $importePoliza, 
                        $monedaPoliza,
                        $agenteSeguros,
                        $telefonoAgente,
                        $telefonoSiniestros,
                        $archivo,
                        $pkVehiculo, 
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosVehiculoServicio":
                    $array = $_REQUEST['datos'];
                    $servicio = $_REQUEST['datos']['servicio'];
                    $descripcion = $_REQUEST['datos']['descripcion'];
                    $lugar = $_REQUEST['datos']['lugar'];
                    $tipoServicio = $_REQUEST['datos']['tipoServicio'];
                    $costoServico = $_REQUEST['datos']['costoServico'];
                    $moneda = $_REQUEST['datos']['moneda'];
                    $pkVehiculo = $_REQUEST['datos']['pkVehiculo'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $archivo = $_REQUEST['datos']['archivo'];
                    $json = $data->saveDatosVehiculoServicio(
                        $array, 
                        $servicio, 
                        $descripcion, 
                        $lugar, 
                        $tipoServicio, 
                        $costoServico, 
                        $moneda, 
                        $pkVehiculo, 
                        $isEdit,
                        $archivo
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosVehiculoPrestamo":
                    $array = $_REQUEST['datos'];
                    $empleado = $_REQUEST['datos']['empleado'];
                    $motivo = $_REQUEST['datos']['motivo'];
                    $nivel_combustible_inicio = $_REQUEST['datos']['nivel_combustible_inicio'];
                    $id_autorizo = $_REQUEST['datos']['id_autorizo'];
                    $kilometraje_inicio = $_REQUEST['datos']['kilometraje_inicio'];
                    $fecha = $_REQUEST['datos']['fecha'];
                    $pkVehiculo = $_REQUEST['datos']['pkVehiculo'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosVehiculoPrestamo(
                        $array, 
                        $empleado, 
                        $motivo, 
                        $nivel_combustible_inicio, 
                        $id_autorizo, 
                        $kilometraje_inicio, 
                        $fecha, 
                        $pkVehiculo,  
                        $isEdit 
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosGuiaGeneral":
                    $array = $_REQUEST['datos'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $numero = $_REQUEST['datos']['numero'];
                    $descripcion = $_REQUEST['datos']['descripcion'];
                    $tipoPago = $_REQUEST['datos']['tipoPago'];
                    $paqueteria = $_REQUEST['datos']['paqueteria'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosGuiaGeneral(
                        $array, 
                        $estatus, 
                        $numero, 
                        $descripcion, 
                        $tipoPago, 
                        $paqueteria,
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_estado_pais":
                    $estado = $_REQUEST['data'];
                    $pais = $_REQUEST['data2'];
                    $json = $data->saveEstadoPais($estado, $pais); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosPaqueteria":
                    $array = $_REQUEST['datos'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $nombreComercial = $_REQUEST['datos']['nombreComercial'];
                    $telefono = $_REQUEST['datos']['telefono'];
                    $email = $_REQUEST['datos']['email'];
                    $razonSocial  = $_REQUEST['datos']['razonSocial'];
                    $rfc  = $_REQUEST['datos']['rfc'];
                    $calle  = $_REQUEST['datos']['calle'];
                    $numeroExt  = $_REQUEST['datos']['numeroExt'];
                    $numeroInt  = $_REQUEST['datos']['numeroInt'];
                    $colonia  = $_REQUEST['datos']['colonia'];
                    $municipio  = $_REQUEST['datos']['municipio'];
                    $pais  = $_REQUEST['datos']['pais'];
                    $estado  = $_REQUEST['datos']['estado'];
                    $cp  = $_REQUEST['datos']['cp'];
                $json = $data->saveDatosPaqueteria(
                        $array, 
                        $estatus,
                        $nombreComercial, 
                        $telefono, 
                        $email, 
                        $razonSocial, 
                        $rfc, 
                        $calle, 
                        $numeroExt, 
                        $numeroInt, 
                        $colonia, 
                        $municipio, 
                        $pais, 
                        $estado, 
                        $cp
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosPaqueteria_Contacto":
                    $array = $_REQUEST['datos'];
                    $nombreContacto = $_REQUEST['datos']['nombreContacto'];
                    $apellidoContacto = $_REQUEST['datos']['apellidoContacto'];
                    $puesto = $_REQUEST['datos']['puesto'];
                    $telefono = $_REQUEST['datos']['telefono'];
                    $celular = $_REQUEST['datos']['celular'];
                    $email = $_REQUEST['datos']['email'];
                    $pkPaqueteria = $_REQUEST['datos']['pkPaqueteria'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosPaqueteriaContacto(
                        $array, 
                        $nombreContacto, 
                        $apellidoContacto, 
                        $puesto, 
                        $telefono, 
                        $celular, 
                        $email, 
                        $pkPaqueteria, 
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosPaqueteria_CuentaBancaria":
                    $array = $_REQUEST['datos'];
                    $banco = $_REQUEST['datos']['banco'];
                    $noCuenta = $_REQUEST['datos']['noCuenta'];
                    $clabe = $_REQUEST['datos']['clabe'];
                    $moneda = $_REQUEST['datos']['moneda'];
                    $pkPaqueteria = $_REQUEST['datos']['pkPaqueteria'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosPaqueteriaCuentaBancaria(
                        $array, 
                        $banco, 
                        $noCuenta, 
                        $clabe, 
                        $moneda, 
                        $pkPaqueteria, 
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "save_datosPaqueteria_Sucursal":
                    $array = $_REQUEST['datos'];
                    $sucursal = $_REQUEST['datos']['sucursal'];
                    $contacto = $_REQUEST['datos']['contacto'];
                    $telefono = $_REQUEST['datos']['telefono'];
                    $email = $_REQUEST['datos']['email'];
                    $calle = $_REQUEST['datos']['calle'];
                    $numeroExt = $_REQUEST['datos']['numeroExt'];
                    $numeroInt = $_REQUEST['datos']['numeroInt'];
                    $colonia = $_REQUEST['datos']['colonia'];
                    $municipio = $_REQUEST['datos']['municipio'];
                    $pais = $_REQUEST['datos']['pais'];
                    $estado = $_REQUEST['datos']['estado'];
                    $cp = $_REQUEST['datos']['cp'];
                    $pkPaqueteria = $_REQUEST['datos']['pkPaqueteria'];
                    $isEdit = $_REQUEST['datos']['isEdit'];
                    $json = $data->saveDatosPaqueteriaSucursal(
                        $array, 
                        $sucursal, 
                        $contacto, 
                        $telefono, 
                        $email, 
                        $calle, 
                        $numeroExt, 
                        $numeroInt, 
                        $colonia, 
                        $municipio, 
                        $pais, 
                        $estado, 
                        $cp, 
                        $pkPaqueteria, 
                        $isEdit
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                //END JAVIER RAMIREZ
            }
            break;

        case "edit_data":
            $data = new edit_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
                case "edit_datosVehiculo":
                    $array = $_REQUEST['datos'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $linea = $_REQUEST['datos']['linea'];
                    $marca = $_REQUEST['datos']['marca'];
                    $serie = $_REQUEST['datos']['serie'];
                    $placas  = $_REQUEST['datos']['placas'];
                    $modelo  = $_REQUEST['datos']['modelo'];
                    $puertas  = $_REQUEST['datos']['puertas'];
                    $cilindros  = $_REQUEST['datos']['cilindros'];
                    $odometro  = $_REQUEST['datos']['odometro'];
                    $kilometros  = $_REQUEST['datos']['kilometros'];
                    $motor  = $_REQUEST['datos']['motor'];
                    $color  = $_REQUEST['datos']['color'];
                    $combustible  = $_REQUEST['datos']['combustible'];
                    $transmision  = $_REQUEST['datos']['transmision'];
                    $responsable  = $_REQUEST['datos']['responsable'];
                    $PKVehiculo = $_REQUEST['datos']['pkVehiculo'];
                $json = $data->editDatosVehiculo(
                        $array, 
                        $estatus,
                        $linea, 
                        $marca, 
                        $serie, 
                        $placas, 
                        $modelo, 
                        $puertas, 
                        $cilindros, 
                        $odometro, 
                        $kilometros, 
                        $motor, 
                        $color, 
                        $combustible, 
                        $transmision, 
                        $responsable,
                        $PKVehiculo
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosVehiculoEstatus":
                    $PKVehiculo = $_REQUEST['datos'];
                    $json = $data->editDatosVehiculoEstatus($PKVehiculo); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosGuiaEstatus":
                    $PKGuia = $_REQUEST['datos'];
                    $json = $data->editDatosGuiaEstatus($PKGuia); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosPaqueteria":
                    $array = $_REQUEST['datos'];
                    $estatus = $_REQUEST['datos']['estatus'];
                    $nombreComercial = $_REQUEST['datos']['nombreComercial'];
                    $telefono = $_REQUEST['datos']['telefono'];
                    $email = $_REQUEST['datos']['email'];
                    $razonSocial  = $_REQUEST['datos']['razonSocial'];
                    $rfc  = $_REQUEST['datos']['rfc'];
                    $calle  = $_REQUEST['datos']['calle'];
                    $numeroExt  = $_REQUEST['datos']['numeroExt'];
                    $numeroInt  = $_REQUEST['datos']['numeroInt'];
                    $colonia  = $_REQUEST['datos']['colonia'];
                    $municipio  = $_REQUEST['datos']['municipio'];
                    $pais  = $_REQUEST['datos']['pais'];
                    $estado  = $_REQUEST['datos']['estado'];
                    $cp  = $_REQUEST['datos']['cp'];
                    $pkPaqueteria  = $_REQUEST['datos']['pkPaqueteria'];
                $json = $data->editDatosPaqueteria(
                        $array, 
                        $estatus,
                        $nombreComercial, 
                        $telefono, 
                        $email, 
                        $razonSocial, 
                        $rfc, 
                        $calle, 
                        $numeroExt, 
                        $numeroInt, 
                        $colonia, 
                        $municipio, 
                        $pais, 
                        $estado, 
                        $cp,
                        $pkPaqueteria
                    ); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "edit_datosPaqueteriaEstatus":
                    $PKPaqueteria = $_REQUEST['datos'];
                    $json = $data->editDatosPaqueteriaEstatus($PKPaqueteria); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;

                //END JAVIER RAMIREZ
            }
            break;

        case "delete_data":
            $data = new delete_data();
            switch ($_REQUEST['funcion']) {
                //JAVIER RAMIREZ
                
                case "delete_vehiculo_cargaCombustible":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteVehiculoCargaCombustible($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_vehiculo_servicio":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteVehiculoServicio($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_vehiculo_prestamo":
                    $value = $_REQUEST['datos'];
                    $json = $data->deleteVehiculoPrestamo($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "close_vehiculo_prestamo":
                    $array = $_REQUEST['datos'];
                    $combustible_final=$_REQUEST['datos']['nivel_combustible_final'];
                    $kilometraje_final=$_REQUEST['datos']['kilometraje_final'];
                    $pkPrestamo=$_REQUEST['datos']['pkPrestamo'];
                    $json = $data->closeVehiculoPrestamo(
                        $array, 
                        $combustible_final, 
                        $kilometraje_final, 
                        $pkPrestamo
                    );//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_paqueteria_contacto":
                    $value = $_REQUEST['datos'];
                    $json = $data->deletePaqueteriaContacto($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_paqueteria_cuentaBancaria":
                    $value = $_REQUEST['datos'];
                    $json = $data->deletePaqueteriaCuentaBancaria($value); //Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                case "delete_paqueteria_sucursales":
                    $value = $_REQUEST['datos'];
                    $json = $data->deletePaqueteriaSucursales($value);//Guardando el return de la función
                    echo json_encode($json); //Retornando el resultado al ajax
                    return;
                break;
                
                //END JAVIER RAMIREZ
            }
            break;
    }

}