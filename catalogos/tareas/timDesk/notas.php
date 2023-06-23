<?php 
	//pruebaenviocorreoslocal@gmail.com . leono4105
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
	<link href="../../../js/flatpickr/dist/flatpickr.css">
	<link rel="stylesheet" type="text/css" href="../../../js/flatpickr/dist/themes/dark.css">
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">
	<link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link href="style/css.css" rel="stylesheet" type="text/css">
	<link href="../../../css/sb-admin-2.css" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link href="../../../js/picker/dist/bcp.css" rel="stylesheet">
	<link rel="stylesheet" href="../../../js/build/css/intlTelInput.css">
  	<link rel="stylesheet" href="../../../js/build/css/demo.css">
  	
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

		.color-delete-handle{
			background-image: url(../../../img/timdesk/cross.png); 
			background-size: 100%;
		    width: 15px;
		    height: 20px;
			left: 107%;
			top: 6px;
			color: black;
			background-repeat: no-repeat;
		}

		.color-edit-text{
			color: black;
		}
		.color-edit-handle{
			right: 100px;
			color: black;
		}


		.n-focus{
			background:#f1f1f1;
			border: 0;
			color: black;
		}
		.n-focus:focus{
			outline: none;
		}
		.border-focus:focus-within{
    		border: 1px solid blue;
		}
		.alto-28p{
			height: 28px;
		}

		.success-checkmark {
		  width: 80px;
		  height: 115px;
		  margin: 0 auto;
		}
		.success-checkmark .check-icon {
		  width: 80px;
		  height: 80px;
		  position: relative;
		  border-radius: 50%;
		  box-sizing: content-box;
		  border: 4px solid #4caf50;
		}
		.success-checkmark .check-icon::before {
		  top: 3px;
		  left: -2px;
		  width: 30px;
		  transform-origin: 100% 50%;
		  border-radius: 100px 0 0 100px;
		}
		.success-checkmark .check-icon::after {
		  top: 0;
		  left: 30px;
		  width: 60px;
		  transform-origin: 0 50%;
		  border-radius: 0 100px 100px 0;
		  animation: rotate-circle 4.25s ease-in;
		}
		.success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
		  content: "";
		  height: 100px;
		  position: absolute;
		  background: #ffffff;
		  transform: rotate(-45deg);
		}
		.success-checkmark .check-icon .icon-line {
		  height: 5px;
		  background-color: #4caf50;
		  display: block;
		  border-radius: 2px;
		  position: absolute;
		  z-index: 10;
		}
		.success-checkmark .check-icon .icon-line.line-tip {
		  top: 46px;
		  left: 14px;
		  width: 25px;
		  transform: rotate(45deg);
		  animation: icon-line-tip 0.75s;
		}
		.success-checkmark .check-icon .icon-line.line-long {
		  top: 38px;
		  right: 8px;
		  width: 47px;
		  transform: rotate(-45deg);
		  animation: icon-line-long 0.75s;
		}
		.success-checkmark .check-icon .icon-circle {
		  top: -4px;
		  left: -4px;
		  z-index: 10;
		  width: 80px;
		  height: 80px;
		  border-radius: 50%;
		  position: absolute;
		  box-sizing: content-box;
		  border: 4px solid rgba(76, 175, 80, 0.5);
		}
		.success-checkmark .check-icon .icon-fix {
		  top: 8px;
		  width: 5px;
		  left: 26px;
		  z-index: 1;
		  height: 85px;
		  position: absolute;
		  transform: rotate(-45deg);
		  background-color: #ffffff;
		}

		@keyframes rotate-circle {
		  0% {
		    transform: rotate(-45deg);
		  }
		  5% {
		    transform: rotate(-45deg);
		  }
		  12% {
		    transform: rotate(-405deg);
		  }
		  100% {
		    transform: rotate(-405deg);
		  }
		}
		@keyframes icon-line-tip {
		  0% {
		    width: 0;
		    left: 1px;
		    top: 19px;
		  }
		  54% {
		    width: 0;
		    left: 1px;
		    top: 19px;
		  }
		  70% {
		    width: 50px;
		    left: -8px;
		    top: 37px;
		  }
		  84% {
		    width: 17px;
		    left: 21px;
		    top: 48px;
		  }
		  100% {
		    width: 25px;
		    left: 14px;
		    top: 45px;
		  }
		}
		@keyframes icon-line-long {
		  0% {
		    width: 0;
		    right: 46px;
		    top: 54px;
		  }
		  65% {
		    width: 0;
		    right: 46px;
		    top: 54px;
		  }
		  84% {
		    width: 55px;
		    right: 0px;
		    top: 35px;
		  }
		  100% {
		    width: 47px;
		    right: 8px;
		    top: 38px;
		  }
		}

	
	</style>
