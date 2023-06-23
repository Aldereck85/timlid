<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Timlid - Portal del cliente</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="../img/header/bluTimlid.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="css/themify-icons.css">
    <link rel="stylesheet" href="css/nice-select.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/gijgo.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/slicknav.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- <link rel="stylesheet" href="css/responsive.css"> -->
<style type="text/css">
 	footer {
	  position: fixed;
	  bottom: 0;
	  width: 100%;
	}
</style>
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- header-start -->
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-2">
                            <div class="logo">
                                <a href="index.html">
                                    <img src="img/ghmedic.png"  alt=""/>
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-7">
                            <div class="main-menu  d-none d-lg-block">
                                <nav>
                                    <ul id="navigation">
                                        <li>&nbsp;</li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <!-- welcome_docmed_area_start -->
    <div class="welcome_docmed_area" style="min-height: 600px;">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                  <br><br><br><br><br><br><br>
                        <center><h2>OCURRIO UN ERROR</h2></center>
                        Si tiene problemas para acceder a la información de su factura, favor de contactar al vendedor que realizo la cotización para usted.
                </div>
            </div>
        </div>
    </div>
    <!-- welcome_docmed_area_end -->

    

<!-- footer start -->
    <footer class="footer">
            <div class="copy-right_text">
                <div class="container">
                    <div class="footer_border"></div>
                    <div class="row">
                        <div class="col-xl-12">
                            <p class="copy_right text-center">
                                Copyright &copy; Timlid <script>document.write(new Date().getFullYear());</script>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </footer>
<!-- footer end  -->

    <!-- JS here -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/vendor/jquery-1.12.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/ajax-form.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/scrollIt.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/nice-select.min.js"></script>
    <script src="js/jquery.slicknav.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/gijgo.min.js"></script>
    <!--contact js-->
    <script src="js/contact.js"></script>
    <script src="js/jquery.ajaxchimp.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/mail-script.js"></script>

    <script src="js/main.js"></script>
    <script>
      $("#btnGuardar").click(function(){
        var mensaje = $('#txtMensaje').val().trim();
        var cotizacion = <?=$FKCotizacion?>;

        if(mensaje === ''){
          $("#txtMensaje")[0].reportValidity();
          $("#txtMensaje")[0].setCustomValidity('Completa este campo.');
          return;
        }
        
      <?php date_default_timezone_set('America/Mexico_City'); ?>
      var fecha = "<?php echo date('d/m/Y H:i:s', time()); ?>";
      var nombreCliente = '<?php echo $nombreCliente;?>';
      var idCliente = <?php echo $idCliente;?>;

      var myData={"Mensaje":mensaje, "Cotizacion":cotizacion, "Fecha" : fecha};

      $.ajax({
          url : "functions/agregarMensaje.php",
          type: "POST",
          data : myData,
          success: function(data,status,xhr)
          {
            if(data == 'exito'){

                var agregarLista =  '<li class="timeline-inverted">' +
                                    '<div class="timeline-badge warning"><i class="glyphicon glyphicon-credit-card"></i></div>' +
                                     '<div class="timeline-panel">' +
                                      '<div class="timeline-heading">' +
                                        '<h4 class="timeline-title">' + nombreCliente + '</h4>' +
                                      '</div>' +
                                      '<div class="timeline-"body">' +
                                        '<p>' + mensaje + '</p>' +
                                      '</div>' +
                                      '<hr>' +
                                      '<div class="row">' +
                                        '<div class="col-md-12" align="right">' +
                                          '<small>' + fecha + '</small>' + 
                                        '</div>' + 
                                      '</div>' +
                                    '</div>' +
                                  '</li>';
                
                $( "#add-timeline" ).addClass( "timeline" );
                $( "#nuevo_mensaje" ).remove();
                $( "#add-timeline").prepend(agregarLista);


                $("#mostrarProyecto").css("display", "block");
                setTimeout(
                function()
                {
                  $("#mostrarProyecto").css("display", "none");
                  $('#agregar_Proyecto').modal('toggle');
                  $('#txtMensaje').val("");

                }, 2000);
            }
            else{
              
              $("#mostrarErrorProyecto").css("display", "block");
                setTimeout(
                function()
                {
                  $("#mostrarErrorProyecto").css("display", "none");
                }, 2000);
            }

          }

      });


    });
</script>
</body>

</html>