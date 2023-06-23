<?php

use Facturapi\Facturapi;

require_once '../../../vendor/facturapi/facturapi-php/src/Facturapi.php';

session_start();

class conectar
{ //Llamado al archivo de la conexión.

	function getDB()
	{
		include "../../../include/db-conn.php";
		return $conn;
	}
}

class get_data
{

	function getEmpleados($empresa_id)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf("SELECT emp.PKEmpleado, emp.Nombres, emp.PrimerApellido, emp.SegundoApellido, estCiv.EstadoCivil, emp.Genero, estFed.Estado, emp.Direccion, emp.Colonia, emp.CP, emp.Ciudad, emp.CURP, emp.RFC, emp.FechaNacimiento, emp.Telefono, emp.estatus, emp.empresa_id, datLabEmp.FechaIngreso, datLabEmp.Infonavit, datLabEmp.DeudaInterna, datLabEmp.DeudaRestante, turno.Turno, puesto.puesto, sucursal.sucursal, datMedEmp.NSS, datMedEmp.TipoSangre, datMedEmp.ContactoEmergencia, datMedEmp.NumeroEmergencia, datMedEmp.Alergias, datMedEmp.Notas, banco.Banco, datBancEmp.CuentaBancaria, datBancEmp.CLABE, datBancEmp.NumeroTarjeta
			FROM empleados AS emp
			LEFT JOIN datos_laborales_empleado AS datLabEmp ON emp.PKEmpleado = datLabEmp.FKEmpleado
			LEFT JOIN datos_medicos_empleado AS datMedEmp ON emp.PKEmpleado = datMedEmp.FKEmpleado
			LEFT JOIN datos_bancarios_empleado AS datBancEmp ON emp.PKEmpleado = datBancEmp.FKEmpleado
			LEFT JOIN estado_civil AS estCiv ON emp.FKEstadoCivil = estCiv.PKEstadoCivil
			LEFT JOIN estados_federativos AS estFed ON emp.FKEstado = estFed.PKEstado
			LEFT JOIN turnos AS turno ON datLabEmp.FKTurno = turno.PKTurno
			LEFT JOIN puestos AS puesto ON datLabEmp.FKPuesto = puesto.id
			LEFT JOIN sucursales AS sucursal ON datLabEmp.FKSucursal = sucursal.id
			LEFT JOIN bancos AS banco ON datBancEmp.FKBanco = banco.PKBanco
			WHERE emp.empresa_id = :empresa_id AND emp.is_generic = 0");
			$stmt = $db->prepare($query);
			$stmt->execute([':empresa_id' => $empresa_id]);
			$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
			


			//return json_encode($stmt->fetchAll(PDO::FETCH_OBJ));
			$table = "";

			foreach ($empleados as $empleado) {
				$nombre = str_replace('"', '\"', $empleado['Nombres']);
				$status = $empleado['estatus'] === 1 ? 'Activo' : 'Inactivo';
				$table .= '{"Acciones":"' . $empleado['PKEmpleado'] . '",
					"PKEmpleado":"' . $empleado['PKEmpleado'] . '",
					"Nombre":"' . $nombre . '",
					"PrimerApellido":"' . $empleado['PrimerApellido'] . '",
					"SegundoApellido":"' . $empleado['SegundoApellido'] . '",
					"EstadoCivil":"' . $empleado['EstadoCivil'] . '",
					"Genero": "' . $empleado['Genero'] . '",
					"Estado": "' . $empleado['Estado'] . '",
					"Direccion": "' . $empleado['Direccion'] . '",
					"Colonia": "' . $empleado['Colonia'] . '",
					"CP": "' . $empleado['CP'] . '",
					"Ciudad": "' . $empleado['Ciudad'] . '",
					"CURP": "' . $empleado['CURP'] . '",
					"RFC": "' . $empleado['RFC'] . '",
					"FechaNacimiento": "' . $empleado['FechaNacimiento'] . '",
					"Telefono": "' . $empleado['Telefono'] . '",
					"Estatus": "' . $status . '",
					"FechaIngreso": "' . $empleado['FechaIngreso'] . '",
					"Infonavit": "' . $empleado['Infonavit'] . '",
					"DeudaInterna": "' . $empleado['DeudaInterna'] . '",
					"DeudaRestante": "' . $empleado['DeudaRestante'] . '",
					"Turno": "' . $empleado['Turno'] . '",
					"Puesto": "' . $empleado['puesto'] . '",
					"Sucursal": "' . $empleado['sucursal'] . '",
					"Empresa": "' . $empleado['empresa_id'] . '",
					"NSS": "' . $empleado['NSS'] . '",
					"TipoSangre": "' . $empleado['TipoSangre'] . '",
					"ContactoEmergencia": "' . $empleado['ContactoEmergencia'] . '",
					"NumeroEmergencia": "' . $empleado['NumeroEmergencia'] . '",
					"Alergias": "' . trim($empleado['Alergias']) . '",
					"NotasMedicas": "' . trim($empleado['Notas']) . '",
					"Banco": "' . $empleado['Banco'] . '",
					"CuentaBancaria": "' . $empleado['CuentaBancaria'] . '",
					"Clabe": "' . $empleado['CLABE'] . '",
					"NumeroTarjeta":"' . $empleado['NumeroTarjeta'] . '"},';
			}
			$table = substr($table, 0, strlen($table) - 1);

			return '{"data":[' . $table . ']}';
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	//Lista las columnas del empleado
	function lista_columnas()
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf("SELECT col.PKColumnasEmp, typeColumn.Nombre, typeColumn.Tabla, typeColumn.Background, typeColumn.Descripcion, typeColumn.Logotipo, col.Seleccionada, col.FKTipoColumnaEmp, col.Orden FROM columnas_empleado AS col
													LEFT JOIN tipo_columna_emp AS typeColumn ON col.FKTipoColumnaEmp = typeColumn.PKTipoColumnaEmp");
			$stmt = $db->prepare($query);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}
	//Lista el orden de las columnas del empleado
	function orden_columnas()
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf("SELECT col.PKColumnasEmp, typeColumn.Nombre, typeColumn.Tabla, col.Seleccionada, col.FKTipoColumnaEmp, col.Orden FROM columnas_empleado AS col
													LEFT JOIN tipo_columna_emp AS typeColumn ON col.FKTipoColumnaEmp = typeColumn.PKTipoColumnaEmp WHERE Seleccionada = 1 ORDER BY Orden");
			$stmt = $db->prepare($query);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}

