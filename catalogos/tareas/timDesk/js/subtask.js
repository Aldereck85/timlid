function sub_done(id_sub) {
  console.log("click");
  $.ajax({
    url: lite,
    data: { clase: "edit_data", funcion: "set_sub_done", id_sub: id_sub },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $(".icon-sub-" + id_sub).removeClass("sub-default-checkmark");
      $(".icon-sub-" + id_sub).addClass("sub-done-checkmark");
      $(".icon-sub-" + id_sub).removeAttr("onclick");
      $(".icon-sub-" + id_sub).attr("onclick", "sub_undone(" + id_sub + ")");

      if (respuesta.action_progress == 1) {
        //que existe columna progreso
        $(".updatesub-bar-" + respuesta.columna).width(
          respuesta.progreso + "%"
        );
        $(".updatesub-bar-text-" + respuesta.columna).html(
          respuesta.progreso + "%"
        );
      }

      if (respuesta.action_check == 1) {
        //que existe columna verificar
        if (respuesta.progreso == 100) {
          //las subtareas están terminadas
          $(".sub-update-checkmark-" + respuesta.columna).removeClass(
            "check-undone"
          );
          $(".sub-update-checkmark-" + respuesta.columna).addClass(
            "check-done-sub"
          );
          $(
            "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
          ).removeClass("default-checkmark");
          $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).addClass(
            "sub-success-checkmark"
          );
          $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).attr(
            "onclick",
            "animate_subtask_undone(" +
              respuesta.columna +
              "," +
              respuesta.id_verificar.PKVerificaSub +
              ")"
          );
        }
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function sub_undone(id_sub) {
  console.log("click");
  $.ajax({
    url: lite,
    data: { clase: "edit_data", funcion: "set_sub_undone", id_sub: id_sub },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      $(".icon-sub-" + id_sub).removeClass("sub-done-checkmark");
      $(".icon-sub-" + id_sub).addClass("sub-default-checkmark");
      $(".icon-sub-" + id_sub).removeAttr("onclick");
      $(".icon-sub-" + id_sub).attr("onclick", "sub_done(" + id_sub + ")");

      if (respuesta.action_progress == 1) {
        $(".updatesub-bar-" + respuesta.columna).width(
          respuesta.progreso + "%"
        );
        $(".updatesub-bar-text-" + respuesta.columna).html(
          respuesta.progreso + "%"
        );
      }

      if (respuesta.action_check == 1) {
        //que existe columna verificar
        $(".sub-update-checkmark-" + respuesta.columna).removeClass(
          "check-done-sub"
        );
        $(".sub-update-checkmark-" + respuesta.columna).addClass(
          "check-undone"
        );
        $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).removeClass(
          "sub-success-checkmark"
        );
        $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).addClass(
          "default-checkmark"
        );
        $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).attr(
          "onclick",
          "animate_subtask_done(" +
            respuesta.columna +
            "," +
            respuesta.id_verificar.PKVerificaSub +
            ")"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function edit_sub(id_sub, texto) {
  if (texto == "enter") {
    let wage = document.getElementById("subTask-text-" + id_sub);
    wage.addEventListener("keydown", function (e) {
      let name = $("#subTask-text-" + id_sub).val();
      if (e.keyCode === 13) {
        new_sub_name(id_sub, name);
      }
    });
  } else {
    let name = $("#subTask-text-" + id_sub).val();
    new_sub_name(id_sub, name);
  }
}

function new_sub_name(id_sub, name) {
  $.ajax({
    url: lite,
    dataType: "json",
    data: {
      clase: "edit_data",
      funcion: "edit_sub",
      id_sub: id_sub,
      nombre: name,
    },
    success: function (resp) {
      if (resp == "ok") {
        $("#subTask-text-" + id_sub).val(name);
        $("#subTask-text-" + id_sub).blur();
      } else {
        $("#subTask-text-" + id_sub).val(resp.SubTarea);
        $("#subTask-text-" + id_sub).blur();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function elim_sub(id_sub, id_tarea) {
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
      text: "Esta subtarea será eliminada junto con sus datos asociados",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter">Eliminar subtarea</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: lite,
          data: {
            clase: "elim_data",
            funcion: "elim_sub",
            id_sub: id_sub,
            id_tarea: id_tarea,
          },
          dataType: "json",
          success: function (respuesta) {
            $(".subTask-" + id_sub).remove();
            if (respuesta.cuenta == 0) {
              $(".count-subtask-" + id_tarea).html("0");
              let task_name = $("#task-name-" + id_tarea).val();
              $("#sub-identifier-" + id_tarea).append(
                '<div class="no-subs" style="color:#a5a5a5;">No se han agregado sub tareas para ' +
                  task_name +
                  "</div>"
              );
              //Si ya no hay subtareas, pero existen las columnas de progreso y verificar:
              if (respuesta.action_progress == 1) {
                $(".updatesub-bar-" + id_tarea).width("0%");
                $(".updatesub-bar-text-" + id_tarea).html("0%");
              }

              if (respuesta.action_check == 1) {
                $(".sub-update-checkmark-" + id_tarea).removeClass(
                  "check-done-sub"
                );
                $(".sub-update-checkmark-" + id_tarea).addClass("check-undone");
                $(
                  "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                ).removeClass("sub-success-checkmark");
                $(
                  "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                ).addClass("default-checkmark");
                $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).attr(
                  "onclick",
                  "animate_subtask_done(" +
                    id_tarea +
                    "," +
                    respuesta.id_verificar.PKVerificaSub +
                    ")"
                );
              }
            } else {
              $(".count-subtask-" + id_tarea).html("");
              $(".count-subtask-" + id_tarea).html(respuesta.cuenta);
              if (respuesta.action_progress == 1) {
                //SI hay columna progreso
                $(".updatesub-bar-" + id_tarea).width(respuesta.progreso + "%");
                $(".updatesub-bar-text-" + id_tarea).html(
                  respuesta.progreso + "%"
                );
              }

              if (respuesta.action_check == 1) {
                //Si hay columna verificar
                if (respuesta.progreso == 100) {
                  //Si las subtareas restantes seguían todas terminadas:
                  $(".sub-update-checkmark-" + id_tarea).removeClass(
                    "check-undone"
                  );
                  $(".sub-update-checkmark-" + id_tarea).addClass(
                    "check-done-sub"
                  );
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).removeClass("default-checkmark");
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).addClass("sub-success-checkmark");
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).attr(
                    "onclick",
                    "animate_subtask_undone(" +
                      id_tarea +
                      "," +
                      respuesta.id_verificar.PKVerificaSub +
                      ")"
                  );
                } else {
                  //Si alguna subtarea no estaba terminada:
                  $(".sub-update-checkmark-" + id_tarea).removeClass(
                    "check-done-sub"
                  );
                  $(".sub-update-checkmark-" + id_tarea).addClass(
                    "check-undone"
                  );
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).removeClass("sub-success-checkmark");
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).addClass("default-checkmark");
                  $(
                    "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
                  ).attr(
                    "onclick",
                    "animate_subtask_done(" +
                      id_tarea +
                      "," +
                      respuesta.id_verificar.PKVerificaSub +
                      ")"
                  );
                }
              }
            }
            lobby_notify(
              "¡Subtarea eliminada!",
              "notificacion_error.svg",
              "error",
              "chat/"
            );
          },
          error: function (error) {
            console.log(error);
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });

  // swal("La subtarea será eliminada, ¿Desea continuar?",{
  // 	buttons: {
  //     cancel: {
  //     	text:"Cancelar",
  //     	value:null,
  //     	visible:true,
  //     	className:"btn-danger",
  //     	closeModal:true,
  //     },
  //     confirm: {
  // 		text: "Eliminar Subtarea",
  // 		value:"delete",
  // 		visible:true,
  // 		className:"btn-primary",
  // 		closeModal:true,
  //     },
  //   },
  // }).then((value) => {
  // 	 switch (value) {

  // 	    case "delete":

  // 	     break;

  // 	    default:
  // 	     break;
  // 	}
  // });
}

function removeSubTask(id_tarea) {
  $("#subTask-" + id_tarea).remove();
  sortableTrue();
  $(".subTask-identifier-" + id_tarea).removeAttr("onclick");
  $(".subTask-identifier-" + id_tarea).attr(
    "onclick",
    "loadSubTask(" + id_tarea + ")"
  );
}

function loadSubTask(id_tarea) {
  $(".sort_task").addClass("sort_alert");
  $(".subTask-identifier-" + id_tarea).removeAttr("onclick");
  $(".subTask-identifier-" + id_tarea).attr(
    "onclick",
    "removeSubTask(" + id_tarea + ")"
  );
  $(".subTask").remove();
  let task_name = $("#task-name-" + id_tarea).val();
  //Mostrar div de subtareas.
  //deshabilitar sortables hasta que se cierra el div
  $("#tarea-" + id_tarea).after(
    '<div id="subTask-' +
      id_tarea +
      '" class="subTask" style="color: #15589b;background: #ffffff;">' +
      '<div class="subTask-tittle" style="padding: 10px;">' +
      "<h5>Subtareas para " +
      task_name +
      ":</h5>" +
      "</div>" +
      '<div id="sub-identifier-' +
      id_tarea +
      '" class="subTask-container"></div>' +
      '<div class="d-flex" style="margin-left: 10px;padding:20px;">' +
      '<i class="sub-plus-icon imgHover imgActive cursorPointer" onclick="add_subtask(' +
      id_tarea +
      ')"></i>' +
      '<div style="margin-left: 10px;"><span style="">Agregar Subtarea</span></div>' +
      "</div>" +
      "</div>"
  );

  $.ajax({
    url: lite,
    data: { clase: "get_data", funcion: "get_sub", id_tarea: id_tarea },
    dataType: "json",
    success: function (respuesta) {
      let attribute = "";
      let subTaskClass = "";
      let enter = "enter";
      let doit = "doit";
      var activo = "";
      var eliminar = "";
      console.log("ETTTO ");
      console.log(respuesta);
      if (respuesta.length != 0) {
        //Que hay subtareas
        $.each(respuesta, function (i) {
          if (respuesta[i].permiso == "-1") {
            //console.log('entro al if')
            if (respuesta[i].Terminada == 0) {
              //Si la subtarea no está terminada
              if (respuesta[respuesta.length - 1].permiso == 0) {
                attribute = "";
              } else {
                attribute =
                  'onclick="sub_done(' + respuesta[i].PKSubTarea + ')"';
              }
              subTaskClass = "sub-default-checkmark";
            } else {
              if (respuesta[respuesta.length - 1].permiso == 0) {
                attribute = "";
              } else {
                attribute =
                  'onclick="sub_undone(' + respuesta[i].PKSubTarea + ')"';
              }
              subTaskClass = "sub-done-checkmark";
            }

            if (respuesta[respuesta.length - 1].permiso == 0) {
              activo = "disabled";
              eliminar = "";
            } else {
              activo = "";
              eliminar =
                '<i class="elim-icon cursorPointer imgActive imgHover" onclick="elim_sub(' +
                respuesta[i].PKSubTarea +
                "," +
                id_tarea +
                ')"></i>';
            }

            $("#sub-identifier-" + id_tarea).append(
              '<div class="subTask-' +
                respuesta[i].PKSubTarea +
                ' subtasks">' +
                '<div class="d-flex">' +
                '<div class="subtask-element"><div class="' +
                subTaskClass +
                " icon-sub-" +
                respuesta[i].PKSubTarea +
                '" ' +
                attribute +
                "></div></div>" +
                '<div style="flex:1;">' +
                '<input id="subTask-text-' +
                respuesta[i].PKSubTarea +
                '" type="text" class="form-check-label" style="background:transparent;" onkeydown="edit_sub(' +
                respuesta[i].PKSubTarea +
                "," +
                caracter2 +
                enter +
                caracter2 +
                ')" onfocusout="edit_sub(' +
                respuesta[i].PKSubTarea +
                "," +
                caracter2 +
                doit +
                caracter2 +
                ')"' +
                'placeholder="Hasta 50 caracteres" value="' +
                respuesta[i].SubTarea +
                '" ' +
                activo +
                ">" +
                "</div>" +
                '<div class="d-flex sub-actions">' +
                "<div>" +
                eliminar +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>"
            );
          }
        });
      }

      if (
        respuesta.length <= 1 &&
        (respuesta[respuesta.length - 1].permiso == 0 ||
          respuesta[respuesta.length - 1].permiso == 1)
      ) {
        //console.log('entro al else')
        $("#sub-identifier-" + id_tarea).append(
          '<div class="no-subs" style="color:#a5a5a5;">No se han agregado sub tareas para ' +
            task_name +
            "</div>"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });

  sortableFalse();
}

function add_subtask(id_tarea) {
  let enter = "enter";
  let doit = "doit";
  $.ajax({
    url: lite,
    data: { clase: "add_data", funcion: "add_sub", id_tarea: id_tarea },
    dataType: "json",
    success: function (respuesta) {
      console.log(respuesta);
      if (respuesta != -1) {
        $(".count-subtask-" + id_tarea).html(" ");
        $(".count-subtask-" + id_tarea).html(respuesta["total"]);
        $(".no-subs").remove();
        $("#sub-identifier-" + id_tarea).append(
          '<div class="subTask-' +
            respuesta["id_subtarea"] +
            ' subtasks">' +
            '<div class="d-flex">' +
            '<div class="subtask-element"><div class="sub-default-checkmark icon-sub-' +
            respuesta["id_subtarea"] +
            '" onclick="sub_done(' +
            respuesta["id_subtarea"] +
            ')"></div></div>' +
            '<div style="flex:1;">' +
            '<input id="subTask-text-' +
            respuesta["id_subtarea"] +
            '" type="text" class="form-check-label" style="background:transparent;" onkeydown="edit_sub(' +
            respuesta["id_subtarea"] +
            "," +
            caracter2 +
            enter +
            caracter2 +
            ')" onfocusout="edit_sub(' +
            respuesta["id_subtarea"] +
            "," +
            caracter2 +
            doit +
            caracter2 +
            ')"' +
            'placeholder="Hasta 50 caracteres" value="' +
            respuesta["nombre"] +
            '">' +
            "</div>" +
            '<div class="d-flex sub-actions">' +
            '<div><i class="elim-icon cursorPointer imgActive imgHover" onclick="elim_sub(' +
            respuesta["id_subtarea"] +
            "," +
            id_tarea +
            ')"></i></div>' +
            "</div>" +
            "</div>" +
            "</div>"
        );

        if (respuesta.action_check == 1) {
          //Existe columna de verificar
          $(".sub-update-checkmark-" + id_tarea).removeClass("check-done-sub");
          $(".sub-update-checkmark-" + id_tarea).addClass("check-undone");
          $(
            "#subcheckmark-" + respuesta.id_verificar.PKVerificaSub
          ).removeClass("sub-success-checkmark");
          $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).addClass(
            "default-checkmark"
          );
          $("#subcheckmark-" + respuesta.id_verificar.PKVerificaSub).attr(
            "onclick",
            "animate_subtask_done(" +
              id_tarea +
              "," +
              respuesta.id_verificar.PKVerificaSub +
              ")"
          );
        }

        if (respuesta.action_progress == 1) {
          //Existe columna progreso
          $(".updatesub-bar-" + id_tarea).width(respuesta.progreso + "%");
          $(".updatesub-bar-text-" + id_tarea).html(respuesta.progreso + "%");
        }
      } else {
        lobby_notify(
          "Sólo el encargado del proyecto o el responsable de la tarea puede agregar subtareas.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function sortableFalse() {
  //Bloquea el sortable de las tareas
  $(".sort_alert").removeClass("sort_task");
  $("#alert-subtask").fadeIn(300);
  setTimeout(function () {
    $("#alert-subtask").fadeOut();
  }, 5000);
}

function sortableTrue() {
  //Desbloquea el sortable de las tareas
  $(".sort_alert").addClass("sort_task");
  $(".sort_task").removeClass("sort_alert");

  let comprobar = $(".subTask");
  console.log("comprobar", comprobar, "largo: ", comprobar.length);
  if (comprobar.length !== 0) {
    $(".subTask").remove();
  }
}
