//Marcar tarea como finalizada
function animate_task_done(id_tarea, id_element) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "comprobar_columna_estado",
      id_project: idProyecto,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta !== 0) {
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
            text:
              'Los estados de las columnas se marcarán como "Hechas" (color verde), ¿Deseas continuar?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Continuar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              $(".check-options-" + id_element).removeClass("check-undone");
              $(".check-options-" + id_element).addClass("check-done");
              $("#checkmark-" + id_element + "").animate(
                {
                  //Animando el elemento
                  width: "27px",
                  height: "27px",
                },
                {
                  duration: 200,
                  specialEasing: {
                    width: "swing",
                  },
                  function: set_task_done(id_tarea, id_element),
                }
              );
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
            }
          });

        // swal({
        // 	title:'Atención',
        // 	text: 'Los estados de las columnas se marcarán como "Hechas" (color verde), ¿Deseas continuar?',
        // 	icon: "warning",
        // 	buttons: {
        // 		cancelar:{
        // 			text:"Cancelar",
        // 			closeModal:true,
        // 			className:"btn-light"
        // 		},
        // 		agregar:{
        // 			text:"Continuar",
        // 			className:"btn-primary",
        // 			closeModal:false,
        // 			value:"continue"
        // 		}
        // 	},
        // }).then((value) => {
        // 	switch (value) {
        // 		case "continue":
        // 		break;

        // 		default:
        // 		swal.close();
        // 	}
        // });
      } else {
        $(".check-options-" + id_element).removeClass("check-undone");
        $(".check-options-" + id_element).addClass("check-done");
        $("#checkmark-" + id_element + "").animate(
          {
            //Animando el elemento
            width: "27px",
            height: "27px",
          },
          {
            duration: 200,
            specialEasing: {
              width: "swing",
            },
            function: set_task_done(id_tarea, id_element),
          }
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function set_task_done(id_tarea, id_element) {
  setTimeout(function () {
    $("#checkmark-" + id_element + "").remove();
    print_check_done(id_tarea, id_element);
  }, 300);
}

function print_check_done(id_tarea, id_element) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "set_task_done",
      id_tarea: id_tarea,
      id_element: id_element,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $(".check-options-" + id_element).append(
        '<div id="checkmark-' +
          id_element +
          '" class="success-checkmark pos-rel" onclick="animate_task_undone(' +
          id_tarea +
          "," +
          id_element +
          ')"></div>'
      );

      if (respuesta.respuesta == "update") {
        //Se actualiza la tarea a "Hecho" (Color #28a745)
        $.each(respuesta[0], function (i) {
          //se debe cambiar text-to-show-
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).removeAttr("style");
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).css(
            "background",
            respuesta[0][i][0].color
          );
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).css(
            "height",
            "100%"
          );
          //$('.tarea-color-'+id_tarea+' span').html(" ");

          if (
            respuesta[0][i][0].nombre == "" ||
            respuesta[0][i][0].nombre == " "
          ) {
            //Si el estado no tiene texto
            paddingClass = "pad-18px"; //Antes 26px
          } else {
            paddingClass = "pad-15px";
          }

          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).removeClass();
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).addClass(
            "d-flex justify-content-center align-items-center blob-btn " +
              paddingClass +
              " padding-point-" +
              respuesta[0][i][0].PKColorColumna +
              " tarea-color-" +
              id_tarea
          );

          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).removeClass();
          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).addClass(
            "white-bold text-to-show-" + respuesta[0][i][0].PKColorColumna
          );
          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).html(
            respuesta[0][i][0].nombre
          );
        });
      }

      if (respuesta.progreso == "si") {
        console.log("ACTUALIZAR COLUMNA PROGRESO");
        $(".update-bar-" + respuesta[2]).width("100%");
        $(".update-bar-text-" + respuesta[2]).html("100%");
      }
      //co_86
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Marcar tarea como no terminada
function animate_task_undone(id_tarea, id_element) {
  //Comprobar si hay columnas de tipo estado, si existen, alertar al usuario que volverán al estado default (gris).
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "comprobar_columna_estado",
      id_project: idProyecto,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta !== 0) {
        //Si hay columnas tipo estado

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
            text:
              "Los estados de las columnas volverán al default, ¿Deseas continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Continuar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              $("#checkmark-" + id_element + "").removeClass(
                "success-checkmark"
              );
              $(".check-options-" + id_element).removeClass("check-done");
              $(".check-options-" + id_element).addClass("check-undone");

              $("#checkmark-" + id_element + "").animate(
                {
                  //Animando el elemento
                  width: "20px",
                  height: "20px",
                },
                {
                  duration: 200,
                  specialEasing: {
                    width: "swing",
                  },
                  function: set_task_undone(id_tarea, id_element),
                }
              );
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
            }
          });

        // swal({
        // 	title:'Atención',
        // 	text: 'Los estados de las columnas volverán al default, ¿Deseas continuar?',
        // 	icon: "warning",
        // 	buttons: {
        // 		cancelar:{
        // 			text:"Cancelar",
        // 			closeModal:true,
        // 			className:"btn-light"
        // 		},
        // 		agregar:{
        // 			text:"Continuar",
        // 			className:"btn-primary",
        // 			closeModal:false,
        // 			value:"continue"
        // 		}
        // 	},
        // }).then((value) => {
        //   switch (value) {

        //     case "continue":

        //       swal.close();
        //     break;

        //     default:
        //       swal.close();
        //   }
        // });
      } else {
        $("#checkmark-" + id_element + "").removeClass("success-checkmark");
        $(".check-options-" + id_element).removeClass("check-done");
        $(".check-options-" + id_element).addClass("check-undone");

        $("#checkmark-" + id_element + "").animate(
          {
            //Animando el elemento
            width: "20px",
            height: "20px",
          },
          {
            duration: 200,
            specialEasing: {
              width: "swing",
            },
            function: set_task_undone(id_tarea, id_element),
          }
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function set_task_undone(id_tarea, id_element) {
  console.log("ENTRO set_task_undone");
  setTimeout(function () {
    $("#checkmark-" + id_element + "").remove();
    print_check_undone(id_tarea, id_element);
  }, 300);
}

function print_check_undone(id_tarea, id_element) {
  console.log("entra ajax");

  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "set_task_undone",
      id_tarea: id_tarea,
      id_element: id_element,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $(".check-options-" + id_element).append(
        '<div id="checkmark-' +
          id_element +
          '" class="default-checkmark pos-rel" onclick="animate_task_done(' +
          id_tarea +
          "," +
          id_element +
          ')"></div>'
      );

      if (respuesta.respuesta == "update") {
        //Se actualiza la tarea a "Hecho" (Color #28a745)
        $.each(respuesta[0], function (i) {
          //se debe cambiar text-to-show-
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).removeAttr("style");
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).css(
            "background",
            respuesta[0][i][0].color
          );
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).css(
            "height",
            "100%"
          );
          //$('.tarea-color-'+id_tarea+' span').html(" ");

          if (
            respuesta[0][i][0].nombre == "" ||
            respuesta[0][i][0].nombre == " "
          ) {
            //Si el estado no tiene texto
            paddingClass = "pad-18px"; //Antes 26
          } else {
            paddingClass = "pad-15px";
          }

          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).removeClass();
          $("#btn-color-" + respuesta[1][i].PKEstadoTarea).addClass(
            "d-flex justify-content-center align-items-center blob-btn " +
              paddingClass +
              " padding-point-" +
              respuesta[0][i][0].PKColorColumna +
              " tarea-color-" +
              id_tarea
          );

          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).removeClass();
          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).addClass(
            "white-bold text-to-show-" + respuesta[0][i][0].PKColorColumna
          );
          $("#btn-text-" + respuesta[1][i].PKEstadoTarea).html(
            respuesta[0][i][0].nombre
          );
        });
      }

      if (respuesta.progreso == "si") {
        console.log("ACTUALIZAR COLUMNA PROGRESO");
        $(".update-bar-" + respuesta[2]).width("0%");
        $(".update-bar-text-" + respuesta[2]).html("0%");
      }
      //co_86
    },
    error: function (error) {
      console.log(error);
    },
  });
}
