<script type="text/javascript">

    
    let tipoNomina = <?=$row_datos_nomina['tipo_id']?>;
    
    let cantidad_clave = <?=$cantidad_clave?>;
    let cantidad_clave_imss = <?=$cantidad_clave_imss?>;
    let cantidad_clave_isr = <?=$cantidad_clave_isr?>;
    let cantidad_clave_horasextra = <?=$cantidad_clave_horasextra?>;
    let cantidad_clave_primadominical = <?=$cantidad_clave_primadominical?>;
    let cantidad_clave_primavacacional = <?=$cantidad_clave_primavacacional?>;
    let cantidad_clave_otrosingresos = <?=$cantidad_clave_otrosingresos?>;
    let cantidad_clave_descuentoincapacidad = <?=$cantidad_clave_descuentoincapacidad?>;
    let cantidad_clave_ausencia = <?=$cantidad_clave_ausencia?>;
    let periodoPago = <?=$row_datos_nomina['DiasPago']?>;
    let diasAguinaldo = <?=$diasAguinaldo?>;*/
	
	
	
	
	
    if(cantidad_clave == 1 || cantidad_clave_imss == 1 || cantidad_clave_isr == 1 || cantidad_clave_horasextra == 1 || cantidad_clave_primadominical == 1 || cantidad_clave_primavacacional == 1 || cantidad_clave_otrosingresos == 1 || cantidad_clave_otrosingresos == 1 || cantidad_clave_ausencia == 1){
      
      if(cantidad_clave == 1){
        $("#claveMostrarSalario").css("display","block");
      }
      if(cantidad_clave_imss == 1){
        $("#claveMostrarIMSS").css("display","block");
      }
      if(cantidad_clave_isr == 1){
        $("#claveMostrarISR").css("display","block");
      }

      if(cantidad_clave_horasextra == 1){
        $("#claveMostrarHorasExtra").css("display","block");
      }
      if(cantidad_clave_primadominical == 1){
        $("#claveMostrarPrimaDominical").css("display","block");
      }
      if(cantidad_clave_primavacacional == 1){
        $("#claveMostrarPrimaVacacional").css("display","block");
      }

      if(cantidad_clave_otrosingresos == 1){
        $("#claveMostrarOtrosIngresos").css("display","block");
      }
      if(cantidad_clave_descuentoincapacidad == 1){
        $("#claveMostrarDescuentoIncapacidad").css("display","block");
      }
      if(cantidad_clave_ausencia == 1){
        $("#claveMostrarAusencia").css("display","block");
      }

      $('#agregar_clave_salario').modal({backdrop: 'static', keyboard: false})  
    }

    $("#agregarClaveSalario").click(function(){
      
      let clave = $("#txtClaveSalario").val().trim();
      let claveIMSS = $("#txtClaveIMSS").val().trim();
      let claveISR = $("#txtClaveISR").val().trim();
      let claveHorasExtra = $("#txtClaveHorasExtra").val().trim();
      let clavePrimaDominical = $("#txtClavePrimaDominical").val().trim();
      let clavePrimaVacacional = $("#txtClavePrimaVacacional").val().trim();
      let claveOtrosIngresos = $("#txtClaveOtrosIngresos").val().trim();
      let claveDescuentoIncapacidad = $("#txtClaveDescuentoIncapacidad").val().trim();
      let claveAusencia = $("#txtClaveAusencia").val().trim();
      let msg;
      let token = $("#csr_token_UT5JP").val();
      let agregarAguinaldo = 0;

      $("#CancelClaveSalario").prop("disabled", true);
      $("#agregarClaveSalario").prop("disabled", true);

      if(cantidad_clave == 1){
        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el salario.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_imss == 1){
        if (claveIMSS == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del IMSS.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveIMSS.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_isr == 1){
        if (claveISR == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del ISR.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveISR.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_horasextra == 1){
        if (claveHorasExtra == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para las horas extras.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveHorasExtra.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_primadominical == 1){
        if (clavePrimaDominical == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de la prima dominical.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clavePrimaDominical.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_primavacacional == 1){
        if (clavePrimaVacacional == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de la prima vacacional.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(clavePrimaVacacional.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_otrosingresos == 1){
        if (claveOtrosIngresos == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para otros ingresos por salarios.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveOtrosIngresos.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_descuentoincapacidad == 1){
        if (claveDescuentoIncapacidad == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave del descuento por incapacidad.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveDescuentoIncapacidad.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }

      if(cantidad_clave_ausencia == 1){
        if (claveAusencia == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar la clave de ausencia.",
          });
          $("#CancelClaveSalario").prop("disabled", false);
          $("#agregarClaveSalario").prop("disabled", false);
          return;
        }

        if(claveAusencia.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelClaveSalario").prop("disabled", false);
            $("#agregarClaveSalario").prop("disabled", false);
            return;
        }
      }


        $.ajax({
          type: 'POST',
          url: 'functions/agregarClavesNecesarias.php',
          data: {
            claveSalario: clave,
            existeclaveSalario: cantidad_clave,
            claveIMSS: claveIMSS,
            existeclaveIMSS: cantidad_clave_imss,
            claveISR: claveISR,
            existeclaveISR: cantidad_clave_isr,
            claveHorasExtra: claveHorasExtra,
            existeclaveHorasExtra: cantidad_clave_horasextra,
            clavePrimaDominical: clavePrimaDominical,
            existeclavePrimaDominical: cantidad_clave_primadominical,
            clavePrimaVacacional: clavePrimaVacacional,
            existeclavePrimaVacacional: cantidad_clave_primavacacional,
            claveOtrosIngresos: claveOtrosIngresos,
            existeclaveIngresos: cantidad_clave_otrosingresos,
            claveDescuentoIncapacidad: claveDescuentoIncapacidad,
            existeclaveDescuentoIncapacidad: cantidad_clave_descuentoincapacidad,
            claveAusencia: claveAusencia,
            existeclaveAusencia: cantidad_clave_ausencia,
            csr_token_UT5JP: token
          },
          success: function(data) {

            if (data == "exito") {

               Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se han agregado las claves"
               });

               $("#agregar_clave_salario").modal("hide");

            } 

            if (data == "existe-clave-salario" || data == "existe-clave-imss" || data == "existe-clave-isr" ||
                data == "existe-clave-HorasExtra" || data == "existe-clave-PrimaDominical" || data == "existe-clave-PrimaVacacional" ||
                data == "existe-clave-OtrosIngresos" || data == "existe-clave-DescuentoIncapacidad" || data == "existe-clave-Ausencia") {

              if (data == "existe-clave-salario"){
                msg = "La clave del salario ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-imss"){
                msg = "La clave del imss ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-isr"){
                msg = "La clave del isr ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-HorasExtra"){
                msg = "La clave de las horas extra ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-PrimaDominical"){
                msg = "La clave de la prima dominical ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-PrimaVacacional"){
                msg = "La clave de la prima vacacional ya esta agregada, ingresa una diferente.";
              }

              if (data == "existe-clave-OtrosIngresos"){
                msg = "La clave de otros ingresos ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-DescuentoIncapacidad"){
                msg = "La clave del descuento por incapacidad ya esta agregada, ingresa una diferente.";
              }
              if (data == "existe-clave-Ausencia"){
                msg = "La clave de ausencia ya esta agregada, ingresa una diferente.";
              }
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: msg
              });

              $("#CancelClaveSalario").prop("disabled", false);
              $("#agregarClaveSalario").prop("disabled", false);

            }

            if (data == "existe-concepto-salario" || data == "existe-concepto-imss" || data == "existe-concepto-isr" ||
                data == "existe-concepto-HorasExtra" || data == "existe-concepto-PrimaDominical" || data == "existe-concepto-PrimaVacacional" ||
                data == "existe-concepto-OtrosIngresos" || data == "existe-concepto-DescuentoIncapacidad" || data == "existe-concepto-Ausencia") {

              if (data == "existe-concepto-salario"){
                msg = "El concepto del salario ya esta agregada.";
                cantidad_clave = 0;
                $("#claveMostrarSalario").css("display","none");
              }
              if (data == "existe-concepto-imss"){
                msg = "El concepto del imss ya esta agregada.";
                cantidad_clave_imss = 0;
                $("#claveMostrarIMSS").css("display","none");
              }
              if (data == "existe-concepto-isr"){
                msg = "El concepto del isr ya esta agregada.";
                cantidad_clave_isr = 0;
                $("#claveMostrarISR").css("display","none");
              }

              if (data == "existe-concepto-HorasExtra"){
                msg = "El concepto de las horas extras ya esta agregado.";
                cantidad_clave_horasextra = 0;
                $("#claveHorasExtra").css("display","none");
              }
              if (data == "existe-concepto-PrimaDominical"){
                msg = "El concepto de la prima dominical ya esta agregado.";
                cantidad_clave_primadominical = 0;
                $("#clavePrimaDominical").css("display","none");
              }
              if (data == "existe-concepto-PrimaVacacional"){
                msg = "El concepto de la prima vacacional ya esta agregado.";
                cantidad_clave_primavacacional = 0;
                $("#clavePrimaVacacional").css("display","none");
              }

              if (data == "existe-concepto-OtrosIngresos"){
                msg = "El concepto de otros ingresos por salario ya esta agregado.";
                cantidad_clave_otrosingresos = 0;
                $("#claveOtrosIngresos").css("display","none");
              }
              if (data == "existe-concepto-DescuentoIncapacidad"){
                msg = "El concepto de de descuento por incapacidad ya esta agregado.";
                cantidad_clave_descuentoincapacidad = 0;
                $("#claveDescuentoIncapacidad").css("display","none");
              }
              if (data == "existe-concepto-Ausencia"){
                msg = "El concepto de ausencia ya esta agregado.";
                cantidad_clave_ausencia = 0;
                $("#claveAusencia").css("display","none");
              }
              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: msg
              });

            }


          }
        });


    });

  function mostrarTimbrado(){
    let txt = '<center>' +
                '<div class="col-lg-6">' +
                    '<button id="timbrarNominaCompleta" class="btn btn-custom btn-custom--border-blue">Timbrar nómina</button>' +
                '</div>' +
                '<div class="row" style="position: relative; top:23px;">' +
                  '<div class="col-lg-6">' +
                    '<button id="timbrarNominaIndividual" class="btn btn-custom btn-custom--border-blue">Timbrar empleado</button>' +
                  '</div>' +
                '</div>' +
              '</center>';

    $("#timbradoNomina").html(txt);

    if(tipoNomina == 1){
      $("#funcionesNomina").css("display","block");
    }     
    
    $("#mostrarFuncionesFactura").css("display","none");
    $("#mostrarFuncionesFactura").html("");  

    let idEmpleado = $("#empleado").val();  
    loadDataTables(idNomina, idEmpleado);
  }

  function mostrarOpcionesFactura(){

    let txt = '<center>' +
                '<div class="row" style="position: relative; top:23px;">' +
                  '<div class="col-lg-6">' +
                    '<button id="cancelarNominaIndividual" class="btn btn-custom btn-custom--red">Cancelar</button>' +
                  '</div>' +
                  '<div class="col-lg-6">' +
                    '<b>Fecha de timbrado:</b><br>' + fechaTimbrado +
                  '</div>' +
                '</div>' +
              '</center>';

    $("#timbradoNomina").html(txt);

      
    let funcionesFactura = '<center>' +
                            '<div class="row">' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_pdf.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar pdf</a>' +
                              '</div>' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_xml.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar xml</a>' +
                              '</div>' +
                              '<div class="col-lg-3">' +
                                '<a href="functions/download_zip.php?value=' + idFactura + '" class="btn btn-custom btn-custom--blue" target="_blank">Descargar zip</a>' +
                               '</div>' +
                               '<div class="col-lg-3">' + 
                                '<a href="#" class="btn btn-custom btn-custom--blue" id="send_email" data-toggle="modal" data-target="#enviarFactura">Enviar por email</a>' +
                              '</div>' +
                            '</div>' +
                          '</center>';

    $("#funcionesNomina").css("display","none");     
    $("#mostrarFuncionesFactura").css("display","block");
    $("#mostrarFuncionesFactura").html(funcionesFactura);

    let idEmpleado = $("#empleado").val();
    loadDataTables(idNomina, idEmpleado);
  }


    $("#cbboxExcluir").click(function(){

      let token = $("#csr_token_UT5JP").val(); 
      let idEmpleado = $("#empleado").val();
      let textoSweet, activo, msgEstado;

      if(idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });

        if ($('#cbboxExcluir').is(":checked")){
          $('#cbboxExcluir').prop('checked', false);
        }
        else{
          $('#cbboxExcluir').prop('checked', true);
        }
        return;
      }

      if ($('#cbboxExcluir').is(":checked"))
      {
        textoSweet = "Se excluirá al empleado de la nómina.";
        activo = 1;
        msgEstado = "Se ha excluido al empleado de la nómina.";

      }
      else{
        textoSweet = "Se volverá a incluir al empleado en la nómina.";
        activo = 0;
        msgEstado = "Se ha incluido al empleado en la nómina.";
      }
      

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: textoSweet,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/estadoNominaEmpleado.php',
              data: {
                idNomina: idNomina,
                idEmpleado: idEmpleado,
                activo: activo,
                csr_token_UT5JP: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: msgEstado
                  });

                } 
                else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbboxExcluir').is(":checked")){
              $('#cbboxExcluir').prop('checked', false);
            }
            else{
              $('#cbboxExcluir').prop('checked', true);
            }

          }
        });
      
    });

    $("#cargarPercepcionDeduccion,#cargarDeduccion").click(function(){

      let idEmpleado = $("#empleado").val();
      let token = $("#csr_token_UT5JP").val(); 

      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
      }

      $("#agregar_percepcion").modal('show');

      let element = this.id;
      let tipo;
      if(element == "cargarPercepcionDeduccion"){
        tipo = 1;
        $("#tipoMovimiento").val(1);
        $("#percepcionDeducionTitulo").html("Agregar percepción");
        $("#mostrarExento").css("display","block");
      }
      else{
        tipo = 2;
        $("#tipoMovimiento").val(2);
        $("#percepcionDeducionTitulo").html("Agregar deducción");
        $("#mostrarExento").css("display","none");
      }

        selectConcepto.destroy();

        $.ajax({
          type: 'POST',
          url: 'functions/cargarConceptosNomina.php',
          data: {
            csr_token_UT5JP: token,
            tipo: tipo
          },
          success: function(data) {

             $("#cmbConcepto").html(data);

          } 
        });

        selectConcepto = new SlimSelect({
          select: '#cmbConcepto',
          deselectLabel: '<span class="">✖</span>'
        });
    });

    $("#agregarPercepcionDeduccion").click(function(){

      if($("#cmbConcepto").val() == null){
        Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona el concepto"
          });
        return;
      }
      
      $("#CancelaragregarPercepcionDeduccion").prop("disabled", true);
      $("#agregarPercepcionDeduccion").prop("disabled", true);

      let concepto = $("#cmbConcepto").val().trim();
      let idEmpleado = $("#empleado").val();
      let importeObj = numeral($("#txtImporte").val().trim());
      let importe = importeObj.value();
      let tipo = $('#tipoMovimiento').val();
      let token = $("#csr_token_UT5JP").val(); 
      let exento, clave = "";

      if ($('#cbboxExento').is(":checked")){
        exento = 1;
      }
      else{
        exento = 0;
      }

      if(tipo == 2){
        exento = 0;
      }

      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
          $("#agregarPercepcionDeduccion").prop("disabled", false);
          return;
      }
      
      if(concepto == ""){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el concepto"
          });
          $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
          $("#agregarPercepcionDeduccion").prop("disabled", false);
          return;
      }

      if(concepto.length >= 50){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "No puedes agregar un concepto de más de 50 caracteres."
          });
          $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
          $("#agregarPercepcionDeduccion").prop("disabled", false);
          return;
      }

      if(importe == "" || importe == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
          $("#agregarPercepcionDeduccion").prop("disabled", false);
          return;
      }

      //es necesario la clave
      if(disponibleClave == 1){
        clave = $("#txtClave").val().trim();

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
          $("#agregarPercepcionDeduccion").prop("disabled", false);
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
            $("#agregarPercepcionDeduccion").prop("disabled", false);
            return;
        }
      }

      $.ajax({
          type: 'POST',
          url: 'functions/agregarNominaEmpleado.php',
          data: {
            idNominaEmpleado: idNominaEmpleado,
            idEmpleado: idEmpleado,
            csr_token_UT5JP: token,
            concepto: concepto,
            importe: importe,
            tipo: tipo,
            exento: exento,
            idNomina: idNomina,
            disponibleClave: disponibleClave,
            clave: clave
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se agregó el concepto a la nómina."
              });

              $("#txtImporte").val("");
              $("#txtClave").val("");
              $("#claveMostrar").css("display","none");
              disponibleClave = 0;

              selectConcepto.set([]);

              $('#cbboxExento').prop('checked', false);
              $(".percepcion").prop("checked", true);
              
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

              $("#agregar_percepcion").modal('hide');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 
            if (data == "fallo") {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if (data == "fallo-agregar") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes agregar un concepto a una nómina timbrada."
                });
                $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
                $("#agregarPercepcionDeduccion").prop("disabled", false);

            }

            if (data == "existe-concepto") {
              Lobibox.notify("warning", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "El concepto ya esta agregado en la nómina."
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }

            if(data == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }

            if(data == "existe-concepto-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
              $("#CancelaragregarPercepcionDeduccion").prop("disabled", false);
              $("#agregarPercepcionDeduccion").prop("disabled", false);
            }
          }
        });

    });
    
    let disponibleClave = 0;
    $("#cmbConcepto").change(function(){

      let val = $( "#cmbConcepto option:selected" ).text();
      let array = val.split("-");

      if(array.length == 3){
        $("#claveMostrar").css("display","none");
      }

      if(array.length == 2){
        disponibleClave = 1;
        $("#claveMostrar").css("display","block");
      }
    });

    $("#editarPercepcionDeduccion").click(function(){

      let idEmpleado = $("#empleado").val();
      let idDetalleNomina = $("#idDetalleNomina").val().trim();
      let importeObj = numeral($("#txtImporteEdit").val().trim());
      let importe = importeObj.value();
      let tipo = $('#txtTipoEdit').val().trim();
      let token = $("#csr_token_UT5JP").val(); 
      let tipo_concepto = $("#editTipoConcepto").val();
      let exento;

      $("#CancelarEditarPercepcionDeduccion").prop("disabled", true);
      $("#editarPercepcionDeduccion").prop("disabled", true);

      if ($('#cbboxExentoEdit').is(":checked")){
        exento = 1;
      }
      else{
        exento = 0;
      }

      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
          $("#editarPercepcionDeduccion").prop("disabled", false);
          return;
      }

      if(importe == "" || importe == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
          $("#editarPercepcionDeduccion").prop("disabled", false);
          return;
      }

      $.ajax({
          type: 'POST',
          url: 'functions/editarNominaEmpleado.php',
          data: {
            idDetalleNomina: idDetalleNomina,
            csr_token_UT5JP: token,
            importe: importe,
            tipo: tipo,
            exento: exento,
            idNominaEmpleado: idNominaEmpleado,
            idNomina: idNomina,
            idEmpleado: idEmpleado,
            tipo_concepto: tipo_concepto
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se modificó el concepto de la nómina."
              });
              $('#editar_percepcion').modal('hide');
              $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
              $("#editarPercepcionDeduccion").prop("disabled", false);
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 
            if (data == "fallo-edicion") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes editar un concepto de una nómina timbrada."
                });
                $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
                $("#editarPercepcionDeduccion").prop("disabled", false);
            }

            if (data == "fallo") {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });
              $("#CancelarEditarPercepcionDeduccion").prop("disabled", false);
              $("#editarPercepcionDeduccion").prop("disabled", false);

            }
          }
        });

    });


    function cargarDetalleNominaEmpleado(idDetalleNomina,tipo){

      let token = $("#csr_token_UT5JP").val();

        $.ajax({
          type: 'POST',
          url: 'functions/cargarDetalleNominaEmpleado.php',
          data: {
            idDetalleNomina: idDetalleNomina,
            tipo: tipo,
            csr_token_UT5JP: token
          },
          success: function(data) {

            var datos = JSON.parse(data);

            if (datos.estatus == "exito") {

                $("#idDetalleNomina").val(datos.idDetalleNomina);
                $("#txtConceptoEdit").val(datos.concepto);
                $("#txtImporteEdit").val(datos.importe);
                $("#editTipoConcepto").val(datos.tipo_concepto);
                $("#editExento").css("display","block");

                //salarios , prima dominical
               if(datos.tipo_concepto == 1 || datos.tipo_concepto == 6){
                $("#editExento").css("display","none");  

               }

               if(datos.tipo_concepto == 1){

                  $("#percepcionDeducionTituloEdit").html("Editar salario");
                  $("#editar_percepcion").modal('toggle');
               }

               //percepciones/deducciones
               if(datos.tipo_concepto == 2){
                    
                   $("#editPercDed").css("display","block");
                   $("#editExento").css("display","block");
                   

                   if(datos.exento == 1){
                    $('#cbboxExentoEdit').prop('checked', true);
                   }
                   else{
                    $('#cbboxExentoEdit').prop('checked', false);
                   }

                    $("#editar_percepcion").modal('toggle');

                    if(tipo == 1){
                      $("#percepcionDeducionTituloEdit").html("Editar percepción");
                    }
                    else{
                      $("#percepcionDeducionTituloEdit").html("Editar deducción");
                    }

                    $("#txtTipoEdit").val(tipo);

               }


               if(datos.tipo_concepto == 3 || datos.tipo_concepto == 7 || datos.tipo_concepto == 8){

                   $("#txtHorasEdit").val(datos.horas);
                   $("#txtImporteHorasEdit").val(datos.importe);
                   $("#idDetalleNominaHorasExtras").val(datos.iddetallenomina);

                   if(datos.tipo_concepto == 3){
                      $("#tipoHorasEdit").val("Sencillas");
                      $("#txttipoHorasEdit").val("1");
                      $("#txtHorasEdit").attr("max",30);
                   }
                   if(datos.tipo_concepto == 7){
                      $("#tipoHorasEdit").val("Doble");
                      $("#txttipoHorasEdit").val("2");
                      $("#txtHorasEdit").attr("max",9);
                   }
                   if(datos.tipo_concepto == 8){
                      $("#tipoHorasEdit").val("Triple");
                      $("#txttipoHorasEdit").val("3");
                      $("#txtHorasEdit").attr("max",30);
                   }

                   $("#editar_horas_extras").modal('toggle');

               }
               

               if(datos.tipo_concepto == 4){
                  
                   $("#txtIdDetalleNominaTurnos").val(datos.iddetallenomina);
                   $("#txtTurnosExtraEdit").val(datos.dias);
                   $("#txtImporteTurnoEdit").val(datos.importe);

                   $("#turnosExtraModalEdit").modal('toggle');

               }

               if(datos.tipo_concepto == 5){
                  
                   $("#txtIdDetalleNominaFaltas").val(datos.iddetallenomina);
                   $("#txtDiasFaltaEdit").val(datos.dias);
                   $("#txtImporteDiasFaltaEdit").val(datos.importe);
                   $("#txtMotivoEdit").val(datos.concepto);

                   if(datos.relacion_tipo_deduccion_id == 6){
                    $("#mostrarEditIncapacidad").css("display", "block");
                    $("#txtmostrarIncapacidad").val(datos.incapacidad);
                   }
                   else{
                    $("#mostrarEditIncapacidad").css("display", "none");
                    $("#txtmostrarIncapacidad").val("");
                   }


                   $("#diasFaltaModalEdit").modal('toggle');

               }

            } 
            else {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });

            }
          }
        });

    }


    function loadDataTables(idNomina, idEmpleado){
        var table = $('#tblNominaEmpleado').DataTable();
        table.destroy();

        var idioma_espanol = {
          sProcessing: "Procesando...",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sSearch: "<img src='../../img/timdesk/buscar.svg' width='20px' />",
          sLoadingRecords: "Cargando...",
          searchPlaceholder: "Buscar...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "<img src='../../img/icons/pagination.svg' width='20px'/>",
            sPrevious:
              "<img src='../../img/icons/pagination.svg' width='20px' style='transform: scaleX(-1)'/>",
            },
        };

        $("#tblNominaEmpleado").dataTable({
          language: idioma_espanol,
          dom: "lfrtip",
          scrollX: true,
          lengthChange: true,
          info: false,
          ordering: false,
          pageLength: 100,
          paging: false,
          ajax : {
                  url : 'functions/function_nomina_extraordinaria_empleado.php',
                  data: {
                    'idEmpleado' : idEmpleado,
                    'idNomina': idNomina
                  },
                  type : 'POST'
          },
          columns: [
            { data: "concepto" },
            { data: "tipo" },
            { data: "importe gravado" },
            { data: "importe exento" },
            { data: "exento" },
            { data: "total" },
          ],
          columnDefs: [
            {
                targets: 2,
                className: 'align-right'
            },
            {
                targets: 3,
                className: 'align-center'
            },
            {
                targets: 4,
                className: 'align-right'
            }
          ],
        });
    }

    function eliminarDetalleNomina(idDetalleNomina, tipo){
    let token = $("#csr_token_UT5JP").val(); 
    let idEmpleado = $("#empleado").val();

    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        actions: "d-flex justify-content-around",
        confirmButton: "btn-custom btn-custom--border-blue",
        cancelButton: "btn-custom btn-custom--blue",
      },
      buttonsStyling: false,
    });

    swalWithBootstrapButtons
      .fire({
        title: "¿Desea continuar?",
        text: "Se eliminará este concepto de la nómina.",
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
        confirmButtonText: '<span class="verticalCenter">Eliminar</span>',
        reverseButtons: true,
      })
      .then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            type: 'POST',
            url: 'functions/eliminar_DetalleNomina.php',
            data: {
              idDetalleNomina: idDetalleNomina,
              idNominaEmpleado: idNominaEmpleado,
              idNomina: idNomina,
              idEmpleado : idEmpleado,
              tipo: tipo,
              csr_token_UT5JP: token
            },
            success: function(data) {

              if (data == "exito") {

                Lobibox.notify("success", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/checkmark.svg',
                  msg: "Se ha eliminado el concepto de la nómina"
                });

                $('#tblNominaEmpleado').DataTable().ajax.reload();

              } 
              else if (data == "fallo-cancelacion") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes eliminar un concepto de una nómina timbrada."
                });

              }
              else if (data == "fallo-salario") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes eliminar el concepto por salario."
                });

              }
              else {

                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "Ocurrio un error, vuelva intentarlo."
                });

              }
            }
          });



        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {}
      });
  }

  
  let agregarClaveTurnoExtra = 0;
  $("#turnosExtra").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    let idclave = 33;
    $.ajax({
          type: 'POST',
          url: 'functions/mostrarClaves.php',
          data: {
            idclave: idclave,
            tipo: 1,
            csr_token_UT5JP: token
          },
          success: function(data) {

            if(data < 1){
              $("#claveMostrarTurnoExtra").css("display","block");
              agregarClaveTurnoExtra = 1;
            }
            else{
              $("#claveMostrarTurnoExtra").css("display","none");
              agregarClaveTurnoExtra = 0;
            }
          }
        });

    $("#turnosExtraModal").modal('toggle');

  });


  $("#txtTurnosExtra,#txtTurnosExtraEdit").change(function(){

      let element = this.id;
      let turnos;

      if(element == "txtTurnosExtra"){
        $("#agregarTurnosExtra").prop("disabled", true);
        turnos = $("#txtTurnosExtra").val().trim();
      }

      if(element == "txtTurnosExtraEdit"){
        $("#modificarTurnosExtra").prop("disabled", true);
        turnos = $("#txtTurnosExtraEdit").val().trim();
      }
      
      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();


      if(turnos == "" || turnos == null || turnos < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de turnos."
        });
        return;
      }
        
      if(isNaN(turnos)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de turnos."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioDiario.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                diasfalta: turnos
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los turnos extra.",
                });
                $("#agregarTurnosExtra").prop("disabled", false);
                $("#modificarTurnosExtra").prop("disabled", false);

              }
              else{
                
                if(element == "txtTurnosExtra"){
                  $("#txtImporteTurno").val(r);
                  $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(element == "txtTurnosExtraEdit"){
                  $("#txtImporteTurnoEdit").val(r);
                  $("#modificarTurnosExtra").prop("disabled", false);
                }

              }
        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los turnos extra.",
          });
          $("#agregarTurnosExtra").prop("disabled", false);
          $("#modificarTurnosExtra").prop("disabled", false);
        }
      });

  });


  $("#agregarTurnosExtra").click(function(){

    $("#agregarTurnosExtra").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasExtra = $("#txtTurnosExtra").val().trim();
    let ImporteTurnosObj = numeral($("#txtImporteTurno").val().trim());
    let ImporteTurnos = ImporteTurnosObj.value();
    let token = $("#csr_token_UT5JP").val();
    let claveHorasExtra = $("#txtClaveTurnoExtra").val().trim();

    if(diasExtra == "" || diasExtra == null || diasExtra < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#agregarTurnosExtra").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasExtra)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#agregarTurnosExtra").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#agregarTurnosExtra").prop("disabled", false);
          return;
    }

    if(ImporteTurnos == "" || ImporteTurnos == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#agregarTurnosExtra").prop("disabled", false);
          return;
    }

    if(agregarClaveTurnoExtra == 1){
        if (claveHorasExtra == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para los turnos extras.",
          });
          $("#agregarTurnosExtra").prop("disabled", false);
          return;
        }

        if(claveHorasExtra.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#agregarTurnosExtra").prop("disabled", false);
            return;
        }
      }

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_TurnosExtra.php',
          data: { 
                  diasExtra : diasExtra,
                  ImporteTurnos : ImporteTurnos,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  claveHorasExtra: claveHorasExtra,
                  agregarClaveTurnoExtra: agregarClaveTurnoExtra
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Turnos extra agregados",
                  });
                  $("#txtTurnosExtra").val("");
                  $("#txtImporteTurno").val("");
                  $("#agregarTurnosExtra").prop("disabled", false);
                  $("#turnosExtraModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-OtrosIngresos"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de turnos extras(otros ingresos por salarios) ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "existe-concepto-OtrosIngresos"){

                    agregarClaveTurnoExtra = 0;
                    $("#claveMostrarTurnoExtra").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de turnos extra(otros ingresos por salario) ya esta agregado."
                    });
                    $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más de 3 turnos extra por semana",
                  });
                  $("#agregarTurnosExtra").prop("disabled", false);
                }

                if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#agregarTurnosExtra").prop("disabled", false);
                }
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#agregarTurnosExtra").prop("disabled", false);
          }
        });


  });


  $("#modificarTurnosExtra").click(function(){

    $("#modificarTurnosExtra").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasExtra = $("#txtTurnosExtraEdit").val().trim();
    let idDetalleNomina = $("#txtIdDetalleNominaTurnos").val().trim();
    let ImporteTurnosObj = numeral($("#txtImporteTurnoEdit").val().trim());
    let ImporteTurnos = ImporteTurnosObj.value();
    let token = $("#csr_token_UT5JP").val();

    if(diasExtra == "" || diasExtra == null || diasExtra < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#modificarTurnosExtra").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasExtra)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de turnos."
      });
      $("#modificarTurnosExtra").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#modificarTurnosExtra").prop("disabled", false);
          return;
    }

    if(ImporteTurnos == "" || ImporteTurnos == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#modificarTurnosExtra").prop("disabled", false);
          return;
    }

    $.ajax({
          type: 'POST',
          url: 'functions/editar_TurnosExtra.php',
          data: { 
                  diasExtra : diasExtra,
                  ImporteTurnos : ImporteTurnos,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idDetalleNomina: idDetalleNomina,
                  idNomina: idNomina
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Turnos extra modificados",
                  });
                  $("#txtTurnosExtra").val("");
                  $("#txtImporteTurno").val("");
                  $("#modificarTurnosExtra").prop("disabled", false);
                  $("#turnosExtraModalEdit").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más de 3 turnos extra por semana",
                  });
                  $("#modificarTurnosExtra").prop("disabled", false);
                }

                if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#modificarTurnosExtra").prop("disabled", false);
                }
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#modificarTurnosExtra").prop("disabled", false);
          }
        });


  });

  let agregarClavePrimaDominical = 0;
  $("#primaDominical").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val(); 
    
    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    let idclave = 15;
    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarPrimaDominicalUnica").css("display","block");
          agregarClavePrimaDominical = 1;
        }
        else{
          $("#claveMostrarPrimaDominicalUnica").css("display","none");
          agregarClavePrimaDominical = 0;
        }
      }
    });

    $.ajax({
          type: 'POST',
          url: 'functions/calcularDiaDomingo.php',
          data: { 
                  csr_token_UT5JP : token,
                  idEmpleado: idEmpleado,
                },
          success: function(r) {

                if(r == "fallo"){
                  Lobibox.notify("warning", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No se pudo cargar el calculo de la prima dominical pero puedes ingresar el importe",
                  });
                  $("#turnosExtraModal").prop("disabled", false);
                }
                else{

                  $("#txtPrimaDominical").val(r);

                }

          
          },
          error: function(){
            Lobibox.notify("warning", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "No se pudo cargar el calculo de la prima dominical pero puedes ingresar el importe",
            });
            $("#turnosExtraModal").prop("disabled", false);
          }
        });

    $("#primaDominicalModal").modal('toggle');

  });


  $("#agregarPrimaDominical").click(function(){

    $("#agregarPrimaDominical").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let primaDominicalObj = numeral($("#txtPrimaDominical").val().trim());
    let primaDominical = primaDominicalObj.value();
    let token = $("#csr_token_UT5JP").val(); 
    let clavePrimaDominicalUnica = $("#txtClavePrimaDominicalUnica").val();

    if(primaDominical == "" || primaDominical == null || primaDominical < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa el importe de la prima dominical."
      });
      $("#agregarPrimaDominical").prop("disabled", false);
      return;
    }
      
    if(isNaN(primaDominical)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número."
      });
      $("#agregarPrimaDominical").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#agregarPrimaDominical").prop("disabled", false);
          return;
    }

    if(agregarClavePrimaDominical == 1){
        if (clavePrimaDominicalUnica == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para la prima dominical.",
          });
          $("#agregarPrimaDominical").prop("disabled", false);
          return;
        }

        if(clavePrimaDominicalUnica.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#agregarPrimaDominical").prop("disabled", false);
            return;
        }
      }

    $.ajax({
          type: 'POST',
          url: 'functions/agregar_PrimaDominical.php',
          data: { 
                  primaDominical : primaDominical,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  agregarClavePrimaDominical : agregarClavePrimaDominical,
                  clavePrimaDominicalUnica: clavePrimaDominicalUnica
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Prima dominical agregada",
                  });
                  $("#txtPrimaDominical").val("");
                  $("#agregarPrimaDominical").prop("disabled", false);
                  $("#primaDominicalModal").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-PrimaDominicalUnica"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave de prima dominical ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "existe-concepto-PrimaDominicalUnica"){

                    agregarClavePrimaDominical = 0;
                    $("#clavePrimaDominicalUnica").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de prima dominical ya esta agregado."
                    });
                    $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "domingopaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "Sólo puedes ingresar un domingo de acuerdo a la cantidad de semanas de la nómina.",
                  });
                  $("#agregarPrimaDominical").prop("disabled", false);
                }

                if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#agregarPrimaDominical").prop("disabled", false);
                }
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#agregarPrimaDominical").prop("disabled", false);
          }
        });


  });



  $("#faltaDias").click(function(){

    let idEmpleado = $("#empleado").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    $("#diasFaltaModal").modal('toggle');

  });

  let agregarClaveFaltas = 0;
  let agregarMotivoIncapacidad = 0;
  $("#cmbMotivo").change(function(){
    let idclave = $("#cmbMotivo").val();
    let token = $("#csr_token_UT5JP").val(); 

    if(idclave == 0){
      $("#claveMostrarFaltas").css("display","none");
      agregarClaveFaltas = 0;
      $("#mostrarIncapacidad").css("display","none");
      agregarMotivoIncapacidad = 0;
    }
    else{
      $.ajax({
        type: 'POST',
        url: 'functions/mostrarClaves.php',
        data: {
          idclave: idclave,
          tipo: 2,
          csr_token_UT5JP: token
        },
        success: function(data) {

          if(data < 1){
            $("#claveMostrarFaltas").css("display","block");
            agregarClaveFaltas = 1;
          }
          else{
            $("#claveMostrarFaltas").css("display","none");
            agregarClaveFaltas = 0;
          }
        }
      });

      if(idclave == 6){
        agregarMotivoIncapacidad = 1;
        $("#mostrarIncapacidad").css("display","block");
      }
    }


  });


  $("#agregarDiasFalta").click(function(){

    $("#agregarDiasFalta").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasFalta = $("#txtDiasFalta").val().trim();
    let ImporteDiasFaltaObj = numeral($("#txtImporteDiasFalta").val().trim());
    let ImporteDiasFalta = ImporteDiasFaltaObj.value();
    let token = $("#csr_token_UT5JP").val();
    let cmbMotivoID = $("#cmbMotivo").val().trim();
    let claveFaltas = $("#txtClaveFaltas").val().trim();
    let claveIncapacidad = $("#cmbTipoIncapacidad").val().trim();

    if(diasFalta == "" || diasFalta == null || diasFalta < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#agregarDiasFalta").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasFalta)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#agregarDiasFalta").prop("disabled", false);
      return;
    }

    if(cmbMotivoID < 1){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Selecciona el motivo de la falta."
      });
      $("#agregarDiasFalta").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#agregarDiasFalta").prop("disabled", false);
          return;
    }

    if(ImporteDiasFalta == "" || ImporteDiasFalta == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#agregarDiasFalta").prop("disabled", false);
          return;
    }

    if(agregarClaveFaltas == 1){
        if (claveFaltas == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave para el motivo de la falta.",
          });
          $("#agregarDiasFalta").prop("disabled", false);
          return;
        }

        if(claveFaltas.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            $("#agregarDiasFalta").prop("disabled", false);
            return;
        }
      }

    if(agregarMotivoIncapacidad == 1){
      if (claveIncapacidad < 1) {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar el motivo de la incapacidad.",
          });
          $("#agregarDiasFalta").prop("disabled", false);
          return;
        }

    }

    $.ajax({
          type: 'POST',
          url: 'functions/faltas_Dias.php',
          data: { 
                  diasFalta : diasFalta,
                  ImporteDiasFalta : ImporteDiasFalta,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina,
                  cmbMotivoID: cmbMotivoID,
                  agregarClaveFaltas : agregarClaveFaltas,
                  claveFaltas: claveFaltas,
                  agregarMotivoIncapacidad: agregarMotivoIncapacidad,
                  claveIncapacidad: claveIncapacidad
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se agrego la falta.",
                  });
                  $("#txtDiasFalta").val("");
                  $("#txtImporteDiasFalta").val("");
                  $("#agregarDiasFalta").prop("disabled", false);
                  $("#diasFaltaModal").modal('hide');
                  agregarClaveFaltas = 0;
                  $("#claveMostrarFaltas").css("display","none");
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "existe-clave-faltas"){

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "La clave del motivo de falta ya esta agregada, ingresa una diferente."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "existe-concepto-faltas"){

                    agregarClaveFaltas = 0;
                    $("#claveMostrarFaltas").css("display","none");

                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "El concepto de faltas ya esta agregado."
                    });
                    $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más faltas.",
                  });
                  $("#agregarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#agregarDiasFalta").prop("disabled", false);
                }
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#agregarDiasFalta").prop("disabled", false);
          }
        });


  });

  $("#modificarDiasFalta").click(function(){

    $("#modificarDiasFalta").prop("disabled", true);

    let idEmpleado = $("#empleado").val();
    let diasFalta = $("#txtDiasFaltaEdit").val().trim();
    let ImporteDiasFaltaObj = numeral($("#txtImporteDiasFaltaEdit").val().trim());
    let ImporteDiasFalta = ImporteDiasFaltaObj.value();
    let token = $("#csr_token_UT5JP").val();
    let idDetalleNominafaltas = $("#txtIdDetalleNominaFaltas").val().trim();

    if(diasFalta == "" || diasFalta == null || diasFalta < 1 ){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }
      
    if(isNaN(diasFalta)){
      Lobibox.notify("error", {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: 'center top', //or 'center bottom'
        icon: false,
        img: '../../img/timdesk/warning_circle.svg',
        msg: "Ingresa un número de faltas."
      });
      $("#modificarDiasFalta").prop("disabled", false);
      return;
    }

    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          $("#modificarDiasFalta").prop("disabled", false);
          return;
    }

    if(ImporteDiasFalta == "" || ImporteDiasFalta == null){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa el importe"
          });
          $("#modificarDiasFalta").prop("disabled", false);
          return;
    }

    $.ajax({
          type: 'POST',
          url: 'functions/editar_faltas_Dias.php',
          data: { 
                  diasFalta : diasFalta,
                  ImporteDiasFalta : ImporteDiasFalta,
                  csr_token_UT5JP : token,
                  idNominaEmpleado: idNominaEmpleado,
                  idDetalleNominafaltas: idDetalleNominafaltas,
                  idEmpleado: idEmpleado,
                  idNomina: idNomina
                },
          success: function(r) {

                if(r == "exito"){
                  Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/checkmark.svg",
                    msg: "Se modficaron las faltas",
                  });
                  $("#modificarDiasFalta").prop("disabled", false);
                  $("#diasFaltaModalEdit").modal('hide');
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                }

                if(r == "diaspaso"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "No puede agregar más faltas.",
                  });
                  $("#modificarDiasFalta").prop("disabled", false);
                }

                if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
                  $("#modificarDiasFalta").prop("disabled", false);
                }
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#modificarDiasFalta").prop("disabled", false);
          }
        });


  });

  $("#txtDiasFalta,#txtDiasFaltaEdit").change(function(){

      let element = this.id;
      let diasfalta;

      if(element == "txtDiasFalta"){
        $("#agregarDiasFalta").prop("disabled", true);
        diasfalta = $("#txtDiasFalta").val().trim();
      }

      if(element == "txtDiasFaltaEdit"){
        $("#modificarDiasFalta").prop("disabled", true);
        diasfalta = $("#txtDiasFaltaEdit").val().trim();
      }

      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();

      if(diasfalta == "" || diasfalta == null || diasfalta < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de faltas."
        });
        return;
      }
        
      if(isNaN(diasfalta)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de faltas."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioDiario.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                diasfalta: diasfalta
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los días.",
                });
                $("#agregarDiasFalta").prop("disabled", false);
                $("#modificarDiasFalta").prop("disabled", false);

              }
              else{

                if(element == "txtDiasFalta"){
                  $("#agregarDiasFalta").prop("disabled", false);
                  $("#txtImporteDiasFalta").val(r);
                }

                if(element == "txtDiasFaltaEdit"){
                  $("#modificarDiasFalta").prop("disabled", false);
                  $("#txtImporteDiasFaltaEdit").val(r);
                }

              }

        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y los días.",
          });
          $("#agregarDiasFalta").prop("disabled", false);
          $("#modificarDiasFalta").prop("disabled", false);
        }
      });

  });


  $("#txtHoras,#tipoHoras,#txtHorasEdit").change(function(){

      let element = this.id;
      let hora, tipoHora;

      if(element == "txtHoras" || element == "tipoHoras"){
        hora = $("#txtHoras").val().trim();
        tipoHora = $("#tipoHoras").val().trim();
        $("#agregarHoras").prop("disabled", true);
      }

      if(element == "txtHorasEdit"){
        hora = $("#txtHorasEdit").val().trim();
        tipoHora = $("#txttipoHorasEdit").val().trim();
        $("#modificarHoras").prop("disabled", true);
      }
      
      let token = $("#csr_token_UT5JP").val();
      let idEmpleado = $("#empleado").val();


      if(hora == "" || hora == null || hora < 1 ){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de horas."
        });
        return;
      }
        
      if(isNaN(hora)){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Ingresa un número de horas."
        });
        return;
      }


      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    $.ajax({
        type: 'POST',
        url: 'functions/calcularSalarioHora.php',
        data: { 
                csr_token_UT5JP : token,
                idEmpleado: idEmpleado,
                hora: hora,
                tipoHora: tipoHora
              },
        success: function(r) {

              if(r == "fallo"){
                Lobibox.notify("warning", {
                  size: "mini",
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: "center top",
                  icon: true,
                  img: "../../img/timdesk/notificacion_error.svg",
                  msg: "No se pudo cargar el calculo del salario por hora pero puedes ingresar el importe y las horas.",
                });
                $("#agregarHoras").prop("disabled", false);
                $("#modificarHoras").prop("disabled", false);

              }
              else{

                
                if(element == "txtHoras" || element == "tipoHoras"){
                  $("#txtImporteHoras").val(r);
                  $("#agregarHoras").prop("disabled", false);
                }

                if(element == "txtHorasEdit"){
                  $("#txtImporteHorasEdit").val(r);
                  $("#modificarHoras").prop("disabled", false);
                }

              }

        
        },
        error: function(){
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "No se pudo cargar el calculo del salario diario pero puedes ingresar el importe y las horas.",
          });
          $("#agregarHoras").prop("disabled", false);
          $("#modificarHoras").prop("disabled", false);
        }
      });

  });


  let agregarClaveHorasExtra = 0;
  $("#horasExtra").click(function(){

    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val();
    
    if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
    }

    let idclave = 14;
    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: idclave,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarHorasExtraUnica").css("display","block");
          agregarClaveHorasExtra = 1;
        }
        else{
          agregarClaveHorasExtra = 0;
          $("#claveMostrarHorasExtraUnica").css("display","none");
        }
      }
    });

    $("#horasExtraModal").modal('toggle');

  });

  
 

  $(document).on('click','.cbxCambio', function(e) {
      let id = this.value;
      let token = $("#csr_token_UT5JP").val(); 
      let idEmpleado = $("#empleado").val();
      let textoSweet, activo, msgEstado;

      if(idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });

        if ($('#cbxCambio_' + id).is(":checked")){
          $('#cbxCambio_' + id).prop('checked', false);
        }
        else{
          $('#cbxCambio_' + id).prop('checked', true);
        }
        return;
      }

      if ($('#cbxCambio_' + id).is(":checked")){
          textoSweet = "Se exentara este concepto de la nómina de este empleado.";
          activo = 1;
          msgEstado = "Concepto exento de nómina.";
      }
        else{
          textoSweet = "Se calcularán los impuestos de este concepto.";
          activo = 0;
          msgEstado = "Concepto con impuestos.";
      }

      

      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: textoSweet,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $.ajax({
              type: 'POST',
              url: 'functions/exentarImpuesto.php',
              data: {
                idDetalleNomina: id,
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                activo: activo,
                csr_token_UT5JP: token
              },
              success: function(data) {

                if (data == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: msgEstado
                  });
                  $('#tblNominaEmpleado').DataTable().ajax.reload();

                } 
                else {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {

            if ($('#cbxCambio_' + id).is(":checked")){
              $('#cbxCambio_' + id).prop('checked', false);
            }
            else{
              $('#cbxCambio_' + id).prop('checked', true);
            }

          }
        });
  });


  $(".tipoConceptoC").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
              }
              else{
                  selectClave.destroy();

                  $("#cmbClave").html(r);

                  selectClave = new SlimSelect({
                    select: '#cmbClave',
                    deselectLabel: '<span class="">✖</span>'
                  });

              }

          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
          }
        });


  });
  
  

  /*
  new Cleave('.txtImporteTurnoEdit', {
    numeral: true,
    numeralDecimalMark: '.',
    delimiter: ','
  });*/


