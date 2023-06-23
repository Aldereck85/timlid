<?php 
	
	// NOTAS AL CÓDIGO PHP
	$colores = array(
		["nombre" => "default",
		"color" => "#1a1a1a"
		],
		["nombre" => "Hecho",
		"color" => "#28a745"
		],
		["nombre" => "Pendiente",
		"color" => "#ffc107"
		],
		["nombre" => "Atrasado",
		"color" => "#dc3545"
		],
	);

	var_dump($colores[3]["nombre"]);

 ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">
	<link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link href="style/css.css" rel="stylesheet" type="text/css">
	<link href="../../../css/sb-admin-2.css" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link href="../../../js/picker/dist/bcp.css" rel="stylesheet">
	<style type="text/css">
		/* NOTAS PARA CÓDIGO CSS */
		.color-container{
			width: 90px;
			height: 30px;
			margin: 10px;
			border-radius: 5px 5px 5px 5px;
		}
		.colors-container{
			border-radius: 10px 10px 0 0;
		    box-shadow: 0px 8px 21px -5px rgba(0,0,0,0.75);
		    display: flex;
		    flex: 1 0 auto;
		    flex-direction: column;
		    flex-wrap: wrap;
		    margin: 20px;
		    margin-left: auto;
		    margin-right: auto;
		    max-height: 280px;
		}
		#colorPicker{
			border-radius: 0 0 10px 10px;
		    box-shadow: 0px 8px 21px -5px rgba(0,0,0,0.75);
		    display: flex;
		    flex: 1 0 auto;
		    flex-direction: column;
		    flex-wrap: wrap;
		    margin: 20px;
		    margin-left: auto;
		    margin-right: auto;
		    max-height: 280px;
		}
		.opcionesColorColumna2{
	        position: absolute;
		    top: 100px;
		    right: 50%;
		    z-index: 100;
		    border-radius: 10px 10px 0 0;
		    width: 660px;
		    height: 280px;
		    text-align: center;
		    max-height: 280px;
		}
		/*---------------------------------------------------------*/
		.colors-container-edit{
			border-radius: 10px 10px 0 0;
		    box-shadow: 0px 8px 21px -5px rgba(0,0,0,0.75);
		    display: flex;
		    flex: 1 0 auto;
		    flex-direction: column;
		    flex-wrap: wrap;
		    margin: 20px;
		    margin-left: auto;
		    margin-right: auto;
		    max-height: 280px;
		}

		.color-container-edit{
			display: flex;
			width: 90px;
			height: 30px;
			margin: 10px;
		}

		.color-edit-text{
			color: black;
		}
		.color-edit-handle{
			right: 100px;
			color: black;
		}
		.n-focus{
			border: 0;
		}
	</style>
</head>
<body>
	<!-- NOTAS PARA CÓDIGO HTML-->

	<label>Direccion</label>
	<input type="" name="" value="Direccion">
	<label>Texto</label>
	<input type="" name="" value="Texto">

	<div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="edit-columna-icon"></i></div><div><span>Editar columna</span></div></div>
	<div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="elim-columna-icon"></i></div><div><span>Eliminar columna</span></div></div>

	<button class="example">clic</button>


	

	<div class="opcionesColorColumna2">
		<div id="abc" class="colors-container-edit">
			<div class="color-container-edit pos-rel" >
				<div class="color-edit-handle pos-abs">::</div>
				<div class="d-flex">
					<div style="background:red">&nbsp&nbsp</div>
					<input class="n-focus" type="text" name="" value="Hecho" style="width: 90px;">
				</div>
				
			</div>
			<div class="color-container-edit" style="background:grey"></div>
			<div class="color-container-edit" style="background:red"></div>
			<div class="color-container-edit" style="background:grey"></div>
			<div class="color-container-edit" style="background:red"></div>
<!--
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
		-->
		</div>
		<div id="colorPicker">
			hola mundo
		</div>
	</div>

	<div class="opcionesColorColumna2 d-no">
		<div id="abc" class="colors-container">
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
<!--
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>

			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
			<div class="color-container" style="background:grey"></div>
			<div class="color-container" style="background:red"></div>
		-->
		</div>
		<div id="colorPicker">
			hola mundo
		</div>
	</div>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="../../../js/picker/dist/bcp.min.js"></script>
