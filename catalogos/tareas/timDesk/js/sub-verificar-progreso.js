function animate_subtask_done(id_tarea, id_element) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "comprobar_subtareas",
      id_tarea: id_tarea,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta", respuesta);
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
            title: "Atención",
            text: "Las subtareas serán marcadas como completadas, ¿Deseas continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Continuar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              $(".sub-check-options-" + id_element).removeClass("check-undone");
              $(".sub-check-options-" + id_element).addClass("check-done-sub");
              $("#subcheckmark-" + id_element + "").animate(
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
                  function: set_subtask_done(id_tarea, id_element),
                }
              );
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
            }
          });
      } else {
        $(".sub-check-options-" + id_element).removeClass("check-undone");
        $(".sub-check-options-" + id_element).addClass("check-done-sub");
        $("#subcheckmark-" + id_element + "").animate(
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
            function: set_subtask_done(id_tarea, id_element),
          }
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function set_subtask_done(id_tarea, id_element) {
  setTimeout(function () {
    $("#subcheckmark-" + id_element).remove();
    print_subcheck_done(id_tarea, id_element);
  }, 300);
}

function print_subcheck_done(id_tarea, id_element) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "set_subtask_done",
      id_tarea: id_tarea,
      id_element: id_element,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta done: ", respuesta);
      //actualizando verificador
      $(".sub-check-options-" + id_element).append(
        '<div id="subcheckmark-' +
          id_element +
          '" class="sub-success-checkmark pos-rel" onclick="animate_subtask_undone(' +
          id_tarea +
          "," +
          id_element +
          ')"></div>'
      );

      //actualizando progreso
      if (respuesta.progreso == 1) {
        console.log("ACTUALIZAR COLUMNA PROGRESO");
        $(".updatesub-bar-" + id_tarea).width("100%");
        $(".updatesub-bar-text-" + id_tarea).html("100%");
      }
      //actualizar subtareas:
      $.each(respuesta[0], function (i) {
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).removeClass(
          "sub-default-checkmark"
        );
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).addClass(
          "sub-done-checkmark"
        );
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).removeAttr("onclick");
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).attr(
          "onclick",
          "sub_undone(" + respuesta[0][i].PKSubTarea + ")"
        );
      });

      alertNotification();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Marcar subtarea como no terminada
function animate_subtask_undone(id_tarea, id_element) {
  //Comprobar si hay columnas de tipo estado, si existen, alertar al usuario que volverán al estado default (gris).
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "comprobar_subtareas",
      id_tarea: id_tarea,
    },
    dataType: "json",
    success: function (respuesta) {
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
            title: "Atención",
            text: "Las subtareas serán marcadas como incompletas, ¿Deseas continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<span class="verticalCenter">Continuar</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              $("#subcheckmark-" + id_element + "").removeClass(
                "sub-success-checkmark"
              );
              $(".sub-check-options-" + id_element).removeClass(
                "check-done-sub"
              );
              $(".sub-check-options-" + id_element).addClass("check-undone");

              $("#subcheckmark-" + id_element + "").animate(
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
                  function: set_subtask_undone(id_tarea, id_element),
                }
              );
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
            }
          });
      } else {
        $("#subcheckmark-" + id_element).removeClass("sub-success-checkmark");
        $(".sub-check-options-" + id_element).removeClass("check-done-sub");
        $(".sub-check-options-" + id_element).addClass("check-undone");

        $("#subcheckmark-" + id_element).animate(
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
            function: set_subtask_undone(id_tarea, id_element),
          }
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function set_subtask_undone(id_tarea, id_element) {
  setTimeout(function () {
    $("#subcheckmark-" + id_element).remove();
    print_subcheck_undone(id_tarea, id_element);
  }, 300);
}

function print_subcheck_undone(id_tarea, id_element) {
  //Quitar check verde
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "edit_data",
      funcion: "set_subtask_undone",
      id_tarea: id_tarea,
      id_element: id_element,
    },
    dataType: "json",
    success: function (respuesta) {
      console.log("respuesta undone: ", respuesta);
      $(".sub-check-options-" + id_element).append(
        '<div id="subcheckmark-' +
          id_element +
          '" class="default-checkmark pos-rel" onclick="animate_subtask_done(' +
          id_tarea +
          "," +
          id_element +
          ')"></div>'
      );
      //actualizando el progreso a 0
      if (respuesta.progreso == 1) {
        console.log("ACTUALIZAR COLUMNA PROGRESO");
        $(".updatesub-bar-" + id_tarea).width("0%");
        $(".updatesub-bar-text-" + id_tarea).html("0%");
      }
      //actualizar subtareas:
      $.each(respuesta[0], function (i) {
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).removeClass(
          "sub-done-checkmark"
        );
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).addClass(
          "sub-default-checkmark"
        );
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).removeAttr("onclick");
        $(".icon-sub-" + respuesta[0][i].PKSubTarea).attr(
          "onclick",
          "sub_done(" + respuesta[0][i].PKSubTarea + ")"
        );
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
}
