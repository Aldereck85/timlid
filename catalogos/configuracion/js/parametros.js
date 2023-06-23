    $(document).on("click","#btnGuardarParametros", function(){
        
      let diasVencimiento = $("#txtDiasVencimiento").val().trim();
      let leyenda = $("#txtLeyenda").val().trim();
      let diasAguinaldo = $("#txtDiasAguinaldo").val().trim();
      let primaVacacional = $("#txtPrimaVacacional").val().trim();
      let riesgoTrabajo = $("#txtRiesgoTrabajo").val().trim();
      let validoVencimiento = 0, validoLeyenda = 0, validoAguinaldo = 0, validoPrima = 0, validoRiesgo = 0;

      $("#ingresar-diasVencimiento").css("display","none");
      $("#invalid-diasVencimiento").css("display","none");
      $("#invalid-Leyenda").css("display","none");
      $("#ingresar-diasAguinaldo").css("display","none");
      $("#invalid-diasAguinaldo").css("display","none");
      $("#ingresar-primaVacacional").css("display","none");
      $("#invalid-primaVacacional").css("display","none");
      $("#invalid-riesgoTrabajo").css("display","none");

      if (diasVencimiento == "") {
          $("#ingresar-diasVencimiento").css("display","block");
          validoVencimiento = 1;
      }

      if (parseInt(diasVencimiento) < 1) {
          if(validoVencimiento == 0){
            $("#invalid-diasVencimiento").css("display","block");
            validoVencimiento = 1;
          }
      }

      if (leyenda.length > 70) {
          $("#invalid-Leyenda").css("display","block");
          validoLeyenda = 1;
      }

      if (diasAguinaldo == "") {
          $("#ingresar-diasAguinaldo").css("display","block");
          validoAguinaldo = 1;
      }

      if (parseInt(diasAguinaldo) < 15) {
          if(validoAguinaldo == 0){
            $("#invalid-diasAguinaldo").css("display","block");
            validoAguinaldo = 1;
          }
      }

      if (primaVacacional == "") {
          $("#ingresar-primaVacacional").css("display","block");
          validoPrima = 1;
      }

      if (parseInt(primaVacacional) < 25) {
          if(validoPrima == 0){
            $("#invalid-primaVacacional").css("display","block");
            validoPrima = 1;
          }
      }

      if (riesgoTrabajo == "") {
          $("#invalid-riesgoTrabajo").css("display","block");
          validoRiesgo = 1;
      }

      if(validoVencimiento == 1 || validoLeyenda == 1 || validoAguinaldo == 1 || validoPrima == 1 || validoRiesgo == 1){
          return;
      }

      $.ajax({
          type: 'POST',
          url: 'parametros/guardarParametros.php',
          data: { 
                  diasVencimiento : diasVencimiento,
                  leyenda : leyenda,
                  diasAguinaldo : diasAguinaldo,
                  primaVacacional : primaVacacional,
                  riesgoTrabajo : riesgoTrabajo,
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
                msg: "Se guardaron los parametros.",
              });
            }
            else{
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