$(document).on("click","#btnEnviarFactura",function(){
  
  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

  if(idEmpleado < 1){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Selecciona un empleado para enviar la nómina por email."
    });
    return;
  }

  if($("#txtDestino").val().trim() == ""){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Ingresa un email."
    });
    return;
  }
  let email = $("#txtDestino").val().trim();

  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

  if( !emailReg.test(email) ){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Ingresa un email válido."
    });
    return;
  }

  $("#btnEnviarFactura").prop("disabled", true);

      //enviar datos del trabajor y nomina
      $.ajax({
        type: 'POST',
        url: 'functions/enviarFactura.php',
        data: { 
                idFactura: idFactura,
                csr_token_UT5JP : token,
                destino:email
              },
        success: function(response){
          if(response == "exito"){
            Lobibox.notify("success", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/checkmark.svg",
              msg: "Se ha envíado el archivo pdf y el xml",
            });
            $("#btnEnviarFactura").prop("disabled", false);
            $("#enviarFactura").modal("toggle");
            
          }

          if(response == "fallo"){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Ocurrio un error, vuelva intentarlo."
            });
            $("#btnEnviarFactura").prop("disabled", false);

          }
          
        },
        error:function(error){
          $("#btnEnviarFactura").prop("disabled", false);
        }
      });

});