</head>
<body>

	

	<input class="flatpickr" type="text" name="" id="flatpickr" value="hola">
	<!-- <div class="date"></div> -->
	<!-- NOTAS PARA CÓDIGO HTML-->
	<!--<input id="phone" name="phone" type="tel" class="form-input border-animation set-4 phone" onfocusout="getNumber(phone)" style="border:1px solid red;">
	
	<label>Direccion</label>
	<input type="" name="" value="Direccion">
	<label>Texto</label>
	<input type="" name="" value="Texto">

	<div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="edit-columna-icon"></i></div><div><span>Editar columna</span></div></div>
	<div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="elim-columna-icon"></i></div><div><span>Eliminar columna</span></div></div>

	<button class="example">clic</button>

	<div id='box-link' class='pos-abs'>
		<div class='form-group-link'>
			<label for='link'>Direccion web</label>
			<br>
			<input id='txt_link' type='text' class='form-control' placeholder='www.ejemplo.com' value='"+direccion+"'>
			<br>
			<label for='textlink'>Texto a mostrar</label>
			<br>
			<input id='txt_texto' type='text' class='form-control ignore-elements'  placeholder='Página' value='"+texto+"'>
			<br>
			<button type='button' id='btn-ok' class='btn btn-primary' onclick=editarHipervinculo('"+id+"')>OK</button>
		</div>
	</div>
	

	<div class="opcionesColorColumna2">
		<div id="abc" class="colors-container-edit">

			<div onmouseenter="show_color_options(50)" onmouseleave="hide_color_options(50)">
				<div class="color-container-edit pos-rel" >
					<div id="sortable-50" class="color-edit-handle pos-abs d-no">::</div>
					<div class="d-flex border-focus">
						<div class="alto-28p" style="background:red">&nbsp&nbsp</div>
						<input class="n-focus alto-28p" type="text" name="" value="Hecho">
					</div>
					<div id="delete-color-50" class="pos-abs color-delete-handle d-no"></div>
				</div>
			</div>


			<div class="color-container-edit" style="background:grey"></div>
			<div class="color-container-edit" style="background:red"></div>
			<div class="color-container-edit" style="background:grey"></div>
			<div class="color-container-edit" style="background:red"></div>
		
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
		<!--
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
		
		</div>
		<div id="colorPicker">
			hola mundo
		</div>
	</div>
	-->


</body>
<script src="../../../js/build/js/utils.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="../../../js/picker/dist/bcp.min.js"></script>
<script src="../../../js/picker/dist/bcp.en.js"></script>
<script src="../../../js/build/js/intlTelInput.js"></script>
<script src="../../../js/flatpickr/dist/flatpickr.js"></script>
<script type="text/javascript">

	$('.flatpickr').flatpickr({
      dateFormat: "d/M/Y",
      mode: "range",
      locale: {
        firstDayOfWeek: 1,

        weekdays: {
          shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        },
        months: {
          shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
          longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        },
      },
      onClose: function(selectedDates, dateStr, instance) {
          console.log(dateStr)
      }
    });
	/* NOTAS PARA CÓDIGO JS */
	//sorteable = document.getElementById('tabla-id-'+(i+1)) // El número del id "tabla-id-n" deberá ser el orden de la etapa

	// $('.example').bcp();
	// $('#colorPicker').bcp();
	// var ancho = document.getElementById("abc");
	// var rows = document.getElementById("abc").children.length;
	// var ancho2 = document.getElementById("colorPicker");
	// console.log(rows);

	// if (rows <= 5) {
	// 	ancho.style.width = "150px";
	// 	ancho.style.alignItems = "center";
	// 	ancho2.style.width = "150px";
	// 	ancho2.style.alignItems = "center";
	// }else if(rows >= 6 && rows <= 10){
	// 	ancho.style.width = "220px";
	// 	ancho.style.alignItems = "normal";
	// 	ancho2.style.width = "220px";
	// 	ancho2.style.alignItems = "normal";
	// }else if(rows >= 11 && rows <= 15){
	// 	ancho.style.width = "330px";
	// 	ancho2.style.width = "330px";
	// }else if(rows >= 16 && rows <= 20){
	// 	ancho.style.width = "450px";
	// 	ancho2.style.width = "450px";
	// }else if(rows >= 21 && rows <= 25){
	// 	ancho.style.width = "550px";
	// 	ancho2.style.width = "550px";
	// }

	// var array = [
	// 	{
	// 		valor1: "1",
	// 		valor2:"2",
	// 		valor3:"3",
	// 		valorW:[
	// 			{
	// 				valorY:"Y",
	// 			}
	// 		],
	// 	},
	// 	{
	// 		valor4: "4",
	// 		valor5:"5",
	// 		valor6:"6",
	// 	},
	// 	[{
	// 		valor7:"7",
	// 		valor8:"8",
	// 		valor9:"9",
	// 	}]
	// ];

	// console.log(array);
	// $.each(array,function(i){
	// 	console.log(array[i])
		
	// })
	// let iti;
	// function getFlag(id){
	// 	console.log("array", array);
	// 	console.log("DENTRO DE GETFLAG");

	// 	var input = document.getElementById(id);
	//     console.log("input", input);
	// 	window.intlTelInput(input, {
	// 		initialCountry: 'mx',
	// 		geoIpLookup: function(callback) {
	// 			$.get('https://ipinfo.io', function() {}, "jsonp").always(function(resp) {
	// 				var countryCode = (resp && resp.country) ? resp.country : "";
	// 				callback(countryCode);
	// 			});
	// 		},
	// 		utilsScript: "../../../js/build/js/utils.js?1590403638580",
	// 	});
	// 	iti = intlTelInput(input)
	// }

	// function getNumber(id){
	// 	var number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
	// 	console.log("number", number);
	// }

	// function show_color_options(id){
	// 	$('#sortable-'+id).show();
	// 	$('#delete-color-'+id).removeClass("d-no")
	// 	$('#delete-color-'+id).attr('display','inline-block');
	// }
	// function hide_color_options(id){
	// 	$('#sortable-'+id).hide();
	// 	$('#delete-color-'+id).addClass("d-no");
	// }

	$("button").click(function () {
	  $(".check-icon").hide();
	  setTimeout(function () {
	    $(".check-icon").show();
	  }, 10);
	});
	//getFlag("phone");
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
	ID "etiqueta-n" Identifica al input con el nombre de los estados de los elementos de tipo ESTADO
	ID "num-color-n" Identifica cada color disponible impreso del array colorsToPrint para que al agregar etiqueta se tome el primer color y consecutivos.

	
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

	COLUMNA ESTADO:
		ID's
		"change-color-place-n" Identifica a la barra de color al lado izquierdo de cada input al dar clic en editar colores para los elementos de los estados de la tarea.
		"config-color-container-n" Identifica a cada elemento configurable en el modal para editar texto y color de los estados.

		CLASES
		"icon_change_color" guarda la configuración del icono dentro de las barras de colores en la edición de colores para los elementos tipo estado.
		"text-to-show-n" Hace referencia al id de colores_columna para en caso de cambio del texto.

	DIV con el id "tabla-id-n" contiene las tareas del proyecto.
	DIV con el id "columnas-principales-etapa-n" contiene las columnas del proyecto para cada etapa.
	DIV con el id "index-n" contiene la información de las tareas.

	DATA-TAB Hace referencia al id de la etapa.
	DATA-POS Hace referencia al id de la columna.
	DATA-ORD Hace referencia al id de la tarea.


	/*=================================
	=            FUNCIONES            =
	=================================*/
	
	OPTIONS_C() Función que despliega las opciones de cada columna (eliminar, editar, etc.)
	do_changesOn_text() Realiza cambios en el texto de los estados.
	
	/*=====  End of FUNCIONES  ======*/
	
	

	//Variable textToShow sirve para imprimir html adaptado según cada tipo de columna.

	TIPO DE COLUMNA:

	1. Tipo de columna Responable.
	2. Tipo de columna Estado.
	3. Tipo de columna Fecha



