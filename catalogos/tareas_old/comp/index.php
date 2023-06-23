<?php
	session_start();
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
	<link href="../../../css/sb-admin-2.css" rel="stylesheet">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

</head>
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
								<div class="container-fluid proyecto">

								</div>
							</div>
							<div class='agregarColumna'><i class='fas fa-plus-circle imgHover imgActive cursorPointer'></i></div>
							<div class='listaColumnas'>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/estado.png" width="35px"> </div><div><span class="fs-20">Estado</span></div></div>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/fecha.png" width="25px"></div><div><span class="fs-20">Responsables</span></div></div>
								<div class="pd-20 columna-item"><div class="text-left mr-30"><img src="../../../img/timdesk/responsables.png" width="25px"></div><div><span class="fs-20">Fecha</span></div></div>
							</div>
						</div>
						<div id="boardContent" class="board-content">

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
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="js/script.js"></script>
<script>
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

	$.ajax({
		url:"php/funciones.php",
		data:{clase:"admin_data",funcion:"getProject"},
		dataType:"json",
		success:function(resp){
			//console.log(resp)
			$('.proyecto').append('<h2>'+resp[0].Proyecto+'</h2>');
			getLevels(resp[0].PKProyecto);//Obtener etapas
		},
		error:function(error){
			console.log(error);
		}

	});

	$('.agregarColumna').click(function(){
		var tieneClase = $('.agregarColumna i').hasClass('fa-plus-circle');
		if (tieneClase) {
			$('.agregarColumna i').removeClass('fa-plus-circle');
			$('.agregarColumna i').addClass('fa-times-circle');
			$('.listaColumnas').show();
		}else{
			$('.agregarColumna i').removeClass('fa-times-circle');
			$('.agregarColumna i').addClass('fa-plus-circle');
			$('.listaColumnas').hide();
		}

	});


</script>
</html>