	function orden_datos($sort, $indice, $array)
	{
		$con = new conectar();
		$db = $con->getDb();
		$orden = '';
		$data = [];
		if ($sort === "0") {
			$orden = 'DESC';
		} else {
			$orden = 'ASC';
		}

		try {
			$query = sprintf("SELECT type.Tabla,type.ColumnaAfectadaEmp AS Columna FROM columnas_empleado AS col
													LEFT JOIN tipo_columna_emp AS type ON col.FKTipoColumnaEmp = type.PKTipoColumnaEmp
													WHERE PKColumnasEmp = ?");
			$stmt = $db->prepare($query);
			$stmt->execute(array($indice));
			$table = $stmt->fetch();

			if ($table['Columna'] !== 'FechaNacimiento') {
				$datos = $table['Columna'];
			} else {
				$datos = 'DATE_FORMAT(FechaNacimiento,"%%Y/%%m/%%d")';
			}

			if ($table['Columna'] !== 'FechaIngreso') {
				$datos = $table['Columna'];
			} else {
				$datos = 'DATE_FORMAT(FechaIngreso,"%%Y/%%m/%%d")';
			}

			//echo $datos;
			//DATE_FORMAT(FechaNacimiento,"%Y/%m/%%d") ORDER BY '.$table['Columna'].' '.$orden
			for ($i = 0; $i < count($array); $i++) {

				$query = sprintf("SELECT DISTINCT " . $array[$i][1] . ".FKEmpleado, " . $array[$i][1] . ".FKColumnasEmp, empleados.PKEmpleado, empleados.PKEmpleado, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido, estado_civil.EstadoCivil, empleados.Genero, empleados.Direccion, empleados.NumeroExterior, empleados.Interior, estados_federativos.Estado, empleados.Ciudad, empleados.Colonia, empleados.CP, empleados.CURP, empleados.RFC, empleados.FechaNacimiento, empleados.Telefono,
              datos_laborales_empleado.FechaIngreso, datos_laborales_empleado.Infonavit, datos_laborales_empleado.DeudaInterna, datos_laborales_empleado.DeudaRestante, turnos.Turno, puestos.Puesto, sucursales.Sucursal, datos_medicos_empleado.NSS, datos_medicos_empleado.TipoSangre, datos_medicos_empleado.ContactoEmergencia, datos_medicos_empleado.NumeroEmergencia, datos_medicos_empleado.Alergias, bancos.Banco, datos_bancarios_empleado.CuentaBancaria, datos_bancarios_empleado.CLABE, datos_bancarios_empleado.NumeroTarjeta, datos_medicos_empleado.Notas FROM " . $array[$i][1] . "
          LEFT JOIN empleados ON " . $array[$i][1] . ".FKEmpleado = empleados.PKEmpleado
          LEFT JOIN estados_federativos ON empleados.FKEstado = estados_federativos.PKEstado
          LEFT JOIN estado_civil ON empleados.FKEstadoCivil = estado_civil.PKEstadoCivil
          LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado
          LEFT JOIN turnos ON datos_laborales_empleado.FKTurno = turnos.PKTurno
          LEFT JOIN puestos ON datos_laborales_empleado.FKPuesto = puestos.id
          LEFT JOIN sucursales ON datos_laborales_empleado.FKSucursal = sucursales.id
          LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado
          LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado
          LEFT JOIN bancos ON datos_bancarios_empleado.FKBanco = bancos.PKBanco
          WHERE " . $array[$i][1] . ".FKColumnasEmp = ? AND empleados.estatus = 1 AND empleados.empresa_id = " . $_SESSION['IDEmpresa'] . " AND empleados.is_generic = 0 ORDER BY " . $datos . " " . $orden);
				//echo $query;
				$stmt = $db->prepare($query);
				$stmt->execute(array($array[$i][0]));
				$ordenEmpleados = $stmt->fetchAll(PDO::FETCH_OBJ);

				array_push($data, [$ordenEmpleados, $array[$i][2]]);
			}


			//array_push($general,[$array,$ordenEmpleados]);

			return $data;
			//return $array;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function info_columnas($array)
	{
		$con = new conectar();
		$db = $con->getDb();
		$data = [];
		try {

			for ($i = 0; $i < count($array); $i++) {

				$query = sprintf("SELECT DISTINCT " . $array[$i][1] . ".*, empleados.PKEmpleado, empleados.id_empleado, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido, estado_civil.EstadoCivil, empleados.Genero, empleados.Direccion, empleados.NumeroExterior, empleados.Interior, estados_federativos.Estado, empleados.Ciudad, empleados.Colonia, empleados.CP, empleados.CURP, empleados.RFC, empleados.FechaNacimiento, empleados.Telefono,
														datos_laborales_empleado.FechaIngreso, datos_laborales_empleado.Infonavit, datos_laborales_empleado.DeudaInterna, datos_laborales_empleado.DeudaRestante, turnos.Turno, puestos.Puesto, sucursales.Sucursal, datos_medicos_empleado.NSS, datos_medicos_empleado.TipoSangre, datos_medicos_empleado.ContactoEmergencia, datos_medicos_empleado.NumeroEmergencia, datos_medicos_empleado.Alergias, bancos.Banco, datos_bancarios_empleado.CuentaBancaria, datos_bancarios_empleado.CLABE, datos_bancarios_empleado.NumeroTarjeta, datos_medicos_empleado.Notas FROM " . $array[$i][1] . "
														LEFT JOIN empleados ON " . $array[$i][1] . ".FKEmpleado = empleados.PKEmpleado
														LEFT JOIN estados_federativos ON empleados.FKEstado = estados_federativos.PKEstado
														LEFT JOIN estado_civil ON empleados.FKEstadoCivil = estado_civil.PKEstadoCivil
														LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado
														LEFT JOIN turnos ON datos_laborales_empleado.FKTurno = turnos.PKTurno
														LEFT JOIN puestos ON datos_laborales_empleado.FKPuesto = puestos.id
														LEFT JOIN sucursales ON datos_laborales_empleado.FKSucursal = sucursales.id
														LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado
														LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado
														LEFT JOIN bancos ON datos_bancarios_empleado.FKBanco = bancos.PKBanco
														WHERE empleados.estatus = 1 AND empleados.empresa_id = " . $_SESSION['IDEmpresa'] . " AND empleados.is_generic = 0 ORDER BY empleados.PKEmpleado DESC");
				//echo "  /// ".$array[$i][1];
				$stmt = $db->prepare($query);
				$stmt->execute();
				$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

				//echo "<br><br>/////////////////////";
				//print_r($lista);

				array_push($data, [$lista, $array[$i][2]]);
			}
			//var_dump($data);
			return $data;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}

	function selectCivilState()
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf('SELECT * FROM estado_civil');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $lista;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
		$stmt = NULL;
		$db = NULL;
	}

	function selectStatus()
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf('SELECT * FROM estatus_empleado');
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

			return $lista;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
		$stmt = NULL;
		$db = NULL;
	}

	function getEmployers()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM empleados');
		$stmt = $db->prepare($query);
		$stmt->execute();
		$array = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $array;
	}

	function getEmployer($data)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM empleados WHERE PKEmpleado = ?');
		$stmt = $db->prepare($query);
		$stmt->execute(array($data));
		$array = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $array;
	}

	function getId()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT PKEmpleado FROM `empleados`");
		$stmt = $db->prepare($query);
		$stmt->execute();

		return $id = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getDataLaboral($data)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT empleados.PKEmpleado,empleados.Nombres,empleados.PrimerApellido,empleados.SegundoApellido, datos_laborales_empleado.* FROM datos_laborales_empleado RIGHT JOIN empleados ON datos_laborales_empleado.FKEmpleado = empleados.PKEmpleado WHERE empleados.PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($data));
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getTurns()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT PKTurno,Turno FROM turnos WHERE estatus = 1 AND empresa_id = ' . $_SESSION['IDEmpresa']);
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getFormasPago()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id, clave, descripcion FROM formas_pago_sat');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getLocation()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id,sucursal FROM sucursales WHERE empresa_id = ' . $_SESSION['IDEmpresa']);
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getJobPositions()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id,puesto FROM puestos WHERE empresa_id = ' . $_SESSION['IDEmpresa']);
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getContractType()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id,tipo_contrato FROM tipo_contrato');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getAreaDepartamento()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id, nombre FROM areaDepartamento WHERE empresa_id = 1 OR empresa_id = :empresa_id');
		$stmt = $db->prepare($query);
		$stmt->execute([':empresa_id' => $_SESSION['IDEmpresa']]);
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getRiskPosition()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id,riesgo_puesto FROM riesgo_puesto');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getRegimeType()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT id, tipo_regimen FROM tipo_regimen');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getPeriods()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT PKPeriodo_pago,Periodo FROM periodo_pago');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getCompany()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT PKEmpresa,NombreComercial FROM empresas');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getDataMedical($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT empleados.PKEmpleado,empleados.Nombres,empleados.PrimerApellido,empleados.SegundoApellido, datos_medicos_empleado.* FROM datos_medicos_empleado RIGHT JOIN empleados ON datos_medicos_empleado.FKEmpleado = empleados.PKEmpleado WHERE empleados.PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getDataBank($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT empleados.PKEmpleado,empleados.Nombres,empleados.PrimerApellido,empleados.SegundoApellido, bancos.Banco, datos_bancarios_empleado.* FROM datos_bancarios_empleado INNER JOIN bancos ON datos_bancarios_empleado.FKBanco = bancos.PKBanco RIGHT JOIN empleados ON datos_bancarios_empleado.FKEmpleado = empleados.PKEmpleado WHERE empleados.PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getDatosRolesEmpleados($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT rte.id as datos_rol_id, te.tipo, e.Nombres, e.PrimerApellido, e.SegundoApellido FROM empleados as e LEFT JOIN relacion_tipo_empleado as rte ON e.PKEmpleado = rte.empleado_id LEFT JOIN tipo_empleado as te ON te.id = rte.tipo_empleado_id WHERE e.PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getDatatablesRolesEmpleados($idEmpleado)
	{
		$con = new conectar();
		$db = $con->getDb();
		$table = "";
		//echo "el id es: ".$id;
		$query = sprintf("SELECT rte.id as datos_rol_id, te.tipo FROM relacion_tipo_empleado as rte INNER JOIN tipo_empleado as te ON te.id = rte.tipo_empleado_id WHERE rte.empleado_id = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($idEmpleado));
		$array = $stmt->fetchAll();

		//print_r($array);

		foreach ($array as $r) {
			$id = $r['datos_rol_id'];
			$rol = $r['tipo'];

			$etiquetaI = '<label class=\"textTable\">';
			$etiquetaF = '</label>';
			$acciones = '<i class=\"fas fa-trash-alt pointer\" onclick=\"eliminarRol(' . $id . ');\"></i>';

			$table .= '{"Id":"' . $id . '",
                "Acciones":"' . $acciones . '",
                "Rol":"' .  $rol . '"},';
		}
		$table = substr($table, 0, strlen($table) - 1);

		return '{"data":[' . $table . ']}';
	}

	function getFedStates()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM estados_federativos');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getTaxRegime()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM claves_regimen_fiscal WHERE id <> 6 AND id <> 17 AND id <> 19 AND id <> 20 ORDER BY clave');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getBanks()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM bancos');
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getRolesEmpleados()
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf('SELECT * FROM tipo_empleado WHERE empresa_id = 1 OR empresa_id = ' . $_SESSION['IDEmpresa']);
		$stmt = $db->prepare($query);
		$stmt->execute();
		return $array = $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	function getName($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Nombres FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$nombre = $stmt->fetch();
		return $nombre['Nombre'];

		$stmt = NULL;
		$db = NULL;
	}

	function getNameComplete($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Nombres,PrimerApellido,SegundoApellido FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$nombre = $stmt->fetch();
		return $nombre['Nombres'] . " " . $nombre['PrimerApellido'] . " " . $nombre['SegundoApellido'];

		$stmt = NULL;
		$db = NULL;
	}

	function getFirstLastName($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Apellido_Paterno FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto = $stmt->fetch();
		return $texto['Apellido_Paterno'];

		$stmt = NULL;
		$db = NULL;
	}

	function getSecondLastName($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Apellido_Materno FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Apellido_Materno'];

		$stmt = NULL;
		$db = NULL;
	}

	function getMaritalStatus($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Estado_Civil FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Estado_Civil'];

		$stmt = NULL;
		$db = NULL;
	}

	function getGender($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Genero FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Sexo'];

		$stmt = NULL;
		$db = NULL;
	}

	function getAddress($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT CONCAT(Direccion,' ',Numero_Exterior,' Int ',Numero_Interior) AS direccion FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['direccion'];

		$stmt = NULL;
		$db = NULL;
	}
	function getState($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Estado FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Estado'];

		$stmt = NULL;
		$db = NULL;
	}

	function getCity($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Ciudad FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Ciudad'];

		$stmt = NULL;
		$db = NULL;
	}

	function getColony($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Colonia FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Colonia'];

		$stmt = NULL;
		$db = NULL;
	}

	function getCP($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT CP FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['CP'];

		$stmt = NULL;
		$db = NULL;
	}

	function getCurp($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT CURP FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['CURP'];

		$stmt = NULL;
		$db = NULL;
	}

	function getRfc($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT RFC FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['RFC'];

		$stmt = NULL;
		$db = NULL;
	}

	function getBirth($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Fecha_de_Nacimiento FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		$fecha = date("d/m/Y", strtotime($texto1['Fecha_de_Nacimiento']));
		return $fecha;

		$stmt = NULL;
		$db = NULL;
	}

	function getTelephone($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT Telefono FROM `empleados` WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		return $texto1['Telefono'];

		$stmt = NULL;
		$db = NULL;
	}

	function getStatus($id)
	{
		$con = new conectar();
		$db = $con->getDb();

		$query = sprintf("SELECT status.Estatus_Empleado AS Estatus FROM `empleados` AS emp
												LEFT JOIN estatus_empleado AS status ON emp.FKEstatus = status.PKEstatusEmpleado
												WHERE PKEmpleado = ?");
		$stmt = $db->prepare($query);
		$stmt->execute(array($id));
		$texto1 = $stmt->fetch();
		if ($texto1['Estatus'] != "" || $texto1['Estatus'] != null) {
			return $texto1['Estatus'];
		} else {
			return "Sin estatus";
		}


		$stmt = NULL;
		$db = NULL;
	}

	function getEmpleadosBaja()
	{
		$con = new conectar();
		$db = $con->getDb();
		$table = "";

		$query = sprintf('SELECT PKEmpleado, Nombres, PrimerApellido, SegundoApellido, FechaBaja FROM empleados WHERE estatus = 0 AND empresa_id =' . $_SESSION['IDEmpresa']);
		$stmt = $db->prepare($query);
		$stmt->execute();
		$array = $stmt->fetchAll();

		foreach ($array as $r) {
			$id = $r['PKEmpleado'];
			$nombres = $r['Nombres'];
			$primerapellido = $r['PrimerApellido'];
			$segundoapellido = $r['SegundoApellido'];
			$fechabaja = $r['FechaBaja'];

			$acciones = '<i class=\"permission-view-edit\"><img class=\"btnEdit\" data-toggle=\"modal\" data-target=\"#modalEditar\" onclick=\"obtenerIdSucursalEditar(' . $id . ');\" src=\"../../img/timdesk/edit.svg\"></i>';

			$table .= '
                    {"id":"' . $id . '",
                  "Nombres":"' . $nombres . '",
                  "PrimerApellido":"' . $primerapellido . '",
                  "SegundoApellido":"' . $segundoapellido . '",
                  "FechaBaja":"' . $fechabaja . $acciones . '"},';
		}

		$table = substr($table, 0, strlen($table) - 1);

		return '{"data":[' . $table . ']}';
	}
}

class data_order
{
	function columnOrder($array)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			for ($i = 0; $i < count($array); $i++) {
				if ($array[$i] != 1) {
					$update = sprintf("UPDATE columnas_empleado SET Orden = ? WHERE PKColumnasEmp = ?");
					$stmt = $db->prepare($update);
					$stmt->execute(array($i + 1, $array[$i]));
				}
			}

			return "ok";
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}
}

class update_data
{
	function update_check_column($id_column, $flag)
	{
		$con = new conectar();
		$db = $con->getDb();
		try {
			if ($flag === "0") {
				$update = sprintf("UPDATE columnas_empleado SET Seleccionada = ?, Orden = 0 WHERE PKColumnasEmp = ?");
				$stmt = $db->prepare($update);
				$stmt->execute(array($flag, $id_column));
				return "ok";
			} else {
				$array = [];
				//SET orden
				$orden = sprintf("SELECT Orden FROM columnas_empleado ORDER BY Orden DESC");
				$stmt = $db->prepare($orden);
				$stmt->execute();
				$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$mayor = $data[0]["Orden"];
				$set_orden = $mayor + 1;

				$update = sprintf("UPDATE columnas_empleado SET Seleccionada = ?, Orden = ? WHERE PKColumnasEmp = ?");
				$stmt = $db->prepare($update);
				$stmt->execute(array($flag, $set_orden, $id_column));

				//Send column
				$column = sprintf("SELECT FKTipoColumnaEmp FROM columnas_empleado WHERE PKColumnasEmp = ?");
				$stmt = $db->prepare($column);
				$stmt->execute(array($id_column));
				$columna = $stmt->fetch(PDO::FETCH_ASSOC);

				$table = sprintf("SELECT Tabla,Nombre FROM tipo_columna_emp WHERE PKTipoColumnaEmp = ?");
				$stmt = $db->prepare($table);
				$stmt->execute(array($columna["FKTipoColumnaEmp"]));
				$tabla = $stmt->fetch(PDO::FETCH_ASSOC);

				$consulta = sprintf("SELECT * FROM " . $tabla["Tabla"] . " LEFT JOIN empleados ON " . $tabla["Tabla"] . ".FKEmpleado = empleados.PKEmpleado");
				$stmt = $db->prepare($consulta);
				$stmt->execute();

				array_push($array, [$stmt->fetchAll(PDO::FETCH_OBJ), $columna["FKTipoColumnaEmp"], $tabla["Nombre"], $tabla["Tabla"]]);

				return $array;
			}
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}

	function editar_elemento($id_elemento, $tipo)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			//Tabla
			$tabla = sprintf("SELECT Tabla, Llave FROM tipo_columna_emp WHERE PKTipoColumnaEmp = ?");
			$stmt = $db->prepare($tabla);
			$stmt->execute(array($tipo));
			$table = $stmt->fetch(PDO::FETCH_ASSOC);

			$editar = sprintf("UPDATE " . $table["Tabla"] . " SET Texto = ? WHERE " . $table["Llave"] . "");
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}

	function update_personalData($array)
	{
		$con = new conectar();
		$db = $con->getDb();

		$fechaNacimiento = '';
		$estatus_api = '';

		try {
			$query = sprintf('UPDATE empleados SET Nombres=?, PrimerApellido=?, SegundoApellido=?, FKEstadoCivil=?, Telefono=?, CURP=?, RFC=?, FechaNacimiento=?, Genero=?, Direccion=?, NumeroExterior=?, Interior=?, Colonia=?, CP=?, Ciudad=?, FKEstado=?, email=?, claves_regimen_fiscal_id = ? WHERE PKEmpleado=?');

			if ($array[8]['datos'] == null || $array[8]['datos'] == '' || $array[8]['datos'] == 0) {
				$fechaNacimiento = '0000-00-00';
			} else {
				$fechaNacimiento = $array[8]['datos'];
			}

			//echo $query;
			$stmt = $db->prepare($query);
			//return false;
			//$stmt->execute(array($nombres,$primerApellido,$segundoApellido,$estadoCivil,$telefono,$curp,$rfc,$fecha,$genero,$calle,$numeroExterior,$interior,$colonia,$cp,$ciudad,$estado,$estatus,$id));
			$stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[9]['datos'], strtoupper($array[6]['datos']), strtoupper($array[7]['datos']), $fechaNacimiento, $array[5]['datos'], $array[11]['datos'], $array[12]['datos'], $array[13]['datos'], $array[14]['datos'], $array[15]['datos'], $array[16]['datos'], $array[17]['datos'], $array[10]['datos'], $array[18]['datos'], $array[0]['datos']));
			$idLast = $db->lastInsertId();

			$query = sprintf("select idtimbrado from empleados where PKEmpleado = :id");
			$stmt = $db->prepare($query);
			$stmt->bindValue(":id", $array[0]['datos']);
			$stmt->execute();
			$idTimbrado = $stmt->fetch();

			//si no se ha dado de alta el usuario en la api
			if (trim($idTimbrado['idtimbrado']) == "") {

				if (trim($array[7]['datos']) != "") {

					if ($array[18]['datos'] > 0) {

						$query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal from empresas where PKEmpresa = :id");
						$stmt = $db->prepare($query);
						$stmt->bindValue(":id", $_SESSION['IDEmpresa']);
						$stmt->execute();
						$key_company_api = $stmt->fetchAll();

						$facturapi = new Facturapi($key_company_api[0]['key_company']);

						$segundoapellido = "";
						if (trim($array[3]['datos']) != "") {
							$segundoapellido = " " . trim($array[3]['datos']);
						}
						$nombre_empleado = trim($array[1]['datos']) . " " . trim($array[2]['datos']) . $segundoapellido;


						$query = sprintf("select clave from claves_regimen_fiscal where id = :id");
						$stmt = $db->prepare($query);
						$stmt->bindValue(":id", $array[18]['datos']);
						$stmt->execute();
						$clave_regimen_fiscal = $stmt->fetch();
						$tax_system = $clave_regimen_fiscal['clave'];

						$direccion = [
							"zip" => $array[15]['datos']
						];

						$email = trim($array[10]['datos']);
						if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$email = "";
						}

						if ($email == "") {
							$cliente = $facturapi->Customers->create(array(
								"legal_name" => $nombre_empleado,
								"tax_id" => trim($array[7]['datos']),
								"tax_system" => $tax_system,
								"address" => $direccion
							));
						} else {
							$cliente = $facturapi->Customers->create(array(
								"email" => $email,
								"legal_name" => $nombre_empleado,
								"tax_id" => trim($array[7]['datos']),
								"tax_system" => $tax_system,
								"address" => $direccion
							));
						}

						//print_r($cliente);
						if (isset($cliente->id)) {
							$query = sprintf('UPDATE empleados SET idtimbrado = ? WHERE PKEmpleado = ?');
							$stmt = $db->prepare($query);
							$stmt->execute(array($cliente->id, $array[0]['datos']));
							$estatus_api = 'exito';
						} else {
							if (isset($cliente->path)) {
								if ($cliente->path == 'legal_name') {
									$estatus_api = 'fallo-legal'; //nombre no coincide con rfc
								} elseif ($cliente->path == 'tax_id') {
									$estatus_api = 'fallo-rfc'; //rfc invalido
								} elseif ($cliente->path == 'tax_system') {
									$estatus_api = 'fallo-system'; //se manda mal el regimen fiscal
								} elseif ($cliente->path == 'address.zip') {
									$estatus_api = 'fallo-zip'; //zip invalido, no coincide con rfc
								}
							}
						}
					}
				}
			} else {
				if (trim($array[7]['datos']) != "") {

					if ($array[18]['datos'] > 0) {

						$query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal from empresas where PKEmpresa = :id");
						$stmt = $db->prepare($query);
						$stmt->bindValue(":id", $_SESSION['IDEmpresa']);
						$stmt->execute();
						$key_company_api = $stmt->fetchAll();

						$facturapi = new Facturapi($key_company_api[0]['key_company']);

						$segundoapellido = "";
						if (trim($array[3]['datos']) != "") {
							$segundoapellido = " " . trim($array[3]['datos']);
						}
						$nombre_empleado = trim($array[1]['datos']) . " " . trim($array[2]['datos']) . $segundoapellido;

						$query = sprintf("select clave from claves_regimen_fiscal where id = :id");
						$stmt = $db->prepare($query);
						$stmt->bindValue(":id", $array[18]['datos']);
						$stmt->execute();
						$clave_regimen_fiscal = $stmt->fetch();
						$tax_system = $clave_regimen_fiscal['clave'];

						$direccion = [
							"zip" => $array[15]['datos']
						];

						if (trim($array[10]['datos']) == "") {
							$cliente = $facturapi->Customers->update(trim($idTimbrado['idtimbrado']), array(
								"legal_name" => $nombre_empleado,
								"tax_id" => trim($array[7]['datos']),
								"tax_system" => $tax_system,
								"address" => $direccion
							));
						} else {
							$cliente = $facturapi->Customers->update(trim($idTimbrado['idtimbrado']), array(
								"email" => $array[10]['datos'],
								"legal_name" => $nombre_empleado,
								"tax_id" => trim($array[7]['datos']),
								"tax_system" => $tax_system,
								"address" => $direccion
							));
						}

						//print_r($cliente);



					}
				}
			}

			/*if(isset($cliente->id)){
				$query = sprintf('UPDATE empleados SET idtimbrado = ? WHERE PKEmpleado = ?');
				$stmt = $db->prepare($query);
				$stmt->execute(array($cliente->id, $array[0]['datos']));
				$estatus_api = 'exito';
			}
			else{
				if(isset($cliente->path)){
					if($cliente->path == 'legal_name'){
						$estatus_api = 'fallo-legal'; //nombre no coincide con rfc
					}
					elseif($cliente->path == 'tax_id'){
						$estatus_api = 'fallo-rfc'; //rfc invalido
					}
					elseif($cliente->path == 'tax_system'){
						$estatus_api = 'fallo-system'; //se manda mal el regimen fiscal
					}							 
					elseif($cliente->path == 'address.zip'){
						$estatus_api = 'fallo-zip'; //zip invalido, no coincide con rfc
					}
					 
				}
			}*/

			$data[0] = ['status' => $idLast, 'estatus_api' => $estatus_api];

			return $data;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function update_laboralData($datos)
	{
		$con = new conectar();
		$db = $con->getDb();
		$id = "";

		try {

			$query = sprintf('SELECT * FROM datos_laborales_empleado WHERE FKEmpleado = ?');
			$stmt = $db->prepare($query);
			$stmt->execute(array($datos['txtIdEmpleado']));
			$rowCount = $stmt->rowCount();

			if ($rowCount > 0) {
				$query = sprintf('UPDATE datos_laborales_empleado SET FechaIngreso = ?,Infonavit = ?,DeudaInterna = ?,FKTurno = ?,FKPuesto = ?,FKSucursal = ?,FKPeriodo = ?,Sueldo = ?, FKTipoContrato = ?, FKRiesgoPuesto = ?,FKRegimen = ?, FechaInicioVacaciones = ?, BaseCotizacion = ?, SalarioDiario = ?, SalarioBaseCotizacionFijo = ?, SalarioBaseCotizacionVariable = ?, idAreaDepartamento = ?, FKFormaPago = ?, Sindicalizado = ?, TipoPrestacion = ?, Confidencial = ? WHERE FKEmpleado = ?');
				$stmt = $db->prepare($query);
				$stmt->execute(array($datos['txtfechaIngreso'], $datos['txtInfonavit'], $datos['txtDeuda'], $datos['cmbTurno'], $datos['cmbPuesto'], $datos['cmbLocacion'], $datos['cmbPeriodo'], $datos['txtSueldo'], $datos['cmbTipoContrato'], $datos['cmbRiesgoPuesto'], $datos['cmbRegimen'], $datos['fechaInicio'], $datos['cmbBaseCotizacion'], $datos['txtSueldoDiario'], $datos['txtSalarioBaseCotizacionFijo'], $datos['txtSalarioBaseCotizacionVariable'], $datos['cmbAreaDepartamento'] , $datos['cmbFormaPago'], $datos['rSindicalizado'], $datos['rTipoPrestacion'], $datos['cboxNominaConfidencialJS'], $datos['txtIdEmpleado']));
			} else {
				$query = sprintf('INSERT INTO datos_laborales_empleado (FechaIngreso,Infonavit,DeudaInterna,DeudaRestante,FKTurno,FKPuesto,FKSucursal,FKEmpleado,FKPeriodo,Sueldo,FKTipoContrato, FKRiesgoPuesto, FKRegimen, FechaInicioVacaciones, BaseCotizacion, SalarioDiario, SalarioBaseCotizacionFijo, SalarioBaseCotizacionVariable, idAreaDepartamento, FKFormaPago, Sindicalizado, TipoPrestacion, Confidencial) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
				$stmt = $db->prepare($query);
				$stmt->execute(array($datos['txtfechaIngreso'], $datos['txtInfonavit'], $datos['txtDeuda'], 0.00, $datos['cmbTurno'], $datos['cmbPuesto'], $datos['cmbLocacion'], $datos['txtIdEmpleado'], $datos['cmbPeriodo'], $datos['txtSueldo'], $datos['cmbTipoContrato'], $datos['cmbRiesgoPuesto'], $datos['cmbRegimen'], $datos['fechaInicio'], $datos['cmbBaseCotizacion'], $datos['txtSueldoDiario'], $datos['txtSalarioBaseCotizacionFijo'], $datos['txtSalarioBaseCotizacionVariable'], $datos['cmbAreaDepartamento'], $datos['cmbFormaPago'], $datos['rSindicalizado'], $datos['rTipoPrestacion'], $datos['cboxNominaConfidencialJS']));
			}
			$id = $datos['txtIdEmpleado'];
			return $id;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function update_medicalData($array)
	{
		$con = new conectar();
		$db = $con->getDb();
		$id = "";

		try {

			$query = sprintf('SELECT * FROM datos_medicos_empleado WHERE FKEmpleado = ?');
			$stmt = $db->prepare($query);
			$stmt->execute(array($array[0]['datos']));
			$rowCount = $stmt->rowCount();

			if ($rowCount > 0) {
				$query = sprintf('UPDATE datos_medicos_empleado SET NSS=?,TipoSangre=?,ContactoEmergencia=?,NumeroEmergencia=?,Alergias=?,Notas=?,Donador = ? WHERE FKEmpleado =?');
				$stmt = $db->prepare($query);
				$stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[5]['datos'], $array[6]['datos'], $array[7]['datos'], $array[0]['datos']));
			} else {
				$query = sprintf('INSERT INTO datos_medicos_empleado (NSS,TipoSangre,ContactoEmergencia,NumeroEmergencia,Alergias,Notas,Donador,FKEmpleado)
														VALUES (?,?,?,?,?,?,?,?)');
				$stmt = $db->prepare($query);
				$status = $stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[5]['datos'], $array[6]['datos'], $array[7]['datos'], $array[0]['datos']));
			}
			$id = $array[0]['datos'];
			return $id;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function update_bancariosData($array)
	{
		$con = new conectar();
		$db = $con->getDb();
		$id = "";

		try {

			$query = sprintf('SELECT * FROM datos_bancarios_empleado WHERE FKEmpleado = ?');
			$stmt = $db->prepare($query);
			$stmt->execute(array($array[0]['datos']));
			$rowCount = $stmt->rowCount();

			if ($rowCount > 0) {
				$query = sprintf('UPDATE datos_bancarios_empleado SET FKBanco=?,CuentaBancaria=?,CLABE=?,NumeroTarjeta=? WHERE FKEmpleado =?');
				$stmt = $db->prepare($query);
				$stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[0]['datos']));
			} else {
				$query = sprintf('INSERT INTO datos_bancarios_empleado (FKBanco,CuentaBancaria,CLABE,NumeroTarjeta,FKEmpleado)
														VALUES (?,?,?,?,?)');
				$stmt = $db->prepare($query);
				$status = $stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[0]['datos']));
			}
			$id = $array[0]['datos'];
			return $id;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}
}

