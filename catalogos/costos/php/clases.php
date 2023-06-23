<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$user = $_SESSION["Usuario"];

class conectar
{ //Llamado al archivo de la conexión.

    public function getDb()
    {
        include "../../../include/db-conn.php";
        return $conn;
    }
}

class get_data
{
    //JAVIER RAMIREZ
    /////////////////////////TABLAS//////////////////////////////
    
    public function get_listaCostos($isPermissionsEdit,$isPermissionsDelete)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('select id, costoidempresa,  claveInterna, nombre,descripcion, estatus, costos_componentes, costos_adicionales, costos_gastosFijos, utilidad, total_costo, imagen   from (
            select c.id, c.costoidempresa, c.costos_componentes, c.costos_gastosFijos, c.costos_adicionales, c.utilidad, c.total_costo,
                   p.ClaveInterna as claveInterna,
                   p.Nombre as nombre,
                   p.Descripcion as descripcion,
                   if (p.estatus is null or p.estatus = "0", "Inactivo", "Activo") as estatus,
                   p.Imagen as imagen
            from costos c
                inner join productos p on p.PKProducto = c.producto_id
                inner join categorias_productos catp on p.FKCategoriaProducto = catp.PKCategoriaProducto
                inner join tipos_productos tipp on p.FKTipoProducto  = tipp.PKTipoProducto
            where p.empresa_id= ?
        ) as tableLitado order by id desc');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
//print_r($array);
        $rutaServer = $_ENV['RUTA_ARCHIVOS_READ'].$PKEmpresa.'/img'.'/';