$(document).on("click","#cancelarNominaIndividual",function(){

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();

  if(idEmpleado < 1){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Selecciona un empleado para enviar la nómina por email."
    });
    return;
  }

  const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se cancelará la factura de esta nómina.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#cancelarNominaIndividual").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/cancelarNominaEmpleado.php',
              data: {
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se cancelo la factura."
                  });
                  mostrarTimbrado();  
                  $("#cancelarNominaIndividual").prop("disabled", false);

                } 
                if (datos.estatus == "fallo") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#cancelarNominaIndividual").prop("disabled", false);

                }
                if (datos.estatus == "fallo-cancelado") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "La factura ya se cancelo, se habilitarán los controles para facturar."
                  });
                  $("#cancelarNominaIndividual").prop("disabled", false);
                  mostrarTimbrado();
                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});



$(document).on("click","#timbrarNominaIndividual",function(){

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();
  

  if(idEmpleado < 1){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Selecciona un empleado para enviar la nómina por email."
    });
    return;
  }

  const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se timbrará la factura de esta empleado.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarNominaIndividual").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/timbrarNominaIndividualExtraordinaria.php',
              data: {
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
              },
              success: function(data) {

                var datos = JSON.parse(data);

                if (datos.estatus == "fallo-exento") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar un empleado exento."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "fallo-general") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: datos.mensaje
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "fallo-estadotimbrado") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes volver a timbrar un empleado."
                  });
                  idFactura = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura();  
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "fallo-isr") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar ninguna nómina, te falta la clave del ISR."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "fallo-imss") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "No puedes timbrar ninguna nómina, te falta la clave del IMSS."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }

                if (datos.estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro la factura del empleado."
                  });
                  idFactura = datos.idFactura;
                  fechaTimbrado = datos.fechaTimbrado;
                  mostrarOpcionesFactura();  
                  $("#timbrarNominaIndividual").prop("disabled", false);

                  if (datos.estatus_email == "exito") {

                    Lobibox.notify("success", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/checkmark.svg',
                      msg: "Se envio el recibo de nómina correo registrado del empleado."
                    });

                  }

                  if (datos.estatus_email == "fallo") {
                    Lobibox.notify("error", {
                      size: 'mini',
                      rounded: true,
                      delay: 3000,
                      delayIndicator: false,
                      position: 'center top', //or 'center bottom'
                      icon: false,
                      img: '../../img/timdesk/warning_circle.svg',
                      msg: "No se pudo enviar el email."
                    });
                  }

                } 

                if (datos.estatus == "fallo") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#timbrarNominaIndividual").prop("disabled", false);

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});