class buscar_data
{
	function searchEmployer($inputValue, $array)
	{
		$con = new conectar();
		$db = $con->getDb();
		$data = [];
		$lista = [];
		$data2 = [];
		$id = [];
		$aux = [];
		$tipo = [];
		try {

			$auxNacimiento = date("Y-m-d", strtotime($inputValue));

			$busqueda = sprintf("SELECT empleados.PKEmpleado FROM empleados
														 LEFT JOIN estados_federativos ON empleados.FKEstado = estados_federativos.PKEstado
														 LEFT JOIN estado_civil ON empleados.FKEstadoCivil = estado_civil.PKEstadoCivil
														 LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado
														 LEFT JOIN turnos ON datos_laborales_empleado.FKTurno = turnos.PKTurno
														 LEFT JOIN puestos ON datos_laborales_empleado.FKPuesto = puestos.id
														 LEFT JOIN sucursales ON datos_laborales_empleado.FKSucursal = sucursales.id
														 LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado
														 WHERE ((empleados.PKEmpleado LIKE ? OR empleados.Nombres LIKE ? OR empleados.PrimerApellido LIKE ? OR empleados.SegundoApellido LIKE ? OR estado_civil.EstadoCivil LIKE ? OR empleados.Genero LIKE ? OR empleados.Direccion LIKE ? OR estados_federativos.Estado LIKE ? OR empleados.Ciudad LIKE ? OR empleados.Colonia LIKE ? OR empleados.CP LIKE ? OR empleados.CURP LIKE ? OR empleados.RFC LIKE ? OR DATE_FORMAT(empleados.FechaNacimiento, '%%d/%%m/%%Y') LIKE ? OR DATE_FORMAT(empleados.FechaNacimiento, '%%d-%%m-%%Y') LIKE ? OR DATE_FORMAT(empleados.FechaNacimiento, '%%Y-%%m-%%d') LIKE ? OR DATE_FORMAT(empleados.FechaNacimiento, '%%Y/%%m/%%d') LIKE ? OR empleados.Telefono LIKE ? OR DATE_FORMAT(datos_laborales_empleado.FechaIngreso, '%%Y/%%m/%%d') LIKE ? OR datos_laborales_empleado.Infonavit LIKE ? OR datos_laborales_empleado.DeudaInterna LIKE ? OR
														 	datos_laborales_empleado.DeudaRestante LIKE ? OR turnos.Turno LIKE ? OR puestos.Puesto LIKE ? OR sucursales.Sucursal LIKE ? OR datos_medicos_empleado.NSS LIKE ? OR datos_medicos_empleado.ContactoEmergencia LIKE ? OR datos_medicos_empleado.NumeroEmergencia LIKE ? OR datos_medicos_empleado.Alergias LIKE ? OR datos_medicos_empleado.Notas LIKE ?) AND empleados.estatus = 1 AND empleados.empresa_id = ? AND empleados.is_generic = 0) ORDER BY empleados.PKEmpleado DESC");

			$stmt = $db->prepare($busqueda);
			$stmt->execute(array("%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", "%" . $inputValue . "%", $_SESSION['IDEmpresa']));
			$noSearch = $stmt->rowCount();

			while ($row = $stmt->fetch()) {
				array_push($id, $row['PKEmpleado']);
			}


			for ($i = 0; $i < count($array); $i++) {
				//echo $array[$i][0]." -- ".$array[$i][1]." -- ".$array[$i][2]."<br> /////";
				$busqueda = sprintf("SELECT " . $array[$i][1] . ".FKEmpleado, " . $array[$i][1] . ".FKColumnasEmp, empleados.PKEmpleado, empleados.PKEmpleado, empleados.Nombres, empleados.PrimerApellido, empleados.SegundoApellido, estado_civil.EstadoCivil, empleados.Genero, empleados.Direccion, empleados.NumeroExterior, empleados.Interior, estados_federativos.Estado, empleados.Ciudad, empleados.Colonia, empleados.CP, empleados.CURP, empleados.RFC, empleados.FechaNacimiento, empleados.Telefono, datos_bancarios_empleado.CLABE,
																	 datos_laborales_empleado.FechaIngreso, datos_laborales_empleado.Infonavit, datos_laborales_empleado.DeudaInterna, datos_laborales_empleado.DeudaRestante, turnos.Turno, puestos.Puesto, sucursales.Sucursal, datos_medicos_empleado.NSS, datos_medicos_empleado.TipoSangre, datos_medicos_empleado.ContactoEmergencia, datos_medicos_empleado.NumeroEmergencia, datos_medicos_empleado.Alergias,
																	 datos_medicos_empleado.Notas FROM " . $array[$i][1] . "
																	 LEFT JOIN empleados ON " . $array[$i][1] . ".FKEmpleado = empleados.PKEmpleado
																	 LEFT JOIN estados_federativos ON empleados.FKEstado = estados_federativos.PKEstado
																	 LEFT JOIN estado_civil ON empleados.FKEstadoCivil = estado_civil.PKEstadoCivil
																	 LEFT JOIN datos_laborales_empleado ON empleados.PKEmpleado = datos_laborales_empleado.FKEmpleado
																	 LEFT JOIN turnos ON datos_laborales_empleado.FKTurno = turnos.PKTurno
																	 LEFT JOIN sucursales ON datos_laborales_empleado.FKSucursal = sucursales.id
																	 LEFT JOIN puestos ON datos_laborales_empleado.FKPuesto = puestos.id
																	 LEFT JOIN datos_medicos_empleado ON empleados.PKEmpleado = datos_medicos_empleado.FKEmpleado
																	 LEFT JOIN datos_bancarios_empleado ON empleados.PKEmpleado = datos_bancarios_empleado.FKEmpleado
																	 WHERE " . $array[$i][1] . ".FKColumnasEmp = ?  AND empleados.estatus = 1 AND empleados.empresa_id = " . $_SESSION['IDEmpresa']);

				$stmt = $db->prepare($busqueda);

				$stmt->execute(array($array[$i][0]));
				$lista = $stmt->fetchAll(PDO::FETCH_OBJ);

				array_push($data, $lista);
			}

			for ($i = 0; $i < count($id); $i++) {

				for ($j = 0; $j < count($lista); $j++) {
					if ($lista[$j]->PKEmpleado === $id[$i]) {

						$aux[$i] = [
							"FKEmpleado" => $lista[$j]->FKEmpleado,
							"FKColumnasEmp" => $lista[$j]->FKColumnasEmp,
							"PKEmpleado" => $lista[$j]->PKEmpleado,
							"Nombres" => $lista[$j]->Nombres,
							"PrimerApellido" => $lista[$j]->PrimerApellido,
							"SegundoApellido" => $lista[$j]->SegundoApellido,
							"EstadoCivil" => $lista[$j]->EstadoCivil,
							"Genero" => $lista[$j]->Genero,
							"Direccion" => $lista[$j]->Direccion,
							"Interior" => $lista[$j]->Interior,
							"NumeroExterior" => $lista[$j]->NumeroExterior,
							"Estado" => $lista[$j]->Estado,
							"Ciudad" => $lista[$j]->Ciudad,
							"Colonia" => $lista[$j]->Colonia,
							"CP" => $lista[$j]->CP,
							"CURP" => $lista[$j]->CURP,
							"RFC" => $lista[$j]->RFC,
							"FechaNacimiento" => $lista[$j]->FechaNacimiento,
							"Telefono" => $lista[$j]->Telefono,
							"FechaIngreso" => $lista[$j]->FechaIngreso,
							"Infonavit" => $lista[$j]->Infonavit,
							"DeudaInterna" => $lista[$j]->DeudaInterna,
							"DeudaRestante" => $lista[$j]->DeudaRestante,
							"Puesto" => $lista[$j]->Puesto,
							"Turno" => $lista[$j]->Turno,
							"Sucursal" => $lista[$j]->Sucursal,
							"NSS" => $lista[$j]->NSS,
							"TipoSangre" => $lista[$j]->TipoSangre,
							"ContactoEmergencia" => $lista[$j]->ContactoEmergencia,
							"NumeroEmergencia" => $lista[$j]->NumeroEmergencia,
							"Alergias" => $lista[$j]->Alergias,
							"Notas" => $lista[$j]->Notas,
							"CLABE" => $lista[$j]->CLABE
						];
					}
				}
			}


			if ($noSearch > 0) {
				array_push($data2, [$aux, $array]);
			}
			return $data2;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}

		$stmt = NULL;
		$db = NULL;
	}
}

class delete_data
{

	function delete_RolEmpleado($array)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {

			$query = sprintf('DELETE FROM relacion_tipo_empleado WHERE id =' . $array['idRol']);
			$stmt = $db->prepare($query);

			if ($stmt->execute()) {
				return 1;
			} else {
				return 0;
			}
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}
}


class save_data
{
	function save_personalData($array)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			$query = sprintf('SELECT PKTipoColumnaEmp, Tabla FROM tipo_columna_emp');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$tabla = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$query = sprintf('SELECT id_empleado FROM empleados WHERE empresa_id = ' . $_SESSION['IDEmpresa'] . ' order by id_empleado DESC LIMIT 1');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$row_idEmpleado = $stmt->fetch();
			$id_empleado = $row_idEmpleado['id_empleado'] + 1;

			$estadoRep = (isset($array['cmbEstados']) && !empty($array['cmbEstados'])) ? $array['cmbEstados'] : NULL;
			$estadoCiv = (isset($array['cmbEstadoCivil']) && !empty($array['cmbEstadoCivil'])) ? $array['cmbEstadoCivil'] : NULL;
			$fechaNac = (isset($array['txtFecha']) && !empty($array['txtFecha'])) ? $array['txtFecha'] : NULL;
			$estatus_api = '';

			//modificar parametro recibir
			$query = sprintf('INSERT INTO empleados (id_empleado, Nombres, PrimerApellido, SegundoApellido, FKEstadoCivil, Telefono, CURP, RFC, FechaNacimiento, Genero, Direccion, NumeroExterior, Interior, Colonia, CP, Ciudad, FKEstado, estatus, empresa_id, email, claves_regimen_fiscal_id)
					VALUES (:idEmpleado, :nombres, :apellidoPat, :apellidoMat, :estadiCivil, :telefono , :curp, :rfc, :fechaNac, :genero, :direccion, :numExt, :numInt, :colonia, :cp, :ciudad, :estadoRep, :estatus, :empresaId, :email, :claves_regimen_fiscal_id)');
			$stmt = $db->prepare($query);
			$status = $stmt->execute([':idEmpleado' => $id_empleado, ':nombres' => $array['txtNombre'], ':apellidoPat' => $array['txtApellidoPaterno'], ':apellidoMat' => $array['txtApellidoMaterno'], ':estadiCivil' => $estadoCiv, ':telefono' => $array['txtTelefono'], ':curp' => $array['txtCURP'], ':rfc' => $array['txtRFC'], ':fechaNac' => $fechaNac, ':genero' => $array['cmbSexo'], ':direccion' => $array['txtCalle'], ':numExt' => $array['txtNumeroExterior'], ':numInt' => $array['txtNumeroInterior'], ':colonia' => $array['txtColonia'], ':cp' => $array['txtCodigoPostal'], ':ciudad' => $array['txtCiudad'], ':estadoRep' => $estadoRep, ':estatus' => 1, ':empresaId' => $_SESSION['IDEmpresa'], ':email' => $array['txtEmail'], ':claves_regimen_fiscal_id' => $array['cmbClaveRegimenFiscal']]);
			$lastId = $db->lastInsertId();

			$query = sprintf('INSERT INTO relacion_tipo_empleado (empleado_id, tipo_empleado_id) VALUES (:idEmpleado, :tipo_empleado)');
			$stmt = $db->prepare($query);
			$status = $stmt->execute([':idEmpleado' => $lastId, ':tipo_empleado' => $array['rolInicial']]);

			if (trim($array['txtRFC']) != "") {

				$query = sprintf("select key_company_api key_company,key_user_company_api key_user, RFC, registro_patronal from empresas where PKEmpresa = :id");
				$stmt = $db->prepare($query);
				$stmt->bindValue(":id", $_SESSION['IDEmpresa']);
				$stmt->execute();
				$key_company_api = $stmt->fetchAll();

				$facturapi = new Facturapi($key_company_api[0]['key_company']);

				$segundoapellido = "";
				if (trim($array['txtApellidoMaterno']) != "") {
					$segundoapellido = " " . trim($array['txtApellidoMaterno']);
				}
				$nombre_empleado = trim($array['txtNombre']) . " " . trim($array['txtApellidoPaterno']) . $segundoapellido;

				$email = trim($array['txtEmail']);
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$email = "";
				}

				if ($array['cmbClaveRegimenFiscal'] > 0) {

					$query = sprintf("select clave from claves_regimen_fiscal where id = :id");
					$stmt = $db->prepare($query);
					$stmt->bindValue(":id", $array['cmbClaveRegimenFiscal']);
					$stmt->execute();
					$clave_regimen_fiscal = $stmt->fetch();
					$tax_system = $clave_regimen_fiscal['clave'];

					$direccion = [
						"zip" => $array['txtCodigoPostal']
					];

					if ($email == "") {

						$cliente = $facturapi->Customers->create(array(
							"legal_name" => $nombre_empleado,
							"tax_id" => trim($array['txtRFC']),
							"tax_system" => $tax_system,
							"address" => $direccion
						));
					} else {
						$cliente = $facturapi->Customers->create(array(
							"email" => $email,
							"legal_name" => $nombre_empleado,
							"tax_id" => trim($array['txtRFC']),
							"tax_system" => $tax_system,
							"address" => $direccion
						));
					}

					if (isset($cliente->id)) {
						$query = sprintf('UPDATE empleados SET idtimbrado = ? WHERE PKEmpleado = ?');
						$stmt = $db->prepare($query);
						$stmt->execute(array($cliente->id, $lastId));
						$estatus_api = 'exito';
					} else {
						if (isset($cliente->path)) {
							if ($cliente->path == 'legal_name') {
								$estatus_api = 'fallo-legal'; //nombre no coincide con rfc
							} elseif ($cliente->path == 'tax_id') {
								$estatus_api = 'fallo-rfc'; //rfc invalido
							} elseif ($cliente->path == 'tax_system') {
								$estatus_api = 'fallo-system'; //se manda mal el regimen fiscal
							} elseif ($cliente->path == 'address.zip') {
								$estatus_api = 'fallo-zip'; //zip invalido, no coincide con rfc
							}
						}
					}
				}
			}


			for ($i = 0; $i < count($tabla); $i++) {
				//echo $tabla[$i]['Tabla']."\n\n";
				$query = sprintf('INSERT INTO ' . $tabla[$i]['Tabla'] . ' (FKEmpleado,FKColumnasEmp) VALUES (?,?)');
				$stmt = $db->prepare($query);
				$stmt->execute(array($lastId, $tabla[$i]['PKTipoColumnaEmp']));
			}

			$data[0] = ['status' => $status, 'id' => $lastId, 'estatus_api' => $estatus_api];

			return $data;
		} catch (PDOException $e) {
			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function save_laboralData($datos)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {
			//print_r($datos);
			$query = sprintf('SELECT PKTipoColumnaEmp, Tabla FROM tipo_columna_emp');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$tabla = $stmt->fetchAll(PDO::FETCH_ASSOC);

			if (trim($datos['txtInfonavit']) == "") {
				$Infonavit = 0.00;
			} else {
				$Infonavit = trim($datos['txtInfonavit']);
			}

			if (trim($datos['txtDeuda']) == "") {
				$DeudaInterna = 0.00;
			} else {
				$DeudaInterna = trim($datos['txtDeuda']);
			}

			if (trim($datos['txtSalarioBaseCotizacionFijo']) == "") {
				$SBCFijo = 0.00;
			} else {
				$SBCFijo = trim($datos['txtSalarioBaseCotizacionFijo']);
			}

			if (trim($datos['txtSalarioBaseCotizacionVariable']) == "") {
				$SBCVariable = 0.00;
			} else {
				$SBCVariable = trim($datos['txtSalarioBaseCotizacionVariable']);
			}

			$query = sprintf('INSERT INTO datos_laborales_empleado (FechaIngreso, Infonavit, DeudaInterna, DeudaRestante, FKTurno, FKPuesto,FKSucursal, FKEmpleado, FKPeriodo, Sueldo, FKTipoContrato, FKRiesgoPuesto, FKRegimen, FechaInicioVacaciones, BaseCotizacion, SalarioDiario, SalarioBaseCotizacionFijo, SalarioBaseCotizacionVariable, idAreaDepartamento, FKFormaPago, Sindicalizado, TipoPrestacion, Confidencial) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
			$stmt = $db->prepare($query);
			$status = $stmt->execute(array($datos['txtfechaIngreso'], $Infonavit, $DeudaInterna, 0.00, $datos['cmbTurno'], $datos['cmbPuesto'], $datos['cmbLocacion'], $datos['txtIdEmpleado'], $datos['cmbPeriodo'], $datos['txtSueldo'], $datos['cmbTipoContrato'], $datos['cmbRiesgoPuesto'], $datos['cmbRegimen'], $datos['fechaInicio'], $datos['cmbBaseCotizacion'], $datos['txtSueldoDiario'], $SBCFijo, $SBCVariable, $datos['cmbAreaDepartamento'], $datos['cmbFormaPago'], $datos['rSindicalizado'], $datos['rTipoPrestacion'], $datos['cboxNominaConfidencialJS']));

			/*$lastId = $db->lastInsertId();

			for ($i = 0; $i < count($tabla); $i++) {
				//echo $tabla[$i]['Tabla']."\n\n";
				$query = sprintf('INSERT INTO ' . $tabla[$i]['Tabla'] . ' (FKEmpleado,FKColumnasEmp) VALUES (?,?)');
				$stmt = $db->prepare($query);
				$stmt->execute(array($lastId, $tabla[$i]['PKTipoColumnaEmp']));
			}*/

			$data[0] = ['status' => $status, 'id' => $datos['txtIdEmpleado']];

			return $data;
		} catch (PDOException $e) {

			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function save_medicalData($array)
	{
		$con = new conectar();
		$db = $con->getDb();
		//print_r($array);
		try {

			$query = sprintf('SELECT PKTipoColumnaEmp, Tabla FROM tipo_columna_emp');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$tabla = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$query = sprintf('INSERT INTO datos_medicos_empleado (NSS,TipoSangre,ContactoEmergencia,NumeroEmergencia,Alergias,Notas,Donador,FKEmpleado)
														VALUES (?,?,?,?,?,?,?,?)');
			$stmt = $db->prepare($query);
			$status = $stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[5]['datos'], $array[6]['datos'], $array[7]['datos'], $array[0]['datos']));
			/*$lastId = $db->lastInsertId();

			for ($i = 0; $i < count($tabla); $i++) {
				//echo $tabla[$i]['Tabla']."\n\n";
				$query = sprintf('INSERT INTO ' . $tabla[$i]['Tabla'] . ' (FKEmpleado,FKColumnasEmp) VALUES (?,?)');
				$stmt = $db->prepare($query);
				$stmt->execute(array($lastId, $tabla[$i]['PKTipoColumnaEmp']));
			}*/

			$data[0] = ['status' => $status, 'id' => $array[0]['datos']];

			return $data;
		} catch (PDOException $e) {

			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function save_bankData($array)
	{
		$con = new conectar();
		$db = $con->getDb();

		try {

			$query = sprintf('SELECT PKTipoColumnaEmp, Tabla FROM tipo_columna_emp');
			$stmt = $db->prepare($query);
			$stmt->execute();
			$tabla = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$query = sprintf('INSERT INTO datos_bancarios_empleado (FKBanco,CuentaBancaria,CLABE,NumeroTarjeta,FKEmpleado)
														VALUES (?,?,?,?,?)');
			$stmt = $db->prepare($query);
			$status = $stmt->execute(array($array[1]['datos'], $array[2]['datos'], $array[3]['datos'], $array[4]['datos'], $array[0]['datos']));
			/*$lastId = $db->lastInsertId();

			for ($i = 0; $i < count($tabla); $i++) {
				//echo $tabla[$i]['Tabla']."\n\n";
				$query = sprintf('INSERT INTO ' . $tabla[$i]['Tabla'] . ' (FKEmpleado,FKColumnasEmp) VALUES (?,?)');
				$stmt = $db->prepare($query);
				$stmt->execute(array($lastId, $tabla[$i]['PKTipoColumnaEmp']));
			}*/

			$data[0] = ['status' => $status, 'id' => $array[0]['datos']];

			return $data;
		} catch (PDOException $e) {

			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function save_rolesEmpleado($array)
	{
		$con = new conectar();
		$db = $con->getDb();
		//print_r($array);
		try {

			$query = sprintf('SELECT id FROM relacion_tipo_empleado WHERE tipo_empleado_id = ? AND empleado_id = ?');
			$stmt = $db->prepare($query);
			$stmt->execute(array($array['idRol'], $array['idEmpleado']));
			$existe = $stmt->rowCount();

			if ($existe > 0) {
				$data[0] = ['status' => "existe"];
			} else {

				$query = sprintf('INSERT INTO relacion_tipo_empleado (tipo_empleado_id,empleado_id)
														VALUES (?,?)');
				$stmt = $db->prepare($query);

				if ($stmt->execute(array($array['idRol'], $array['idEmpleado']))) {
					$data[0] = ['status' => "exito"];
				} else {
					$data[0] = ['status' => "fallo"];
				}
			}

			return $data;
		} catch (PDOException $e) {

			return "Error en Consulta: " . $e->getMessage();
		}
	}

	function saveAreaDepartamento($nombre)
	{
		$con = new conectar();
		$db = $con->getDb();
		$values = [':nombre' => $nombre, ':idEmpresa' => $_SESSION['IDEmpresa']];
		$query = sprintf('SELECT id FROM areaDepartamento WHERE nombre = :nombre AND empresa_id = :idEmpresa');
		$stmt = $db->prepare($query);
		$stmt->execute($values);
		if (!$stmt->fetchColumn()) {
			$query = sprintf('INSERT INTO areaDepartamento (nombre, empresa_id) VALUES (:nombre, :idEmpresa)');
			$stmt = $db->prepare($query);
			if (!$stmt->execute($values)) {
				return ['status' => 'fail', 'message' => 'Fallo al agregar'];
			}
			return ['status' => 'success', 'message' => 'Registro añadido', 'id' => $db->lastInsertId()];
		}
		return ['status' => 'fail', 'message' => 'Ya existe el registro'];
	}
}