        foreach ($array as $r) {
            $id = $r['id'];
            $costoidempresa = $r['costoidempresa'];
            $claveInterna = $r['claveInterna'];
            $nombre = $r['nombre'];
            $descripcion = $r['descripcion'];
            $costo_componentes = $r['costos_componentes'];
            $costo_adicionales = $r['costos_adicionales'];
            $costos_gastosFijos = $r['costos_gastosFijos'];
            $utilidad = $r['utilidad'];
            $costo_total = $r['total_costo'];
            $estatus = $r['estatus'];

            if ($r['imagen'] == 'agregar.svg') {
                $imagen = '<img src=\"../../../../imgProd/' . $r['imagen'] . '\" width=\"25px\">';
            } else {
                $imagen = '<img src=\"' . $rutaServer . $r['imagen'] . '\" width=\"50px\">';
            }

            $etiquetaI = '<span class=\"textTable\">';
            $etiquetaF = '</span>'; 

            if ($isPermissionsEdit == '1'){
                $acciones = '<i class=\"fas fa-edit pointer\"  data-toggle=\"modal\" data-target=\"#editarCostosModal\"  onclick=\"editarCosto(\''.$id.'\');\"></i>';

                $clave_interna = '<a href=\"#\" data-toggle=\"modal\" data-target=\"#editarCostosModal\"  onclick=\"editarCosto(\''.$id.'\');\">' . $etiquetaI . $claveInterna . $etiquetaF . '</a>';
            } else{
                $clave_interna = $etiquetaI . $claveInterna . $etiquetaF;
            }

            if ($isPermissionsDelete == '1'){
                $acciones = $acciones . '<i class=\"fas fa-trash-alt pointer\" data-toggle=\"modal\" data-target=\"#eliminar_Costo\" onclick=\"obtenerIDCosto(\''.$id.'\');\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $costoidempresa . $etiquetaF . '",
                "ClaveInterna":"' . $clave_interna . '",
                "Nombre":"' . $etiquetaI . $nombre . $etiquetaF . '",
                "Costo componentes":"' . $etiquetaI . number_format($costo_componentes,2) . $etiquetaF . '",
                "Costo adicionales":"' . $etiquetaI . number_format($costo_adicionales,2) . $etiquetaF . '",
                "Gastos fijos":"' . $etiquetaI . number_format($costos_gastosFijos,2) . $etiquetaF . '",
                "Utilidad":"' . $etiquetaI . number_format($utilidad,2) . $etiquetaF . '",
                "Costo total":"' . $etiquetaI . number_format($costo_total,2) . $etiquetaF . '",
                "Imagen":"' . $etiquetaI . $imagen . $etiquetaF . '",
                "estatus":"'.$estatus.'",
                "Acciones":""},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    public function getUnidadesSATTable($buscador, $modo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $stmt = $db->prepare('SELECT csu.PKClaveSATUnidad as id,
           csu.Clave as clave,
           csu.Descripcion as descripcion
            FROM claves_sat_unidades csu 
                 where concat(csu.PKClaveSATUnidad,csu.Clave,csu.Descripcion) regexp ?
            order by csu.orden desc limit 100');
        $stmt->execute(array($buscador));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $id = $r['id'];
            $clave = $r['clave'];
            $descripcion = $r['descripcion'];

            $etiquetaF = '</span>';

            //echo "modo: ".$modo;
            if($modo == 1){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 2){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarEditar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarEditar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 3){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarAdicionales(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarAdicionales(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 4){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarAdicionalesEdit(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarAgregarAdicionalesEdit(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 5){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 6){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 7){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionar(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 8){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarGastoF(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarGastoF(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 9){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarGastoFEdit(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdUnidadSeleccionarGastoFEdit(' . $id . ',\'' . $clave . '\',\'' . $descripcion . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Clave":"' . $etiquetaI . $clave . $etiquetaF . '",
                "Descripcion":"' . $etiquetaI . $descripcion . $etiquetaF . $acciones . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';

    }

    /////////////////////////COMBOS//////////////////////////////

    public function getProductos()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT pkProducto, nombre , clave, contador from(
            select @idProducto := p.PKProducto as pkProducto,
               p.Nombre as nombre,
               p.ClaveInterna as clave,
               (select ifnull(count(c.id),0) from productos p
                    left join costos c on p.PKProducto = c.producto_id
                    where p.PKProducto = @idProducto) as contador
            from productos p
            where p.empresa_id = ? 
                #and p.FKTipoProducto = 1 
                and p.FKTipoProducto != 5 
                and p.FKTipoProducto != 3
                and p.FKTipoProducto != 10
        ) as productos 
        where contador = 0');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();
        $option = "<option value='0' disabled selected>Selecciona un producto</option>";

        foreach($array as $a){

            $option.= "<option value='".$a['pkProducto']."'>".$a['clave']." - ".$a['nombre']."</option>";

        }
            $option.= "<option value='add' style='background-color: #15589B !important;  color:white; text-align:center; width:100%;'>Añadir producto</option>";

        return $option;
    }

    public function getCostos($idCosto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $json = new \stdClass();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('SELECT c.producto_id, c.total_costo, c.moneda_id, p.Nombre, p.ClaveInterna, c.costos_componentes, c.costos_adicionales, c.costos_gastosFijos, c.utilidad, c.utilidad_porcentaje, c.total_costo FROM costos c INNER JOIN productos p ON p.PKProducto = c.producto_id WHERE c.id = ? ');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idCosto));
        $costos = $stmt->fetch();

        $stmt = $db->prepare('SELECT * FROM monedas WHERE estatus = 1');
        $stmt->execute();
        $monedas = $stmt->fetchAll();

        $info_moneda = "";
        foreach($monedas as $m){
            $info_moneda.="<option value='".$m['PKMoneda']."' ";

            if($m['PKMoneda'] == $costos['moneda_id']){
              $info_moneda.=" selected";
            }

            $info_moneda.=">".$m['Clave']."</option>";
        }
//c.producto_id, c.total_costo, c.moneda_id, p.Nombre, p.ClaveInterna

        $query = sprintf('SELECT cd.costo_producto_id, cd.cantidad, cd.costo, cd.proveedor_id, pr.NombreComercial, p.Nombre, p.ClaveInterna, csu.Descripcion FROM costos_detalle cd 
                                INNER JOIN productos p ON p.PKProducto = cd.costo_producto_id
                                LEFT JOIN proveedores pr ON cd.proveedor_id = pr.PKProveedor
                                LEFT JOIN info_fiscal_productos ifp ON ifp.FKProducto = p.PKProducto
                                LEFT JOIN claves_sat_unidades csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
                                WHERE cd.costos_id = ? ');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idCosto));
        $detalle_costos = $stmt->fetchAll();

        //print_r($detalle_costos);
        $datos_tabla = "";
        $cont = 1;
        foreach($detalle_costos as $dc){

            if(trim($dc['NombreComercial']) == ''){
                $nombreProveedor = 'S/P - Sin Proveedor';
            }
            else{
                $nombreProveedor = $dc['NombreComercial'];
            }

            if(trim($dc['Descripcion']) == ''){
                $claveSATUnidad = 'Sin Clave';
            }
            else{
                $claveSATUnidad = trim($dc['Descripcion']);
            }

            $total = number_format($dc['cantidad'] * $dc['costo'],2);
            $datos_tabla .= 
                        '<tr>
                            <td>
                              <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" data-click="cmbProductosEdit'.$cont.'" name="addProd" width="20px" height="20px" style=" position: relative;">
                            </td>
                            <td>
                              <input class="contabilizarProductosEdit" name="txtProductosEdit'.$cont.'" id="txtProductosEdit'.$cont.'" type="hidden" value="'.$dc['costo_producto_id'].'" readonly>
                              <input type="text" class="form-control" name="cmbProductosEdit'.$cont.'" id="cmbProductosEdit'.$cont.'" data-toggle="modal" data-target="#editar_Producto" 
                              placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProdEdit('.$cont.')" value="'.$dc['ClaveInterna']." - ".$dc['Nombre'].'">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione por lo menos un producto" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-6">
                                  <input class="form-control cantidadProductoEdit" type="text" name="txtCantidadCompuestaEdit_'.$cont.'" id="txtCantidadCompuestaEdit_'.$cont.'" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10" value="'.$dc['cantidad'].'">
                                </div>
                                <div class="col-lg-6">
                                  <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatEdit('.$cont.',2)"><span id="lblUnidadMedidaEdit'.$cont.'">'.$claveSATUnidad.'</span></label>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCostoEdit'.$cont.'" hidden> </span>
                                      <div class="row">
                                        <div class="col-lg-8"><input class="form-control precioProductoEdit" type="text" id="txtCostoEdit_'.$cont.'"  name="txtCostoEdit_'.$cont.'" value="'.$dc['costo'].'" required></label></div> 
                                        <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostos('.$cont.',2);" src="../../../../img/timdesk/ver.svg"></i></div> 
                                      </div>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>
                            <td>
                              <input  name="txtProveedoresEdit_'.$cont.'" id="txtProveedoresEdit_'.$cont.'" type="hidden" value="'.$dc['proveedor_id'].'" readonly>
                              <input type="text" class="form-control" name="cmbProveedoresEdit'.$cont.'" id="cmbProveedoresEdit'.$cont.'" data-toggle="modal" data-target="#editar_Proveedores" 
                              placeholder="Seleccione un proveedor..." readonly required="" onclick="clickSeleccionarProvEdit('.$cont.')" value="'.$nombreProveedor.'">
                              <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                              src="../../../../img/timdesk/alerta.svg" width=30px
                              title="Seleccione un proveedor" readonly>
                            </td>
                            <td>
                              <div class="row">
                                <div class="col-lg-12 input-group">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <label  for="usr"><span id="lblCostoEdit'.$cont.'" hidden> </span><input class="form-control getTotalesEdit" type="text" id="txtTotalCostoEdit'.$cont.'"  required value="'.$total.'" readonly></label>
                                    </div>  
                                  </div>  
                                </div>
                              </div>
                            </td>';

                        $datos_tabla .= '
                            <td>
                                <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminarEdit_'.$cont.'" onclick="eliminarCompTempEdit(this);"></i>
                            </td>';

                    $datos_tabla .= '</tr>';

                    $cont++;

        }


        $query = sprintf('SELECT cda.costo_adicionales_producto_id, cda.cantidad_adicionales, cda.costo_adicionales, p.Nombre, p.ClaveInterna, csu.Descripcion FROM costos_detalle_adicionales cda 
                                INNER JOIN productos p ON p.PKProducto = cda.costo_adicionales_producto_id
                                LEFT JOIN info_fiscal_productos ifp ON ifp.FKProducto = p.PKProducto
                                LEFT JOIN claves_sat_unidades csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
                                WHERE cda.costos_id = ? ');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idCosto));
        $detalle_costos_adicionales = $stmt->fetchAll();

        //print_r($detalle_costos);
        $datos_adicionales = "";
        $cont_adicionales = 1;
        foreach($detalle_costos_adicionales as $dca){

            $total_adicionales = number_format($dca['cantidad_adicionales'] * $dca['costo_adicionales'], 2);

            if(trim($dca['Descripcion']) == ''){
                $claveSATUnidad = 'Sin Clave';
            }
            else{
                $claveSATUnidad = trim($dca['Descripcion']);
            }

            $datos_adicionales.='
                              <tr>
                                <td>
                                  <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar producto" id="addProd" name="addProdAdicionalEdit" data-click="cmbProductosAdicionalesEdit'.$cont_adicionales.'" width="20px" height="20px" style=" position: relative;">
                                </td>
                                <td>
                                  <input class="contabilizarProductosAdicionalesEdit" name="AtxtProductosAdicionalesEdit'.$cont_adicionales.'" id="AtxtProductosAdicionalesEdit'.$cont_adicionales.'" type="hidden" value="'.$dca['costo_adicionales_producto_id'].'" readonly>
                                  <input type="text" class="form-control" name="cmbProductosAdicionalesEdit'.$cont_adicionales.'" id="cmbProductosAdicionalesEdit'.$cont_adicionales.'" value="'.$dca['ClaveInterna'].' - '.$dca['Nombre'].'" data-toggle="modal" data-target="#editar_Producto_Adicionales" 
                                  placeholder="Seleccione un producto..." readonly required="" onclick="clickSeleccionarProdAdicionalesEdit('.$cont_adicionales.')">
                                  <img  id="notaFProdComp" name="notaFProdComp" style="display: none;"
                                  src="../../../../img/timdesk/alerta.svg" width=30px
                                  title="Seleccione por lo menos un producto" readonly>
                                </td>
                                <td>
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <input class="form-control cantidadProductoAdicionalesEdit" type="text" name="AtxtCantidadCompuestaAdicionalesEdit_'.$cont_adicionales.'" id="AtxtCantidadCompuestaAdicionalesEdit_'.$cont_adicionales.'" value="'.$dca['cantidad_adicionales'].'" min="0" maxlength="12" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus="" required="" placeholder="Ej. 10">
                                    </div>
                                    <div class="col-lg-6">
                                      <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatAdicionalesEdit('.$cont_adicionales.',4)"><span id="lblUnidadMedidaAdicionalEdit'.$cont_adicionales.'">'.$claveSATUnidad.'</span></label>
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <div class="row">
                                    <div class="col-lg-12 input-group">
                                      <div class="row">
                                        <div class="col-lg-12">
                                          <label  for="usr"><span id="lblCosto1" hidden> </span>
                                            <div class="row">
                                                <div class="col-lg-8"><input class="form-control precioProductoAdicionalesEdit" type="text" id="AtxtCostoAdicionalesEdit_'.$cont_adicionales.'"  name="AtxtCostoAdicionalesEdit_'.$cont_adicionales.'" value="'.$dca['costo_adicionales'].'" maxlength="12" required></div>
                                                <div class="col-lg-4"><i><img class="btnEdit" onclick="cargarCostosAdicionales('.$cont_adicionales.',4);" src="../../../../img/timdesk/ver.svg"></i></div>
                                            </div>
                                          </label>
                                        </div>  
                                      </div>  
                                    </div>
                                  </div>
                                </td>
                                <td>
                                  <div class="row">
                                    <div class="col-lg-12 input-group">
                                      <div class="row">
                                        <div class="col-lg-12">
                                          <label  for="usr"><span id="lblCosto1" hidden> </span><input class="form-control getTotalAdicionalesEdit" type="text" id="txtTotalCostoAdicionalesEdit'.$cont_adicionales.'" value="'.$total_adicionales.'" required readonly></label>
                                        </div>  
                                      </div>  
                                    </div>
                                  </div>
                                </td>';

                                $datos_adicionales.=  '<td>
                                                        <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminarAdicionalesEdit_'.$cont_adicionales.'" onclick="eliminarCompTempAdicionalesEdit(this);"></i>
                                                      </td>';

                        $datos_adicionales.= '</tr>';
                $cont_adicionales++;
        }

        $query = sprintf('SELECT cdgf.costo_gasto_fijo_producto_id, cdgf.costo_gasto_fijo, cdgf.porcentaje_gasto_fijo, p.Nombre, p.ClaveInterna, csu.Descripcion FROM costos_detalle_gastosFijos cdgf 
                                INNER JOIN productos p ON p.PKProducto = cdgf.costo_gasto_fijo_producto_id
                                LEFT JOIN info_fiscal_productos ifp ON ifp.FKProducto = p.PKProducto
                                LEFT JOIN claves_sat_unidades csu ON csu.PKClaveSATUnidad = ifp.FKClaveSATUnidad
                                WHERE cdgf.costos_id = ? ');
        $stmt = $db->prepare($query);
        $stmt->execute(array($idCosto));
        $detalle_costos_gastos_fijos = $stmt->fetchAll();

        //print_r($detalle_costos);
        $datos_GF = "";
        $cont_GF = 1;
        foreach($detalle_costos_gastos_fijos as $dcgf){

            $total_GF = number_format(($dcgf['costo_gasto_fijo'] * $dcgf['porcentaje_gasto_fijo'])/100, 2);

            if(trim($dcgf['Descripcion']) == ''){
                $claveSATUnidad = 'Sin Clave';
            }
            else{
                $claveSATUnidad = trim($dcgf['Descripcion']);
            }

            $datos_GF.='
                              <tr>
                                <td>
                                  <img class="z-depth-1-half" src="../../../../img/timdesk/ICONO AGREGAR3.svg" alt="Seleccionar un gasto fijo" id="addProd" name="addProdGastoFijoEdit" data-click="cmbGastoFEdit'.$cont_GF.'" width="20px" height="20px" style=" position: relative;">
                                </td>
                                <td>
                                  <input class="contabilizarGastosFEdit" name="txtGastoFEdit'.$cont_GF.'" id="txtGastoFEdit'.$cont_GF.'" type="hidden" value="'.$dcgf['costo_gasto_fijo_producto_id'].'" readonly>
                                  <input type="text" class="form-control" name="cmbGastoFEdit'.$cont_GF.'" id="cmbGastoFEdit'.$cont_GF.'" value="'.$dcgf['ClaveInterna'].' - '.$dcgf['Nombre'].'" data-toggle="modal" data-target="#agregar_GastoFEdit" 
                                  placeholder="Seleccione un producto..." readonly onclick="clickSeleccionarProdGastoFEdit('.$cont_GF.')">
                                </td>
                                <td>
                                    <label  for="usr" data-toggle="modal" data-target="#agregar_UnidadSAT" onclick="cargarUnidadesSatGastoFEdit('.$cont_GF.', 9)"><span id="lblUnidadMedidaGastoFEdit'.$cont_GF.'">'.$claveSATUnidad.'</span></label>
                                </td>
                                <td>
                                    <div class="col-lg-12"><input class="form-control precioGastoFEdit" type="text" id="BtxtCostoGastoFEdit_'.$cont_GF.'"  name="BtxtCostoGastoFEdit_'.$cont_GF.'" maxlength="12" value="'.$dcgf['costo_gasto_fijo'].'"></div>                                    
                                </td>
                                <td>
                                    <input type="text" class="form-control utilidadPorcentajeClass porcentajeGastoFEdit" id="AtxtGastoFPorcentajeEdit_'.$cont_GF.'" name="AtxtGastoFPorcentajeEdit_'.$cont_GF.'" maxlength="12" value="'.number_format($dcgf['porcentaje_gasto_fijo'],2).'" style="width:200px;">  
                                </td>
                                <td>
                                    <label  for="usr"><span id="lblCostoGastoF'.$cont_GF.'" hidden> </span><input class="form-control getTotalesGFEdit" type="text" id="txtTotalCostoGastoFEdit'.$cont_GF.'" readonly value="'.$total_GF.'"></label>      
                                </td>';

                                $datos_GF.=  '<td>
                                                        <i><img class="btnEdit" src="../../../../img/timdesk/delete.svg" id="btnEliminarGFEdit_'.$cont_GF.'" onclick="eliminarGastoFEdit(this);"></i>
                                                      </td>';

                        $datos_GF.= '</tr>';
                $cont_GF++;
        }

        $json->idCosto = $idCosto;
        $json->idProducto = $costos['producto_id'];
        $json->contadorProducto = $cont;
        $json->contadorAdicionales = $cont_adicionales;
        $json->producto = $costos['ClaveInterna'] . " - " . $costos['Nombre'];
        $json->moneda = $info_moneda;
        $json->datos_tabla = $datos_tabla;
        $json->datos_adicionales = $datos_adicionales;
        $json->datos_GF = $datos_GF;
        $json->total_costo = number_format($costos['total_costo'],2);
        $json->total_costo_componentes = number_format($costos['costos_componentes'],2);
        $json->total_costo_adicionales = number_format($costos['costos_adicionales'],2);
        $json->total_costo_gasto_fijo = number_format($costos['costos_gastosFijos'],2);
        $json->utilidad = $costos['utilidad'];
        $json->utilidad_porcentaje = number_format($costos['utilidad_porcentaje'],2);
        $json = json_encode($json);

        return $json;
    }

    
    public function getCmbProductos($PKProducto, $modo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        if($modo == 3 || $modo == 4){
            $stmt = $db->prepare("SELECT @id := p.PKProducto as id, 
                   p.ClaveInterna as claveInterna,
                   p.Nombre as nombre,
                   p.estatus as fkEstatusGeneral,
                   csu.Descripcion as unidadMedida,
                   cvp.CostoCompra as costoFabri,
                   ifnull(tm.TipoMoneda,'MXN') as tipoMoneda,
                   ifnull(cvp.FKTipoMoneda,100) as fkTipoMoneda
            from productos p
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
                left join tipo_moneda tm on cvp.FKTipoMoneda = tm.PKTipoMoneda
                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto 
                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad 
                inner join operaciones_producto op on p.PKProducto = op.FKProducto
            where p.estatus = '1' 
            and p.empresa_id = ? 
            #and op.Fabricacion = '1'
            and p.PKProducto != ?
            and p.FKTipoProducto = 7");
            
        }else if($modo == 5 || $modo == 6){
            $stmt = $db->prepare("SELECT @id := p.PKProducto as id, 
                    p.ClaveInterna as claveInterna,
                    p.Nombre as nombre,
                    p.estatus as fkEstatusGeneral,
                    csu.Descripcion as unidadMedida,
                    ifnull(cvp.costoGastoFijo, 0.00) as costoFabri,
                    ifnull(tm.TipoMoneda,'MXN') as tipoMoneda,
                    ifnull(cvp.FKTipoMoneda,100) as fkTipoMoneda
            from productos p
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
                left join tipo_moneda tm on cvp.FKTipoMoneda = tm.PKTipoMoneda
                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto 
                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad 
                inner join operaciones_producto op on p.PKProducto = op.FKProducto
            where p.estatus = '1' 
            and p.empresa_id = ? 
            and p.PKProducto != ?
            and p.FKTipoProducto = 10");
        }
        else{
            $stmt = $db->prepare("SELECT @id := p.PKProducto as id, 
                   p.ClaveInterna as claveInterna,
                   p.Nombre as nombre,
                   p.estatus as fkEstatusGeneral,
                   csu.Descripcion as unidadMedida,
                   CASE
						WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN cvp.CostoFabricacion
						ELSE cvp.CostoCompra
					END as costoFabri,
                   ifnull(tm.TipoMoneda,'MXN') as tipoMoneda,
                   ifnull(cvp.FKTipoMoneda,100) as fkTipoMoneda
            from productos p
                inner join costo_venta_producto cvp on p.PKProducto = cvp.FKProducto
                left join tipo_moneda tm on cvp.FKTipoMoneda = tm.PKTipoMoneda
                left join info_fiscal_productos ifp on p.PKProducto = ifp.FKProducto 
                left join claves_sat_unidades csu on ifp.FKClaveSATUnidad = csu.PKClaveSATUnidad 
                inner join operaciones_producto op on p.PKProducto = op.FKProducto
            where p.estatus = '1' 
            and p.empresa_id = ? 
            #and op.Fabricacion = '1'
            and p.PKProducto != ?
            and p.FKTipoProducto != 7");
            //$stmt = $db->prepare('call spc_Combo_Productos(?,?)');
        }
        $stmt->execute(array($PKEmpresa, $PKProducto));
        $array = $stmt->fetchAll();



        foreach ($array as $r) {
            $id = $r['id'];
            $claveInterna = $r['claveInterna'];
            $nombre = $r['nombre'];
            $fkEstatusGeneral = $r['fkEstatusGeneral'];
            $unidadMedida = $r['unidadMedida'];
            $costoFabri = $r['costoFabri'];
            $moneda = $r['tipoMoneda'];
            $fkMoneda = $r['fkTipoMoneda'];

            //$nombre = trim(addslashes($nombre));
            $nombre = str_replace('"','',$nombre);
            $nombre = str_replace("'",'',$nombre);

            $etiquetaF = '</span>';

            if($modo == 1){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 2){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 3){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarAdicionales(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarAdicionales(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 4){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarAdicionalesEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProductoSeleccionarAdicionalesEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 5){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdGastoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdGastoSeleccionar(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 6){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdGastoSeleccionarEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdGastoSeleccionarEdit(\'' . $id . '\',\'' . $claveInterna . '\',\'' . $nombre . '\',\'' . $unidadMedida . '\',\'' . $costoFabri . '\',\'' . $moneda . '\',\'' . $fkMoneda . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }

            

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "ClaveInterna":"' . $etiquetaI . $claveInterna . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombre. $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . $fkEstatusGeneral . $etiquetaF . '"},';
                
                //"Nombre":"' . $etiquetaI . $nombre . $etiquetaF . $acciones . '",
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }


    public function getCostosHistoricos($PKProducto, $modo, $proveedor)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $proveedor = $proveedor == 0 ? '' : $proveedor;

        $stmt = $db->prepare("SELECT p.ClaveInterna, p.Nombre, pr.NombreComercial, ch.costo, ch.proveedor_id, DATE_FORMAT(ch.fecha_alta, '%d/%m/%Y %H:%i:%s') as fecha FROM costos_historico ch INNER JOIN productos p ON ch.producto_id = p.PKProducto LEFT JOIN proveedores pr ON pr.PKProveedor = ch.proveedor_id
                                WHERE ch.producto_id = ? and ch.proveedor_id REGEXP ? ORDER BY ch.fecha_alta ASC");
        $stmt->execute(array($PKProducto, $proveedor));
        $array = $stmt->fetchAll();

        foreach ($array as $r) {
            $nombre_Producto = $r['ClaveInterna'].' - '.$r['Nombre'];
            $costo = number_format($r['costo'],2);
            $PKProveedor = $r['proveedor_id'];

            if(trim($r['NombreComercial']) == ''){
                $proveedor = 'Sin Proveedor';
            }
            else{
                $proveedor = $r['NombreComercial'];
            }
            $fecha = $r['fecha'];

            $etiquetaF = '</span>';

            if($modo == 1){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"fijarCosto(\'' . $costo . '\', '.$PKProveedor.', \''.$proveedor.'\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"fijarCosto(\'' . $costo . '\', '.$PKProveedor.', \''.$proveedor.'\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 2){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"fijarCostoEdit(\'' . $costo . '\', '.$PKProveedor.', \''.$proveedor.'\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"fijarCostoEdit(\'' . $costo . '\', '.$PKProveedor.', \''.$proveedor.'\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 3){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"fijarCostoAdicionales(\'' . $costo . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"fijarCostoAdicionales(\'' . $costo . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            if($modo == 4){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"fijarCostoAdicionalesEdit(\'' . $costo . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"fijarCostoAdicionalesEdit(\'' . $costo . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }

            $table .= '{"Producto":"' . $etiquetaI . $nombre_Producto . $etiquetaF . '",
                "Proveedor":"' . $etiquetaI . $proveedor . $etiquetaF . '",
                "Fecha":"' . $etiquetaI . $fecha . $etiquetaF . '",
                "Costo":"' . $etiquetaI . $costo . $etiquetaF . $acciones .'"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCmbProveedores($modo)
    {
        $con = new conectar();
        $db = $con->getDb();
        $table = "";

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $stmt = $db->prepare('SELECT pv.PKProveedor, pv.NombreComercial, pv.estatus, dfp.Razon_Social
                                from proveedores pv 
                                LEFT JOIN domicilio_fiscal_proveedor dfp ON dfp.FKProveedor = pv.PKProveedor
                                where pv.estatus = "1" and pv.empresa_id = ? and pv.tipo = 1');
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll();


        if($modo == 1){
            $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionar(\'' . '0' . '\',\'' . 'S/P - Sin Proveedor' . '\');\">';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionar(\'' . '0' . '\',\'' . 'S/P - Sin Proveedor' . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
        }
        else{
            $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionarEdit(\'' . '0' . '\',\'' . 'S/P - Sin Proveedor' . '\');\">';
            $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionarEdit(\'' . '0' . '\',\'' . 'S/P - Sin Proveedor' . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
        }
        $etiquetaF = '</span>';
        $table .= '{"Id":"' . $etiquetaI . '0' . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . 'S/P' . $etiquetaF . '",
                "Razon":"' . $etiquetaI . 'Sin Proveedor' . $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . '1' . $etiquetaF . '"},';

        foreach ($array as $r) {
            $id = $r['PKProveedor'];
            $nombreComercial = $r['NombreComercial'];
            $RazonSocial = $r['Razon_Social'];
            $estatus = $r['estatus'];

            $etiquetaF = '</span>';
            if($modo == 1){
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionar(\'' . $id . '\',\'' . $nombreComercial . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionar(\'' . $id . '\',\'' . $nombreComercial . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }
            else{
                $etiquetaI = '<span class=\"textTable\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionarEdit(\'' . $id . '\',\'' . $nombreComercial . '\');\">';
                $acciones = '<i><img class=\"btnEdit\" data-dismiss=\"modal\" onclick=\"obtenerIdProveedorSeleccionarEdit(\'' . $id . '\',\'' . $nombreComercial . '\');\" src=\"../../../../img/timdesk/ICONO ESTADO-TERMINADO_Mesa de trabajo 1.svg\"></i>';
            }

            $table .= '{"Id":"' . $etiquetaI . $id . $etiquetaF . '",
                "Nombre":"' . $etiquetaI . $nombreComercial . $etiquetaF . '",
                "Razon":"' . $etiquetaI . $RazonSocial . $etiquetaF . $acciones . '",
                "Estatus":"' . $etiquetaI . $estatus . $etiquetaF . '"},';
        }
        $table = substr($table, 0, strlen($table) - 1);

        return '{"data":[' . $table . ']}';
    }

    public function getCmbTipo($tipo)
    {
        $con = new conectar();
        $db = $con->getDb();

        if($tipo == 1){
            $query = sprintf('SELECT tp.PKTipoProducto, 
                                        tp.TipoProducto 
                                    from tipos_productos tp 
                         where tp.estatus = "1" and tp.PKTipoProducto != 7 order by tp.TipoProducto asc');
        }else if($tipo == 3){
            $query = sprintf('SELECT tp.PKTipoProducto, 
                                        tp.TipoProducto 
                                    from tipos_productos tp 
                         where tp.estatus = "1" and tp.PKTipoProducto = 10 order by tp.TipoProducto asc');
        }
        else{
            $query = sprintf('SELECT tp.PKTipoProducto, 
                                        tp.TipoProducto 
                                    from tipos_productos tp 
                         where tp.estatus = "1" and tp.PKTipoProducto = 7 order by tp.TipoProducto asc');
        }
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCmbProductoListaCompuestos()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKEmpresa = $_SESSION["IDEmpresa"];

        $query = sprintf('call spc_Combo_Productos_ListaNoCompuestos(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKEmpresa));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getCmbMoneda()
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Combo_Moneda()');
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /////////////////////////DATOS PARA EDICIÓN//////////////////////////////

    public function getDataDatosProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $query = sprintf('call spc_Datos_Prod_ConsultaGeneral(?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($value));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function getDataDatosProductoCompuesto($pkProducto, $pkUsuario)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        $query = sprintf('call spc_Datos_Prod_ConsultaGeneralCompuestos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////VALIDACIONES//////////////////////////////
    
    public function validarPermisos($pkPantalla)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_Validar_Permisos(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($PKuser, $pkPantalla));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    public function validarProductoCompuestoTemp($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        $query = sprintf('call spc_ValidarUnicoProductoCompTemp(?,?)');
        $stmt = $db->prepare($query);
        $stmt->execute(array($pkProducto, $PKuser));
        $array = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $array;
    }

    /////////////////////////INFO//////////////////////////////
    


    //END JAVIER RAMIREZ
}

class save_data
{
    public function guardarCostos($datos)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fecha_alta = date("Y-m-d H:i:s");
        $PKuser = $_SESSION["PKUsuario"];
      /*  print_r($datos);
return;*/
        $arrayProductos = array();
        $contProd = 0;

        $arrayCantidad = array();
        $contCantidad = 0;

        $arrayCosto = array();
        $contCosto = 0;

        $arrayProveedores = array();
        $contProveedores = 0;

        $arrayProductosAdicionales = array();
        $contProdAdicionales = 0;

        $arrayCantidadAdicional = array();
        $contCantidadAdicional = 0;

        $arrayCostoAdicional = array();
        $contCostoAdicional = 0;

        $arrayGastosFijos = array();
        $countGastosFijos = 0;

        $arrayCostoGastoFijo = array();
        $countCostoGastoFijo = 0;

        $arrayPorcentajeGastoFijo = array();
        $countPorcentajeGastoFijo = 0;


        $utilidad = 0.00;
        $utilidad_porcentaje = 0.00;

        foreach($datos as $d => $key){

            if($d == "cmbProductoMaterial"){
                $idProducto = $key;
            }

            if($d == "cmbMoneda"){
                $idMoneda = $key;
            }

            if($d == "AtxtUtilidades"){
                $utilidad = $key;
            }
            //echo $d." - ".$key."<br>";

            //ingresar id productos en array 
            if(substr($d, 0, 12) == 'txtProductos'){
                $arrayProductos[$contProd] =$key;
                $contProd++;
            }

            // ingresar cantidad en array
            if(substr($d, 0, 20) == 'txtCantidadCompuesta'){
                if(trim($key) != ""){
                    $arrayCantidad[$contCantidad] =$key;
                    $contCantidad++;
                }
                else{
                    $arrayCantidad[$contCantidad] = 0;
                    $contCantidad++;
                }
            }


            //ingresar costo en el array
            if(substr($d, 0, 8) == 'txtCosto'){
                if(trim($key) != ""){
                    $arrayCosto[$contCosto] =$key;
                    $contCosto++;
                }
                else{
                    $arrayCosto[$contCosto] = 0.00;
                    $contCosto++;
                }
            }

            //echo "CADENA ".substr($d, 0, 14)."<br>";
            //Ingresar proveedores en el array
            if(substr($d, 0, 14) == 'txtProveedores'){
                $arrayProveedores[$contProveedores] =$key;
                $contProveedores++;
            }
            

            if(substr($d, 0, 24) == 'AtxtProductosAdicionales'){
                $arrayProductosAdicionales[$contProdAdicionales] =$key;
                $contProdAdicionales++;
            }

            // ingresar cantidad adicional en array
            if(substr($d, 0, 32) == 'AtxtCantidadCompuestaAdicionales'){
                if(trim($key) != ""){
                    $arrayCantidadAdicional[$contCantidadAdicional] =$key;
                    $contCantidadAdicional++;
                }
                else{
                    $arrayCantidadAdicional[$contCantidadAdicional] = 0;
                    $contCantidadAdicional++;
                }
            }


            //ingresar costo en el array
            if(substr($d, 0, 20) == 'AtxtCostoAdicionales'){
                if(trim($key) != ""){
                    $arrayCostoAdicional[$contCostoAdicional] =$key;
                    $contCostoAdicional++;
                }
                else{
                    $arrayCostoAdicional[$contCostoAdicional] = 0;
                    $contCostoAdicional++;
                }

            }

            //llena array de gastos fijos
            if(substr($d, 0, 9) == 'txtGastoF'){
                $arrayGastosFijos[$countGastosFijos] =$key;
                $countGastosFijos++;
            }

            //ingresar costo del gasto fijo en el array
            if(substr($d, 0, 15) == 'BtxtCostoGastoF'){
                if(trim($key) != ""){
                    $arrayCostoGastoFijo[$countCostoGastoFijo] =$key;
                }
                else{
                    $arrayCostoGastoFijo[$countCostoGastoFijo] = 0;
                }
                $countCostoGastoFijo++;
            }
            
            //ingresar porcentaje del gasto fijo en el array
            if(substr($d, 0, 20) == 'AtxtGastoFPorcentaje'){
                if(trim($key) != ""){
                    $arrayPorcentajeGastoFijo[$countPorcentajeGastoFijo] =$key;
                }
                else{
                    $arrayPorcentajeGastoFijo[$countPorcentajeGastoFijo] = 0;
                }
                $countPorcentajeGastoFijo++;
            }

        }

        //CALCULAR TOTAL
        $totalCostoComponentes = 0.00;
        for($x = 0; $x < $contCosto; $x++){
            if(trim($arrayProductos[$x]) != ''){
                $totalCostoComponentes = $totalCostoComponentes + ($arrayCantidad[$x] * $arrayCosto[$x]);
            }
        }

        $totalCostoAdicionales = 0.00;
        for($x = 0; $x < $contCostoAdicional; $x++){
            if(trim($arrayProductosAdicionales[$x]) != ''){
                $totalCostoAdicionales = $totalCostoAdicionales + ($arrayCantidadAdicional[$x] * $arrayCostoAdicional[$x]);
            }
        }

        $totalCostoGastoF = 0.00;
        for($x = 0; $x < $countPorcentajeGastoFijo; $x++){
            if(trim($arrayGastosFijos[$x]) != ''){
                $totalCostoGastoF = $totalCostoGastoF + (($arrayCostoGastoFijo[$x] * $arrayPorcentajeGastoFijo[$x])/100);
            }
        }

        $totalCosto = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF + $utilidad;

        $totalFabricacion = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF;

        $totalCostoDirecto = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF;

        if($totalCostoComponentes == 0 && $totalCostoAdicionales == 0 && $totalCostoGastoF == 0){
            $utilidad_porcentaje = 0.00;
        }
        else{
            $utilidad_porcentaje = number_format(($utilidad / $totalCostoDirecto) * 100, 2);
        }
        

        //echo "idProducto ".$idProducto."<br>"; //correcto
        //echo "idMoneda ".$idMoneda."<br>"; //correcto
        $PKEmpresa = $_SESSION["IDEmpresa"]; //corecto
        //echo "totalCosto ".$totalCosto."<br>"; //correcto
        /*print_r($arrayProductos);
        print_r($arrayCantidad);
        print_r($arrayCosto);
        print_r($arrayProveedores);*/


        try {
            $db->beginTransaction();

            $query = sprintf('SELECT MAX(costoidempresa) as costoidempresa FROM costos WHERE empresa_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($PKEmpresa));
            $row = $stmt->fetch();
            $costoidempresa = $row['costoidempresa'] + 1;

            $query = sprintf('INSERT INTO costos (costoidempresa, producto_id, costos_componentes, costos_adicionales, costos_gastosFijos, utilidad, utilidad_porcentaje, total_costo, empresa_id, moneda_id) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($costoidempresa, $idProducto, $totalCostoComponentes, $totalCostoAdicionales, $totalCostoGastoF, $utilidad, $utilidad_porcentaje, $totalCosto, $PKEmpresa,$idMoneda));

            $idCosto = $db->lastInsertId();
            //echo "idCosto ".$idCosto;

            $cont = 0;
            foreach($arrayProductos as $aP){

                if(trim($arrayProductos[$cont]) != ''){

                    //recupera texto de unidad de medida
                    $query = sprintf('SELECT ifnull(u.descripcion, "Sin Clave") as descripcion FROM claves_sat_unidades as u
                                        inner join info_fiscal_productos as f on f.FKClaveSATUnidad = u.PKClaveSATUnidad 
                                    WHERE f.FKProducto = ?;');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));
                    $unidad = $stmt->fetchAll();

                    //si no eiste registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));
                    $numRow = $stmt->rowCount();

                    if($numRow <= 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayProductos[$cont]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayProductos[$cont], 0, 100, 0, 100, 0, 100));
                    }

                    $query = sprintf('INSERT INTO costos_detalle (costo_producto_id, cantidad, costo, proveedor_id, costos_id) VALUES (?,?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont], $arrayCantidad[$cont], $arrayCosto[$cont], $arrayProveedores[$cont], $idCosto));
                    
                    //actualiza el costo de fabricación/compra del producto
                    if($arrayProveedores[$cont] != "" && $arrayProveedores[$cont] != null && $arrayProveedores[$cont] != 0){
                        
                        $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            inner join productos as p on p.PKProducto = cvp.FKProducto
                                                set cvp.CostoCompra = if(cvp.CostoCompra = 0, ?, cvp.CostoCompra),
                                                cvp.CostoFabricacion = CASE
                                                                            WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN case WHEN cvp.CostoFabricacion = 0 then ? else cvp.CostoFabricacion end
                                                                            ELSE cvp.CostoFabricacion
                                                                        END
                                            where cvp.FKProducto = ?;');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayCosto[$cont], $arrayCosto[$cont], $arrayProductos[$cont]));

                        $query = sprintf('UPDATE operaciones_producto as op 
                                            inner join productos as p on p.PKProducto = op.FKProducto
                                                SET op.compra = 1,
                                                op.Fabricacion = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN 1
                                                                    ELSE op.Fabricacion
                                                                END
                                            WHERE op.FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont]));

                        //consulta si existe pago para el provedor, para insertar o modificar el costo
                        $query = sprintf('SELECT PKDatosProductoProveedor FROM datos_producto_proveedores WHERE FKProducto = ? and FKProveedor = ?');
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont]));
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        if($stmt->rowCount() <= 0){
                            $query = sprintf('UPDATE datos_producto_proveedores set 
                            Precio = ?, 
                            FKTipoMoneda = ?, 
                            UnidadMedida = ?
                            where PKDatosProductoProveedor = ?');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($arrayCosto[$cont], $idMoneda, $unidad[0]['descripcion'], $row[0]['PKDatosProductoProveedor']));
                        }else{
                            //recupera nombre y clave del producto
                            $query = sprintf('SELECT Nombre, ClaveInterna from productos where PKProducto = ?');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($arrayProductos[$cont]));
                            $res = $stmt -> fetchAll();


                            $query = sprintf('INSERT into datos_producto_proveedores (NombreProducto, Clave, Precio, FKTipoMoneda, CantidadMinima, DiasEntrega, UnidadMedida, FKProveedor, FKProducto) values (?,?,?,?,0,0,?,?,?);');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($res[0]['Nombre'], $res[0]['ClaveInterna'], $arrayCosto[$cont], $idMoneda, $unidad[0]['descripcion'], $arrayProveedores[$cont], $arrayProductos[$cont]));    
                        }

                    }else{
                        $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            inner join productos as p on p.PKProducto = cvp.FKProducto
                                                set cvp.CostoCompra = CASE
                                                                        WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN cvp.CostoCompra
                                                                        ELSE ?
                                                                    END,
                                                cvp.CostoFabricacion = CASE
                                                                        WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN ?
                                                                        ELSE cvp.CostoFabricacion
                                                                    END
                                            where cvp.FKProducto = ?;');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayCosto[$cont], $arrayCosto[$cont], $arrayProductos[$cont]));

                        $query = sprintf('UPDATE operaciones_producto as op 
                                            inner join productos as p on p.PKProducto = op.FKProducto
                                                SET op.compra = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN op.compra
                                                                    ELSE 1
                                                                END, 
                                                op.fabricacion = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN 1
                                                                    ELSE op.fabricacion
                                                                END 
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont]));
                    }            
                    
                    $query = sprintf('SELECT producto_id from costos_historico 
                                        where producto_id = ? and proveedor_id = ? and costo = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont], $arrayCosto[$cont]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT INTO costos_historico (producto_id, proveedor_id, costo, fecha_alta, usuario_id) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont], $arrayCosto[$cont], $fecha_alta, $PKuser));
                    }
                }
                $cont++;
            }

            $contAd = 0;
            foreach($arrayProductosAdicionales as $aPa){

                if(trim($arrayProductosAdicionales[$contAd]) != ''){
                    $query = sprintf('INSERT INTO costos_detalle_adicionales (costo_adicionales_producto_id, cantidad_adicionales, costo_adicionales, costos_id) VALUES (?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd], $arrayCantidadAdicional[$contAd], $arrayCostoAdicional[$contAd], $idCosto));

                    $query = sprintf('SELECT producto_id from costos_historico 
                                        where producto_id = ? and proveedor_id = ? and costo = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd], 0, $arrayCostoAdicional[$contAd]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT INTO costos_historico (producto_id, proveedor_id, costo, fecha_alta, usuario_id) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductosAdicionales[$contAd], 0, $arrayCostoAdicional[$contAd], $fecha_alta, $PKuser));    
                    }
                    
                    //si no eiste registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd]));
                    $numRow = $stmt->rowCount();

                    if($numRow == 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayProductosAdicionales[$contAd]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayProductosAdicionales[$contAd], 0, 100, 0, 100, 0, 100));
                    }

                    //actualiza el costo de compra del producto
                    $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            set cvp.CostoCompra = ?
                                        where cvp.FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayCostoAdicional[$contAd], $arrayProductosAdicionales[$contAd]));

                    $query = sprintf('UPDATE operaciones_producto as op 
                                                SET op.compra = 1                                              
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductosAdicionales[$contAd]));
                    
                }
                $contAd++;
            }

            //registro del detalle de los gastos fijos
            $contGF = 0;
            foreach($arrayGastosFijos as $aGF){

                if(trim($arrayGastosFijos[$contGF]) != ''){
                    $query = sprintf('INSERT INTO costos_detalle_gastosFijos (costo_gasto_fijo_producto_id, costo_gasto_fijo, porcentaje_gasto_fijo, costos_id) VALUES (?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF], $arrayCostoGastoFijo[$contGF], $arrayPorcentajeGastoFijo[$contGF], $idCosto));
                    
                    //si no eiste registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF]));
                    $numRow = $stmt->rowCount();

                    if($numRow == 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayGastosFijos[$contGF]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayGastosFijos[$contGF], 0, 100, 0, 100, 0, 100));
                    }

                    //actualiza el costo de gasto fijo del producto
                    $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            set cvp.costoGastoFijo = ?
                                        where cvp.FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayCostoGastoFijo[$contGF], $arrayGastosFijos[$contGF]));

                    $query = sprintf('UPDATE operaciones_producto as op 
                                                SET op.Gasto_fijo = 1                                              
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayGastosFijos[$contGF]));
                    
                }
                $contGF++;
            }

            $query = sprintf('SELECT id, Venta FROM operaciones_producto WHERE FKProducto = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($idProducto));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $idOperacion = $row[0]['id'];
            /* $ventaProducto = $row[0]['Venta']; */

            $query = sprintf('SELECT PKCostoVentaProducto FROM costo_venta_producto WHERE FKProducto = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idProducto));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $idCostoVentaProducto = $row[0]['PKCostoVentaProducto'];

            if($idOperacion == null){
                $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) 
                                    select ?, ?, if(p.FKTipoProducto = 3 or p.FKTipoProducto = 5, 0, 1), ?, ? from productos as p where p.PKProducto = ?;');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array(0, 1, 0, $idProducto, $idProducto));
            }else /* if($ventaProducto == 0) */{
                $query = sprintf('UPDATE operaciones_producto as op inner join productos as p on p.PKProducto = op.FKProducto
                                SET op.Venta = 1,
                                 op.fabricacion = case 
                                                    when p.FKTipoProducto = 3 or p.FKTipoProducto = 5 then op.fabricacion
                                                    else 1
                                                end
                                WHERE op.FKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($idProducto));
            }

            if($idCostoVentaProducto != null){
                $query = sprintf('UPDATE costo_venta_producto as cvp inner join productos as p on p.PKProducto = cvp.FKProducto
                                        SET cvp.CostoGeneral = ?,
                                        cvp.CostoFabricacion = case
                                                                    when p.FKTipoProducto = 3 or p.FKTipoProducto = 5 then cvp.CostoFabricacion
                                                                    else ?
                                                                end                                      
                                        WHERE cvp.FKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($totalCosto, $totalFabricacion, $idProducto));
            }else{
                $query = sprintf('INSERT INTO costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo)
                                    select ?,?,?,?,?,if(p.FKTipoProducto = 3 or p.FKTipoProducto = 5, 0, ?),?, ?, ? from productos as p where p.PKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($totalCosto, 100, $idProducto, 0.00, 100, 0.00, 100, 0.00, 100, $idProducto));
            }

            $status = $db->commit();

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }


    public function guardarProducto($value)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fkEstatusGeneral = 1;
        $nombre = $value['nombre'];
        $claveInterna = $value['claveInterna'];
        $codigoBarras = $value['codigoBarra'];
        $fkCategoriaProducto = $value['categoria'];
        $fkMarcaProducto = $value['marca'];
        $fkTipoProducto = $value['tipo'];
        $descripcion = $value['descripcion'];
        $unidadSAT = $value['unidadSAT'];

        /*$producto_api[] = [
          'description' => $nombre,
          'sku' => $claveInterna,
          'price' =>$costoVenta,
          'tax_included' => false
        ];*/

        $imagen = 'agregar.svg';

        $PKProducto = "0";
        $PKEmpresa = $_SESSION["IDEmpresa"];
        $pkUsuario = $_SESSION["PKUsuario"];
        $isCaducidad = 0;
        $isSerie = 0;
        $isLote = 0;
        $precioCompra = 0;

        $precioVenta1 = 0.00;
        $precio_compra = 0.00;

        $costoGeneral = 0.00;
        $costoCompra = 0.00;

        $costoFabricacion = $value['fabricacion']['costo'];
        $monedaFabricacion = $value['fabricacion']['moneda'];

        try {

            $db->beginTransaction();

            $query = sprintf('insert into productos (Nombre, ClaveInterna, CodigoBarras, FKCategoriaProducto, FKMarcaProducto, Descripcion, FKTipoProducto, estatus, serie, lote, fecha_caducidad, Imagen, usuario_creacion_id, precio_venta1, precio_compra, created_at, usuario_edicion_id, updated_at, empresa_id) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,(select now()), ?, (select now()), ? );');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($nombre, $claveInterna, $codigoBarras, $fkCategoriaProducto, $fkMarcaProducto, $descripcion, $fkTipoProducto, $fkEstatusGeneral, $isSerie, $isLote, $isCaducidad ,$imagen, $pkUsuario, $precioVenta1, $precioCompra, $pkUsuario, $PKEmpresa));
            $PKProducto = $db->lastInsertId();

            if($fkTipoProducto == 10){
                $query = sprintf(' insert into operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) values (?,?,?,?,?);');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array(0,0,0,1,$PKProducto));

                $query = sprintf('insert into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values (?,?,?,?,?,?,?,?,?) ');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($costoGeneral, 100, $PKProducto, $costoCompra, 100, 0.00, 100, $costoFabricacion, $monedaFabricacion ));

            }else{
                $query = sprintf(' insert into operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) values (?,?,?,?,?);');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array(0,0,1,0,$PKProducto));

                $query = sprintf('insert into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values (?,?,?,?,?,?,?,?,?) ');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($costoGeneral, 100, $PKProducto, $costoCompra, 100, $costoFabricacion, $monedaFabricacion, 0.00, 100));
            }

            $query = sprintf('insert into info_fiscal_productos (FKProducto, FKClaveSAT, FKClaveSATUnidad) values (?,?,?) ');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($PKProducto, 1, $unidadSAT));

            $status = $db->commit();

            $data[0] = ['status' => $status, 'id' => $PKProducto];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function saveDatosProductoCompTemp($pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = sprintf('call spi_Prod_AgregarProdCompuestoTemp (?,?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $cantidad, $PKCompuestoTemp, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}

class edit_data
{
    public function editarCostos($datos)
    {
        $con = new conectar();
        $db = $con->getDb();

        $fecha_alta = date("Y-m-d H:i:s");
        $PKuser = $_SESSION["PKUsuario"];

        //print_r($datos);

        $arrayProductos = array();
        $contProd = 0;

        $arrayCantidad = array();
        $contCantidad = 0;

        $arrayCosto = array();
        $contCosto = 0;

        $arrayProveedores = array();
        $contProveedores = 0;

        $arrayProductosAdicionales = array();
        $contProdAdicionales = 0;

        $arrayCantidadAdicional = array();
        $contCantidadAdicional = 0;

        $arrayCostoAdicional = array();
        $contCostoAdicional = 0;

        $arrayGastosFijos = array();
        $countGastosFijos = 0;

        $arrayCostoGastoFijo = array();
        $countCostoGastoFijo = 0;

        $arrayPorcentajeGastoFijo = array();
        $countPorcentajeGastoFijo = 0;

        $utilidad = 0.00;
        $utilidad_porcentaje = 0.00;

        foreach($datos as $d => $key){

            if($d == "cmbMonedaEdit"){
                $idMoneda = $key;
            }
            if($d == "idCostoEdit"){
                $idCostoEdit = $key;
            }

            if($d == "AtxtUtilidadesEdit"){
                $utilidad = $key;
            }
            //echo $d." - ".$key."<br>";

            //ingresar id productos en array 
            if(substr($d, 0, 16) == 'txtProductosEdit'){
                $arrayProductos[$contProd] =$key;
                $contProd++;
            }

            // ingresar cantidad en array
            if(substr($d, 0, 24) == 'txtCantidadCompuestaEdit'){
                if(trim($key) != ""){
                    $arrayCantidad[$contCantidad] =$key;
                    $contCantidad++;
                }
                else{
                    $arrayCantidad[$contCantidad] = 0;
                    $contCantidad++;
                }
            }


            //ingresar costo en el array
            if(substr($d, 0, 12) == 'txtCostoEdit'){
                if(trim($key) != ""){
                    $arrayCosto[$contCosto] =$key;
                    $contCosto++;
                }
                else{
                    $arrayCosto[$contCosto] = 0.00;
                    $contCosto++;
                }
            }

            //echo "CADENA ".substr($d, 0, 14)."<br>";
            //Ingresar proveedores en el array
            if(substr($d, 0, 18) == 'txtProveedoresEdit'){
                $arrayProveedores[$contProveedores] =$key;
                $contProveedores++;
            }

            if(substr($d, 0, 24) == 'AtxtProductosAdicionales'){
                $arrayProductosAdicionales[$contProdAdicionales] =$key;
                $contProdAdicionales++;
            }

            // ingresar cantidad adicional en array
            if(substr($d, 0, 32) == 'AtxtCantidadCompuestaAdicionales'){
                if(trim($key) != ""){
                    $arrayCantidadAdicional[$contCantidadAdicional] =$key;
                    $contCantidadAdicional++;
                }
                else{
                    $arrayCantidadAdicional[$contCantidadAdicional] = 0;
                    $contCantidadAdicional++;
                }
            }


            //ingresar costo en el array
            if(substr($d, 0, 20) == 'AtxtCostoAdicionales'){
                if(trim($key) != ""){
                    $arrayCostoAdicional[$contCostoAdicional] =$key;
                    $contCostoAdicional++;
                }
                else{
                    $arrayCostoAdicional[$contCostoAdicional] = 0;
                    $contCostoAdicional++;
                }
            }

            //llena array de gastos fijos
            if(substr($d, 0, 13) == 'txtGastoFEdit'){
                $arrayGastosFijos[$countGastosFijos] =$key;
                $countGastosFijos++;
            }

            //ingresar costo del gasto fijo en el array
            if(substr($d, 0, 19) == 'BtxtCostoGastoFEdit'){
                if(trim($key) != ""){
                    $arrayCostoGastoFijo[$countCostoGastoFijo] =$key;
                }
                else{
                    $arrayCostoGastoFijo[$countCostoGastoFijo] = 0;
                }
                $countCostoGastoFijo++;
            }
            
            //ingresar porcentaje del gasto fijo en el array
            if(substr($d, 0, 24) == 'AtxtGastoFPorcentajeEdit'){
                if(trim($key) != ""){
                    $arrayPorcentajeGastoFijo[$countPorcentajeGastoFijo] =$key;
                }
                else{
                    $arrayPorcentajeGastoFijo[$countPorcentajeGastoFijo] = 0;
                }
                $countPorcentajeGastoFijo++;
            }
            

        }

        //CALCULAR TOTAL
        $totalCostoComponentes = 0.00;
        for($x = 0; $x < $contCosto; $x++){
            if(trim($arrayProductos[$x]) != ''){
                $totalCostoComponentes = $totalCostoComponentes + ($arrayCantidad[$x] * $arrayCosto[$x]);
            }
        }

        $totalCostoAdicionales = 0.00;
        for($x = 0; $x < $contCostoAdicional; $x++){
            if(trim($arrayProductosAdicionales[$x]) != ''){
                $totalCostoAdicionales = $totalCostoAdicionales + ($arrayCantidadAdicional[$x] * $arrayCostoAdicional[$x]);
            }
        }

        $totalCostoGastoF = 0.00;
        for($x = 0; $x < $countPorcentajeGastoFijo; $x++){
            if(trim($arrayGastosFijos[$x]) != ''){
                $totalCostoGastoF = $totalCostoGastoF + (($arrayCostoGastoFijo[$x] * $arrayPorcentajeGastoFijo[$x])/100);
            }
        }

        $totalCosto = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF + $utilidad;

        $totalFabricacion = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF;

        $totalCostoDirecto = $totalCostoComponentes + $totalCostoAdicionales + $totalCostoGastoF;

        if($totalCostoComponentes == 0 && $totalCostoAdicionales == 0 && $totalCostoGastoF == 0){
            $utilidad_porcentaje = 0.00;
        }
        else{
            $utilidad_porcentaje = number_format(($utilidad / $totalCostoDirecto) * 100, 2);
        }

        //echo "idProducto ".$idProducto."<br>"; //correcto
        //echo "idMoneda ".$idMoneda."<br>"; //correcto
        //echo "totalCosto ".$totalCosto."<br>"; //correcto
        /*print_r($arrayProductos);
        print_r($arrayCantidad);
        print_r($arrayCosto);*/
        // print_r($arrayProveedores);
            
        try {
            $db->beginTransaction();

            $query = sprintf('UPDATE costos SET costos_componentes = ?, costos_adicionales = ?, costos_gastosFijos = ?, utilidad = ?, utilidad_porcentaje = ?, total_costo = ?, moneda_id = ? WHERE id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($totalCostoComponentes, $totalCostoAdicionales, $totalCostoGastoF, $utilidad, $utilidad_porcentaje, $totalCosto, $idMoneda, $idCostoEdit));

            $query = sprintf('DELETE FROM  costos_detalle WHERE costos_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idCostoEdit));

            $query = sprintf('DELETE FROM  costos_detalle_adicionales WHERE costos_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idCostoEdit));

            $query = sprintf('DELETE FROM  costos_detalle_gastosFijos WHERE costos_id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idCostoEdit));

            $cont = 0;
            foreach($arrayProductos as $aP){

                if(trim($arrayProductos[$cont]) != ''){

                    //recupera texto de unidad de medida
                    $query = sprintf('SELECT ifnull(u.descripcion, "Sin Clave") as descripcion FROM claves_sat_unidades as u
                                        inner join info_fiscal_productos as f on f.FKClaveSATUnidad = u.PKClaveSATUnidad 
                                    WHERE f.FKProducto = ?;');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));
                    $unidad = $stmt->fetchAll();
                    $unidad_des = count($unidad) > 0 ? $unidad[0]['descripcion'] : "Sin Clave";
                    //si no existe registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));
                    $numRow = $stmt->rowCount();

                    if($numRow <= 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayProductos[$cont]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayProductos[$cont], 0, 100, 0, 100, 0, 100));
                    }

                    $query = sprintf('INSERT INTO costos_detalle (costo_producto_id, cantidad, costo, proveedor_id, costos_id) VALUES (?,?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont], $arrayCantidad[$cont], $arrayCosto[$cont], $arrayProveedores[$cont], $idCostoEdit));

                    //actualiza el costo de fabricación/compra del producto
                    if($arrayProveedores[$cont] != "" && $arrayProveedores[$cont] != null && $arrayProveedores[$cont] != 0){
                        
                        $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            inner join productos as p on p.PKProducto = cvp.FKProducto
                                                set cvp.CostoCompra = if(cvp.CostoCompra = 0, ?, cvp.CostoCompra),
                                                cvp.CostoFabricacion = CASE
                                                                            WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN case WHEN cvp.CostoFabricacion = 0 then ? else cvp.CostoFabricacion end
                                                                            ELSE cvp.CostoFabricacion
                                                                        END
                                            where cvp.FKProducto = ?;');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayCosto[$cont], $arrayCosto[$cont], $arrayProductos[$cont]));

                        $query = sprintf('UPDATE operaciones_producto as op 
                                            inner join productos as p on p.PKProducto = op.FKProducto
                                                SET op.compra = 1,
                                                op.Fabricacion = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN 1
                                                                    ELSE op.Fabricacion
                                                                END
                                            WHERE op.FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont]));

                        //consulta si existe el producto para el provedor, para insertar o modificar el costo
                        $query = sprintf('SELECT PKDatosProductoProveedor FROM datos_producto_proveedores WHERE FKProducto = ? and FKProveedor = ?');
                        $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont]));
                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                       
                        if($stmt->rowCount() > 0){
                            $query = sprintf('UPDATE datos_producto_proveedores set 
                            Precio = ?, 
                            FKTipoMoneda = ?, 
                            UnidadMedida = ?
                            where PKDatosProductoProveedor = ?');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($arrayCosto[$cont], $idMoneda, $unidad_des, $row[0]['PKDatosProductoProveedor']));
                        }else{
                            //recupera nombre y clave del producto
                            $query = sprintf('SELECT Nombre, ClaveInterna from productos where PKProducto = ?');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($arrayProductos[$cont]));
                            $res = $stmt -> fetchAll();


                            $query = sprintf('INSERT into datos_producto_proveedores (NombreProducto, Clave, Precio, FKTipoMoneda, CantidadMinima, DiasEntrega, UnidadMedida, FKProveedor, FKProducto) values (?,?,?,?,0,0,?,?,?);');
                            $stmt = $stmt = $db->prepare($query);
                            $stmt->execute(array($res[0]['Nombre'], $res[0]['ClaveInterna'], $arrayCosto[$cont], $idMoneda, $unidad[0]['descripcion'], $arrayProveedores[$cont], $arrayProductos[$cont]));    
                        }

                    }else{
                        $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            inner join productos as p on p.PKProducto = cvp.FKProducto
                                                set cvp.CostoCompra = CASE
                                                                        WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN cvp.CostoCompra
                                                                        ELSE ?
                                                                    END,
                                                cvp.CostoFabricacion = CASE
                                                                        WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN ?
                                                                        ELSE cvp.CostoFabricacion
                                                                    END
                                            where cvp.FKProducto = ?;');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayCosto[$cont], $arrayCosto[$cont], $arrayProductos[$cont]));

                        $query = sprintf('UPDATE operaciones_producto as op 
                                            inner join productos as p on p.PKProducto = op.FKProducto
                                                SET op.compra = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN op.compra
                                                                    ELSE 1
                                                                END, 
                                                op.fabricacion = CASE
                                                                    WHEN p.FKTipoProducto = 3 or p.FKTipoProducto = 1 THEN 1
                                                                    ELSE op.fabricacion
                                                                END 
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont]));
                    }

                    $query = sprintf('SELECT producto_id from costos_historico 
                                        where producto_id = ? and proveedor_id = ? and costo = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont], $arrayCosto[$cont]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT INTO costos_historico (producto_id, proveedor_id, costo, fecha_alta, usuario_id) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductos[$cont], $arrayProveedores[$cont], $arrayCosto[$cont], $fecha_alta, $PKuser));
                    }
                }
                $cont++;
            }

            $contAd = 0;
            foreach($arrayProductosAdicionales as $aPa){

                if(trim($arrayProductosAdicionales[$contAd]) != ''){
                    $query = sprintf('INSERT INTO costos_detalle_adicionales (costo_adicionales_producto_id, cantidad_adicionales, costo_adicionales, costos_id) VALUES (?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd], $arrayCantidadAdicional[$contAd], $arrayCostoAdicional[$contAd], $idCostoEdit));

                    $query = sprintf('SELECT producto_id from costos_historico 
                                        where producto_id = ? and proveedor_id = ? and costo = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd], 0, $arrayCostoAdicional[$contAd]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT INTO costos_historico (producto_id, proveedor_id, costo, fecha_alta, usuario_id) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductosAdicionales[$contAd], 0, $arrayCostoAdicional[$contAd], $fecha_alta, $PKuser));
                    }

                    //si no existe registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd]));
                    $numRow = $stmt->rowCount();

                    if($numRow <= 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayProductosAdicionales[$contAd]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayProductosAdicionales[$contAd]));
                    
                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayProductosAdicionales[$contAd], 0, 100, 0, 100, 0.00, 100));
                    }

                    //actualiza el costo de compra del producto
                    $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            set cvp.CostoCompra = ?
                                        where cvp.FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayCostoAdicional[$contAd], $arrayProductosAdicionales[$contAd]));

                    $query = sprintf('UPDATE operaciones_producto as op 
                                                SET op.compra = 1                                              
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayProductosAdicionales[$contAd]));
                    
                }
                $contAd++;
            }