<script src="../../../js/picker/dist/bcp.en.js"></script>

<script type="text/javascript">
	/* NOTAS PARA CÓDIGO JS */
	//sorteable = document.getElementById('tabla-id-'+(i+1)) // El número del id "tabla-id-n" deberá ser el orden de la etapa
	$('.example').bcp();
	$('#colorPicker').bcp();
	var ancho = document.getElementById("abc");
	var rows = document.getElementById("abc").children.length;
	var ancho2 = document.getElementById("colorPicker");
	console.log(rows);

	if (rows <= 5) {
		ancho.style.width = "150px";
		ancho.style.alignItems = "center";
		ancho2.style.width = "150px";
		ancho2.style.alignItems = "center";
	}else if(rows >= 6 && rows <= 10){
		ancho.style.width = "220px";
		ancho.style.alignItems = "normal";
		ancho2.style.width = "220px";
		ancho2.style.alignItems = "normal";
	}else if(rows >= 11 && rows <= 15){
		ancho.style.width = "330px";
		ancho2.style.width = "330px";
	}else if(rows >= 16 && rows <= 20){
		ancho.style.width = "450px";
		ancho2.style.width = "450px";
	}else if(rows >= 21 && rows <= 25){
		ancho.style.width = "550px";
		ancho2.style.width = "550px";
	}

	var array = [
		{
			valor1: "1",
			valor2:"2",
			valor3:"3",
			valorW:[
				{
					valorY:"Y",
				}
			],
		},
		{
			valor4: "4",
			valor5:"5",
			valor6:"6",
		},
		[{
			valor7:"7",
			valor8:"8",
			valor9:"9",
		}]
	];

	console.log(array);
	$.each(array,function(i){
		console.log(array[i])
		
	})

</script>
</html>


<!-- NOTAS  VARIAS-->
<!--


	Se quita el style order a las columnas para mantener el efecto de la clase fantasma.

	Se elimina la propiedad data-co dentro de los divs de los elementos, 

	

-->

<!-- VARIABLES, ATRIBUTOS, CLASES, IDENTIFICADORES, DIVS, etc-->
<!--

	ID "etapa-n" Hace referencia al orden de la etapa dentro del proyecto.
	ID "columnas-principales-etapa-n" Hace referencia al id de la etapa.
	ID "tabla-id-n" hace referencia al id de la etapa.
	ID "tarea-n" hace referencia al id de la tarea.
	ID "index-n" hace referencia al orden en el que se imprimen las tareas (relacionada con el orden en la BBDD).
	
	CLASES:

	"order_n" Hace referencia al orden de la etapa dentro del proyecto.
	"order_ta_" Hace referencia al orden del contenedor de las tareas y para recorrerlas en caso del cambio de posición de alguna tarea
	"header" contiene la posición relativa.
	co_"id de la columna" clase en los elementos de las tareas para relacionarlo con las columnas.
	"group_n" clase que identifica a las etapas (grupos) en el proyecto, el número corresponde al id de la etapa.
	"condenseGroup" Hace que la etapa luzca dentro de un rectángulo gris.
	"task" Asignada a cada tarea del proyecto, usada cuando las etapas se "contraen" se le agrega la clase d-no (display:none;).
	"header" Asignada a cada columna del proyecto, usada cuando las etapas se contraen se le agrega la clase d-no (display:none;).
	"agregarTarea" Asignada al espacio donde al hacer clic se agregará una nueva tarea (fila).
	"div_r_" Identifica a una columna de tipo responsable para generar el select debajo. LLeva el id del elemento.

	DIV con el id "tabla-id-n" contiene las tareas del proyecto.
	DIV con el id "columnas-principales-etapa-n" contiene las columnas del proyecto para cada etapa.
	DIV con el id "index-n" contiene la información de las tareas.

	DATA-TAB Hace referencia al id de la etapa.
	DATA-POS Hace referencia al id de la columna.
	DATA-ORD Hace referencia al id de la tarea.

	OPTIONS_C() Función que despliega las opciones de cada columna (eliminar, editar, etc.)

	//Variable textToShow sirve para imprimir html adaptado según cada tipo de columna.

	TIPO DE COLUMNA:

	1. Tipo de columna Responable.
	2. Tipo de columna Estado.
	3. Tipo de columna Fecha



-->