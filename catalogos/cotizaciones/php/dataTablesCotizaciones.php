<?php
    session_start();
    require '../../../php/dataTablesServerSide.php';
    require '../../../include/db-conn.php';

    // $table_data->get($conn,'cotizacion','PKCotizacion',array(
    //     'id_cotizacion_empresa',
    //     'FKCliente',
    //     'CodigoCotizacion',
    //     'ImporteTotal',
    //     'FKSucursal',
    //     'estatus_cotizacion_id',
    //     'FKUsuarioCreacion',
    //     'estatus_factura_id',
    //     'empresa_id',
    //     'flujo_almacen'));

    $columns = array(
        array( 'db' => 'c.PKCotizacion', 'dt' => 0, 'dc' => 'PKCotizacion'),
        array( 'db' => 'c.id_cotizacion_empresa', 'dt' => 1, 'dc'=> 'id_cotizacion_empresa', 'formatter' => function( $d, $row ) {
            $html = '<a id="detalle_cotizacion" href="#" data-id="' . $row['PKCotizacion'] . '">' . $d . '</a>';
            return $html;
        } ),
        array( 'db' => 'cl.NombreComercial', 'dt' => 2, 'dc'=> 'NombreComercial' ),
        array( 'db' => 'cl.RFC', 'dt' => 3, 'dc'=> 'RFC' ),
        array( 'db' => 'ImporteTotal', 'dt' => 4, 'dc'=> 'ImporteTotal', 'formatter' => function( $d, $row ) {
            return '$'.number_format($d,2);
        } ),
        array( 'db' => 's.sucursal', 'dt' => 5, 'dc'=> 'sucursal' ),
        array( 'db' => 'ec.tipo_estatus', 'dt' => 6, 'dc'=> 'tipo_estatus' ),
        array( 'db' => 'vd.Referencia', 'dt' => 7,'dc' => 'Referencia'),
        array( 'db' => 'c.estatus_factura_id', 'dt' => 8, 'dc'=> 'estatus_factura_id', 'formatter' => function( $d, $row ) {
            switch ($d) {
                case 1:
                    $estatus_factura = '<span class="left-dot turquoise-dot">Facturado completo</span>';
                    // $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 2:
                    $estatus_factura = '<span class="left-dot turquoise-dot">Facturado directo</span>';
                    // $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 3:
                    $estatus_factura = '<span class="left-dot yellow-dot">Pendiente de <br>facturar</span>';
                    break;
                case 4:
                    $estatus_factura = '<span class="left-dot yellow-dot">Pendiente de <br>facturar directo</span>';
                    break;
                case 5:
                    $estatus_factura = '<span class="left-dot green-dot">Parcialmente<br> facturado almacén</span>';
                    break;
                case 6:
                    $estatus_factura = '<span class="left-dot red-dot">Cancelada</span>';
                    break;
                case 9:
                    // $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
                case 10:
                    // $enlace .= '<br><i data-toggle=\"modal\" data-target=\"#copyVenta\" onclick=\"idCopear(' . $Id . ');\" class=\"fas fa-copy pointer\"></i>';
                    break;
            }
            return $estatus_factura;
        } ),
        array( 'db' => 'c.estatus_factura_id', 'dt' => 9, 'dc'=> 'estatus_factura_id', 'formatter' => function ($d,$row){
            $marcarVendida = "";
            if ($d != 1 && $d != 2 && $d != 5 && $d != 6 && $d != 9 && $d != 10) {
                $marcarVendida = '<input class="form-check-input" type="checkbox" id="cbxMarcarVenta-1" name="cbxMarcarVenta-1" onchange="venderCotizacion(1, this)">';

            }
            return $marcarVendida;
        } )
    );

    $add_sql = "
    LEFT JOIN estatus_cotizacion as ec ON ec.id = c.estatus_cotizacion_id
    LEFT JOIN clientes as cl ON cl.PKCliente = c.FKCliente
    LEFT JOIN sucursales as s ON s.id = c.FKSucursal
    left join orden_pedido_por_sucursales as ops on ops.numero_cotizacion=c.PKCotizacion
    LEFT JOIN ventas_directas vd ON c.id_cotizacion_empresa = vd.referencia_cotizacion
    ";
    $company = "c.empresa_id = ".$_SESSION['IDEmpresa']." ORDER BY c.id_cotizacion_empresa DESC";
    echo json_encode(
        SSP::simple( $_POST, $conn, 'cotizacion as c', 'PKCotizacion', $columns, $add_sql, $company
            // array(
            //     'id_cotizacion_empresa',
            //     'FKCliente',
            //     'CodigoCotizacion',
            //     'ImporteTotal',
            //     'FKSucursal',
            //     'estatus_cotizacion_id',
            //     'FKUsuarioCreacion',
            //     'estatus_factura_id',
            //     'empresa_id',
            //     'flujo_almacen'
            // )
        )
    );
?>