$(document).on("click","#timbrarNominaCompleta",function(){

  let token = $("#csr_token_UT5JP").val();
  let idEmpleado = $("#empleado").val();
  

  if(idEmpleado < 1){
    Lobibox.notify("error", {
      size: 'mini',
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: 'center top', //or 'center bottom'
      icon: false,
      img: '../../img/timdesk/warning_circle.svg',
      msg: "Selecciona un empleado para enviar la nómina por email."
    });
    return;
  }

  const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          actions: "d-flex justify-content-around",
          confirmButton: "btn-custom btn-custom--border-blue",
          cancelButton: "btn-custom btn-custom--blue",
        },
        buttonsStyling: false,
      });

      swalWithBootstrapButtons
        .fire({
          title: "¿Desea continuar?",
          text: "Se timbrará la factura de todos los empleados de esta nómina.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: '<span class="verticalCenter">Confirmar</span>',
          cancelButtonText: '<span class="verticalCenter">Cerrar</span>',
          reverseButtons: true,
          allowOutsideClick: false,
        })
        .then((result) => {
          if (result.isConfirmed) {

            $("#timbrarNominaCompleta").prop("disabled", true);

            $.ajax({
              type: 'POST',
              url: 'functions/timbrarNominaCompleta.php',
              data: {
                idNominaEmpleado: idNominaEmpleado,
                idEmpleado: idEmpleado,
                idNomina: idNomina,
                csr_token_UT5JP : token,
              },
              success: function(data) {

                var datos = JSON.parse(data);

                console.log(datos);

                if (datos.resultado[0].estatus == "exito") {

                  Lobibox.notify("success", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/checkmark.svg',
                    msg: "Se timbro la nómina completa."
                  });
                  
                  let texto = "<h3 style='font-size:16px;'>Estatus al enviar email:</h3><br>";

                  $.each( datos.resultado, function( key, value ) {

                    texto = texto + "<b>Nombre:</b> " + value.nombre;

                    if(value.estatus_email.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Enviado <br>";
                    }
                    if(value.estatus_email.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo <br>";
                    }

                    if(value.estatus_ind.trim() == "exito"){

                      if(idEmpleado == value.idempleado){
                          idFactura = value.idfactura;
                          mostrarOpcionesFactura();  
                      }
                    }


                  });

                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-info");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                  $("#timbrarNominaCompleta").prop("disabled", false);

                } 

                if (datos.resultado[0].estatus == "fallo") {

                  $("#timbrarNominaCompleta").prop("disabled", false);

                  let texto = "<h3 style='font-size:16px;'>Errores en el timbrado:</h3><br>";
                  $.each( datos.resultado, function( key, value ) {
                    texto = texto + "<b>Nombre:</b> " + value.nombre;

                    if(value.estatus_curp.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_curp;
                    }

                    if(value.estatus_rfc.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_rfc;
                    }

                    if(value.estatus_imss.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_imss;
                    }

                    if(value.estatus_timbrado.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_timbrado;
                    }

                    if(value.estatus_curpt.trim() !== ""){
                      texto = texto + " <b>Error:</b> " + value.mensaje_curpt;
                    }

                    if(value.estatus_ind.trim() == "exito"){
                      texto = texto + " <b>Estatus:</b> Timbrado exitosamente.<br>";

                      if(idEmpleado == value.idempleado){
                          idFactura = value.idfactura;
                          mostrarOpcionesFactura();  
                      }
                    }
                    if(value.estatus_ind.trim() == "fallo"){
                      texto = texto + " <b>Estatus:</b> Fallo.<br>";
                    }

                  });


                  $("#mostrarResultadosPadre").removeClass("alert-danger"); 
                  $("#mostrarResultadosPadre").removeClass("alert-info");
                  $("#mostrarResultadosPadre").addClass("alert-danger");
                  $("#espacioMostrarresultado").css("display","block");
                  $("#mostrarResultadosPadre").css("display","block");
                  $("#mostrarResultados").html(texto);

                }

                if (datos.resultado[0].estatus_token== "fallo") {

                  Lobibox.notify("error", {
                    size: 'mini',
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: 'center top', //or 'center bottom'
                    icon: false,
                    img: '../../img/timdesk/warning_circle.svg',
                    msg: "Ocurrio un error, vuelva intentarlo."
                  });
                  $("#timbrarNominaCompleta").prop("disabled", false);

                }
              }
            });



          } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
          ) {


          }
        });

});

