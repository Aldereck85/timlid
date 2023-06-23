var tabs = [];

$(document).ready(function () {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_screens",
      data: $("#txtUsuario").val(),
    },
    method: "post",
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.length > 0) {
        console.log("screen", respuesta);
        html = '<ul class="nav nav-tabs">';
        $.each(respuesta, function (i) {
          pantalla = respuesta[i].pantalla.replace(/\s/g, "");
          html +=
            '<li class="nav-item">' +
            '<a id="Cargar' +
            pantalla +
            '" class="nav-link" href="#" data-id="' +
            respuesta[i].id +
            '">' +
            respuesta[i].pantalla +
            "</a>" +
            "</li>";
        });
        html += "</ul>";
        //console.log(html);
        $(".config-tabs").html(html);
        screenPermission = respuesta[0].pantalla.replace(/\s/g, "");
        $("#Cargar" + screenPermission).addClass("active");

        $.each(respuesta, function (i) {
          pantalla = respuesta[i].pantalla.replace(/\s/g, "");
          $("#Cargar" + pantalla + "").on("click", function () {
            $(".active").removeClass("active");
            $(this).addClass("active");

            //console.log($("#Cargar"+pantalla+"").data("id"));
            //console.log(pantalla);
            //$('#data'+pantalla).html(html1);
            //console.log(html1);
          });

          //$('#data'+pantalla).html(html1);
          //console.log(pantalla);
        });

        $(".config-tabs li a").each(function () {
          tabs.push($(this).attr("id"));
          //console.log($(this).attr("id"));
        });
        //console.log(tabs);
        var id;
        $.each(tabs, function (i) {
          $("#" + tabs[i]).on("click", function () {
            id = $(this).data("id");
            $.ajax({
              url: "php/funciones.php",
              data: {
                clase: "get_data",
                funcion: "get_permission_screen",
                usuario: $("#txtUsuario").val(),
                pantalla: id,
              },
              method: "post",
              dataType: "json",
              success: function (respuesta) {
                console.log({ respuesta });
                pantalla = quitarAcentos(
                  respuesta[0].pantalla.replace(/\s+/g, "")
                );
                modal = pantalla + "_" + id;
                console.log(modal);

                var btn_agregar = "";

                $(".permission-view-table").html(loadTable(pantalla));
                $(".permission-view-table").addClass("table-responsive");

                if (pantalla === "Widgets") {
                  loadSlimWidgets();
                }
                var agregarEditar = { agregar: false, editar: false };

                if (respuesta[0].funcion_ver === 1) {
                  /* Funcion ver */
                }
                if (respuesta[0].funcion_agregar === 1) {
                  agregarEditar.agregar = true;
                  /* Funcion agregar */
                  //console.log("permiso concedido para "+respuesta[1].funcion+" en "+respuesta[1].pantalla);
                  switch (pantalla) {
                    case "Usuarios":
                      console.log({ pantalla });
                      //console.log('agregar_'+modal);
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar usuario</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Perfiles":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="#" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar perfil</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Puestos":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar puesto</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Turnos":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar turno</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "CategoriaGastos":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar categoría</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "SubcategoriaGastos":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar subcategoría</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "ResponsableGastos":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar responsable</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Sucursales":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar sucursales</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Tipoordeninventario":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar tipo orden</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "DatosEmpresa":
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar " +
                        respuesta[0].pantalla +
                        "</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "MarcadeProductos":
                      console.log({ pantalla });
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar " +
                        respuesta[0].pantalla +
                        "</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                    case "Personal":
                      console.log({ pantalla });
                      btn_agregar =
                        '<div class="float-right">' +
                        '<div class="button-container2">' +
                        '<div class="button-icon-container">' +
                        '<a href="" class="btn btn-circle float-right waves-effect waves-light shadow btn-table btn-Agregar"' +
                        'id="btn_add_' +
                        modal +
                        '" data-toggle="modal" data-target="#agregar_' +
                        modal +
                        '">' +
                        '<i class="fas fa-plus"></i>' +
                        "</a>" +
                        "</div>" +
                        '<div class="button-text-container">' +
                        "<span>Agregar " +
                        respuesta[0].pantalla +
                        "</span>" +
                        "</div>" +
                        "</div>" +
                        "</div>";
                      break;
                  }
                  btn_agregar += "</div>";
                }
                if (respuesta[0].funcion_editar === 1) {
                  //console.log("permiso concedido para "+respuesta[2].funcion);
                  /* Funcion editar */

                  switch (pantalla) {
                    case "Usuarios":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "Puestos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "Turnos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "CategoriaGastos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "SubcategoriaGastos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';

                      break;
                    case "ResponsableGastos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "Sucursales":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "Tipoordeninventario":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "DatosEmpresa":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "MarcadeProductos":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                    case "Personal":
                      btn_editar =
                        '<button type="button" class="btnesp espAgregar float-right" name="btnAgregar" id="btnEditar_' +
                        modal +
                        '"><span' +
                        'class="ajusteProyecto">Guardar</span></button>';
                      break;
                  }
                  $(".permission-view-edit-" + modal).html(btn_editar);
                }
                if (respuesta[0].funcion_eliminar === 1) {
                  //console.log("permiso concedido para "+respuesta[3].funcion);
                  /* Funcion eliminar */
                  switch (pantalla) {
                    case "Usuarios":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>' +
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnActivar_' +
                        modal +
                        '"><span class="ajusteProyecto">Activar</span></button>';
                      break;
                    case "Puestos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';

                      break;
                    case "Turnos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "CategoriaGastos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "SubcategoriaGastos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';

                      break;
                    case "ResponsableGastos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "Sucursales":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "Tipoordeninventario":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "DatosEmpresa":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "MarcadeProductos":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                    case "Personal":
                      btn_eliminar =
                        '<button type="button" class="btnesp espAgregar float-right" data-dismiss="modal"' +
                        'id="btnEliminar_' +
                        modal +
                        '"><span class="ajusteProyecto">Eliminar</span></button>';
                      break;
                  }
                  $(".permission-view-delete-" + modal).html(btn_eliminar);

                  $("#btnEliminar_" + modal).on("click", function () {
                    $("#txtPK" + modal + "D").val(
                      $("#txtUpdatePK" + modal).val()
                    );
                    $("#txt" + modal + "D").val($("#txtUpdate" + modal).val());

                    $("#eliminar_" + modal).modal("show");
                  });
                }
                if (respuesta[0].funcion_exportar === 1) {
                  agregarEditar.editar = true;
                }
                getTableUser(pantalla, agregarEditar, id, modal);
              },
              error: function (error) {
                console.log(error);
              },
            });
          });
        });
        document.getElementById("CargarUsuarios").click();
      } else {
        $("#redirect_dashboard").modal("show");
        window.location.href = "../dashboard.php";
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

function loadSlimWidgets() {
  $(".permission-view-table").removeClass("table-responsive");

  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_users_by_widget",
    },
    dataType: "json",
    success: function (response) {
      var responseFormated = response.reduce((object, currentValue) => {
        if (object[currentValue.widgetName]) {
          object[currentValue.widgetName].push({
            value: currentValue.usuarioID,
            text: currentValue.usuario,
            selected: Boolean(currentValue.permiso),
          });
        } else {
          object[currentValue.widgetName] = [
            {
              value: currentValue.usuarioID,
              text: currentValue.usuario,
              selected: Boolean(currentValue.permiso),
            },
          ];
        }
        return object;
      }, {});

      new SlimSelect({
        select: "#wg-mi-facturacion",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.mifacturacion,
      });

      new SlimSelect({
        select: "#wg-ventas",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventas,
      });

      new SlimSelect({
        select: "#wg-ventas-ejecutivo",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventasejecutivo,
      });

      new SlimSelect({
        select: "#wg-cuentas-pagar",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.cuentaspagar,
      });

      new SlimSelect({
        select: "#wg-cuentas-cobrar",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.cuentascobrar,
      });

      new SlimSelect({
        select: "#wg-ventas-anio",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventasanios,
      });

      new SlimSelect({
        select: "#wg-proyectos",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.proyectos,
      });

      new SlimSelect({
        select: "#wg-cumpleanios",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.cumpleanios,
      });

      new SlimSelect({
        select: "#wg-notas",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.notas,
      });

      new SlimSelect({
        select: "#wg-calendario",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.calendario,
      });

      new SlimSelect({
        select: "#wg-ventas-anio-grafica-1",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventasaniograficauno,
      });

      new SlimSelect({
        select: "#wg-ventas-anio-grafica-2",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventasaniograficados,
      });

      new SlimSelect({
        select: "#wg-ventas-mes",
        placeholder: "Usuarios con acceso",
        searchPlaceholder: "Buscar usuario",
        deselectLabel: '<span class="">✖</span>',
        data: responseFormated.ventasmesgrafica,
      });
    },
    error: function (error) {
      console.log({ error });
    },
  });
}

