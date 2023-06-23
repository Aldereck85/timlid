<?php
session_start();
$idempresa = $_SESSION["IDEmpresa"];
if(isset($_POST['id'])){
    require_once('../../../include/db-conn.php');
    $json = new \stdClass();

    $stmt = $conn->prepare('SELECT mcbe.PKMovimiento, mcbe.Fecha, FKProveedor, mcbe.Descripcion, mcbe.Retiro, mcbe.Saldo, Referencia, Comprobado, tipoCambio, tipo_movimiento_id, cuenta_origen_id, cuenta_destino_id, FKSubcategoria, FKCategoria, FKResponsable FROM movimientos_cuentas_bancarias_empresa mcbe WHERE mcbe.PKMovimiento = :id');
    $stmt->bindValue(':id',$_POST['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    $cuenta_id = $row['cuenta_origen_id'];
    $responsable_id = $row['FKResponsable'];
    $importe = $row['Retiro'];
    $fecha = date('Y-m-d', strtotime($row['Fecha']));
    $proveedor_id = $row['FKProveedor'];
    $observaciones = $row['Descripcion'];
    $categoria_id = $row['FKCategoria'];
    $subcategoria_id = $row['FKSubcategoria'];
    $saldo = $row['Saldo'];

    // SELECCIÓN DE LA CUENTA
    $stmt = $conn->prepare('SELECT PKCuenta, Nombre, tipo_cuenta FROM  cuentas_bancarias_empresa WHERE estatus=1 AND tipo_cuenta!=2 AND empresa_id='.$idempresa);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $cuentas = "";
    foreach($row as $r){
        $cuentas .= "<option value='".$r["PKCuenta"]."'";
        if($cuenta_id == $r["PKCuenta"]){
            $cuentas .= " selected";
        }
        switch($r["tipo_cuenta"]){
            case 1:
                $cuentas .= ">".$r['Nombre']." - Cheques"."</option>";
            break;
            case 3:
                $cuentas .= ">".$r['Nombre']." - Otras"."</option>";
            break;
            case 4:
                $cuentas .= ">".$r['Nombre']." - Caja chica"."</option>";
            break;
        }
    }

    // SELECCIÓN DEL RESPONSABLE
    $stmt = $conn->prepare('SELECT emp.PKEmpleado, emp.Nombres, emp.PrimerApellido, emp.SegundoApellido
                            FROM empleados emp
                            INNER JOIN relacion_tipo_empleado rte
                            ON emp.PKEmpleado = rte.empleado_id
                            WHERE emp.empresa_id = :empresa AND rte.tipo_empleado_id = 2');
    $stmt->bindValue(':empresa', $idempresa);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $responsable = '';
    foreach($row as $r){
    $responsable .= "<option value='".$r["PKEmpleado"]."'";
    if($responsable_id == $r["PKEmpleado"]){
        $responsable .= " selected";
    }
    $responsable .= ">".$r['Nombres']." ".$r['PrimerApellido']." ".$r['SegundoApellido']."</option>";
    }

    //SELECCION DEL PROVEEDOR
    $stmt = $conn->prepare('SELECT PKProveedor, NombreComercial FROM proveedores WHERE tipo = 1 AND empresa_id='.$idempresa);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $proveedores = '<option value="" selected disabled hidden>Seleccionar un proveedor</option>';
    foreach($row as $b){
        $proveedores .= "<option value='".$b["PKProveedor"]."'";
        if($proveedor_id == $b["PKProveedor"]){
        $proveedores .= " selected";
        }
        $proveedores .= ">".$b['NombreComercial']."</option>";
    }

    //SELECCION DE LA CATEGORÍA
    $stmt = $conn->prepare('SELECT * FROM (SELECT cg.PKCategoria, cg.Nombre 
                            FROM categoria_gastos cg WHERE cg.estatus = 1 AND cg.empresa_id= :id
                                
                            union 

                            select
                                c.PKCategoria, 
                                c.Nombre 
                            from 
                                categoria_gastos c
                            where PKCategoria = 1)
                            as cat ORDER BY cat.PKCategoria');
    $stmt->bindValue(':id',$idempresa);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $categorias = "<option data-placeholder='true'></option>";
    foreach($row as $b){
        $categorias .= "<option value='".$b["PKCategoria"]."'";
        if($categoria_id == $b["PKCategoria"]){
            $categorias .= " selected";
        }
        $categorias .= ">".$b['Nombre']."</option>";
    }
    
    //Respuesta de los combos en JSON
    $json->cuentas = $cuentas;
    $json->responsable = $responsable;
    $json->proveedores = $proveedores;
    $json->categorias = $categorias;
    $json->categoria = $categoria_id;
    $json->subcategoria = $subcategoria_id;

    //Respuesta del resto de los datos en JSON
    $json->importe = $importe;
    $json->fecha = $fecha;
    $json->observaciones = $observaciones;
    $json->saldo = $saldo;
  
    $json = json_encode($json);
    echo $json;
}
?>