$(document).on("click","#close-mostrarResultado", function(){
  $("#mostrarResultadosPadre").css("display","none");
  $("#espacioMostrarresultado").css("display","none");
});


$(".tipoConceptoCAgregar").change(function(){
    let tipo = this.value;
    let token = $("#csr_token_UT5JP").val(); 
    
    $.ajax({
          type: 'POST',
          url: 'functions/cargar_claves.php',
          data: { 
                  tipo : tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {
                
              if(r == "fallo"){
                  Lobibox.notify("error", {
                    size: "mini",
                    rounded: true,
                    delay: 3000,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/notificacion_error.svg",
                    msg: "¡Ocurrio un error, intentalo nuevamente!",
                  });
              }
              else{
                  selectClaveAgregar.destroy();

                  $("#cmbConceptoClave").html(r);

                  selectClaveAgregar = new SlimSelect({
                    select: '#cmbConceptoClave',
                    deselectLabel: '<span class="">✖</span>'
                  });

              }

          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
          }
        });

  });


  $("#btnAgregarClave").click(function(){
        let clave = $("#txtClaveAgregar").val().trim();
        let concepto = $("#cmbConceptoClave").val().trim();
        let tipo = $('input[name="tipoConceptoAgregar"]:checked').val();
        let token = $("#csr_token_UT5JP").val(); 

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }

        $("#btnCancelarClave").prop("disabled", true);
        $("#btnAgregarClave").prop("disabled", true);

        $.ajax({
          type: 'POST',
          url: 'functions/agregar_Clave.php',
          data: { 
                  concepto : concepto,
                  clave: clave,
                  tipo: tipo,
                  csr_token_UT5JP : token
                },
          success: function(r) {

            if(r == "exito"){
              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/checkmark.svg",
                msg: "Clave agregada!",
              });
              $("#txtClaveAgregar").val("");
              $("#btnCancelarClave").prop("disabled", false);
              $("#btnAgregarClave").prop("disabled", false);
              $('#agregar_clave').modal('hide');
            }
            
            if(r == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
            }

            if(r == "existe-concepto"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
            }

            if(r == "fallo"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
            }
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnAgregarClave").prop("disabled", false);
          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });
            $("#btnCancelarClave").prop("disabled", false);
            $("#btnAgregarClave").prop("disabled", false);
          }
        });

     });

    function agregarDias(fechaOr, diasAgregarOr){

      let fecha = new Date(fechaOr);
      let diasAgregar = parseInt(diasAgregarOr);
      fecha.setDate(fecha.getDate() + diasAgregar);

      var day = ("0" + fecha.getDate()).slice(-2);
      var month = ("0" + (fecha.getMonth() + 1)).slice(-2);
      var today = fecha.getFullYear()+"-"+(month)+"-"+(day) ;

      return today;
    }

  $("#mostrarModalAguinaldo").click(function(){
    let idEmpleado = $("#empleado").val();
    let token = $("#csr_token_UT5JP").val();

    if(idEmpleado == "" || idEmpleado < 1){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "Selecciona un empleado para modificar su estado de nómina."
        });
        return;
      }

    $.ajax({
      type: 'POST',
      url: 'functions/mostrarClaves.php',
      data: {
        idclave: 2,
        tipo: 1,
        csr_token_UT5JP: token
      },
      success: function(data) {

        if(data < 1){
          $("#claveMostrarAguinaldo").css("display","block");
          agregarAguinaldo = 1;
        }
        else{
          agregarAguinaldo = 0;
          $("#claveMostrarAguinaldo").css("display","none");
        }
      }
    });


    $("#aguinaldo_modal").modal('toggle');

  });

  //FUNCIONES NOMINA EXTRAORDINARIA