function loadTable(pantalla) {
  var tabla = "";
  //console.log(pantalla);

  switch (pantalla) {
    case "Usuarios":
      tabla =
        '<table class="table" id="tblUsuarios" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Id</th>" +
        "<th>No</th>" +
        "<th>Usuario</th>" +
        "<th>Nombre completo</th>" +
        "<th>Estatus</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Perfiles":
      tabla =
        '<table class="table" id="tblPerfiles" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Id</th>" +
        "<th>No</th>" +
        "<th>Nombre</th>" +
        "<th>Estatus</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Puestos":
      tabla =
        '<table class="table" id="tblPuestos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>No</th>" +
        "<th>Puesto</th>" +
        "<th></th>" +
        " </tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Turnos":
      tabla =
        '<table class="table" id="tblTurnos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>Turno</th>" +
        "<th>Entrada</th>" +
        "<th>Salida</th>" +
        "<th>Días de labores</th>" +
        "<th>Horas/Semana</th>" +
        "<th>Tiempo de comida</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "CategoriaGastos":
      tabla =
        '<table class="table" id="tblCategoriaGastos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>No. Categoria</th>" +
        "<th>Nombre</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "SubcategoriaGastos":
      tabla =
        '<table class="table" id="tblSubcategoriaGastos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>No. Subcategoria</th>" +
        "<th>Nombre</th>" +
        "<th>Categoría</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "ResponsableGastos":
      tabla =
        '<table class="table" id="tblResponsableGastos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>No. Responsable</th>" +
        "<th>Nombre</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Sucursales":
      tabla =
        '<table class="table" id="tblSucursales" width=" 100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>Sucursal</th>" +
        "<th>Domicilio</th>" +
        "<th>Colonia</th>" +
        "<th>Municipio</th>" +
        "<th>Estado</th>" +
        "<th>País</th>" +
        "<th>Teléfono</th>" +
        "<th>Con Inventario</th>" +
        "<th>Zona salario</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "<tbody>" +
        "</tbody>" +
        "</table>";
      break;
    case "Tipoordeninventario":
      tabla =
        '<table class="table" id="tblListadoTipoOrdenInventario" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Id</th>" +
        "<th>Tipo de orden de inventario</th>" +
        "<th>Estatus</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "DatosEmpresa":
      tabla =
        '<table class="table" id="tblDatosEmpresa" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Razon social</th>" +
        "<th>RFC</th>" +
        "<th>Giro comercial</th>" +
        "<th>Domicilio fiscal</th>" +
        "<th>Régimen fiscal</th>" +
        "<th>Registro patronal IMSS</th>" +
        "<th>Propietario sello CFDI</th>" +
        "<th>Sello CFDI</th>" +
        "<th>Vencimiento CFDI</th>" +
        "<th>Logo</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Parametros":
      $.ajax({
        type: "POST",
        url: "parametros/cargarParametros.php",
        success: function (r) {
          var datos = JSON.parse(r);

          if (datos.resultado == "exito") {
            $("#txtDiasVencimiento").val(datos.diasVencimiento);
            $("#txtLeyenda").val(datos.Leyenda);
            $("#txtDiasAguinaldo").val(datos.diasAguinaldo);
            $("#txtPrimaVacacional").val(datos.primaVacacional);
            $("#txtRiesgoTrabajo").val(datos.riesgotrabajo);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: false,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "Ocurrio un error, vuelva intentarlo.",
            });
          }
        },
        error: function () {
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
        },
      });

      tabla =
        '<div class="modal-body">' +
        "<h4>Cotizaciones</h4>" +
        '<div class="row">' +
        '<div class="col-lg-12">' +
        '<div class="form-group">' +
        '<div class="row">' +
        '<div class="col-lg-6">' +
        '<label for="usr">Días de vencimiento:</label>' +
        '<div class="row">' +
        '<div class="col-lg-12 input-group">' +
        '<input class="form-control" type="number" name="txtDiasVencimiento" id="txtDiasVencimiento" required="" maxlength="5" min="1" placeholder="15">' +
        '<div class="invalid-feedback" id="ingresar-diasVencimiento">Debes ingresar los días de vencimiento para la cotización.</div>' +
        '<div class="invalid-feedback" id="invalid-diasVencimiento">No puedes ingresar menos de 1 día de vencimiento para la cotización.</div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-lg-6">' +
        '<label for="usr">Leyenda:</label>' +
        '<div class="row">' +
        '<div class="col-lg-12 input-group">' +
        '<input type="text" class="form-control alphaNumeric-only" name="txtLeyenda" id="txtLeyenda" maxlength="70" placeholder="La vigencia de la cotización son 30 días.">' +
        '<div class="invalid-feedback" id="invalid-Leyenda">La leyenda no puede ser mayor a 70 carácteres.</div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<h4>Nómina</h4>" +
        '<div class="row">' +
        '<div class="col-lg-12">' +
        '<div class="form-group">' +
        '<div class="row">' +
        '<div class="col-lg-6">' +
        '<label for="usr">Días de aguinaldo:</label>' +
        '<div class="row">' +
        '<div class="col-lg-12 input-group">' +
        '<input class="form-control" type="number" name="txtDiasAguinaldo" id="txtDiasAguinaldo" required="" min="15" maxlength="5" placeholder="15">' +
        '<div class="invalid-feedback" id="ingresar-diasAguinaldo">Debes ingresar los días de aguinaldo.</div>' +
        '<div class="invalid-feedback" id="invalid-diasAguinaldo">El aguinaldo son mínimo 15 días.</div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-lg-6">' +
        '<label for="usr">Prima vacacional(%):</label>' +
        '<div class="row">' +
        '<div class="col-lg-12 input-group">' +
        '<input type="number" maxlength="50" class="form-control" name="txtPrimaVacacional" id="txtPrimaVacacional" maxlength="5" min="25" placeholder="25">' +
        '<div class="invalid-feedback" id="ingresar-primaVacacional">Debes ingresar  la prima vacacional.</div>' +
        '<div class="invalid-feedback" id="invalid-primaVacacional">La prima vacacional no puede ser menor de un 25%.</div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="form-group">' +
        '<div class="row">' +
        '<div class="col-lg-6">' +
        '<label for="usr">Riesgo de trabajo:</label>' +
        '<div class="row">' +
        '<div class="col-lg-12 input-group">' +
        '<input class="form-control" type="number" name="txtRiesgoTrabajo" id="txtRiesgoTrabajo" required="" maxlength="10" placeholder="0.852">' +
        '<div class="invalid-feedback" id="invalid-riesgoTrabajo">Debes ingresar el riesgo de trabajo.</div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div class="col-lg-6">' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        '<a class="btn-custom btn-custom--blue float-right" id="btnGuardarParametros">Guardar</a>' +
        "</div> ";
      break;
    case "MarcadeProductos":
      tabla =
        '<table class="table" id="tblMarcadeProductos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Id</th>" +
        "<th>Marca</th>" +
        "<th>Estatus</th>" +
        "<th>Acciones</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "CategoriadeProductos":
      tabla =
        '<table class="table" id="tblCategoriadeProductos" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Id</th>" +
        "<th>Categoria</th>" +
        "<th>Estatus</th>" +
        "<th>Acciones</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Personal":
      tabla =
        '<table class="table" id="tblPersonal" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>id</th>" +
        "<th>Nombre</th>" +
        "<th>Género</th>" +
        "<th>Estado</th>" +
        "<th>Roles</th>" +
        "<th></th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "CategoriadeClientes":
      tabla =
        '<table class="table" id="tblCategoriadeClientes" width="100%" cellspacing="0">' +
        "<thead>" +
        "<tr>" +
        "<th>Nº</th>" +
        "<th>Categoria</th>" +
        "<th>Estatus</th>" +
        "<th>Acciones</th>" +
        "</tr>" +
        "</thead>" +
        "</table>";
      break;
    case "Widgets":
      $.ajax({
        type: "POST",
        url: "parametros/cargarParametros.php",
        success: function (r) {
          var datos = JSON.parse(r);

          if (datos.resultado == "exito") {
            $("#txtDiasVencimiento").val(datos.diasVencimiento);
            $("#txtLeyenda").val(datos.Leyenda);
            $("#txtDiasAguinaldo").val(datos.diasAguinaldo);
            $("#txtPrimaVacacional").val(datos.primaVacacional);
            $("#txtRiesgoTrabajo").val(datos.riesgotrabajo);
          } else {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top", //or 'center bottom'
              icon: false,
              img: "../../img/timdesk/warning_circle.svg",
              msg: "Ocurrio un error, vuelva intentarlo.",
            });
          }
        },
        error: function () {
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
        },
      });

      tabla =
        '<div class="container-fluid">' +
          "<h4>Widgets</h4>" +
          '<div class="row">' +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-mi-facturacion" class="form-check-label">Mi facturación</label>' +
                '<select class="custom-select" id="wg-mi-facturacion" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas" class="form-check-label">Ventas</label>' +
                '<select class="custom-select" id="wg-ventas" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas-ejecutivo" class="form-check-label">Ventas por ejecutivo</label>' +
                '<select class="custom-select" id="wg-ventas-ejecutivo" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-cuentas-pagar" class="form-check-label">Cuentas por pagar</label>' +
                '<select class="custom-select" id="wg-cuentas-pagar" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-cuentas-cobrar" class="form-check-label">Cuentas por cobrar</label>' +
                '<select class="custom-select" id="wg-cuentas-cobrar" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas-anio" class="form-check-label">Ventas del año</label>' +
                '<select class="custom-select" id="wg-ventas-anio" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-proyectos" class="form-check-label">Proyectos en curso</label>' +
                '<select class="custom-select" id="wg-proyectos" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-cumpleanios" class="form-check-label">Cumpleaños del mes</label>' +
                '<select class="custom-select" id="wg-cumpleanios" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-notas" class="form-check-label">Notas</label>' +
                '<select class="custom-select" id="wg-notas" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-calendario" class="form-check-label">Calendario</label>' +
                '<select class="custom-select" id="wg-calendario" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas-anio-grafica-1" class="form-check-label">Ventas del año grafica 1</label>' +
                '<select class="custom-select" id="wg-ventas-anio-grafica-1" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas-anio-grafica-2" class="form-check-label">Ventas del año grafica 2</label>' +
                '<select class="custom-select" id="wg-ventas-anio-grafica-2" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
            '<div class="col-12">' +
              '<div class="form-group">' +
                '<label for="wg-ventas-mes" class="form-check-label">Ventas del mes grafica</label>' +
                '<select class="custom-select" id="wg-ventas-mes" multiple>' +
                "</select>" +
              "</div>" +
            "</div>" +
          "</div>" +
          "<div class='row justify-content-end mt-2'>" +
            "<div className='col-1'>" +
              "<button id='guardar-widgets' class='btn-custom btn-custom--blue'>Guardar</button>"
            "</div>" +
          "</div>" +
        "</div>";
      break;
  }

  return tabla;
}

