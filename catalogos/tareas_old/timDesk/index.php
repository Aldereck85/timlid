<?php
	session_start();
	//cuenta monday.com disenosocialjal@gmail.com perfil2020
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>TimLid</title>
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">
	<link rel="icon" type="image/png" href="../../../img/header/bluTimlid.png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link href="style/css.css" rel="stylesheet" type="text/css">
	<link href="style/buttons.css" rel="stylesheet" type="text/css">
	<link href="../../../css/sb-admin-2.css" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
	<link href="../../../js/picker/dist/bcp.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css" rel="stylesheet"></link>
	<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
	<style type="text/css">
		
	</style>
</head>
<!--<div class="se-pre-con"></div>-->
<body id="page-top">
	<div id="wrapper">
		<?php
        $ruta = "../../";
        require_once('../../menu3.php');
      ?>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<?php
            $rutatb = "../../";
            require_once('../../topbar.php');
          ?>
				<div class="d-flex">
				    <div id="page-content-wrapper">
						<div class="board-header">
							<div class="header-top">
								<!-- titulo -->
								<div class="search" align="right">
									<!--<input type="text" id="search-input" class="search-tareas">-->
									<input id="search-input" type="text" name="search_tareas" class="search-tareas" />
								</div>
								<div class="container-fluid proyecto">
									<h2></h2>
								</div>
							</div>
							

							<div class="severalOptions">
								<div id="select-project" class="mr-10 pos-rel" style="padding: 6px;">
									<i class="projects-icon imgHover imgActive" onclick="seeProjects()"></i>
									<div id="div-projects-list" class="pos-abs d-no" style="right:10%;">
										<select id="projects-List" class="w-300px">
											<option value="0">Selecciona un proyecto</option>
										</select>
									</div>
								</div>
								<div class="mr-10">
									<button id="agregarEtapa" class="btn btn-primary">Agregar etapa</button>
								</div>
								<div id="cerrarEtapas"><i class="close_rows_icon" onclick="hideAllTask()" data-toggle="taskHideTip" data-placement="left" title="Ocultar tareas"></i></div>
								<div id="abrirEtapas"><i class="open_rows_icon" onclick="showAllTask()" data-toggle="taskTip" data-placement="left" title="Mostrar tareas"></i></div>
								<div class='agregarColumna'><i class='plus-icon imgHover imgActive cursorPointer' data-toggle="columnTip" data-placement="left" title="Agregar columna"></i></div>
							</div>

							<div class='listaColumnas'>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/estado.png" width="35px"> </div><div><span class="fs-20">Estado</span></div></div>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/responsables.png" width="25px"></div><div><span class="fs-20">Responsables</span></div></div>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/fecha.svg" width="25px"></div><div><span class="fs-20">Fecha</span></div></div>
							</div>
						</div>
						<div class="goaway">
							<div class="se-pre-pro"></div>
						</div>
						<div id="boardContent" class="board-content" style="min-height: 300px;">
							
						</div>
					</div>
				    <!-- /#page-content-wrapper -->
				</div>
			</div>

			
			<footer class="sticky-footer">
					<img style="float:right;margin-right:20px" src="../../../img/header/timlidAzul.png" width="120px">
      </footer>
		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="../../../js/picker/dist/bcp.js"></script>
<script src="../../../js/picker/dist/bcp.en.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>
<script src="js/script.js"></script>
<script>

	
	// Animate loader off screen
	//$(".se-pre-con").fadeOut(3000);
	$(".goaway").fadeOut(2000,function(){
		$('#boardContent').fadeIn("fast");
	});
	
	

	$('.listaColumnas').hide();
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		if ($('#menu-toggle i').hasClass('fa-arrow-left')) {
			$('#menu-toggle i').removeClass('fa-arrow-left');
			$('#menu-toggle i').addClass('fa-arrow-right');
		}else{
			$('#menu-toggle i').removeClass('fa-arrow-right');
			$('#menu-toggle i').addClass('fa-arrow-left');
		}
		$("#wrapper").toggleClass("toggled");
	});

	function call_project(id){
		console.log("LLAME AL PROYECTO")
		$.ajax({
			url:"php/funciones.php",
			data:{clase:"admin_data",funcion:"getProject", id:id},
			dataType:"json",
			success:function(resp){
				
				console.log(resp)
				$('.proyecto h2').append(resp[0].Proyecto);
				getLevels(resp[0].PKProyecto);//Obtener etapas
			},
			error:function(error){
				console.log(error);
			}

		});
	}
	

	$('.agregarColumna').click(function(){

		var tieneClase = $('.agregarColumna i').hasClass('plus-icon');
		if (tieneClase) {
			$('.agregarColumna i').removeClass('plus-icon');
			$('.agregarColumna i').addClass('close-icon');
			$('.listaColumnas').show();
		}else{
			$('.agregarColumna i').removeClass('close-icon');
			$('.agregarColumna i').addClass('plus-icon');
			$('.listaColumnas').hide();
		}

		container = $(".agregarColumna");
		hide = $(".listaColumnas");
		oCaracter = tieneClase;

	});

	$('#abrirEtapas').mouseenter(function(){
		$('[data-toggle="taskTip"]').tooltip();
	});

	$('.agregarColumna').mouseenter(function(){
		$('[data-toggle="columnTip"]').tooltip()
	})

	$('#cerrarEtapas').mouseenter(function(){
		$('[data-toggle="taskHideTip"]').tooltip()
	})

	var seleccionProyecto = new SlimSelect({
        select: '#projects-List',
        placeholder: 'Seleccione un proyecto',
        searchPlaceholder: 'Buscar proyecto',
        beforeOpen: function () { 
        	console.log('afterOpen')
        },
        onChange:(info)=>{
        	$('.proyecto h2').empty();
        	$('.goaway').remove();
        	$('#boardContent').remove();
        	idProyecto = info.value;
        	$('.proyecto h2').append(info.text);
        	
        	$('#page-content-wrapper').append('<div class="goaway"><div class="se-pre-pro"></div></div>');
        	
        	goAway();
        	setTimeout(function(){
        		arrColumnas=[];
	        	indexArray=[];
	        	columnasArray=[];
	        	numTareas=[];
	        	tablas=[];
	        	numTask=0;//Número que se le asignará cuando se agreguen nuevas tareas.
				//picker=0;
        		getLevels(info.value);//Obtener etapas
        		getDrag(); //Función que hará las etapas sorteables.
        	}, 2050);
        	$('#div-projects-list').hide();
        	$('#boardContent').fadeIn("fast");
        }
    });



	call_project(1);
</script>
</html>
