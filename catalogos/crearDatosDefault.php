<?php
try{
    $conn->beginTransaction();
    #Consulta de la empresa del usuario que se logea
    $selectEmpresa = " SELECT empresa_id, id FROM usuarios WHERE usuario = :user";
    $stmEmpresa = $conn->prepare($selectEmpresa);
    $stmEmpresa->execute(array(':user' => $user));
    $usuarioRes = $stmEmpresa->fetch(PDO::FETCH_ASSOC);

    #Consulta del id_empleado
    $selectIdEmpleado = "SELECT COUNT(PKEmpleado) + 1 AS id_empleado FROM empleados WHERE empresa_id=:empresa_id";
    $stmSelectIdEmpleado = $conn->prepare($selectIdEmpleado);
    $stmSelectIdEmpleado->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $id_empleado = $stmSelectIdEmpleado->fetch(PDO::FETCH_ASSOC);

    #Inserción del empleado predeterminado
    $insertEmpleado = "INSERT INTO empleados(id_empleado, Nombres, PrimerApellido, Genero, FKEstado, empresa_id, estatus)
                        VALUES (:id_empleado, 'Empleado', 'Predeterminado', 'Masculino', 14, :empresa_id, 1)";
    $stmEmp = $conn->prepare($insertEmpleado);
    $stmEmp->execute(array(':id_empleado' => $id_empleado['id_empleado'], ':empresa_id' => $usuarioRes['empresa_id']));
    $idEmpleado = $conn->lastInsertId();

    #Inserción del id del empleado
    $insertIdEmpleado = "INSERT INTO id_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 1)";
    $stmIdEmpleado = $conn->prepare($insertIdEmpleado);
    $stmIdEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del nombre del empleado
    $insertNombreEmpleado = "INSERT INTO nombre_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 2)";
    $stmNombreEmpleado = $conn->prepare($insertNombreEmpleado);
    $stmNombreEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del primer apellido del empleado
    $insertPrimerApEmpleado = "INSERT INTO primer_apellido (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 3)";
    $stmPrimerApEmpleado = $conn->prepare($insertPrimerApEmpleado);
    $stmPrimerApEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del segundo apellido del empleado
    $insertSegundoApEmpleado = "INSERT INTO segundo_apellido (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 4)";
    $stmSegundoApEmpleado = $conn->prepare($insertSegundoApEmpleado);
    $stmSegundoApEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del estado civil del empleado
    $insertECivilEmpleado = "INSERT INTO e_civil_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 5)";
    $stmECivilEmpleado = $conn->prepare($insertECivilEmpleado);
    $stmECivilEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del género del empleado
    $insertGeneroEmpleado = "INSERT INTO genero_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 6)";
    $stmGeneroEmpleado = $conn->prepare($insertGeneroEmpleado);
    $stmGeneroEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción de la calle del empleado
    $insertDireccionEmpleado = "INSERT INTO direccion_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 7)";
    $stmDireccionEmpleado = $conn->prepare($insertDireccionEmpleado);
    $stmDireccionEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del estado del empleado
    $insertEstadoEmpleado = "INSERT INTO estado_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 8)";
    $stmEstadoEmpleado = $conn->prepare($insertEstadoEmpleado);
    $stmEstadoEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del ciudad del empleado
    $insertCiudadEmpleado = "INSERT INTO ciudad_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 9)";
    $stmCiudadEmpleado = $conn->prepare($insertCiudadEmpleado);
    $stmCiudadEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del colonia del empleado
    $insertColoniaEmpleado = "INSERT INTO colonia_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 10)";
    $stmColoniaEmpleado = $conn->prepare($insertColoniaEmpleado);
    $stmColoniaEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del código postal del empleado
    $insertCPostalEmpleado = "INSERT INTO postal_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 11)";
    $stmCPostalEmpleado = $conn->prepare($insertCPostalEmpleado);
    $stmCPostalEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción de la CURP del empleado
    $insertCURPEmpleado = "INSERT INTO curp_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 12)";
    $stmCURPEmpleado = $conn->prepare($insertCURPEmpleado);
    $stmCURPEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del RFC del empleado
    $insertRFCEmpleado = "INSERT INTO rfc_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 13)";
    $stmRFCEmpleado = $conn->prepare($insertRFCEmpleado);
    $stmRFCEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción de la fecha de nacimiento del empleado
    $insertFNacimientoEmpleado = "INSERT INTO nacimiento_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 14)";
    $stmFNacimientoEmpleado = $conn->prepare($insertFNacimientoEmpleado);
    $stmFNacimientoEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del teléfono del empleado
    $insertTelefonoEmpleado = "INSERT INTO telefono_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 15)";
    $stmTelefonoEmpleado = $conn->prepare($insertTelefonoEmpleado);
    $stmTelefonoEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del estatus del empleado
    $insertEstatusEmpleado = "INSERT INTO estatus_de_empleado (FKEmpleado, FKColumnasEmp) VALUES(:empleado_id, 16)";
    $stmEstatusEmpleado = $conn->prepare($insertEstatusEmpleado);
    $stmEstatusEmpleado->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Vendedor'
    $insertRolVend = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES( :empleado_id, 1)";
    $stmRolVend = $conn->prepare($insertRolVend);
    $stmRolVend->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Responsable gastos'
    $insertRolVend = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES (:empleado_id, 2)";
    $stmRolVend = $conn->prepare($insertRolVend);
    $stmRolVend->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Surtidor'
    $insertRolSurt = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES(:empleado_id, 3)";
    $stmRolSurt = $conn->prepare($insertRolSurt);
    $stmRolSurt->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Chofer'
    $insertRolChof = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES(:empleado_id, 4)";
    $stmRolChof = $conn->prepare($insertRolChof);
    $stmRolChof->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Comprador'
    $insertRolComp = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES(:empleado_id, 5)";
    $stmRolComp = $conn->prepare($insertRolComp);
    $stmRolComp->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Almacenista'
    $insertRolAlmac = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES(:empleado_id, 7)";
    $stmRolAlmac = $conn->prepare($insertRolAlmac);
    $stmRolAlmac->execute(array(':empleado_id' => $idEmpleado));

    #Inserción del rol 'Recursos Humanos'
    $insertRolRRHH = "INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES(:empleado_id, 8)";
    $stmRolRRHH = $conn->prepare($insertRolRRHH);
    $stmRolRRHH->execute(array(':empleado_id' => $idEmpleado));

    #Inserción de la sucursal con inventario
    $insertSucursalCI = "INSERT INTO sucursales (sucursal, calle, numero_exterior, prefijo, numero_interior, colonia, municipio, telefono,              activar_inventario, estado_id, pais_id, empresa_id, estatus, inventario_inicial, zona_salario_minimo) 
                        VALUES ('Sucursal con inventario',  'Av. Terranova', 978, 'Bodega', '', 'Prados Providencia', 'Zapopan', '3312455623', 1, 14, 146, :empresa_id, 1, 0, 1)";
    $stmSucursalCI = $conn->prepare($insertSucursalCI);
    $stmSucursalCI->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idSucursalCI = $conn->lastInsertId();

    #Inserción de la cuenta caja chica con inventario en cuentas_bancarias_empresa
    $insertCuentaCajaChicaCI = "INSERT INTO cuentas_bancarias_empresa (tipo_cuenta, Nombre, estatus, empresa_id, saldo_actual)
                                VALUES (4, 'Caja chica con inventario', 1, :empresa_id, 0.00)";
    $stmCuentaCajaChicaCI = $conn->prepare($insertCuentaCajaChicaCI);
    $stmCuentaCajaChicaCI->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCuentaCajaChicaCI = $conn->lastInsertId();

    #Inserción de la cuenta caja chica con inventario en cuenta_caja_chica
    $insertCajaChicaCI = "INSERT INTO cuenta_caja_chica (Descripcion, SaldoInicialCaja, usuario_id, FKCuenta, FKMoneda, FKLocacion)
                            VALUES ('Cuenta de caja chica con inventario', 0.00, :usuario_id, :cuenta_id, 100, :sucursal_id)";
    $stmCajaChicaCI = $conn->prepare($insertCajaChicaCI);
    $stmCajaChicaCI->execute(array(':usuario_id' => $usuarioRes['id'], ':cuenta_id' => $idCuentaCajaChicaCI, ':sucursal_id' => $idSucursalCI));

    #Inserción de la sucursal sin inventario
    $insertSucursalSI = "INSERT INTO sucursales (sucursal, calle, numero_exterior, prefijo, numero_interior, colonia, municipio, telefono,                     activar_inventario, estado_id, pais_id, empresa_id, estatus, inventario_inicial, zona_salario_minimo)
                            VALUES ('Sucursal sin inventario', 'Blvd. Marcelino Barragán', 2077, 'Bodega', '', 'Prados del Nilo', 'Guadalajara', '3345895612', 0, 14, 146, :empresa_id, 1, 0, 1)";
    $stmSucursalSI = $conn->prepare($insertSucursalSI);
    $stmSucursalSI->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idSucursalSI = $conn->lastInsertId();

    #Inserción de la cuenta caja chica sin inventario en cuentas_bancarias_empresa
    $insertCuentaCajaChicaSI = "INSERT INTO cuentas_bancarias_empresa (tipo_cuenta, Nombre, estatus, empresa_id, saldo_actual)
                                VALUES (4, 'Caja chica sin inventario', 1, :empresa_id, 0.00)";
    $stmCuentaCajaChicaSI = $conn->prepare($insertCuentaCajaChicaSI);
    $stmCuentaCajaChicaSI->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCuentaCajaChicaSI = $conn->lastInsertId();

    #Inserción de la cuenta caja chica sin inventario en cuenta_caja_chica
    $insertCajaChicaSI = "INSERT INTO cuenta_caja_chica (Descripcion, SaldoInicialCaja, usuario_id, FKCuenta, FKMoneda, FKLocacion)
                            VALUES ('Cuenta de caja chica sin inventario', 0.00, :usuario_id, :cuenta_id, 100, :sucursal_id)";
    $stmCajaChicaSI = $conn->prepare($insertCajaChicaSI);
    $stmCajaChicaSI->execute(array(':usuario_id' => $usuarioRes['id'], ':cuenta_id' => $idCuentaCajaChicaSI, ':sucursal_id' => $idSucursalSI));

    #Inserción de la categoría del producto
    $insertCategoriasProductos = "INSERT INTO categorias_productos (CategoriaProductos, estatus, empresa_id)
                                    VALUES ('Predeterminada', 1, :empresa_id)";
    $stmCategoriasProductos = $conn->prepare($insertCategoriasProductos);
    $stmCategoriasProductos->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCategoria = $conn->lastInsertId();

    #Inserción de la marca del producto
    $insertMarcasProductos = "INSERT INTO marcas_productos (MarcaProducto, empresa_id, estatus)
                                VALUES ('Predeterminada', :empresa_id, 1)";
    $stmMarcasProductos = $conn->prepare($insertMarcasProductos);
    $stmMarcasProductos->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idMarca = $conn->lastInsertId();

    #Inserción del producto
    $insertProducto = "INSERT INTO productos (id_api, Nombre, ClaveInterna, CodigoBarras, Descripcion, Imagen, FKCategoriaProducto, FKTipoProducto,                 FKMarcaProducto, usuario_creacion_id, usuario_edicion_id, empresa_id, created_at, updated_at, estatus, serie, lote, fecha_caducidad, Predeterminado)
                        VALUES (NULL, 'Predeterminado', 'P-000001', '5901234123457', 'Producto predeterminado', 'agregar.svg', :categoria_id, 4, :marca_id, :usuario_id,:usuario_id2, :empresa_id, (SELECT NOW()), (SELECT NOW()), 1, 0, 0, 0, 1)";
    $stmProducto = $conn->prepare($insertProducto);
    $stmProducto->execute(array(':categoria_id' => $idCategoria, ':marca_id' => $idMarca, ':usuario_id' => $usuarioRes['id'], ':usuario_id2' => $usuarioRes['id'], ':empresa_id' => $usuarioRes['empresa_id']));
    $idProducto = $conn->lastInsertId();

    #Inserción de las operaciones del producto
    $insertOperacionesProducto = "INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto)
                                    VALUES (1, 1, 1, 1, :producto_id)";
    $stmOperacionesProducto = $conn->prepare($insertOperacionesProducto);
    $stmOperacionesProducto->execute(array(':producto_id' => $idProducto));

    #Inserción de la información fiscal del producto ////PENDIENTE
    // $insertOperacionesProducto = "INSERT INTO info_fiscal_productos (FKProducto, FKClaveSAT, FKClaveSATUnidad)
    //                                 VALUES (1, 1, 1, :producto_id)";
    // $stmOperacionesProducto = $conn->prepare($insertOperacionesProducto);
    // $stmOperacionesProducto->execute(array(':producto_id' => $idProducto));

    #Inserción del cliente
    $insertCliente = "INSERT INTO clientes (id_api, NombreComercial, Telefono, Email, Fecha_Ultimo_Contacto, Fecha_Siguiente_Contacto, Monto_credito, Dias_credito,razon_social, rfc, Calle, Numero_exterior, Numero_Interior, Municipio, Colonia, `UID`, codigo_postal, estatus_prospecto_id, pais_id, estado_id, empresa_id,medio_contacto_id, usuario_creacion_id, usuario_edicion_id, created_at, updated_at, estatus, empleado_id, regimen_fiscal_id, Zona, Predeterminado)
                        VALUES (NULL, 'PUBLICO EN GENERAL', '3331427189', 'AAAAAAAA@HOTMAIL.COM', NULL, NULL, 0.00, 0, 'PÚBLICO EN GENERAL', 'XAXX010101000', '', '', 'S/N', '', '', '', '45087', 4, 146, 14, :empresa_id, 2, :usuario_id, :usuario_id2, (SELECT NOW()), (SELECT NOW()), 1, :empleado_id, 11, 'Occidente', 1)";
    $stmCliente = $conn->prepare($insertCliente);
    $stmCliente->execute(array(':empresa_id' => $usuarioRes['empresa_id'], ':usuario_id' => $usuarioRes['id'], ':usuario_id2' => $usuarioRes['id'], ':empleado_id' => $idEmpleado));

    #Inserción del proveedor
    $insertProveedor = "INSERT INTO proveedores (NombreComercial, Email, Monto_credito, Dias_credito, tipo, empresa_id, estatus, usuario_creacion_id, usuario_edicion_id,created_at, updated_at, tipo_persona)
                        VALUES ('Predeterminado', 'contacto@predeterminado.com.mx', 0.00, 0, 1, :empresa_id, 1, :usuario_id, :usuario_id2, (SELECT NOW()), (SELECT NOW()), 'Fisica')";
    $stmProveedor = $conn->prepare($insertProveedor);
    $stmProveedor->execute(array(':empresa_id' => $usuarioRes['empresa_id'], ':usuario_id' => $usuarioRes['id'], ':usuario_id2' => $usuarioRes['id']));

    #Inserción de la cuenta otras en cuentas_bancarias_empresa
    $insertCuentaEmpresaOtras = "INSERT INTO cuentas_bancarias_empresa (tipo_cuenta, Nombre, estatus, empresa_id, saldo_actual)
                                    VALUES (3, 'Otras', 1, :empresa_id, 0.00)";
    $stmCuentaEmpresaOtras = $conn->prepare($insertCuentaEmpresaOtras);
    $stmCuentaEmpresaOtras->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCuentaOtras = $conn->lastInsertId();

    #Inserción de la cuenta otras en cuentas_otras
    $insertCuentaOtras = "INSERT INTO cuentas_otras (Cuenta, Descripcion, Saldo_Inicial, FKMoneda, FKCuenta, saldo_actual)
                            VALUES ('12546325521526', 'Cuenta otras', 0.00, 100, :cuenta_id, 0.00)";
    $stmCuentaOtras = $conn->prepare($insertCuentaOtras);
    $stmCuentaOtras->execute(array(':cuenta_id' => $idCuentaOtras));

    #Inserción de la cuenta cheques en cuentas_bancarias_empresa
    $insertCuentaEmpresaCheques = "INSERT INTO cuentas_bancarias_empresa (tipo_cuenta, Nombre, estatus, empresa_id, saldo_actual)
                                    VALUES (1, 'Cuenta de cheques', 1, :empresa_id, 0.00)";
    $stmCuentaEmpresaCheques = $conn->prepare($insertCuentaEmpresaCheques);
    $stmCuentaEmpresaCheques->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCuentaChueques = $conn->lastInsertId();

    #Inserción de la cuenta cheques en cuentas_cheques
    $insertCuentaCheques = "INSERT INTO cuentas_cheques (Numero_Cuenta, CLABE, Saldo_Inicial, FKBanco, FKMoneda, FKCuenta, saldo_actual)
                            VALUES ('14074672545132', '135965532458745220', 0.00, 1, 100, :cuenta_id, 0.00)";
    $stmCuentaCheques = $conn->prepare($insertCuentaCheques);
    $stmCuentaCheques->execute(array(':cuenta_id' => $idCuentaChueques));

    #Inserción de la cuenta credito en cuentas_bancarias_empresa
    $insertCuentaEmpresaCredito = "INSERT INTO cuentas_bancarias_empresa (tipo_cuenta, Nombre, estatus, empresa_id, saldo_actual)
                                    VALUES (2, 'Cuenta de crédito', 1, :empresa_id, 0.00)";
    $stmCuentaEmpresaCredito = $conn->prepare($insertCuentaEmpresaCredito);
    $stmCuentaEmpresaCredito->execute(array(':empresa_id' => $usuarioRes['empresa_id']));
    $idCuentaCredito = $conn->lastInsertId();

    #Inserción de la cuenta credito en cuentas_credito
    $insertCuentaCredito = "INSERT INTO cuentas_credito (Numero_Credito, Referencia, Limite_Credito, Credito_Utilizado, FKBanco, FKMoneda, FKCuenta)
                            VALUES ('89546725521327', 'Cuenta crédito', 10000.00, 0.00, 1, 100, :cuenta_id)";
    $stmCuentaCredito = $conn->prepare($insertCuentaCredito);
    $stmCuentaCredito->execute(array(':cuenta_id' => $idCuentaCredito));
    $conn->commit();
}catch(PDOException $error){
    return "Error en Consulta: " . $e->getMessage();
    $conn->rollback();
}

?>