function getTableUser(pantalla, permisoAgrEdit, id, modal) {
  var buttonsTop = [];
  if (pantalla !== "DatosEmpresa") {
    if (permisoAgrEdit.agregar) {
      buttonsTop.push({
        text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO_AGREGAR_DARK.svg" width="20" class="mr-1"> Añadir registro</span>',
        className: "btn-custom--white-dark",
        action: function () {
          if (pantalla === "Perfiles") {
            window.location.href = "perfiles/roles";
          } else {
            $("#agregar_" + modal).modal("show");
          }
        },
      });
    }
  }
  if (permisoAgrEdit.editar) {
    buttonsTop.push({
      extend: "excelHtml5",
      text: '<span class="d-flex align-items-center"><img src="../../img/icons/ICONO DESCARGAR2_Mesa de trabajo 1.svg" width="20" class="mr-1"> Descargar excel</span>',
      className: "btn-custom--white-dark",
      titleAttr: "Excel",
    });
  }
  switch (pantalla) {
    case "CategoriadeProductos":
      $("#tblCategoriadeProductos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        // columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
      <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_marcasP",
            value: $("#emp_id").val(),
            session_user: $("#txtUsuario").val(),
            screen: id,
          },
        },
        columns: [
          { data: "Id" },
          { data: "CategoriaProducto" },
          { data: "Estatus" },
          { data: "Acciones", width: "5%", orderable: false },
        ],
      });
      break;
    case "CategoriadeClientes":
      $("#tblCategoriadeClientes").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        // columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
      <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_categoriasClientes",
            value: $("#emp_id").val(),
            session_user: $("#txtUsuario").val(),
            screen: id,
          },
        },
        columns: [
          { data: "Id" },
          { data: "CategoriaCliente" },
          { data: "Estatus" },
          { data: "Acciones", width: "5%", orderable: false },
        ],
      });
      break;
    case "MarcadeProductos":
      $("#tblMarcadeProductos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        // columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_marcasTable",
            value: $("#emp_id").val(),
            session_user: $("#txtUsuario").val(),
            screen: id,
          },
        },
        columns: [
          { data: "Id" },
          { data: "MarcaProducto" },
          { data: "Estatus" },
          { data: "Acciones", width: "5%", orderable: false },
        ],
      });
      break;
    case "Usuarios":
      $("#tblUsuarios").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_userTable",
            value: $("#emp_id").val(),
            session_user: $("#txtUsuario").val(),
            screen: id,
          },
        },
        order: [0, "desc"],
        columns: [
          { data: "id" },
          { data: "No" },
          { data: "Usuario" },
          { data: "Nombre completo" },
          { data: "Estatus" },
          { data: "Acciones" },
        ],
      });
      break;
    case "Perfiles":
      $("#tblPerfiles").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_profilesTable",
          },
        },
        columns: [
          { data: "Id" },
          { data: "No" },
          { data: "Nombre" },
          { data: "Estatus" },
          { data: "Acciones", width: "5%" },
        ],
      });
      break;
    case "Puestos":
      $("#tblPuestos").dataTable({
        language: setFormatDatatables(),
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "puestos/functions/functions_puestos.php",
          data: {
            value: $("#emp_id").val(),
          },
        },
        columns: [
          { data: "id" },
          { data: "No" },
          { data: "Puesto" },
          { data: "Acciones", width: "5%" },
        ],
      });
      break;
    case "Turnos":
      $("#tblTurnos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: "turnos/functions/function_Turno.php",
        columns: [
          { data: "id" },
          { data: "Turno" },
          { data: "Entrada" },
          { data: "Salida" },
          { data: "Dias" },
          { data: "Horas/Semana" },
          { data: "TiempoComida" },
          { data: "Acciones" },
        ],
      });
      break;
    case "CategoriaGastos":
      $(document).ready(function () {
        var idemp = $("#emp_id").val();
        $("#tblCategoriaGastos").dataTable({
          language: setFormatDatatables(),
          info: false,
          scrollX: true,
          bSort: false,
          pageLength: 10,
          responsive: true,
          lengthChange: false,
          columnDefs: [{ orderable: false, targets: 0, visible: false }],
          dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
          buttons: {
            dom: {
              button: {
                tag: "button",
                className: "btn-custom mr-2",
              },
              buttonLiner: {
                tag: null,
              },
            },
            buttons: buttonsTop,
          },
          ajax: {
            url: "categoria_gastos/php/funciones.php",
            data: {
              clase: "get_data",
              funcion: "get_categoryTable",
              data: idemp,
            },
          },
          columns: [
            { data: "id" },
            { data: "NoCategoria" },
            { data: "Nombre" },
            { data: "Acciones", width: "5%" },
          ],
        });
      });
      break;
    case "SubcategoriaGastos":
      $("#tblSubcategoriaGastos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "subcategorias_gastos/php/funciones.php",
          data: { clase: "get_data", funcion: "get_subcategoryTable" },
        },
        columns: [
          { data: "id" },
          { data: "NoSubcategoria" },
          { data: "Nombre" },
          { data: "Categoria" },
          { data: "Acciones", width: "5%" },
        ],
      });
      break;
    case "ResponsableGastos":
      $("#tblResponsableGastos").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "responsables_gastos/php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_responsableTable",
            data: $("#emp_id").val(),
          },
        },
        columns: [
          { data: "id" },
          { data: "NoResponsable" },
          { data: "Nombre" },
          { data: "Acciones", width: "5%" },
        ],
      });
      break;
    case "Sucursales":
      $("#tblSucursales").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "sucursales/php/funciones.php",
          data: { clase: "get_data", funcion: "get_sucursalTable" },
        },
        columns: [
          { data: "id" },
          { data: "Sucursal" },
          { data: "Domicilio" },
          { data: "Colonia" },
          { data: "Municipio" },
          { data: "Estado" },
          { data: "Pais" },
          { data: "Telefono" },
          { data: "Inventario" },
          { data: "Zona salario" },
          { data: "Acciones" },
        ],
      });
      break;
    case "Tipoordeninventario":
      $("#tblListadoTipoOrdenInventario").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "../inventarios_productos/php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_tipoOrdenInventarioTable",
            value: $("#emp_id").val(),
          },
        },
        columns: [
          { data: "Id" },
          { data: "TipoOrdenInventario" },
          { data: "Estatus" },
          { data: "Acciones", width: "5%" },
        ],
      });
      break;
    case "DatosEmpresa":
      $("#tblDatosEmpresa").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_companyDataTable",
          },
        },
        columns: [
          { data: "Razon social" },
          { data: "RFC" },
          { data: "Giro comercial" },
          { data: "Domicilio fiscal" },
          { data: "Regimen fiscal" },
          { data: "IMSS" },
          { data: "Sello cfdi" },
          { data: "Propietario sello cfdi" },
          { data: "Vencimiento sello cfdi" },
          { data: "Logo" },
          { data: "Acciones" },
        ],
      });
      break;
    case "Personal":
      $("#tblPersonal").dataTable({
        language: setFormatDatatables(),
        info: false,
        scrollX: true,
        bSort: false,
        pageLength: 10,
        responsive: true,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0, visible: false }],
        dom: `<"container-fluid"<"row mb-3"<"col-sm-12 col-md-9 p-0 d-flex align-items-center"B><"col-sm-12 col-md-3 p-0"f>>>rti
    <"container-fluid mt-4"<"row"<"#btn-filters.col-sm-12 col-md-10 p-0"><"col-sm-12 col-md-2 p-0"p>>>`,
        buttons: {
          dom: {
            button: {
              tag: "button",
              className: "btn-custom mr-2",
            },
            buttonLiner: {
              tag: null,
            },
          },
          buttons: buttonsTop,
        },
        ajax: {
          url: "php/funciones.php",
          data: {
            clase: "get_data",
            funcion: "get_personalDataTable",
          },
        },
        columns: [
          { data: "id" },
          { data: "Nombre" },
          { data: "Genero" },
          { data: "Estado" },
          { data: "Roles" },
          { data: "Acciones" },
        ],
      });
      break;
  }
}

function quitarAcentos(cadena) {
  const acentos = {
    á: "a",
    é: "e",
    í: "i",
    ó: "o",
    ú: "u",
    Á: "A",
    É: "E",
    Í: "I",
    Ó: "O",
    Ú: "U",
  };
  return cadena
    .split("")
    .map((letra) => acentos[letra] || letra)
    .join("")
    .toString();
}
//VALIDACIONES DE PUESTOS

//VALIDACIONES TURNOS

function setFormatDatatables() {
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
      sNext: "<i class='fas fa-chevron-right'></i>",
      sPrevious: "<i class='fas fa-chevron-left'></i>",
    },
  };
  return idioma_espanol;
}