            //registro del detalle de los gastos fijos
            $contGF = 0;
            foreach($arrayGastosFijos as $aGF){

                if(trim($arrayGastosFijos[$contGF]) != ''){
                    $query = sprintf('INSERT INTO costos_detalle_gastosFijos (costo_gasto_fijo_producto_id, costo_gasto_fijo, porcentaje_gasto_fijo, costos_id) VALUES (?,?,?,?)');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF], $arrayCostoGastoFijo[$contGF], $arrayPorcentajeGastoFijo[$contGF], $idCostoEdit));
                    
                    //si no eiste registro de las operaciones, por default los ingresa en 0 
                    $query = sprintf('SELECT id FROM operaciones_producto WHERE FKProducto = ?');
                    $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF]));
                    $numRow = $stmt->rowCount();

                    if($numRow == 0){
                        $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) VALUES (?,?,?,?,?)');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 0, 0, 0, $arrayGastosFijos[$contGF]));
                    }

                    //si no eiste registro de los costos, por default los ingresa en 0 
                    $query = sprintf('SELECT PKCostoVentaProducto from costo_venta_producto 
                                        where FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayGastosFijos[$contGF]));

                    if($stmt->rowCount() <= 0){
                        $query = sprintf('INSERT into costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo) values(?,?,?,?,?,?,?,?,?);');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array(0, 100, $arrayGastosFijos[$contGF], 0, 100, 0, 100, 0, 100));
                    }

                    //actualiza el costo de gasto fijo del producto
                    $query = sprintf('UPDATE costo_venta_producto as cvp 
                                            set cvp.costoGastoFijo = ?
                                        where cvp.FKProducto = ?;');
                    $stmt = $stmt = $db->prepare($query);
                    $stmt->execute(array($arrayCostoGastoFijo[$contGF], $arrayGastosFijos[$contGF]));

                    $query = sprintf('UPDATE operaciones_producto as op 
                                                SET op.Gasto_fijo = 1                                              
                                            WHERE FKProducto = ?');
                        $stmt = $stmt = $db->prepare($query);
                        $stmt->execute(array($arrayGastosFijos[$contGF]));
                    
                }
                $contGF++;
            }

            $query = sprintf('SELECT producto_id FROM costos WHERE id = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idCostoEdit));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $idProducto = $row[0]['producto_id'];

            $query = sprintf('SELECT id, Venta FROM operaciones_producto WHERE FKProducto = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idProducto));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $idOperacion = $row[0]['id'];
            /* $ventaProducto = $row[0]['Venta']; */

            $query = sprintf('SELECT PKCostoVentaProducto FROM costo_venta_producto WHERE FKProducto = ?');
            $stmt = $stmt = $db->prepare($query);
            $stmt->execute(array($idProducto));
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $idCostoVentaProducto = $row[0]['PKCostoVentaProducto'];

            if($idOperacion == null){
                $query = sprintf('INSERT INTO operaciones_producto (Compra, Venta, Fabricacion, Gasto_fijo, FKProducto) 
                                    select ?, ?, if(p.FKTipoProducto = 3 or p.FKTipoProducto = 5, 0, 1), ?, ? from productos as p where p.PKProducto = ?;');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array(0, 1, 0, $idProducto, $idProducto));
            }else /* if($ventaProducto == 0) */{
                $query = sprintf('UPDATE operaciones_producto as op inner join productos as p on p.PKProducto = op.FKProducto
                                SET op.Venta = 1,
                                 op.Fabricacion = case 
                                                    when p.FKTipoProducto = 3 or p.FKTipoProducto = 5 then op.Fabricacion
                                                    else 1
                                                end
                                WHERE op.FKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($idProducto));
            }

            if($idCostoVentaProducto != null){
                $query = sprintf('UPDATE costo_venta_producto as cvp inner join productos as p on p.PKProducto = cvp.FKProducto
                                        SET cvp.CostoGeneral = ?,
                                        cvp.CostoFabricacion = case
                                                                    when p.FKTipoProducto = 3 or p.FKTipoProducto = 5 then cvp.CostoFabricacion
                                                                    else ?
                                                                end                                      
                                        WHERE cvp.FKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($totalCosto, $totalFabricacion, $idProducto));
            }else{
                $query = sprintf('INSERT INTO costo_venta_producto (CostoGeneral, FKTipoMoneda, FKProducto, CostoCompra, FKTipoMonedaCompra, CostoFabricacion, FKTipoMonedaFabricacion, costoGastoFijo, FKTipoMonedaGastoFijo)
                                    select ?,?,?,?,?,if(p.FKTipoProducto = 3 or p.FKTipoProducto = 5, 0, ?),?,?,? from productos as p where p.PKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $stmt->execute(array($totalCosto, 100, $idProducto, 0.00, 100, 0.00, 100, 0.00, 100, $idProducto));
            }

            $status = $db->commit();

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function modificarUnidadSAT($idProducto, $idUnidadSat)
    {
        $con = new conectar();
        $db = $con->getDb();

        //print_r($datos);

        
        try {

            $query = sprintf('SELECT PKInfoFiscalProducto FROM info_fiscal_productos WHERE FKProducto = ?');
            $stmt = $db->prepare($query);
            $stmt->execute(array($idProducto));
            $existe = $stmt->rowCount();

            if($existe > 0){
                $query = sprintf('UPDATE info_fiscal_productos SET FKClaveSATUnidad = ? WHERE FKProducto = ?');
                $stmt = $stmt = $db->prepare($query);
                $status = $stmt->execute(array($idUnidadSat,$idProducto));
            }
            else{
                $query = sprintf('INSERT INTO info_fiscal_productos (FKClaveSATUnidad , FKProducto, FKClaveSAT) VALUES (?,?,1)');
                $stmt = $stmt = $db->prepare($query);
                $status = $stmt->execute(array($idUnidadSat,$idProducto));   
            }

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    
    public function editListaMaterialesProducto($value, $pkProducto, $estatus)
    {
        $con = new conectar();
        $db = $con->getDb();

        //$fkEstatusGeneral = $value['estatus'];
        if (isset($_SESSION["Usuario"])) {
            $PKuser = $_SESSION["PKUsuario"];
        }

        try {
            $query = sprintf('call spu_Prod_ActualizarLstMaterialesGeneral (?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($estatus, $pkProducto, $PKuser));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCantidadProductoCompTemp($pkProducto, $cantidad, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CantidadProdCompuestoTemp_Actualizar (?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $cantidad, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosCostoProductoCompTemp($pkProducto, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CostoProdCompuestoTemp_Actualizar (?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosMonedaProductoCompTemp($pkProducto, $costo, $moneda)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_CostoProdCompuestoTemp_Actualizar (?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosUnidadMProductoCompTemp($pkProducto, $costo, $moneda, $unidadMedidaID)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_UnidadMedidaProdCompuestoTemp_Actualizar (?,?,?,?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto, $costo, $moneda, $unidadMedidaID));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function editDatosListaMaterialEstatus($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spu_Prod_Estatus_Actualizar (?,?)');
            $stmt = $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto));

            $data[0] = ['status' => $status];

            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    //END JAVIER RAMIREZ
}

class delete_data
{
    public function deleteCostos($pkCosto)
    {
        $con = new conectar();
        $db = $con->getDb();

        try {
            $query = sprintf('DELETE c, d1, d2, d3 
                                FROM costos c 
                                    inner join costos_detalle d1 on d1.costos_id = c.id 
                                    left join costos_detalle_adicionales d2 on d2.costos_id = c.id
                                    left join costos_detalle_gastosFijos d3 on d3.costos_id = c.id 
                                WHERE c.id = ?');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($pkCosto));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }

    public function deleteDatosProductoCompTemp($pkProducto)
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdCompuestoTemp(?,?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser, $pkProducto));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
    
    public function deleteDatosProductoCompTempAll()
    {
        $con = new conectar();
        $db = $con->getDb();

        $PKuser = $_SESSION["PKUsuario"];

        try {
            $query = sprintf('call spd_EliminarProdCompuestoTempAll(?)');
            $stmt = $db->prepare($query);
            $status = $stmt->execute(array($PKuser));

            $data[0] = ['status' => $status];
            return $data;

        } catch (PDOException $e) {
            return "Error en Consulta: " . $e->getMessage();
        }

        $stmt = null;
        $db = null;
    }
}