-->

<!--===================================
=            CREAR COLUMNA            =
====================================-->

<!-- 
	1.- Crear la configuración básica de la(s) tabla(s) que necesitara la columna y agregar una línea de ejemplo para probar los datos.
	2.- Agregar dos líneas manualmente como ejemplo en la tabla "columnas_proyecto" para que en la primer carga traiga los datos y probar la configuración adecuada para manejar los datos en varias columnas.
	3.- En clases.php en la función getInfo() añadir la condición según el tipo de columna, agregar la consulta de los datos necesarios.
	4.- En el script.js en la función getInfo() y print_elements() añadir la condición según el tipo de columna y añadir la configuración necesaria para la impresión de elemento.
	NOTA: Esto servirá para ir probando la configuración de la columna en la primer carga del sistema (sin agregar columna ni tarea).

	--- Agregar columna: ---
	5. Crear div en el div con clase listaColumnas.
	6. Añadir if en la función getColumn() para identificar el tipo de columna.
	7. En clases.php agregar condición según el tipo de columna:
		Añadir llamado a getAName() y columnComun() según el caso.
		Agregar el ciclo for en caso de tener que crear elemento default para cada tarea.
	8. en script.js en el success añadir la condición según el tipo de columna y crear la configuración necesaria para el elemento.

	--- Columna al agregar tarea: ---
	9.- Dentro de la función addTask en clases.php en la parte de //Columnas: donde se llama a addElementsFromTask() ir a ésta función y añadir la condición según el tipo de columna y realizar el insert.
	10.- Dentro de la función addTask en clases.php en la parte de //Columnas: donde $resp es igual a "ok" declarar un array vacío que será llenado con la información del elemento según el tipo de columna.
	11.- Dentro del ciclo for inmediato añadir una condición según el tipo de columna y dentro hacer la consulta para traer la información, guardarla en el array vacío creado anteriormente.
	12.- Añadir a la variable $resultado dentro del merge la variable con el array de la información del elemento.
	13.- En el success de addTask, se encuentra el llamado a la función print_elements() que ya fue configurada en el punto cuatro (4).

-->



<!--====  End of CREAR COLUMNA  ====-->
