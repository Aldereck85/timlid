<?php
session_start();
date_default_timezone_set('America/Mexico_City');

class conectar
{ //Llamado al archivo de la conexión.
    public function getDb()
    {
        include "../include/db-conn.php";
        return $conn;
    }
}

class get_data
{
    public function getNumberTeams()
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = 'SELECT * FROM integrantes_equipo WHERE FKEmpleado = :idUusario';
        $stmt = $db->prepare($query);
        $stmt->execute([':idUusario' => $_SESSION['PKUsuario']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjects()
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = 'SELECT DISTINCT p.Proyecto FROM integrantes_proyecto ip
        INNER JOIN proyectos p ON ip.FKProyecto = p.PKProyecto
        WHERE ip.FKUsuario = :idUsuario ORDER BY ip.FKProyecto DESC';
        $stmt = $db->prepare($query);
        $stmt->execute([':idUsuario' => $_SESSION['PKUsuario']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNota()
    {
        $idUser = $_SESSION['PKUsuario'];
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf('SELECT Notas FROM usuarios WHERE id = :id');
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $idUser]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVentasAnio()
    {
        $anioActual = date('Y');
        $idEmpresa = $_SESSION['IDEmpresa'];
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("SELECT
        SUM(f.total_facturado) AS ImporteTotal, MONTH(f.fecha_timbrado) AS Mes
        FROM facturacion AS f
        WHERE f.empresa_id = :idEmpresa
        AND YEAR(f.fecha_timbrado) = :anio
        AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)
        GROUP BY MONTH(f.fecha_timbrado)");
        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $idEmpresa, ':anio' => $anioActual]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasAnioEmpleados()
    {
        $anioActual = date('Y');
        $idEmpresa = $_SESSION['IDEmpresa'];
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("SELECT
        SUM(f.total_facturado) AS ImporteTotal, CONCAT(e.Nombres, ' ', e.PrimerApellido) AS nombre
        FROM facturacion AS f
       	LEFT JOIN empleados AS e ON f.empleado_id = e.PKEmpleado
        WHERE f.empresa_id = :idEmpresa
        AND YEAR(f.fecha_timbrado) = :anio
        AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)
        GROUP BY f.empleado_id");
        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $idEmpresa, ':anio' => $anioActual]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasPorMesEmpleados($mes)
    {
        $mesAtual = $mes ? $mes : date('n');
        $anioActual = date('Y');
        $idEmpresa = $_SESSION['IDEmpresa'];
        $con = new conectar();
        $db = $con->getDb();
        $query = sprintf("SELECT
        SUM(f.total_facturado) AS ImporteTotal, CONCAT(e.Nombres, ' ', e.PrimerApellido) AS nombre
        FROM facturacion AS f
       	LEFT JOIN empleados AS e ON f.empleado_id = e.PKEmpleado
        WHERE f.empresa_id = :idEmpresa
        AND YEAR(f.fecha_timbrado) = :anio
        AND MONTH(f.fecha_timbrado) = :mes
        AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)
        GROUP BY f.empleado_id");
        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $idEmpresa, ':anio' => $anioActual, ':mes' => $mesAtual]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCumpleanios()
    {
        $con = new conectar();
        $db = $con->getDb();
        try {
            $query = 'SELECT Nombres, DAY(FechaNacimiento) as diaNac
            FROM empleados
            WHERE MONTH(FechaNacimiento) = :mes AND empresa_id = :idEmpresa
            ORDER BY DAY(FechaNacimiento)';
            $stmt = $db->prepare($query);
            if (!$stmt->execute([':mes' => date("n"), ':idEmpresa' => $_SESSION["IDEmpresa"]])) {
                throw new Exception("Algo salio mal");
            }
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCuentasPorCobrar($facturas = 1)
    {
        $con = new conectar();
        $db = $con->getDb();

        if ($facturas == 1) {
            $query = 'SELECT SUM(f.saldo_insoluto) AS total
            FROM facturacion AS f
            WHERE f.empresa_id = :idEmpresa AND (f.estatus = 1 OR f.estatus = 2)';
        } else {
            $query = 'SELECT SUM(vd.saldo_insoluto_venta) AS total
            FROM ventas_directas AS vd
            WHERE vd.empresa_id = :idEmpresa AND (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4 OR vd.estatus_factura_id = 5)';
        }

        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $_SESSION['IDEmpresa']]);
        $res =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'];
    }

    public function getCuentasPorPagar()
    {
        $con = new conectar();
        $db = $con->getDb();
        $query = 'SELECT SUM(cpp.saldo_insoluto) AS total
        FROM cuentas_por_pagar AS cpp
        LEFT JOIN sucursales AS s ON cpp.sucursal_id = s.id
        WHERE s.empresa_id = :idEmpresa AND cpp.estatus_factura != 5';
        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $_SESSION['IDEmpresa']]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'];
    }

    public function getVentasMesEmpleados()
    {
        $con = new conectar();
        $db = $con->getDb();
        $mesActual = date('n');
        $anioActual = date('Y');
        $query = "SELECT SUM(Ventas) as Ventas, Nombre FROM
        (
        (SELECT
                SUM(f.total_facturado) AS Ventas,
                CONCAT(e.Nombres, ' ', e.PrimerApellido) AS Nombre,
                f.empleado_id,
                f.total_facturado as total
                FROM facturacion AS f
                LEFT JOIN empleados AS e ON f.empleado_id = e.PKEmpleado 
                WHERE f.empresa_id = :idEmpresa 
                AND MONTH(f.fecha_timbrado) = :mes
                AND YEAR(f.fecha_timbrado) = :anio
                AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)
                GROUP BY f.empleado_id)
                
        UNION
        
        (SELECT
                SUM(vd.importe) AS Ventas,
                CONCAT(e.Nombres, ' ', e.PrimerApellido) AS Nombre,
                vd.empleado_id,
                vd.importe as total
                FROM ventas_directas AS vd
                LEFT JOIN empleados AS e ON vd.empleado_id = e.PKEmpleado 
                WHERE vd.empresa_id = :idEmpresa2 
                AND MONTH(vd.created_at) = :mes2
                AND YEAR(vd.created_at) = :anio1
                AND (vd.estatus_factura_id = 3 OR vd.estatus_factura_id = 4)
                GROUP BY vd.empleado_id)
        ) AS tablaReporte
                GROUP BY Nombre
                ORDER BY Ventas DESC LIMIT 15";
        $stmt = $db->prepare($query);
        $stmt->execute([':idEmpresa' => $_SESSION["IDEmpresa"], ':mes' => $mesActual, ':idEmpresa2' => $_SESSION["IDEmpresa"], ':mes2' => $mesActual, ':anio' => $anioActual, ':anio1' => $anioActual]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasMes($mes, $anio, $idEmpleado = 0, $is_facturado = 1)
    {
        $con = new conectar();
        $db = $con->getDb();
        $idEmpresa = $_SESSION["IDEmpresa"];

        if ($mes != 0) {
            if ($is_facturado == 1) {
                $query = "SELECT  SUM(f.total_facturado) AS Importe
                FROM facturacion AS f
                WHERE f.empresa_id = :idEmpresa
                AND MONTH(f.fecha_timbrado) = :mes
                AND YEAR(f.fecha_timbrado) = :anio 
                AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)";
                $values = [':idEmpresa' => $idEmpresa, ':mes' => $mes, ':anio' => $anio];
                if ($idEmpleado > 0) {
                    $query = "SELECT SUM(f.total_facturado) AS Importe
                    FROM facturacion AS f
                    WHERE f.empresa_id = :idEmpresa
                    AND MONTH(f.fecha_timbrado) = :mes
                    AND YEAR(f.fecha_timbrado) = :anio
                    AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)
                    AND f.empleado_id = :idEmpleado";
                    $values = [':idEmpresa' => $idEmpresa, ':mes' => $mes, ':anio' => $anio, ':idEmpleado' => $idEmpleado];
                }
            } else {
                $query = "SELECT  SUM(vd.importe) AS Importe
                FROM ventas_directas AS vd
                WHERE vd.empresa_id = :idEmpresa
                AND MONTH(vd.created_at) = :mes
                AND YEAR(vd.created_at) = :anio
                and vd.FKEstatusVenta != 2 and vd.FKEstatusVenta != 5 
                AND vd.estatus_factura_id in (3, 4)";
                $values = [':idEmpresa' => $idEmpresa, ':mes' => $mes, ':anio' => $anio];
            }
        } else {
            if ($is_facturado == 1) {
                $query = "SELECT  SUM(f.total_facturado) AS Importe
                FROM facturacion AS f
                WHERE f.empresa_id = :idEmpresa
                AND YEAR(f.fecha_timbrado) = :anio 
                AND (f.estatus = 1 OR f.estatus = 2 OR f.estatus = 3)";
                $values = [':idEmpresa' => $idEmpresa, ':anio' => $anio];
            } else {
                $query = "SELECT  SUM(vd.importe) AS Importe
                FROM ventas_directas AS vd
                WHERE vd.empresa_id = :idEmpresa
                AND YEAR(vd.created_at) = :anio 
                and vd.FKEstatusVenta != 2 and vd.FKEstatusVenta != 5
                AND vd.estatus_factura_id in (3, 4)";
                $values = [':idEmpresa' => $idEmpresa, ':anio' => $anio];
            }
        }

        $stmt = $db->prepare($query);
        $stmt->execute($values);
        $factMes = $stmt->fetch(PDO::FETCH_ASSOC);
        $ventasMes = $factMes['Importe'] ? $factMes['Importe'] : 0.00;
        return $ventasMes;
    }

    public function getRol()
    {
        $con = new conectar();
        $db = $con->getDb();
        $idUser = $_SESSION['PKUsuario'];
        $query = 'SELECT r.rol AS rol
        FROM usuarios AS u
        INNER JOIN roles AS r ON u.role_id = r.id
        WHERE u.id = :idUser';
        $stmt = $db->prepare($query);
        $stmt->execute([':idUser' => $idUser]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['rol'];
    }

    public function getWidgetsPermissions()
    {
        $con = new conectar();
        $db = $con->getDb();
        $idUser = $_SESSION['PKUsuario'];
        $query = 'SELECT wg.id AS widgetID, wg.name AS widgetName, pwg.Permiso AS permiso
        FROM usuarios AS u
        INNER JOIN permisos_widgets AS pwg ON u.id = pwg.FKUsuario
        INNER JOIN widgets AS wg ON pwg.FKWidget = wg.id
        WHERE u.id = :idUser';
        $stmt = $db->prepare($query);
        $stmt->execute([':idUser' => $idUser]);
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = [];
        foreach ($permissions as $permission) {
            $res[$permission['widgetName']] = $permission;
        }
        return $res;
    }
}

class set_data
{
    public function setNota($nota)
    {
        try {
            $idUser = $_SESSION['PKUsuario'];
            $con = new conectar();
            $db = $con->getDb();
            $query = 'UPDATE usuarios SET Notas = :nota WHERE id = :idUsuario';
            $stmt = $db->prepare($query);
            $stmt->execute([':nota' => $nota, ':idUsuario' => $idUser]);
            $rowCount = $stmt->rowCount();
            if ($rowCount > 0) {
                return "success";
            }
            return "fail";
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