$("#agregarAguinaldo").click(function(){

      let idEmpleado = $("#empleado").val();
      let procedimiento = $('input[name="procedimientoISR"]:checked').val();
      let token = $("#csr_token_UT5JP").val(); 
      let clave = "";

      if(idEmpleado == "" || idEmpleado < 1){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Selecciona un empleado."
          });
          return;
      }

      if($("#txtDiasAguinaldo").val().trim() == ""){
          Lobibox.notify("error", {
            size: 'mini',
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: 'center top', //or 'center bottom'
            icon: false,
            img: '../../img/timdesk/warning_circle.svg',
            msg: "Ingresa los días de aguinaldo."
          });
          return;
      }

      let diasAguinaldo = $("#txtDiasAguinaldo").val();
      if(diasAguinaldo > 30){
        Lobibox.notify("error", {
          size: 'mini',
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: 'center top', //or 'center bottom'
          icon: false,
          img: '../../img/timdesk/warning_circle.svg',
          msg: "No puedes ingresar más de 30 días."
        });
        return;
      }


      //es necesario la clave
      if(agregarAguinaldo == 1){
        clave = $("#txtClaveAguinaldo").val().trim();

        if (clave == "") {
          Lobibox.notify("error", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "center top",
            icon: true,
            img: "../../img/timdesk/notificacion_error.svg",
            msg: "Es necesario ingresar una clave.",
          });
          return;
        }

        if(clave.length > 15){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "No puedes agregar una clave de más de 15 caracteres."
            });
            return;
        }
      }

      $("#agregarAguinaldo").prop("disabled", true);

      $.ajax({
          type: 'POST',
          url: 'functions/agregarAguinaldo.php',
          data: {
            idNominaEmpleado: idNominaEmpleado,
            idEmpleado: idEmpleado,
            csr_token_UT5JP: token,
            idNomina: idNomina,
            diasAguinaldo: diasAguinaldo,
            tipoCalculoISR: procedimiento,
            periodo: periodoPago,
            disponibleClave: agregarAguinaldo,
            clave: clave
          },
          success: function(data) {

            if (data == "exito") {

              Lobibox.notify("success", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/checkmark.svg',
                msg: "Se agregó el aguinaldo a la nómina."
              });

              $("#txtDiasAguinaldo").val(diasAguinaldo);
              $("#txtClaveAguinaldo").val("");
              agregarAguinaldo = 0;
              $("#agregarAguinaldo").prop("disabled", false);
              $("#aguinaldo_modal").modal('hide');
              $('#tblNominaEmpleado').DataTable().ajax.reload();

            } 
            if (data == "fallo") {

              Lobibox.notify("error", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "Ocurrio un error, vuelva intentarlo."
              });
              $("#agregarAguinaldo").prop("disabled", false);

            }

            if (data == "fallo-agregar") {
                Lobibox.notify("error", {
                  size: 'mini',
                  rounded: true,
                  delay: 3000,
                  delayIndicator: false,
                  position: 'center top', //or 'center bottom'
                  icon: false,
                  img: '../../img/timdesk/warning_circle.svg',
                  msg: "No puedes agregar un concepto a una nómina timbrada."
                });
                $("#agregarAguinaldo").prop("disabled", false);

            }

            if (data == "existe-concepto") {
              Lobibox.notify("warning", {
                size: 'mini',
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: 'center top', //or 'center bottom'
                icon: false,
                img: '../../img/timdesk/warning_circle.svg',
                msg: "El concepto ya esta agregado en la nómina."
              });
              $("#agregarAguinaldo").prop("disabled", false);
            }

            if(data == "existe-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ya existe esa clave.",
              });
              $("#agregarAguinaldo").prop("disabled", false);
            }

            if(data == "existe-concepto-clave"){
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "Ese concepto ya tiene asignada una clave.",
              });
              $("#agregarAguinaldo").prop("disabled", false);
            }
          }
        });

    });

    $(document).on("click","#mostrarISR", function(){

        let idEmpleado = $("#empleado").val();
        let token = $("#csr_token_UT5JP").val(); 

        if(idEmpleado == "" || idEmpleado < 1){
            Lobibox.notify("error", {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: false,
              img: '../../img/timdesk/warning_circle.svg',
              msg: "Selecciona un empleado."
            });
            return;
        }

        $.ajax({
          type: 'POST',
          url: 'functions/calculoISR.php',
          data: { 
                  idNominaEmpleado: idNominaEmpleado,
                  idEmpleado: idEmpleado,
                  csr_token_UT5JP: token,
                  idNomina: idNomina,
                },
          success: function(r) {

            var datos = JSON.parse(r);

            if(datos.estatus == "exito"){
              
              $("#mostrarUnicoISR").html(datos.resultado);
              $("#calculo_isr_modal").modal('toggle');

            }

            if(datos.estatus == "fallo"){
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../img/timdesk/notificacion_error.svg",
                msg: "¡Ocurrio un error, intentalo nuevamente!",
              });
            }

          },
          error: function(){
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../img/timdesk/notificacion_error.svg",
              msg: "¡Ocurrio un error, intentalo nuevamente!",
            });

          }
        });
        
    });

</script>