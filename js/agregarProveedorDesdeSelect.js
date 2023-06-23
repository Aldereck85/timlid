$("#btnAgregarProveedor").click(function() {
    var nombre = $("#nombreProv").val();
    var email = $("#emailProv").val();
    var tipoPersona = $("#cmbTipoPersona").val();
    var isCreditoCheck = $("#creditoProv").is(':checked');
    var diascredito = $("#txtDiasCredito").val();
    var limitepractico = $("#txtLimiteCredito").val();

    if (!nombre) {
      $("#invalid-nombreProv").css("display", "block");
      $("#nombreProv").addClass("is-invalid");
    }
    if (!email) {
      $("#invalid-emailProv").css("display", "block");
      $("#emailProv").addClass("is-invalid");
    }
    if (!tipoPersona) {
      $("#invalid-tipoPersonaProv").css("display", "block");
    }
    if (isCreditoCheck) {
      if (!diascredito) {
        $("#invalid-diasProv").css("display", "block");
        $("#txtDiasCredito").addClass("is-invalid");
      }
      if (!limitepractico) {
        $("#invalid-credProv").css("display", "block");
        $("#txtLimiteCredito").addClass("is-invalid");
      }
    }

    var badNombreProv =
      $("#invalid-nombreProv").css("display") === "block" ? false : true;
    var badEmailProv =
      $("#invalid-emailProv").css("display") === "block" ? false : true;
    var badTipoPersonaProv =
      $("#invalid-tipoPersonaProv").css("display") === "block" ? false : true;
    var badDiasProv =
      $("#invalid-diasProv").css("display") === "block" ? false : true;
    var badCredProv =
      $("#invalid-credProv").css("display") === "block" ? false : true;

    if (badNombreProv && badEmailProv && badTipoPersonaProv && badDiasProv && badCredProv) {
      $.ajax({
        url: "functions/agregar_Proveedor.php",
        type: "POST",
        data: {
          "nombre": nombre,
          "email": email,
          "tipoPersona": tipoPersona,
          "isCreditoCheck": isCreditoCheck,
          "diascredito": diascredito,
          "limitepractico": limitepractico,
        },
        success: function(data, status, xhr) {
          console.log(data);
          if (data.trim() == "exito") {
            $('#nuevo_Provedor').modal('toggle');
            $("#nombreProv").val("");
            $("#emailProv").val("");
            cmbTipoPersona.set('');
            $("#creditoProv").val("");
            $("#txtDiasCredito").val("");
            $("#txtLimiteCredito").val("");
            $('#agregarProveedor').trigger("reset");
            $('#tblProveedores').DataTable().ajax.reload();
            cargarCMBProveedor("cmbProvedoresGasto");
            cargarCMBProveedorEdit($("#cmbProvedoresGastoEdit").val(), "cmbProvedoresGastoEdit");
            Lobibox.notify('success', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top', //or 'center bottom'
              icon: true,
              //img: '<i class="fas fa-check-circle"></i>',
              img: '../../img/timdesk/checkmark.svg',
              msg: '¡Registro agregado!'
            });
          } else {
            Lobibox.notify('warning', {
              size: 'mini',
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: 'center top',
              icon: true,
              img: '../../../../img/timdesk/warning_circle.svg',
              img: null,
              msg: 'Ocurrió un error al agregar'
            });
          }
        }
      });
    }
  });