<?php 
session_start();

 	if(isset($_SESSION["Usuario"])){
		require_once('../../include/db-conn.php');
		
		try{
			//SELECT  estados.Clave, estados.Estado, SUM(Importe) as Ventas FROM facturas INNER JOIN locacion ON facturas.FKlocacion = locacion.PKLocacion INNER JOIN estados ON locacion.FKEstado = estados.PKEstado GROUP BY Estado
			$consulta = sprintf('SELECT e.Clave, e.Estado, SUM(Total) as Ventas FROM facturacion INNER JOIN domicilio_fiscal as df ON df.PKDomicilioFiscal=facturacion.FKDomicilioFiscal INNER JOIN estados as e ON df.Estado=e.Estado WHERE facturacion.Estatus="pagado" GROUP BY Estado');
			$stmt = $conn->prepare($consulta);
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($data);
			$num = count($data);//length del array.

			$consulta2 = sprintf('SELECT Clave, Estado FROM estados');
			$stmt2 = $conn->prepare($consulta2);
			$stmt2->execute();
			$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
			//var_dump($data2);

			$sellsInfo = [];
			foreach ($data2 as $key => $value) {
				//var_dump($myrow["Clave"]);
				$d = $value["Clave"];
				array_push($sellsInfo,[$value["Clave"],$value["Estado"], 0]);
				for($i = 0; $i<$num; $i++){
					if ($value["Clave"] == $data[$i]["Clave"]) {
						//array_push($sellsInfo,[$myrow["Clave"],$myrow["Estado"], $data[$i]["Ventas"]]);
						$entero = $data[$i]["Ventas"];
						$int = (int)$entero;
						$sellsInfo[$key][2]=$int;
					}
				}
			}
			echo json_encode($sellsInfo);
		}catch(PDOException $error){
	      $message = $error->getMessage();
		}

	}else {
		header("location:../index.php");
	}