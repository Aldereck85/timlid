/* Funciones para el modal de elemento estado (lista de colores, array de colores)*/

function getColor(id_estado, id_columna) {
  //Opciones de color para columnas estado
  id_del_estado = id_estado;
  console.log("id_del_estado", id_del_estado);
  $("#boardContent").animate({ scrollTop: $("#boardContent").height() }, 1000);
  varContainer = ".opcionesColorColumna";
  //console.log("id_estado", id_estado);
  $(".opcionesColorColumna").remove();
  //$('.estado-tarea-'+id_estado+' div[onclick]').attr('onclick','close_color_modal('+id_estado+','+id_columna+')');
  //console.log("ID DEL ESTADO_TAREA: ",id_estado," ID DE LA COLUMNA: ", id_columna);
  respColors = [];
  //Solicitando los colores que corresponden a esa columna estado

  $.ajax({
    url: lite,
    data: {
      clase: "admin_data",
      funcion: "getColorColumna",
      id_estado: id_estado,
      id_columna: id_columna,
    },
    dataType: "json",
    success: function (resp) {
      //console.log("getColor Response: ",resp)
      let contador = 1;

      var botonEditar;

      if (permisoGeneral == 1) {
        botonEditar =
          '<div id="colorPicker" onclick="go_edit_colors()">' +
          '<span class="m-10">Agregar/Editar</span>' +
          "</div>";
      } else {
        botonEditar = "";
      }
      $(".estado-tarea-" + id_estado).append(
        '<div class="opcionesColorColumna">' +
          '<div id="shadowColors-edit">' +
          '<div id="color-list-edit" class="d-no colors-container-edit"></div>' +
          '<div id="container-colors-add">' +
          '<div id="colorPicker-add" class="d-no"></div>' +
          "</div>" +
          '<div class="do-color-change d-no" onclick="getColor(' +
          id_estado +
          "," +
          id_columna +
          ')">Aplicar</div>' +
          "</div>" +
          '<div id="shadowColors">' +
          '<div id="color-list" class="colors-container"></div>' +
          botonEditar +
          "</div>" +
          "</div>"
      );

      $.each(resp, function (i) {
        if (resp[i].nombre !== " ") {
          $("#color-list").append(
            "<div id='square-color-" +
              resp[i].PKColorColumna +
              "' class='color-container imgActive imgHover' style='background:" +
              resp[i].color +
              "' onclick='setColorTask(" +
              resp[i].PKColorColumna +
              "," +
              id_estado +
              "," +
              caracter +
              resp[i].color +
              caracter +
              "," +
              caracter +
              resp[i].nombre +
              caracter +
              ")'>" +
              resp[i].nombre +
              "</div>"
          );

          //'<div id="square-color-'+resp[i].PKColorColumna+'" class="color-container imgActive imgHover" style="background:'+
          // resp[i].color+'" onclick=setColorTask('+
          // resp[i].PKColorColumna+','+id_estado+','+caracter+resp[i].color+caracter+','+caracter2+resp[i].nombre+caracter2+')>'+resp[i].nombre+'</div>'
        } else {
          $("#color-list").append(
            '<div id="square-color-' +
              resp[i].PKColorColumna +
              '" class="color-container imgActive imgHover" style="background:' +
              resp[i].color +
              '" onclick=setColorTask(' +
              resp[i].PKColorColumna +
              "," +
              id_estado +
              "," +
              caracter +
              resp[i].color +
              caracter +
              ',"")>' +
              resp[i].nombre +
              "</div>"
          );
        }

        print_color_elements(
          resp[0].FKColumnaProyecto,
          resp[i].PKColorColumna,
          resp[i].color,
          resp[i].nombre,
          false,
          resp[i].bandera
        );

        respColors.push(resp[i].color);
      });

      $("#color-list-edit").append(
        '<div class="d-flex div-new-label" style="justify-content:center"><div class="color-container-edit pos-rel"><div class="new-label cursorPointer" onclick="new_label(' +
          resp[0].FKColumnaProyecto +
          ')">Nueva etiqueta</div></div>'
      );

      //Si no estoy usando los 28 colores
      if (respColors.length !== 30) {
        colorsToPrint = colors_list_array.filter(function (val) {
          return respColors.indexOf(val) == -1;
        });

        for (i = 0; i < colorsToPrint.length; i++) {
          $("#colorPicker-add").append(
            '<div id="num-color-' +
              contador +
              '" class="status-color-icon imgActive" data-style=' +
              colorsToPrint[i] +
              ' style="background:' +
              colorsToPrint[i] +
              ';" onclick="new_label_square(' +
              resp[0].FKColumnaProyecto +
              "," +
              contador +
              ')"></div>'
          );
          contador++;
        }
      } else {
        $(".div-new-label").removeClass("d-flex");
        $(".div-new-label").hide();
        $("#colorPicker-add").addClass("justify-content-c");
        $("#colorPicker-add").append(
          '<div class="no-elements-identifier" style="color:black;">Sin elementos</div>'
        );
      }

      getColor_listWidth(id_del_estado);
      getColor_listWidth_edit(id_del_estado);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

// function close_color_modal(id_estado, id_columna){
// 	$('.opcionesColorColumna').remove();
// 	$('.estado-tarea-'+id_estado+' div[onclick]').attr('onclick','getColor('+id_estado+','+id_columna+')');
// }

function setColorTask(id_color, id_estado, color, nombre) {
  //Cuando se cambia de estado a algún elemento
  //console.log(color,id_estado,id_color,nombre)
  $(".opcionesColorColumna").remove();
  let flag = false;
  if (color == "#28c67a") {
    flag = true;
  }

  $.ajax({
    url: lite,
    data: {
      clase: "admin_data",
      funcion: "setColorTarea",
      id_estado: id_estado,
      id_color: id_color,
      flag: flag,
    },
    dataType: "json",
    success: function (resp) {
      //console.log("RESPUESTA SETCOLORTASK: ",resp)
      var btnColor = document.getElementById("btn-color-" + id_estado);
      btnColor.style.background = color; //Agregandole el nuevo color

      if (nombre == "") {
        //Si no tiene texto el estado elegido
        //console.log('quito 15 y pongo 26')
        $("#btn-color-" + id_estado).removeClass("pad-15px");
        $("#btn-color-" + id_estado).addClass("pad-18px"); //era 26px
      }

      if (nombre != "") {
        //Si tiene texto el estado elegido
        //console.log('quito 26 y pongo 15')
        $("#btn-color-" + id_estado).removeClass("pad-18px"); //era 26px
        $("#btn-color-" + id_estado).addClass("pad-15px");
      }

      $("#btn-text-" + id_estado).text();
      $("#btn-text-" + id_estado).text(nombre);
      $("#btn-text-" + id_estado).removeClass();
      $("#btn-text-" + id_estado).addClass(
        "white-bold text-to-show-" + id_color
      );
      $("#btn-text-" + id_estado).css("color", "white");

      if (resp.updatecheck == "checar") {
        //Se debe actualizar el checkmark a done
        console.log(
          "actualizo el checkmark según estado (terminada 1 o 0) de la tarea"
        );
        console.log(resp[0]);
        if (resp[0][0].Estado == 0) {
          //tarea no terminada

          $(".update-checkmark-" + resp[0][0].FKTarea).removeClass(
            "check-done"
          );
          $(".update-checkmark-" + resp[0][0].FKTarea).addClass("check-undone");
          $("#checkmark-" + resp[0][0].PKVerificacion).removeClass(
            "success-checkmark"
          );
          $("#checkmark-" + resp[0][0].PKVerificacion).addClass(
            "default-checkmark"
          );
          $("#checkmark-" + resp[0][0].PKVerificacion).attr(
            "onclick",
            "animate_task_done(" +
              resp[0][0].FKTarea +
              "," +
              resp[0][0].PKVerificacion +
              ")"
          );
        } else {
          console.log("aqui");
          $(".update-checkmark-" + resp[0][0].FKTarea).removeClass(
            "check-undone"
          );
          $(".update-checkmark-" + resp[0][0].FKTarea).addClass("check-done");
          $("#checkmark-" + resp[0][0].PKVerificacion).removeClass(
            "default-checkmark"
          );
          $("#checkmark-" + resp[0][0].PKVerificacion).addClass(
            "success-checkmark"
          );
          $("#checkmark-" + resp[0][0].PKVerificacion).attr(
            "onclick",
            "animate_task_undone(" +
              resp[0][0].FKTarea +
              "," +
              resp[0][0].PKVerificacion +
              ")"
          );
        }
      }

      if (resp.updatecheck == 0) {
        //Se debe actualizar el checkmark a undone
        console.log("actualizo el checkmark a undone");
        console.log(resp[0]);
        $(".update-checkmark-" + resp[0].FKTarea).removeClass("check-done");
        $(".update-checkmark-" + resp[0].FKTarea).addClass("check-undone");
        $("#checkmark-" + resp[0].PKVerificacion).removeClass(
          "success-checkmark"
        );
        $("#checkmark-" + resp[0].PKVerificacion).addClass("default-checkmark");
        $("#checkmark-" + resp[0].PKVerificacion).attr(
          "onclick",
          "animate_task_done(" +
            resp[0].FKTarea +
            "," +
            resp[0].PKVerificacion +
            ")"
        );
      } //Se debe actualizar el checkmark a undone

      if (
        resp.progreso_update == "si" &&
        (resp.updatecheck == "checar" || resp.updatecheck == 0)
      ) {
        //actualiza la barra de progreso
        //console.log('actualiza barra de progreso')
        $(".update-bar-" + resp[1].PKTarea).width(resp[1].progreso + "%");
        $(".update-bar-text-" + resp[1].PKTarea).html(resp[1].progreso + "%");
      } else if (resp.progreso_update == "si") {
        //console.log('actualiza barra de progreso')
        $(".update-bar-" + resp[0].PKTarea).width(resp[0].progreso + "%");
        $(".update-bar-text-" + resp[0].PKTarea).html(resp[0].progreso + "%");
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function go_edit_colors() {
  //Oculta el primer modal de la lista de estados (colores) y muestra el modal para editarlos
  $("#shadowColors").hide();
  $("#color-list-edit").removeClass("d-no");
  $("#colorPicker-add").removeClass("d-no");
  $(".do-color-change").removeClass("d-no");
  $("#colorPicker-add").addClass("d-flex");
  $(".animate-color").animate(
    {
      width: "0",
    },
    {
      duration: 200,
      specialEasing: {
        width: "swing",
      },
    }
  );
}

/* Opciones de los inputs */
function show_color_options(id) {
  //Mostrar Opciones de cada estado de la columna tipo estado (mover, eliminar)
  $("#sortable-" + id).show();
  $("#delete-color-" + id).removeClass("d-no");
  $("#delete-color-" + id).attr("display", "inline-block");
}

function hide_color_options(id) {
  //Ocultar Opciones de cada estado de la columna tipo estado (mover, eliminar)
  $("#sortable-" + id).hide();
  $("#delete-color-" + id).addClass("d-no");
}

function showIconChange(id) {
  //Muestra Icono de burbuja
  $("#change-color-place-" + id).removeClass("d-no");
  $("#change-color-place-" + id).addClass("d-in-block");
  $("#change-color-place-" + id).css("cursor", "pointer");
}

function hideIconChange(id) {
  //Oculta Icono de burbuja
  $("#change-color-place-" + id).removeClass("d-in-block");
  $("#change-color-place-" + id).addClass("d-no");
}

/* Ediciones en los estados */

function do_changesOn_text(id) {
  //Cambio en el texto de un estado
  let texto_color = $("#etiqueta-" + id).val();
  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "change_text_elements",
      PKColorColumna: id,
      texto: texto_color,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      //Checar si el elemento tenía texto o no:
      //let comprobar = $('.text-to-show-'+id).parent().attr('id');
      //console.log("comprobar", comprobar);
      if (texto_color == "") {
        $(".padding-point-" + id).removeClass("pad-15px");
        $(".padding-point-" + id).addClass("pad-18px"); //era 26px
      } else {
        $(".padding-point-" + id).removeClass("pad-18px"); //era 26px
        $(".padding-point-" + id).addClass("pad-15px");
      }

      $("#etiqueta-" + id).val(" ");
      $("#etiqueta-" + id).val(texto_color);
      $(".text-to-show-" + id).html(texto_color);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Crear nuevo input de estado */
function new_label(id) {
  //console.log("LOS COLORES CON INPUT: ",respColors);

  let color = $("#colorPicker-add div").first();
  let colorStyle = color[0].dataset.style; //Obteniendo el color del primer cuadro

  $.ajax({
    url: lite,
    data: {
      clase: "add_data",
      funcion: "add_label",
      PKColumnaProyecto: id,
      id_proyecto: idProyecto,
      color: colorStyle,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      $(".div-new-label").remove();

      print_color_elements(id, respuesta, colorStyle, " ", true, 0);

      //actualizando el array respColors:
      respColors.push(colorStyle);
      //console.log("respColors actualizado", respColors);
      $("#color-list-edit").append(
        '<div class="d-flex div-new-label" style="justify-content:center"><div class="color-container-edit pos-rel"><div class="new-label cursorPointer" onclick="new_label(' +
          id +
          ')">Nueva etiqueta</div></div>'
      );

      if (respColors.length == 30) {
        //Si ya no hay bolitas de colores que elegir, se oculta el botón de crear nueva etiqueta:
        $(".div-new-label").removeClass("d-flex");
        $(".div-new-label").hide();
      }

      color.remove();
      let status = document.getElementsByClassName("status-color-icon");
      //console.log("status", status);
      for (i = 0; i < status.length; i++) {
        $("#" + status[i].id).attr("id", "num-color-" + (i + 1));
        $("#" + status[i].id).attr(
          "onclick",
          "new_label_square(" + id + "," + (i + 1) + ")"
        );
      }

      getColor_listWidth_edit(id_del_estado);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

/* Crea una nueva etiqueta desde los cuadritpos de colores*/
function new_label_square(columna, num) {
  //console.log("LOS COLORES CON INPUT: ",respColors);
  let colorStyle = $("#num-color-" + num).data("style"); //Obteniendo el color del cuadro seleccionado

  $.ajax({
    url: lite,
    data: {
      clase: "add_data",
      funcion: "add_label",
      PKColumnaProyecto: columna,
      id_proyecto: idProyecto,
      color: colorStyle,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
      $(".div-new-label").remove(); //Removiendo el botón

      print_color_elements(columna, respuesta, colorStyle, " ", true, 0);

      //actualizando el array respColors:
      respColors.push(colorStyle);
      //Imprimiendo el botón de nueva etiqueta:
      $("#color-list-edit").append(
        '<div class="d-flex div-new-label" style="justify-content:center"><div class="color-container-edit pos-rel"><div class="new-label cursorPointer" onclick="new_label(' +
          columna +
          ')">Nueva etiqueta</div></div>'
      );

      if (respColors.length == 30) {
        //Si ya no hay cuadritos de colores que elegir, se oculta el botón de crear nueva etiqueta:
        $(".div-new-label").removeClass("d-flex");
        $(".div-new-label").hide();
      }

      $("#num-color-" + num).remove(); //Removiendo el cuadro de color seleccionado
      let status = document.getElementsByClassName("status-color-icon");
      //console.log("status", status);
      for (i = 0; i < status.length; i++) {
        $("#" + status[i].id).attr("id", "num-color-" + (i + 1)); //Actualizando id's de los cuadritos de color
        $("#" + status[i].id).attr(
          "onclick",
          "new_label_square(" + columna + "," + (i + 1) + ")"
        );
      }

      getColor_listWidth_edit(id_del_estado);
    },
    error: function (error) {
      console.log(error);
    },
  });
}
/* Remover input de estado */
function remove_element_color(id, columna) {
  //Comprobar si no está asignado el estado en algún elemento del proyecto:
  let comprobar = document.getElementsByClassName("text-to-show-" + id);
  let elementos = [];
  let no_elements = 0;
  const arrayIndividualColors = [];
  if (comprobar.length == 0) {
    //si comprobar.length es igual a 0 (El estado no está asignado a ningún elemento)
    let color = $(".bar-color-" + id).data("background"); //Obteniendo el color de la barrita al lado del input.
    // console.log("color", color);
    // console.log("respColors antes del IF: ", respColors);
    if (respColors.length !== 30) {
      //Si todavía hay colores para elegir respColors.length no es igual a 30
      const arrayElementColors = []; //Array que se llenará con cada elemento que contiene los cuadritos de colores

      let status = document.getElementById("colorPicker-add").childElementCount; //Obteniendo los cuadritos de colores disponibles
      //console.log("ELEMENTOS DE COLORPICKER-ADD", status);

      //Comprobando si el elemento se removió con un modal individual abierto:
      let comprobarModal = [];
      let modal_ind = document.getElementById("individual-colors-add");
      comprobarModal.push(modal_ind);

      if (comprobarModal[0] !== null) {
        //Se removió un elemento CON el modal individual abierto
        //console.log("Se removió un elemento CON el modal individual abierto");

        elementos = comprobarModal[0].children[0].children;
        //console.log("elementos", elementos[0].id);

        for (i = 0; i < status; i++) {
          //Recorriendo los cuadritos de colores disponibles
          let elemento = $("#" + elementos[i].id); //Obteniendo cada elemento
          let elementoArray = elemento.toArray(); //Convirtiendo cada elemento en array
          arrayIndividualColors.push(elementoArray); //Agrgando cada elemento convertido en array al array vacío
        }
        //console.log("arrayIndividualColors", arrayIndividualColors);
        for (i = 0; i < status; i++) {
          //Recorriendo los cuadritos de colores disponibles
          arrayIndividualColors[i][0].id = "num-color-" + (i + 2); //Actualizando el id de cada elemento.
          document
            .getElementById("num-color-" + (i + 2))
            .setAttribute(
              "onclick",
              "new_label_square(" + columna + "," + (i + 2) + ")"
            ); //Actualizando la función de cada elemento
        }

        $("#individual-colors-add").remove(); //Removiendo el modal individual abierto
      } else {
        //se removió elemento SIN MODAL ABIERTO
        //console.log('se removió elemento SIN MODAL ABIERTO')
        /*====================================================
				= Elementos impresos en el div  "container-colors-add" =
				=====================================================*/

        for (i = 0; i < status; i++) {
          //Recorriendo los cuadritos de colores disponibles
          let elemento = $("#num-color-" + (i + 1)); //Obteniendo cada elemento
          let elementoArray = elemento.toArray(); //Convirtiendo cada elemento en array
          arrayElementColors.push(elementoArray); //Agrgando cada elemento convertido en array al array vacío
        }
        //console.log("arrayElementColors", arrayElementColors);
        for (i = 0; i < status; i++) {
          //Recorriendo los cuadritos de colores disponibles
          arrayElementColors[i][0].id = "num-color-" + (i + 2); //Actualizando el id de cada elemento.
          document
            .getElementById("num-color-" + (i + 2))
            .setAttribute(
              "onclick",
              "new_label_square(" + columna + "," + (i + 2) + ")"
            ); //Actualizando la función de cada elemento
        }

        /*=====  End of Elementos impresos en el div "container-colors-add"  ======*/
      }
    } else {
      $("#colorPicker-add").removeClass("justify-content-c");
      $(".no-elements-identifier").remove();
      no_elements = 1;
    }

    comprobar_impresion(no_elements);
    //console.log('EL ARRAY DE ELEMENTOS PORQUE SE REMOVIO CON MODAL ABIERTO', elementos)
    const ciclo = elementos.length;

    //Actualizando el array de las bolitas de colores y el respColors:
    divColorPicker = document.getElementById("container-colors-add");
    colorPicker_array = [];
    colorPicker_array.push(divColorPicker);

    var index = respColors.indexOf(color);
    respColors.splice(index, 1);
    //console.log("respColors actualizado", respColors);

    //Removiendo la fila de la BBDD

    $("#config-color-container-" + id).remove();
    //Imprimiendo el nuevo color disponible:
    if (arrayIndividualColors.length !== 0) {
      //El elemento se removió con un modal individual abierto
      for (i = 0; i < arrayIndividualColors.length; i++) {
        $("#colorPicker-add").append(arrayIndividualColors[i][0]);
        $("#num-color-" + (i + 1)).removeAttr("onmouseenter");
      }
    }
    $("#colorPicker-add").prepend(
      '<div id="num-color-1" class="status-color-icon imgActive" data-style=' +
        color +
        ' style="background:' +
        color +
        ';" onclick="new_label_square(' +
        columna +
        ',1)"></div>'
    );

    $.ajax({
      url: lite,
      data: {
        clase: "elim_data",
        funcion: "elimColorElement",
        PKColorColumna: id,
      },
      dataType: "json",
      success: function (respuesta) {
        //console.log(respuesta);
        getColor_listWidth_edit(id_del_estado);
        $(".div-new-label").addClass("d-flex");
        $("div-new-label").show();
      },
      error: function (error) {
        console.log(error);
      },
    });
  }
}

/* Comprueba si las opciones de cuadritos de colores están impresas en medio del div de inputs y del div "aplicar" */
function comprobar_impresion(flag) {
  let buleano;
  let theDiv = document.getElementById("shadowColors-edit").children;
  //console.log("theDiv", theDiv);
  //console.log("theDiv[i].id", theDiv[i].id);
  if (theDiv[1].id == "container-colors-add" && flag !== 1) {
    buleano = true; //Está impreso
    //console.log("esta impreso", buleano);
  } else {
    buleano = false; //No está impreso
    //console.log("no esta impreso", buleano);
  }
  //console.log("BULEANO", buleano);
  if (!buleano) {
    $("#color-list-edit").after(
      '<div id="container-colors-add"><div id="colorPicker-add" class="d-flex"></div></div>'
    );
    // $('#colorPicker-add').width(div_width);
  }
}

function open_colorPicker_add(old_color, id, columna) {
  //abre el div individual por input que contiene los círculos de los colores

  $("#colorPicker-add").width("170"); //Estableciendo el nuevo ancho

  divColorPicker = document.getElementById("container-colors-add");
  //console.log("divColorPicker", divColorPicker);

  //Si ya estaba abierto un modal individual de colores y sin cerrarlo se abrió otro, divColorPicker es null:
  if (divColorPicker === null) {
    //Toma los datos de un modal individual de colores
    divColorPicker = document.getElementById("individual-colors-add");
    console.log("divColorPicker de individual", divColorPicker);
  }

  colorPicker_array = [];
  colorPicker_array.push(divColorPicker);

  $("#individual-colors-add").remove();
  $("#container-colors-add").remove();
  $("#change-color-place-" + id).attr(
    "onclick",
    "close_colorPicker_add(" +
      caracter +
      old_color +
      caracter +
      "," +
      id +
      "," +
      columna +
      ")"
  );

  if (respColors.length !== 30) {
    //Que hay colores para elegir

    $("#config-color-container-" + id).append(
      '<div id="individual-colors-add" class="pos-abs" onmouseenter="fill_color(' +
        id +
        ')" onmouseleave="empty_color(' +
        id +
        ')">' +
        colorPicker_array[0].innerHTML +
        "</div>"
    );

    let theStyleColors = document.getElementById("colorPicker-add").children;
    //console.log("theStyleColors", theStyleColors);
    let contador = 1;
    for (var i = 0; i < theStyleColors.length; i++) {
      $("#" + theStyleColors[i].id).attr(
        "onmouseenter",
        "paint_color(" +
          caracter +
          theStyleColors[i].dataset.style +
          caracter +
          "," +
          id +
          ")"
      );
      $("#" + theStyleColors[i].id).attr(
        "onclick",
        "send_color(" +
          caracter +
          theStyleColors[i].dataset.style +
          caracter +
          "," +
          id +
          "," +
          caracter +
          old_color +
          caracter +
          "," +
          contador +
          "," +
          columna +
          ")"
      );
      contador++;
    }
  } else {
    //No hay más colores para elegir
    $("#config-color-container-" + id).append(
      '<div id="individual-colors-add" class="pos-abs" style="width:125px;"><div class="no-elements-identifier" style="color:black;">Sin elementos</div></div>'
    );
  }
}

function close_colorPicker_add(old_color, id, columna) {
  //cierra el div que contiene los círculos de los colores

  let div_width = $("#color-list-edit").width();
  //console.log("div_width", div_width);

  divColorPicker = document.getElementById("individual-colors-add");
  colorPicker_array = [];
  colorPicker_array.push(divColorPicker);
  $("#individual-colors-add").remove();
  $("#change-color-place-" + id).attr(
    "onclick",
    "open_colorPicker_add(" +
      caracter +
      old_color +
      caracter +
      "," +
      id +
      "," +
      columna +
      ")"
  );
  //comprobando que no esté ya impreso container-colors-add en la parte inferior:
  let buleano;

  let theDiv = document.getElementById("shadowColors-edit").children;

  for (var i = 0; i < theDiv.length; i++) {
    //console.log("theDiv[i].id", theDiv[i].id);
    if (theDiv[i].id == "container-colors-add") {
      buleano = true; //Está impreso
      i = theDiv.length;
      //console.log("esta impreso", buleano);
    } else {
      buleano = false; //No está impreso
      //console.log("no esta impreso", buleano);
    }
  }
  //console.log("BULEANO", buleano);
  if (!buleano) {
    $("#color-list-edit").after(
      '<div id="container-colors-add">' +
        colorPicker_array[0].innerHTML +
        "</div>"
    );
    $("#colorPicker-add").width(div_width);
    if (respColors.length !== 30) {
      //Si todavía hay colores que elegir
      let theStyleColors = document.getElementById("colorPicker-add").children;
      let contador = 1;
      for (var i = 0; i < theStyleColors.length; i++) {
        $("#" + theStyleColors[i].id).removeAttr("onmouseenter");
        $("#" + theStyleColors[i].id).removeAttr("onclick");
        contador++;
      }
    }
  }
}

function fill_color(id) {
  let elTexto = $("#etiqueta-" + id).val();
  $("#identifier-animate-" + id).text(elTexto);
  $("#identifier-animate-" + id).animate(
    {
      width: "100",
    },
    {
      duration: 200,
      specialEasing: {
        width: "swing",
      },
    }
  );
}
function empty_color(id) {
  $("#identifier-animate-" + id).text(" ");
  $("#identifier-animate-" + id).animate(
    {
      width: "0",
    },
    {
      duration: 200,
      specialEasing: {
        width: "swing",
      },
    }
  );
}
function paint_color(color, id) {
  $("#identifier-animate-" + id).css("background", color);
}
function send_color(color, id, old_color, order, columna) {
  $("#tip-content-" + id).removeClass("d-in-block");
  $("#tip-content-" + id).addClass("d-no");
  //Cierre del color-picker individual e impresión del color-picker general
  let div_width = $("#color-list-edit").width();
  //console.log("div_width", div_width);

  //Obteniendo el HTML (los colores) del modal individual
  divColorPicker = document.getElementById("individual-colors-add");
  colorPicker_array = [];
  colorPicker_array.push(divColorPicker); //

  $("#individual-colors-add").remove(); //Cerrando el modal individual de colores
  $("#change-color-place-" + id).attr(
    "onclick",
    "open_colorPicker_add(" +
      caracter +
      color +
      caracter +
      "," +
      id +
      "," +
      columna +
      ")"
  ); //Cambiando la función al clic de la gotita
  $("#color-list-edit").after(
    '<div id="container-colors-add">' +
      colorPicker_array[0].innerHTML +
      "</div>"
  ); //Imprimiendo la barra de colores inferior
  $("#colorPicker-add").width(div_width);

  //Modificando la configuración de atributos de la barra de colores inferior:
  let theStyleColors = document.getElementById("colorPicker-add").children;
  let contador = 1;
  for (var i = 0; i < theStyleColors.length; i++) {
    $("#" + theStyleColors[i].id).removeAttr("onmouseenter");
    $("#" + theStyleColors[i].id).attr(
      "onclick",
      "new_label_square(" + columna + "," + (i + 1) + ")"
    );
    contador++;
  }
  //

  //Quitar el color elegido por el usuario del color-picker general y agregar por el que se cambió
  $("#num-color-" + order).css("background", old_color);
  $("#num-color-" + order).attr("data-style", old_color);

  //console.log("color", color, "ID: ", id, "old_color: ", old_color);
  $("#identifier-animate-" + id).text(" "); //Vaciando el texto del cuadaro de la animación
  $("#identifier-animate-" + id).css("background", color); //implementando el color al cuadro de animación.
  //Cerrando la animación del cuadro
  $("#identifier-animate-" + id).animate(
    {
      width: "0",
    },
    {
      duration: 200,
      specialEasing: {
        width: "swing",
      },
    }
  );
  $(".bar-color-" + id).css("background", color); //Poniendo el color sobre bar-color-n
  $(".bar-color-" + id).attr("data-background", color); //Cambiando el atributo data-background de la barrita de color al lado del input
  let parent = $(".text-to-show-" + id)
    .parent()
    .css("background", color); //Cambiando color a cada elemento seleccionado con ese color

  //Guardando la información en la base de datos
  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "change_color_elements",
      PKColorColumna: id,
      color: color,
    },
    dataType: "json",
    success: function (respuesta) {
      //console.log(respuesta);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function estado_tooltip(id) {
  console.log("estado_tooltip ENTRA", id);
  //Comprobar si no está asignado el estado en algún elemento del proyecto:
  let comprobar = document.getElementsByClassName("text-to-show-" + id);
  $("#tip-content-" + id).css("width", "150px");
  $("#tip-content-" + id).removeClass("d-no");
  $("#tip-content-" + id).addClass("d-in-block");
  if (comprobar.length !== 0) {
    //Elementos tienen asignado el estado
    $("#tip-content-" + id).html("El elemento está en uso");
  } else {
    $("#tip-content-" + id).html("Eliminar el elemento");
  }
}

function remove_estado_tooltip(id) {
  //console.log("remove_estado_tooltip ENTRA");
  $("#tip-content-" + id).removeClass("d-in-block");
  $("#tip-content-" + id).addClass("d-no");
  //Comprobar si no está asignado el estado en algún elemento del proyecto:
}

function print_color_elements(columna, id, color, nombre, flag, cerouno) {
  //Imprime los elementos con la barra de color e inputs:
  let eliminar;
  let icono;
  if (cerouno == 0) {
    eliminar =
      '<div id="delete-color-' +
      id +
      '" class="pos-abs color-delete-handle d-no cursorPointer" onclick="remove_element_color(' +
      id +
      "," +
      columna +
      ')" onmouseenter=estado_tooltip(' +
      id +
      ') onmouseleave="remove_estado_tooltip(' +
      id +
      ')"><span id="tip-content-' +
      id +
      '" class="pos-abs d-no tip-content"></span></div></div>';
    icono =
      '<i id="change-color-place-' +
      id +
      '" class="icon_change_color d-no" onclick="open_colorPicker_add(' +
      caracter2 +
      color +
      caracter2 +
      "," +
      id +
      "," +
      columna +
      ')"></i>';
  } else {
    eliminar = "";
    icono = "";
  }
  $("#color-list-edit").append(
    '<div id="config-color-container-' +
      id +
      '" class="d-flex pos-rel" style="justify-content:center" onmouseenter="show_color_options(' +
      id +
      ')" onmouseleave="hide_color_options(' +
      id +
      ')">' +
      '<div class="color-container-edit pos-rel">' +
      '<div id="identifier-animate-' +
      id +
      '" class="pos-abs animate-color" style="background:' +
      color +
      '"></div>' +
      '<div id="sortable-' +
      id +
      '" class="color-edit-handle pos-abs d-no">&nbsp;</div>' +
      '<div class="d-flex border-focus">' +
      '<div class="alto-28p d-flex bar-color-' +
      id +
      '" data-background=' +
      color +
      ' style="background:' +
      color +
      ';width:16px;align-items:center;" onmouseenter="showIconChange(' +
      id +
      ')" onmouseleave="hideIconChange(' +
      id +
      ')">' +
      icono +
      "</div>" +
      '<input id="etiqueta-' +
      id +
      '" class="n-focus alto-28p" type="text" placeholder="Agregar etiqueta"  onfocusout="do_changesOn_text(' +
      id +
      ')" value=' +
      caracter +
      nombre +
      caracter +
      ">" +
      "</div>" +
      eliminar +
      "</div>"
  );

  if (flag) {
    $(".animate-color").animate(
      {
        width: "0",
      },
      {
        duration: 200,
        specialEasing: {
          width: "swing",
        },
      }
    );
  }
}

/* Configuración de ancho de los modales */
function getColor_listWidth(id_estado) {
  //Calcula el ancho del modal para elegir estado del elemento
  var ancho = document.getElementById("color-list");
  var rows = document.getElementById("color-list").children.length;
  var ancho2 = document.getElementById("colorPicker");
  var ancho3 = document.getElementById("shadowColors");
  //console.log(rows);

  if (rows <= 5) {
    ancho.style.width = "150px";
    ancho.style.alignItems = "center";
    ancho2.style.width = "150px";
    ancho2.style.alignItems = "center";
    ancho3.style.width = "150px";
    ancho3.style.alignItems = "center";

    $(".opcionesColorColumna").removeClass("aux-width");
  } else if (rows >= 6 && rows <= 10) {
    ancho.style.width = "260px";
    ancho.style.alignItems = "normal";
    ancho2.style.width = "260px";
    ancho2.style.alignItems = "normal";
    ancho3.style.width = "260px";
    ancho3.style.alignItems = "normal";

    $(".opcionesColorColumna").removeClass("aux-width");
  } else if (rows >= 11 && rows <= 15) {
    ancho.style.width = "365px";
    ancho2.style.width = "365px";
    ancho3.style.width = "365px";

    $(".opcionesColorColumna").removeClass("aux-width");
  } else if (rows >= 16 && rows <= 20) {
    ancho.style.width = "485px";
    ancho2.style.width = "485px";
    ancho3.style.width = "485px";
    check_col_position(id_estado);
  } else if (rows >= 21 && rows <= 25) {
    ancho.style.width = "598px";
    ancho2.style.width = "598px";
    ancho3.style.width = "598px";
    check_col_position(id_estado);
  } else {
    ancho.style.width = "720px";
    ancho2.style.width = "720px";
    ancho3.style.width = "720px";
    check_col_position(id_estado);
  }
}

function check_col_position(id_estado) {
  let square = $("#btn-color-" + id_estado);
  let position = square[0].parentElement.parentElement;

  if (position.style.order <= 1) {
    $(".opcionesColorColumna").addClass("aux-width");
  } else {
    $(".opcionesColorColumna").removeClass("aux-width");
  }
}

function getColor_listWidth_edit(id_estado) {
  //Calcula el ancho del modal para editar los estados del elemento
  var ancho = document.getElementById("color-list-edit");
  var rows = document.getElementById("color-list-edit").children.length;
  var ancho2 = document.getElementById("colorPicker-add");
  var ancho3 = document.getElementById("shadowColors-edit");

  ancho2.style.width = "auto";
  //console.log(rows);

  if (rows <= 5) {
    //ancho.style.width = "150px";
    ancho.style.alignItems = "center";
    // ancho2.style.width = "150px";
    // ancho2.style.alignItems = "center";
    ancho3.style.width = "150px";
    ancho2.style.justifyContent = "center";
    $(".opcionesColorColumna").removeClass("aux-width");
    ancho3.style.alignItems = "center";
  } else if (rows >= 6 && rows <= 10) {
    //ancho.style.width = "340px";
    ancho.style.alignItems = "normal";
    // ancho2.style.width = "340px";
    // ancho2.style.alignItems = "normal";
    ancho2.style.justifyContent = "flex-start";
    ancho3.style.width = "340px";
    $(".opcionesColorColumna").removeClass("aux-width");
    ancho3.style.alignItems = "normal";
  } else if (rows >= 11 && rows <= 15) {
    //ancho.style.width = "500px";
    // ancho2.style.width = "500px";
    ancho3.style.width = "500px";
    $(".opcionesColorColumna").removeClass("aux-width");
  } else if (rows >= 16 && rows <= 20) {
    //ancho.style.width = "600px";
    // ancho2.style.width = "600px";
    ancho3.style.width = "600px";
    check_col_position(id_estado);
  } else if (rows >= 21 && rows <= 25) {
    //ancho.style.width = "750px";
    // ancho2.style.width = "750px";
    ancho3.style.width = "750px";
    check_col_position(id_estado);
  } else {
    //ancho.style.width = "1050px";
    // ancho2.style.width = "1050px";
    ancho3.style.width = "1050px";
    check_col_position(id_estado);
  }
}

//Colores para el modal de edición de elementos.
const colors_list_array = [
  "#ffc107",
  "#dc3545",
  "#00efff",
  "#e50dde",
  "#ff158a",
  "#e2445c",
  "#7f5347",
  "#ff642e",
  "#d5e300",
  "#9f8f2f",
  "#9cd326",
  "#037f4c",
  "#0086c0",
  "#579bfc",
  "#66ccff",
  "#fff93c",
  "#a25ddc",
  "#784bd1",
  "#6a6a6a",
  "#333333",
  "#ff7575",
  "#faa1f1",
  "#ffadad",
  "#ff5200",
  "#68a1bd",
  "#225091",
];

console.log("colors_list_array", colors_list_array);
