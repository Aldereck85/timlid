//var columnas="";
var handleClass; //Variable que tomara el id de la columna para funcionar como el "manubrio" para mover las columnas de lugar.
var sortable; //Variable que recibe la columna para hacerla sorteable
var arrColumnas = []; //Array que recibe los tipos de columna en el proyecto, para llamar los elementos en getInfo según el tipo.
var indexArray = []; //Se llenará con la información de las tareas, sirviendo como referencia para el movimiento de los elementos al mover las columnas.
var columnasArray = []; //Se llenará con las columnas de las etapas, sirviendo como referencia para el movimiento de las columnas.
let idProyecto; //Variuble que se llena según el proyecto elegido por el ususario.
var numTareas = []; //Array que sirve como referencia al eliminar o agregar elementos, as
var sorteable; //Variable que recibe cada elemento de la lista de las etapas (las tareas) para hacerlo "sorteable"
var tablas = []; //Array que guarda el total de etapas y sus id's. Sirve como referencia para calcular posiciones de elementos y llenar otros arrays
var varContainer = ""; //Variable para acción de ocultar modales
var oCaracter = ""; //Suceso adicional a clic fuera del elemento agregar columna.
var mCaracter = ""; //Suceso fuera del elemento del menú de los grupos.
var hide = ""; //Variable para acción de ocultar modales
var numTask = 0; //Número que se le asignará cuando se agreguen nuevas tareas.
var picker = 0; //Variable para la funcón de color picker
var specialClass = ""; //Variable para agregar string de nombre de clases según el tipo de columna.
var paddingClass = ""; //Variable para agregar string de atributo paddings según el tipo de columna
var flags = []; //variable para telefonos
var input; //variable para función de API teléfonos
var mostrarIcono; //guardar la variable para saber si se mostrara el icono o no
var idGlobal; //id global para cambio de nombre de proyecto
let configTel = ""; //Variable que guardara cada elemento convertido con la API de teléfonos (intlTelInput)
let arrayConfigTel = []; //Array que guardara los elementos convertidos con intlTelInput
let divColorPicker;
let colorPicker_array = [];
let colorsToPrint = [];
let respColors = [];
let caracter2 = "'";
let etiquetasTarea = [];
let columns_counter = 0;
let id_text_element = 0;
let boolean_option_task = false;
let id_task_option = 0;
let sorteable_tasks;
let enter_input_task = false;
let ss_main;
let cuenta_idElementos_columna = 1;
let array_menu_selected = []; //array que guarda los elementos seleccionados de un menú despegable.
let array_menu_elements = []; //array que guarda los elementos de un menú despegable.
let id_del_estado;
//Variables para timdesklite
let lite = "";
let lite_lock;

let wageProject;

const optionFlatpickr = {
  dateFormat: "d/M/Y",
  mode: "range",
  locale: {
    firstDayOfWeek: 1,
    weekdays: {
      shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      longhand: [
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
      ],
    },
    months: {
      shorthand: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Sep",
        "Oct",
        "Nov",
        "Dic",
      ],
      longhand: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],
    },
  },
  onClose: function (selectedDates, dateStr, instance) {
    let id_element = instance.element.dataset.rank;
    $.ajax({
      url: lite,
      data: {
        clase: "edit_data",
        funcion: "edit_rank",
        id_element: id_element,
        rango: dateStr,
      },
      dataType: "json",
      success: function (respuesta) {
        $(".rank-" + id_element).val("");
        $(".rank-" + id_element).val(dateStr);
      },
      error: function (error) {},
    });
  },
};
/* var today = new Date();
var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate(); */
const SimpleoptionFlatpickr = {
  dateFormat: "Y-m-d",
  mode: "single",
  maxDate: "2050-12-30",
  minDate: "2015-12-30",
  /* defaultDate: date, */
  locale: {
    firstDayOfWeek: 1,
    weekdays: {
      shorthand: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      longhand: [
        "Domingo",
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
      ],
    },
    months: {
      shorthand: [
        "Ene",
        "Feb",
        "Mar",
        "Abr",
        "May",
        "Jun",
        "Jul",
        "Ago",
        "Sep",
        "Оct",
        "Nov",
        "Dic",
      ],
      longhand: [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
      ],
    },
  },
  onClose: function (selectedDates, dateStr, instance, element) {
    let id_element = instance.element.dataset.fecha;
    $.ajax({
      url: lite,
      data: {
        clase: "admin_data",
        funcion: "getFecha",
        id: id_element,
        fecha: dateStr,
      },
      dataType: "json",
      success: function (respuesta) {
        $(".fecha-" + id_element).val("");
        $(".fecha-" + id_element).val(dateStr);
      },
      error: function (error) {},
    });
  },
};

/***********************************************************************************/
/*######    PRIMERAS FUNCIONES PARA TRAER LA INFORMACIÓN DEL PROYECTO        ######*/
/***********************************************************************************/

function getLevels(id) {
  var cont = 1;
  caracter = '"';
  var identificador = "";
  var activo = "";
  var eliminarmostrar = "";
  idGlobal = id;

  $.ajax({
    url: lite,
    data: { clase: "admin_data", funcion: "getLevels", id: id },
    dataType: "json",
    success: function (resp) {
      let enter = "enter";
      let doit = "doit";
      if (resp.length !== 0 && resp[0].permiso != 1 && resp[0].permiso != 0) {
        var columnsCount = resp[0][0].length;
        mostrarIcono = resp[resp.length - 1].permiso;

        $.each(resp, function (i) {
          if (i != resp.length - 1) {
            if (mostrarIcono == 1) {
              activo = "";
            } else {
              activo = "disabled";
              eliminarmostrar = "style='display:none !important;'";
            }

            $("#boardContent").append(
              '<div id="etapa-' +
                (i + 1) +
                '" class="pr-0 hideEtapa container-fluid group_' +
                this.PKEtapa +
                ' grupo" data-group=' +
                this.PKEtapa +
                ">" +
                '<div class="contenedor groupBox" style="flex-direction:column">' +
                '<div class="backColor_' +
                this.PKEtapa +
                ' encabezado-grupo titulo-etapa estilo-etapa d-flex align-items-center" style="background-color:' +
                this.color +
                '">' +
                '<span id="group-tip-' +
                this.PKEtapa +
                '" class="pos-abs group-tip-content d-no" style="font-family: system-ui;"></span>' +
                //input --------
                '<input id="input-group-' +
                this.PKEtapa +
                '" type="text" class="backColor_' +
                this.PKEtapa +
                ' group-name-dots" style="background-color:' +
                this.color +
                '; padding-left: 52px; color: #ffffff;" onkeydown="editarGrupo(' +
                this.PKEtapa +
                "," +
                caracter2 +
                enter +
                caracter2 +
                ')" onfocusout="editarGrupo(' +
                this.PKEtapa +
                "," +
                caracter2 +
                doit +
                caracter2 +
                ')" onclick="show_garbage_group(' +
                this.PKEtapa +
                ')" onmouseenter=group_tip(' +
                this.PKEtapa +
                ") onmouseleave=group_tip_hidden(" +
                this.PKEtapa +
                ') value="' +
                this.Etapa +
                '" ' +
                activo +
                ">" +
                '<div id="group-garbage-' +
                this.PKEtapa +
                '" class="task-garbage-icon-blanco imgHover imgActive" onclick="eliminarGrupo(' +
                this.PKEtapa +
                ')"></div>' +
                '<div id="append-' +
                this.PKEtapa +
                '" class="opt-menu d-flex">' +
                '<i id="drag-group-' +
                this.PKEtapa +
                '" class="opt_group_sort_icon" onclick="getDrag(' +
                this.PKEtapa +
                ')"></i><i id="opt-group-' +
                this.PKEtapa +
                '" class="opt-menu-icon" onclick="getCondense(' +
                this.PKEtapa +
                ')"></i>' +
                "</div>" +
                "<button id=" +
                this.PKEtapa +
                ' class="btnColorPicker btn imgActive ignore-elements picker_' +
                this.PKEtapa +
                '" data-color=' +
                this.color +
                ' data-toggle="coloresTip" title="" data-original-title="Selecciona un color"></button>' +
                "</div>" +
                "</div>"
            );
            $('[data-toggle="coloresTip"]').tooltip();
            // '<div style="padding-top:3px;flex-grow:1;"><span class="color_'+
            // 	this.PKEtapa+' group-name-dots" style="color:'+this.color+'">'+this.Etapa+'</span></div>'

            $("#etapa-" + (i + 1)).append(`
              <div id="tabla-id-${this.PKEtapa}" class="items order_ta_${
              i + 1
            }" data-tab="${this.PKEtapa}" style="overflow-x: scroll;">
                <div class="d-flex pt-2 pb-1 disabled">
                <span class="header titulo-item et_id_${
                  this.PKEtapa
                }" style="margin-left: 32px;">Tareas</span>
                <div id="columnas-principales-etapa-${
                  this.PKEtapa
                }" class="etapas order_${i + 1} d-flex"></div>
              </div>
              </div>
              <div class="mb-5 agregarTarea et_id_${this.PKEtapa}">
                <a class="pointer" onclick="addTask('${this.PKEtapa}', '${
              this.color
            }')">+ Agregar tarea</a></div>
            `);

            for (j = 0; j < columnsCount; j++) {
              $("#columnas-principales-etapa-" + resp[i].PKEtapa).append(
                "<div class='columna-tarea text-center header columna_" +
                  resp[i][0][j].PKColumnaProyecto +
                  " et_id_" +
                  this.PKEtapa +
                  "' data-pos='" +
                  resp[i][0][j].PKColumnaProyecto +
                  "' onmouseenter='showIcon(" +
                  caracter +
                  "icon_" +
                  cont +
                  caracter +
                  "," +
                  caracter +
                  "column-id-text-" +
                  cont +
                  caracter +
                  ")' onmouseleave='hideIcon(" +
                  caracter +
                  "icon_" +
                  cont +
                  caracter +
                  ")'>" +
                  "<div class='icon_i'>" +
                  "<span class='icon icon_" +
                  cont +
                  "' onmouseenter='getSortable()'></span>" +
                  "</div>" +
                  "<div id='opciones_" +
                  cont +
                  "' class='icon_r'>" +
                  "<span class='icon_row icon_" +
                  cont +
                  "' onclick='eliminarColumna(" +
                  resp[i][0][j].PKColumnaProyecto +
                  "," +
                  resp[i][0][j].tipo +
                  ")' " +
                  eliminarmostrar +
                  " ></span>" +
                  "</div>" +
                  '<span id="tip_icon_' +
                  cont +
                  '" class="pos-abs column-tip-content d-no"></span>' +
                  "<input type='text' id='column-id-text-" +
                  cont +
                  "' class='text-square text-center column-name-" +
                  resp[i][0][j].PKColumnaProyecto +
                  "' style='background: transparent;' onfocusout='editColumn(" +
                  cont +
                  "," +
                  resp[i][0][j].PKColumnaProyecto +
                  ")' value='" +
                  resp[i][0][j].nombre +
                  "' " +
                  activo +
                  ">" +
                  "</div>"
              );

              tipo = resp[i][0][j].tipo;
              arrColumnas.push(tipo);
              cont++;
            }
            columns_counter = cont - 1;
            tablas.push(this.PKEtapa);

            var cajaDeColumnas = $(
              "#columnas-principales-etapa-" + this.PKEtapa
            );
            var columnasEtapas = cajaDeColumnas.children();
            var children = columnasEtapas.toArray();
            columnasArray.push(children);
          }
        });
        getTask(id);
      } else {
        mostrarIcono = resp[resp.length - 1].permiso;
        if (mostrarIcono == 1) {
          setTimeout(function () {
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
                title: "El proyecto está vacío",
                text: "¡Agrega una etapa y comienza un gran proyecto!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText:
                  '<span class="verticalCenter">¡Agregar etapa!</span>',
                cancelButtonText:
                  '<span class="verticalCenter">Cancelar</span>',
                reverseButtons: false,
              })
              .then((result) => {
                if (result.isConfirmed) {
                  agregarGrupo();
                } else if (
                  /* Read more about handling dismissals below */
                  result.dismiss === Swal.DismissReason.cancel
                ) {
                }
              });
          }, 2050);
        } else {
          setTimeout(function () {
            const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                actions: "d-flex justify-content-around",
                confirmButton: "btn-custom btn-custom--border-blue",
                cancelButton: "btn-custom btn-custom--blue",
              },
              buttonsStyling: false,
            });

            swalWithBootstrapButtons.fire(
              "El proyecto está vacío",
              "Sólo el encargado del proyecto puede agregar etapas.",
              "warning"
            );
          }, 2050);
        }
      }
    },
    error: function (error) {},
  });
}

function getTask(id) {
  let ids_task = [];
  $.ajax({
    url: lite,
    data: { clase: "admin_data", funcion: "getTask", id: id },
    dataType: "json",
    success: function (resp) {
      let loadChat = "";
      numTareas = resp;
      var activo;
      var clasev;
      $.each(resp, function (i) {
        if (lite_lock != 1) {
          loadChat =
            "<i class='chat_icon imgActive' onclick='loadChat(" +
            this.PKTarea +
            ",1)' data-toggle='chatTip' data-placement='top' title='' data-original-title='Chat'></i>" +
            "<span class='count-chatTask-" +
            this.PKTarea +
            " badge badge-pill badge-counter badge-circle contadorChat' onclick='loadChat(" +
            this.PKTarea +
            ",1)'></span>";
        }

        if (this.permiso == 0) {
          activo = "disabled";
          clasev = "sort_task_v";
        } else {
          activo = "";
          clasev = "sort_task";
        }

        $("#tabla-id-" + resp[i].FKEtapa).append(
          "<div id='tarea-" +
            this.PKTarea +
            "' class='pos-rel hideTarea contenedor et_id_" +
            this.FKEtapa +
            " task' data-ord=" +
            this.PKTarea +
            ">" +
            "<i id='opt-task-" +
            this.PKTarea +
            "' class='" +
            clasev +
            " backColor_" +
            this.FKEtapa +
            "' style='background-color:" +
            this.color +
            ";'></i>" +
            "<div class='encabezado-tarea titulo-item ml-1 modal-opt-task-" +
            this.PKTarea +
            "'>" +
            '<span id="task-tip-' +
            this.PKTarea +
            '" class="pos-abs task-tip-content d-no"></span>' +
            "<input id='task-name-" +
            this.PKTarea +
            "' class='task-name-dots' style='flex:1;padding:0;' onkeydown='editTaskEnter(" +
            this.PKTarea +
            ")' onfocusout='editTask(" +
            this.PKTarea +
            ")' onclick='show_garbage_tak(" +
            this.PKTarea +
            ")' onmouseenter='show_task_tip(" +
            this.PKTarea +
            ")' onmouseleave='hide_task_tip(" +
            this.PKTarea +
            ")' value='" +
            this.Tarea +
            "'' " +
            activo +
            ">" +
            "<div id='task-garbage-" +
            this.PKTarea +
            "' class='task-garbage-icon imgHover imgActive' onclick='eliminarTarea(" +
            this.PKTarea +
            ")'></div>" +
            "<div class='d-flex'>" +
            "<div class='d-flex' style='padding:4px;'>" +
            "<i class='subTask-identifier-" +
            this.PKTarea +
            " subtarea_icon imgActive cursorPointer' onclick='loadSubTask(" +
            this.PKTarea +
            ")' style='margin-right:15px;' data-toggle='subtareasTip' data-placement='top' title='' data-original-title='Subtareas'></i>" +
            "<span class='subTask-identifier-" +
            this.PKTarea +
            " count-subtask-" +
            this.PKTarea +
            " badge badge-pill badge-counter badge-circle contadorSubtareas' onclick='loadSubTask(" +
            this.PKTarea +
            ")'></span>" +
            loadChat +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
        $('[data-toggle="subtareasTip"]').tooltip();
        $('[data-toggle="chatTip"]').tooltip();

        $("#tarea-" + resp[i].PKTarea).append(
          "<div id='index-" +
            (i + 1) +
            "' class='index' style='display: flex;'></div>"
        ); //???index-
        // <div id='index-"+this.PKTarea+"' style='display: flex;'></div>
        ids_task.push(this.PKTarea);
      });

      if (arrColumnas.length != 0) {
        //Si existen columnas:
        //Obtener información de los elementos (cirulo de responsable, la fecha, etc) por cada columna .
        getInfo(arrColumnas, id, resp);
      } else {
        //Si no existen columnas:
        //Hacer solamente las tareas sorteables entre las etapas.
        getTablas(tablas);
      }

      if (resp.length != 0) {
        get_subtask(ids_task);
      }
      var url = new URL(window.location.href);
      var taskId = url.searchParams.get("task");
      var subtaskId = !!url.searchParams.get("subtask");
      window.location.hash = "task-name-" + taskId;
      if (subtaskId) {
        loadSubTask(taskId);
      }
    },
    error: function (error) {},
  });
}

function get_subtask(array) {
  $.ajax({
    url: lite,
    data: { clase: "get_data", funcion: "get_subtask", array: array },
    dataType: "json",
    success: function (respuesta) {
      $.each(respuesta, function (i) {
        $(".count-subtask-" + respuesta[i][0]).html(respuesta[i][1]);
        $(".count-chatTask-" + respuesta[i][0]).html(respuesta[i][2]);
      });
    },
    error: function (error) {},
  });
}

function getInfo(array, id, num) {
  var arrFiltrado = getUnique(array);
  console.log({ lite });
  $.ajax({
    url: lite,
    data: {
      clase: "admin_data",
      funcion: "getInfo",
      array: arrFiltrado,
      id: id,
    },
    dataType: "json",
    success: function (resp) {
      $.each(resp[0], function (i) {
        specialClass = "";
        paddingClass = "";
        var textToShow = print_elements(
          resp[0][i],
          resp["idEmpresa"]["empresa_id"]
        );
        if (resp[0][i].Tipo == 3 || resp[0][i].Tipo == 11) {
          $("#index-" + resp[0][i].tOrden).append(
            "<div class='columna-tarea text-center columna-fecha item-name  alli222 co_" +
              resp[0][i].PKColumnaProyecto +
              " " +
              specialClass +
              "' style='order: " +
              (resp[0][i].cOrden - 1) +
              "'>" +
              textToShow +
              "</div>"
          );
        } else {
          $("#index-" + resp[0][i].tOrden).append(
            "<div class='columna-tarea text-center item-name co_" +
              resp[0][i].PKColumnaProyecto +
              " " +
              specialClass +
              "' style='order: " +
              (resp[0][i].cOrden - 1) +
              "'>" +
              textToShow +
              "</div>"
          );
        }

        if (this.Tipo == 7) {
          flags.push(resp[0][i].id);
          //getFlag(resp[0][i].id);
        }

        if (this.Tipo == 11) {
          getFlat(resp[0][i].id);
        }
        if (this.Tipo == 3) {
          getFlatDate(resp[0][i].id);
        }

        cuenta_idElementos_columna++;
      });
      $.each(num, function (i) {
        var index = $("#index-" + (i + 1)); //000 num[i].PKTarea
        var count = index.children();
        var children = count.toArray();
        var hijos = children.sort(function (a, b) {
          return parseFloat(a.style.order) - parseFloat(b.style.order);
        });
        indexArray.push(hijos);
      });
      for (i = 0; i < indexArray.length; i++) {
        for (j = 0; j < indexArray[i].length; j++) {
          $("#index-" + (i + 1)).append(indexArray[i][j]); //
        }
      }

      getTablas(tablas);

      if (flags.length !== 0) {
        getFlag(flags);
      }
    },
    error: function (error) {},
  });
}

function showIcon(id, id_column) {
  if (mostrarIcono == 1) {
    console.log("primer if");
    console.log({ id, id_column });
    console.log($(".icon." + id));
    $(".icon." + id).css("display", "inline-block");
    $(".icon." + id).css("z-indez", "100");
    $(".icon_row." + id).css("display", "inline-block");
    $(".icon_row." + id).css("z-indez", "100");
    if (lite_lock != 1) {
      $(".icon." + id).css("display", "inline-block");
      $(".icon." + id).css("z-indez", "100");
      $(".icon_row." + id).css("display", "inline-block");
      $(".icon_row." + id).css("z-indez", "100");
    }
  }
  let comprobar = $("#" + id_column).val();
  let contar = comprobar.length;
  if (contar >= 13) {
    $("#tip_" + id).html(comprobar);
    $("#tip_" + id).removeClass("d-no");
    $("#tip_" + id).addClass("d-in-block");
  }
}

function hideIcon(id) {
  $("." + id).hide();
  $("#tip_" + id).removeClass("d-in-block");
  $("#tip_" + id).addClass("d-no");
}

function show_task_tip(id) {
  let comprobar = $("#task-name-" + id).val();
  let contar = comprobar.length;
  if (contar >= 21) {
    $("#task-tip-" + id).html(comprobar);
    $("#task-tip-" + id).removeClass("d-no");
    $("#task-tip-" + id).addClass("d-in-block");
  }
}

function hide_task_tip(id) {
  $("#task-tip-" + id).removeClass("d-in-block");
  $("#task-tip-" + id).addClass("d-no");
}

$(document).on("click", "#btnEliminarProyecto", function () {
  var id = $("#txtIdProject").val();
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
      text: "El proyecto será eliminado junto con sus datos asociados",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar tarea</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: lite,
          data: {
            clase: "elim_data",
            funcion: "delete_proyect",
            idProyecto: id,
          },
          dataType: "json",
          success: function (resp) {
            if (resp.status !== "success") {
              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: true,
                img: "../../../../img/timdesk/checkmark.svg",
                msg: "¡Algo salio mal!",
                sound: "../../../../../sounds/sound4",
              });
              return;
            }

            window.location.href = "../../proyectos";
          },
          error: function (error) {
            Lobibox.notify("error", {
              size: "mini",
              rounded: true,
              delay: 3000,
              delayIndicator: false,
              position: "center top",
              icon: true,
              img: "../../../img/timdesk/checkmark.svg",
              msg: "¡Algo salio mal inténtalo de nuevo más tarde!",
              sound: "../../../sounds/sound4",
            });
          },
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
});

/*****************************************/
/*######    SORTABLE FUNCTIONS     ######*/
/*****************************************/

/*--- SORTABLE FUNCTIONS ---*/
function getTablas(array) {
  var options = {
    animation: 150,
    delay: 100,
    handle: ".sort_task",
    chosenClass: "seleccionado",
    ghostClass: "fantasma",
    dragClass: "drag",
    dataIdAttr: "data-ord",
    direction: "vertical",
    disabled: false,
    filter: ".disabled",
    filter: function (evt) {},
    onUpdate: function (e) {
      var newO = calcPositionTask();

      $.ajax({
        url: lite,
        dataType: "json",
        data: {
          clase: "data_order",
          funcion: "etapaOrder",
          id: idProyecto,
          ordenArray: newO,
        },
        success: function (resp) {
          console.log({ lite });
          console.log({ newO });
          console.log({ resp });
          for (i = 0; i < resp.length; i++) {
            var newId = "index-" + resp[i].Orden;
            $("#tarea-" + resp[i].PKTarea + " .index").attr("id", newId);
          }

          /*============================================
					=            prueba prueba prueba            =
					============================================*/

          indexArray = [];
          for (i = 0; i < numTareas.length; i++) {
            var index = $("#index-" + (i + 1));
            var count = index.children();
            var children = count.toArray();
            indexArray.push(children);
          }

          /*=====  End of prueba prueba prueba  ======*/
        },
        error: function (error) {
          console.log({ error });
        },
      });
    },
    onAdd: function (evt) {
      var tareaCambio = evt.item.getAttribute("data-ord");
      var vEtapa = evt.from.getAttribute("data-tab");
      var nEtapa = evt.to.getAttribute("data-tab");
      var newO = calcPositionTask();

      $("#tarea-" + tareaCambio).removeClass("et_id_" + vEtapa);
      $("#tarea-" + tareaCambio).addClass("et_id_" + nEtapa);
      $("#tarea-" + tareaCambio + " .rcorners1").removeClass(
        "backColor_" + vEtapa
      );
      $("#tarea-" + tareaCambio + " .rcorners1").addClass(
        "backColor_" + nEtapa
      );

      var theColor = document.getElementById(nEtapa).style.background;

      var estilo = $("#tarea-" + tareaCambio + " .backColor_" + nEtapa);
      $.each(estilo, function (i) {
        estilo[i].style.background = theColor;
      });

      tieneClase = $(".group_" + nEtapa).hasClass("condenseGroup");
      if (tieneClase) {
        $("#tarea-" + tareaCambio).addClass("d-no");
      }

      $.ajax({
        url: lite,
        dataType: "json",
        data: {
          clase: "data_order",
          funcion: "tablaOrder",
          id: idProyecto,
          ordenArray: newO,
          tarea: tareaCambio,
          etapa: nEtapa,
        },
        success: function (resp) {
          for (i = 0; i < resp.length; i++) {
            var newId = "index-" + resp[i].Orden;
            $("#tarea-" + resp[i].PKTarea + " .index").attr("id", newId);
          }

          /*============================================
					=            prueba prueba prueba            =
					============================================*/

          indexArray = [];
          for (i = 0; i < numTareas.length; i++) {
            var index = $("#index-" + (i + 1));
            var count = index.children();
            var children = count.toArray();
            indexArray.push(children);
          }

          /*=====  End of prueba prueba prueba  ======*/
        },
        error: function (error) {},
      });
    },
    onChoose: function (evt) {
      let subtask = $(".subTask");

      if (subtask.length >= 1) {
      }
    },
    onMove: function (evt) {
      return evt.related.className.indexOf("disabled") === -1;
    },
  };
  $.each(array, function (i) {
    sorteable = document.getElementById("tabla-id-" + array[i]);
    sorteable_tasks = Sortable.create(sorteable, options);
  });
}

function getSortable(id) {
  $(".opcionesColumna").remove();
  sortable = "";
  var padre = $(event.target).parents(); //contenedores del "manubrio" de la columna para obtener id y clase
  var sortableId = padre[2].id; //Los divs que serán sortables.
  handleClass = padre[0].classList[0]; //El "manubrio"

  var options = {
    group: "columnas",
    animation: 150,
    delay: 100, // time in milliseconds to define when the sorting should start
    //easing:"cubic-bezier(0.895,0.03,0.685,0.22)", //Estilo de animación
    handle: "." + handleClass,
    direction: "horizontal",
    chosenClass: "seleccionado",
    ghostClass: "fantasma",
    dragClass: "drag",
    dataIdAttr: "data-pos",
    filter: function (evt) {},
    onEnd: function (evt) {
      item_movido = evt.item;
      oldPosition = evt.oldIndex;
      position = evt.newIndex;
      calcPosition(columnasArray, indexArray, oldPosition, position);
    },
    store: {
      set: function (sortable) {
        orden = sortable.toArray();

        $.ajax({
          url: lite,
          dataType: "json",
          data: {
            clase: "data_order",
            funcion: "columnOrder",
            id: idProyecto,
            ordenArray: orden,
          },
          success: function (resp) {},
          error: function (error) {},
        });
      },
    },
  };

  var columnas = document.getElementById(sortableId);

  sortable = Sortable.create(columnas, options);
}

function getDrag(id) {
  sortableGroups = "";
  var opcionesEtapa = {
    group: {
      name: "sortable-list",
    },
    animation: 250,
    fallbackTolerance: 0,
    forceFallback: true,
    dataIdAttr: "data-group",
    filter: ".ignore-elements",

    filter: function (evt) {
      $(".popover").remove();
      elId = evt.target.getAttribute("id");
      var entero = parseInt(elId);
      var entero_comprobar = Number.isInteger(entero);
      picker = elId;
      if (entero_comprobar) {
        var color = $(".picker_" + elId).bcp();
      }
    },

    handle: ".opt_group_sort_icon",
    onMove: function () {
      $(".subTask").remove();
      $(".opcionesGrupo").remove();
      for (var i = 0; i < tablas.length; i++) {
        $(".group_" + tablas[i]).addClass("condenseGroup");
        $(".task").addClass("d-no");
        $(".header").addClass("d-no");
        $(".agregarTarea").addClass("d-no");
      }
    },
    onEnd: function (evt) {
      item_movido = evt.item;
      oldPosition = evt.oldIndex;
      position = evt.newIndex;

      showAllTask();
      $(".opt-menu").show();
    },
    store: {
      set: function (sortable) {
        orden = sortable.toArray();

        $.ajax({
          url: lite,
          dataType: "json",
          data: {
            clase: "data_order",
            funcion: "groupOrder",
            id: idProyecto,
            ordenArray: orden,
          },
          success: function (resp) {
            if (resp.info == "tareas") {
              for (i = 0; i < resp[0].length; i++) {
                var newId = "index-" + resp[0][i].Orden;
                $("#tarea-" + resp[0][i].PKTarea + " .index").attr("id", newId);
              }

              for (i = 0; i < resp[1].length; i++) {
                newId = "etapa-" + resp[1][i]["Orden"];
                $(".group_" + resp[1][i]["PKEtapa"]).attr("id", newId);
                $(
                  "#columnas-principales-etapa-" + resp[1][i]["PKEtapa"]
                ).removeClass();
                $(
                  "#columnas-principales-etapa-" + resp[1][i]["PKEtapa"]
                ).addClass("etapas order_" + resp[1][i]["Orden"] + " d-flex");
                $("#tabla-id-" + resp[1][i]["PKEtapa"]).removeClass();
                $("#tabla-id-" + resp[1][i]["PKEtapa"]).addClass(
                  "items order_ta_" + resp[1][i]["Orden"]
                );
              }
            } else {
              for (i = 0; i < resp[0].length; i++) {
                newId = "etapa-" + resp[0][i]["Orden"];
                $(".group_" + resp[0][i]["PKEtapa"]).attr("id", newId);
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).removeClass();
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).addClass("etapas order_" + resp[0][i]["Orden"] + " d-flex");
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).removeClass();
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).addClass(
                  "items order_ta_" + resp[0][i]["Orden"]
                );
              }
            }

            sortableGroups = "";

            /*============================================
						=            prueba prueba prueba            =
						============================================*/

            //Actualizando la información de los elementos en las tareas.
            indexArray = [];
            for (i = 0; i < numTareas.length; i++) {
              var index = $("#index-" + (i + 1));
              var count = index.children();
              var children = count.toArray();
              indexArray.push(children);
            }

            /*=====  End of prueba prueba prueba  ======*/
          },
          error: function (error) {},
        });
      },
    },
  };
  containers = document.getElementById("boardContent");
  sortableGroups = Sortable.create(containers, opcionesEtapa);
}

function destroySortable() {
  sortable.destroy();
}

/**********************************************************/
/*######    CALCULAR POSICIONES DE ELEMENTOS        ######*/
/**********************************************************/

function calcPosition(arrCol, arrIndex, oldPosition, newPosition) {
  for (i = 0; i < arrCol.length; i++) {
    arrCol[i].splice(newPosition, 0, arrCol[i].splice(oldPosition, 1)[0]);
  }

  for (i = 0; i < arrCol.length; i++) {
    for (j = 0; j < arrCol[i].length; j++) {
      $(".order_" + (i + 1)).append(arrCol[i][j]);
    }
  }

  for (i = 0; i < arrIndex.length; i++) {
    arrIndex[i].splice(newPosition, 0, arrIndex[i].splice(oldPosition, 1)[0]);
  }

  for (i = 0; i < arrIndex.length; i++) {
    for (j = 0; j < arrIndex[i].length; j++) {
      arrIndex[i][j].style.order = j;
      $("#index-" + (i + 1)).append(arrIndex[i][j]); //
    }
  }
}

function calcPositionTask() {
  console.log({ tablas });
  var result = [];
  for (i = 0; i < tablas.length; i++) {
    var cajaDeTablas = $(".order_ta_" + (i + 1));
    var tablasEtapas = cajaDeTablas.children();
    for (var j = 0; j < tablasEtapas.length; j++) {
      if ($(tablasEtapas[j]).data("ord")) {
        result.push($(tablasEtapas[j]).data("ord"));
      }
    }
  }
  return result;
}

/**********************************************************/
/*###     MODALES DE OPCIONES PARA LOS ELEMENTOS       ###*/
/**********************************************************/

function options_c(id, cont, tipo) {
  varContainer = ".opcionesColumna";
  $(".opcionesGrupo").remove();
  $(".opt-menu-icon").removeClass("colorMenuBlue");
  $(".opcionesColumna").remove();
  $("#opciones_" + cont).append(
    '<div id="liOpCo_' +
      id +
      '" class="opcionesColumna timdesk-text"><div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="edit-icon"></i></div><div onclick="editColumn(' +
      id +
      ')"><span>Editar columna</span></div></div><div class="pd-20 columna-opcion"><div class="text-left mr-30"><i class="elim-icon"></i></div><div onclick="eliminarColumna(' +
      id +
      "," +
      tipo +
      ')"><span>Eliminar columna</span></div></div></div>'
  );

  hide = $("#liOpCo_" + id);
  container = $("div[data-pos='" + id + "']");
}

function optionTask(id) {
  //Crea un modal de opciones para las tareas
  varContainer = ".opcionesTarea";

  $(".opcionesTarea").remove();
  //$('#opt-task-'+id).addClass('colorOptionBlue');
  $(".modal-opt-task-" + id).after(
    '<div class="opcionesTarea"><div class="pd-20 tarea-opcion" onclick="editTask(' +
      id +
      ')"><div class="text-left mr-30"><i class="edit-icon"></i></div><div ><span class="timdesk-text">Editar tarea</span></div></div><div class="pd-20 tarea-opcion" onclick="eliminarTarea(' +
      id +
      ')"><div class="text-left mr-30"><i class="elim-icon"></i></div><div ><span class="timdesk-text">Eliminar tarea</span></div></div></div>'
  );
}

function menuGroup(id) {
  //Crea un modal de opciones para la etapa
  varContainer = ".opcionesGrupo";
  $(".opcionesColumna").remove();
  $(".opcionesGrupo").remove();
  //$('#menu_group_'+id).addClass('colorMenuBlue');
  $("#append-" + id).append(
    '<div class="opcionesGrupo"><div class="pd-20 grupo-opcion" onclick="editarGrupo(' +
      id +
      ')"><div class="text-left mr-30"><i class="edit-icon"></i></div><div><span>Editar etapa</span></div></div><div class="pd-20 grupo-opcion" onclick="eliminarGrupo(' +
      id +
      ')"><div class="text-left mr-30"><i class="elim-icon"></i></div><div><span>Eliminar etapa</span></div></div></div>'
  );

  container = $(".opt-menu");
  hide = $(".opcionesGrupo");
  mCaracter = $(".opt-menu-icon");
}

function optionGroup(id) {
  //Muestra las opciones del grupo (etapa) contraer, mover
  $("#opt-group-" + id).show();
  $("#drag-group-" + id).show();
}

function hideOptionGroup(id) {
  //Oculta las opciones del grupo (etapa) contraer, mover
  $("#opt-group-" + id).hide();
  $("#drag-group-" + id).hide();
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR COLUMNAS      ######*/
/**********************************************************/

function getColumnM(type, table) {
  getColumn(type, table);
  $("#modalColumnsElement").modal("toggle");
}

function getColumn(type, table) {
  $(".listaColumnas").hide();
  var icon = 0;
  var elementText = "";
  $.ajax({
    url: lite,
    data: {
      clase: "add_data",
      funcion: "addColumn",
      id: idProyecto,
      tipo: type,
      tabla: table,
    },
    dataType: "json",
    success: function (resp) {
      let enter = "enter";
      let doit = "doit";
      if (resp == "noGroups") {
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
            title: "No hay etapas en el proyecto",
            text: "¡No puedes agregar columnas sin etapas!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText:
              '<span class="verticalCenter">¡Agregar etapa!</span>',
            cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              agregarGrupo();
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
            }
          });
      } else if (resp == "verifica1") {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons
          .fire({
            title: 'Ya existe una columna "Verificar"',
            text: "¡No puedes agregar más de una!",
            icon: "warning",
            showCancelButton: false,
            confirmButtonText: '<span class="verticalCenter">Ok</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              /*Accion para cuando se acepta*/
            }
          });
      } else if (resp == "progreso1") {
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: "btn-custom btn-custom--border-blue",
          },
          buttonsStyling: false,
        });

        swalWithBootstrapButtons
          .fire({
            title: 'Ya existe una columna "Progreso"',
            text: "¡No puedes agregar más de una!",
            icon: "warning",
            showCancelButton: false,
            confirmButtonText: '<span class="verticalCenter">Ok</span>',
            reverseButtons: false,
          })
          .then((result) => {
            if (result.isConfirmed) {
              /*Accion para cuando se acepta*/
            }
          });
      } else if (resp == -1) {
        lobby_notify(
          "Sólo el encargado puede agregar columnas.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      } else {
        icon = resp.Orden + resp.id; //Identificador único para cada icono de la columna que se va agregando, este se transforma una vez se recarga la página
        var columnFlag = [];

        for (i = 0; i < tablas.length; i++) {
          //Por cada etapa en el proyecto imprime la nueva columna

          var got = $(".group_" + tablas[i]).hasClass("condenseGroup"); //Tiene la clase que contrae la etapa?
          //style='order: "+(resp.Orden-1)+"'
          $("#columnas-principales-etapa-" + tablas[i]).append(
            "<div class='columna-tarea text-center header columna_" +
              resp.PKColumnaProyecto +
              " et_id_" +
              tablas[i] +
              "' data-pos='" +
              resp.PKColumnaProyecto +
              "' onmouseenter='showIcon(" +
              caracter +
              "icon_0" +
              icon +
              caracter +
              "," +
              caracter +
              "column-id-text-" +
              icon +
              caracter +
              ")' onmouseleave='hideIcon(" +
              caracter +
              "icon_0" +
              icon +
              caracter +
              ")'>" +
              "<div class='icon_i'>" +
              "<span class='icon icon_0" +
              icon +
              "'' onmouseenter='getSortable()'></span>" +
              "</div>" +
              "<div id='opciones_n" +
              icon +
              "' class='icon_r'>" +
              "<span class='icon_row icon_0" +
              icon +
              "' onclick='eliminarColumna(" +
              resp.PKColumnaProyecto +
              "," +
              resp.Tipo +
              ")' style='display:none;'></span>" +
              "</div>" +
              '<span id="tip_icon_0' +
              icon +
              '" class="pos-abs column-tip-content d-no"></span>' +
              "<input id='column-id-text-" +
              icon +
              "' class='text-square text-center column-name-" +
              resp.PKColumnaProyecto +
              "' style='background: transparent;' onfocusout='editColumn(" +
              icon +
              "," +
              resp.PKColumnaProyecto +
              ")' value='" +
              resp.Nombre +
              "'>" +
              "</div>"
          );

          icon = icon + columnasArray[0].length;

          if (got) {
            $(".et_id_" + tablas[i]).addClass("d-no"); //lengthEtapas por tablas(que sólo trae id de cada etapa)
          }
        }
        let contColor = 1;
        let contIds = 0;
        let nombre_color = "";
        for (i = 0; i < numTareas.length; i++) {
          //Por cada tarea del proyecto imprime el nuevo elemento default
          specialClass = "";
          paddingClass = "";
          nombre_color = "";
          if (resp.Tipo == 1) {
            //Si es de tipo responsable
            specialClass = "div_r_" + resp[0][i] + " pos-rel";
            elementText =
              "<i id='no-lead-" +
              resp[0][i] +
              "' class='noLead-icon imgHover imgActive cursorPointer' data-placement='top' data-toggle='leadTip-" +
              resp[0][i] +
              "' title='Agregar responsable' onclick='getLead(" +
              resp[0][i] +
              ")' onmouseenter='activeToolTip(" +
              resp[0][i] +
              ")'></i>";
          }
          if (resp.Tipo == 2) {
            //Estado
            if (resp.Verificar == "si") {
              $(".update-checkmark-" + resp[1][i].FKTarea).removeClass(
                "check-done"
              );
              $(".update-checkmark-" + resp[1][i].FKTarea).addClass(
                "check-undone"
              );
              $("#checkmark-" + resp[1][i].PKVerificacion).removeClass(
                "success-checkmark"
              );
              $("#checkmark-" + resp[1][i].PKVerificacion).addClass(
                "default-checkmark"
              );
              $("#checkmark-" + resp[1][i].PKVerificacion).attr(
                "onclick",
                "animate_task_done(" +
                  resp[1][i].FKTarea +
                  "," +
                  resp[1][i].PKVerificacion +
                  ")"
              );

              elementText =
                "<div style='height: 100%; width: 100%;' class='buttons lighten'  onclick='getColor(" +
                resp[0][i] +
                "," +
                resp.PKColumnaProyecto +
                ")'><div id='btn-color-" +
                //era pad-26px
                resp[0][i] +
                "' class='d-flex justify-content-center align-items-center blob-btn pad-18px padding-point-" +
                resp.id +
                " tarea-color-" +
                resp[0][i] +
                "'  style='height: 100%; background:#b7b7b7;'><span id='btn-text-" +
                resp[0][i] +
                "' class='white-bold text-to-show-" +
                resp.id +
                "'></span></div></div>";

              specialClass = "no-padding pos-rel estado-tarea-" + resp[0][i];
            } else if (resp.Verificar == "si_primera") {
              if (resp[0][contColor] == "#28c67a") {
                nombre_color = "Hecho";
                paddingClass = "pad-15px";
              } else {
                paddingClass = "pad-18px"; //era26px
              }

              elementText =
                "<div style='height: 100%; width: 100%;' class='buttons lighten'  onclick='getColor(" +
                resp[0][contIds] +
                "," +
                resp.PKColumnaProyecto +
                ")'><div id='btn-color-" +
                resp[0][contIds] +
                "' class='d-flex justify-content-center align-items-center blob-btn " +
                paddingClass +
                " padding-point-" +
                resp.id +
                " tarea-color-" +
                resp[0][contIds] +
                "'  style='height: 100%; background:" +
                resp[0][contColor] +
                ";'><span id='btn-text-" +
                resp[0][contIds] +
                "' class='white-bold text-to-show-" +
                resp.id +
                "'>" +
                nombre_color +
                "</span></div></div>";

              specialClass =
                "no-padding pos-rel estado-tarea-" + resp[0][contIds];

              contColor = contColor + 2;
              contIds = contIds + 2;
            } else {
              elementText =
                "<div style='height: 100%; width: 100%;' class='buttons lighten'  onclick='getColor(" +
                resp[0][i] +
                "," +
                resp.PKColumnaProyecto +
                ")'><div id='btn-color-" +
                //era pad-26px
                resp[0][i] +
                "' class='d-flex justify-content-center align-items-center blob-btn pad-18px padding-point-" +
                resp.id +
                " tarea-color-" +
                resp[0][i] +
                "'  style='height: 100%; background:#b7b7b7;'><span id='btn-text-" +
                resp[0][i] +
                "' class='white-bold text-to-show-" +
                resp.id +
                "'></span></div></div>";

              specialClass = "no-padding pos-rel estado-tarea-" + resp[0][i];
            }

            if (resp.Progreso == "si") {
              $(".update-bar-" + resp[2][i].PKTarea).width(
                resp[2][i].progreso + "%"
              );
              $(".update-bar-text-" + resp[2][i].PKTarea).html(
                resp[2][i].progreso + "%"
              );
            }
          }
          if (resp.Tipo == 3) {
            //Si es de tipo fecha
            let Texto = "Seleccione";
            elementText =
              "<input class='fecha-" +
              resp[0][i] +
              "' " +
              "id='fecha-" +
              resp[0][i] +
              "' name='txtFecha' placeholder='Selecciona el rango de fechas' class='form-control' step='1' style='border: 1px solid #ffffff;' data-fecha='" +
              resp[0][i] +
              "' onchange='getFecha(" +
              resp[0][i] +
              ")' value=" +
              Texto +
              " " +
              ">";
          }
          if (resp.Tipo == 4) {
            //SI es de tipo Hipervínculo
            specialClass = "link-" + resp[0][i] + " pos-rel";
            elementText =
              "<div class='pad-7 hypervinculo-" +
              resp[0][i] +
              " cursorPointer' onclick='getLink(" +
              resp[0][i] +
              "," +
              caracter +
              resp.Direccion +
              caracter +
              "," +
              caracter +
              resp.Texto +
              caracter +
              ")' style='height:40px;'><a class='hyper-text-dots' href='" +
              resp.Direccion +
              "' target='_blank'>" +
              resp.Texto +
              "</a></div>";
          }
          if (resp.Tipo == 5) {
            //Id del elemento
            elementText =
              "<div onclick='copyText(" +
              resp[0][i].PKTarea +
              "," +
              resp.cuenta +
              ")'><input id='copy-text-" +
              resp[0][i].PKTarea +
              "-" +
              resp.cuenta +
              "' class='input-copy-text cursorPointer' type='text' value=" +
              resp[0][i].PKTarea +
              "></input></div>";
          }

          if (resp.Tipo == 6) {
            specialClass = "menud-" + resp[0][i] + " pos-rel"; //
            elementText =
              "<div id='menud-" +
              resp[0][i] +
              "' width='100%' heigth='100%' class='tt menudesplegable cursorPointer sizeDiv'  onclick='getMenuDesplegable(" +
              resp[0][i] +
              "," +
              resp.id +
              ")' style='cursor:pointer'></div>";
          }

          if (resp.Tipo == 7) {
            //Teléfono
            if (resp.Telefono !== " ") {
              var valueAttr = "value=" + resp.Telefono;
            } else {
              var valueAttr = "";
            }
            elementText =
              '<input id="phone-' +
              resp[0][i] +
              '" name="phone" type="tel" class="form-input border-animation set-4 phone" onfocusout="getNumber(' +
              resp[0][i] +
              ')" ' +
              valueAttr +
              ">";
          }

          if (resp.Tipo == 8) {
            //Números
            specialClass = "numeric-options-" + resp[0][i] + " pos-rel";
            let valueAttrN = "";
            let left_symbol =
              '<div class="input-group-prepend" style="width:28px;margin-right:-7px;"><span class="input-group-text numeric-symbol-style numeric-symbol-' +
              resp.PKColumnaProyecto +
              " side-left-" +
              resp.PKColumnaProyecto +
              '">' +
              resp.Simbolo +
              "</span></div>";
            let class_side = "timlid-input-group-left";

            elementText =
              '<div class="change-lr change-left-to-right-' +
              resp.PKColumnaProyecto +
              '"><span class="symbol-show-' +
              resp.PKColumnaProyecto +
              '"></span></div><div class="input-group input-group-numeric ' +
              class_side +
              " numeric-column-" +
              resp.PKColumnaProyecto +
              '" data-symbol=' +
              resp[0][i] +
              ">" +
              left_symbol +
              '<input id="number-' +
              resp[0][i] +
              '" type="number" class="form-control border-animation set-4 phone text-center" style="width:75%;" onfocusout="set_numeric_value(' +
              resp[0][i] +
              ')"' +
              valueAttrN +
              '><div class="input-group-append right-side-numbers-' +
              resp[0][i] +
              '" style="width:0px;"><span class="input-group-text cursorPointer down-arrow attr-' +
              resp[0][i] +
              '" onclick="change_data_number(' +
              resp[0][i] +
              "," +
              caracter2 +
              resp.Simbolo +
              caracter2 +
              "," +
              resp.PKColumnaProyecto +
              ')"></span></div></div>';
          }

          if (resp.Tipo == 9) {
            //Verificar
            specialClass =
              "circle-display check-options-" +
              resp[0][i] +
              " update-checkmark-" +
              resp[1][i].PKTarea +
              "";
            let circle_display = "";

            if (resp[1][i].Estado == 1) {
              //Que la tarea está marcada como terminada

              circle_display =
                '<div id="checkmark-' +
                resp[0][i] +
                '" class="success-checkmark pos-rel" onclick="animate_task_undone(' +
                resp[1][i].PKTarea +
                "," +
                resp[0][i] +
                ')"><span class="icon-line line-tip animate-linetip-' +
                resp[0][i] +
                '"></span><span class="icon-line line-long animate-linelong-' +
                resp[0][i] +
                '"></span></div>';
            } else {
              //La tarea no está marcada como terminada

              circle_display =
                '<div id="checkmark-' +
                resp[0][i] +
                '" class="default-checkmark" onclick="animate_task_done(' +
                resp[1][i].PKTarea +
                "," +
                resp[0][i] +
                ')"></div>';
            }

            elementText = circle_display;
          }

          if (resp.Tipo == 10) {
            //Progreso
            specialClass = "pad-18px";
            id_progreso = resp[0][i];
            progreso = resp[1][i][id_progreso];
            elementText =
              '<div class="progress"><div class="progress-bar bar-' +
              resp[0][i] +
              " update-bar-" +
              resp[2][i].PKTarea +
              '" role="progressbar" style="width: ' +
              progreso +
              '%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>' +
              '<span class="color-primary mt-1 update-bar-text-' +
              resp[2][i].PKTarea +
              '">' +
              progreso +
              "%</span>";
          }

          if (resp.Tipo == 11) {
            //Rango
            elementText =
              '<input placeholder="Selecciona el rango de fechas" class="flatpickr rank-' +
              resp[0][i] +
              ' cursorPointer" type="text" style="font-size:13px;" data-rank=' +
              resp[0][i] +
              ">";
          }

          if (resp.Tipo == 12) {
            //Texto
            elementText =
              '<div class="square-text-element-' +
              resp[0][i] +
              ' cursorPointer pad-4" data-toggle="modal" data-target="#modalTextElement" data-whatever=' +
              resp[0][i] +
              " data-texttask=" +
              numTareas[i].PKTarea +
              '><p class="text-task-element" style="margin-bottom:0;"></p></div>';
          }

          if (resp.Tipo == 13) {
            let valueAttrNs;
            elementText =
              '<div><input class="text-center simple-number-' +
              resp[0][i] +
              '" type="number" placeholder="123" onfocusout="set_simple_number(' +
              resp[0][i] +
              ')" ' +
              valueAttrNs +
              "></div>";
          }

          if (resp.Tipo == 14) {
            //Texto corto
            elementText =
              '<div class="form-group-simple-text"><input type="text" class="form-input border-animation set-4 simple-text-' +
              resp[0][i] +
              '" maxlength="20" placeholder="Añade una nota corta" onfocusout="set_simple_text(' +
              resp[0][i] +
              ')"><span class="border-line-animation top-bottom"></span><span class="border-line-animation left-right"></span></div>';
          }

          if (resp.Tipo == 15) {
            let sub_circle_display = "";
            specialClass =
              "circle-display sub-check-options-" +
              resp[0][i] +
              " sub-update-checkmark-" +
              resp[1][i].PKTarea +
              "";

            if (resp[1][i].Terminada == 1) {
              sub_circle_display =
                '<div id="subcheckmark-' +
                resp[0][i] +
                '" class="sub-success-checkmark pos-rel" onclick="animate_subtask_undone(' +
                resp[1][i].PKTarea +
                "," +
                resp[0][i] +
                ')"><span class="icon-line line-tip animate-linetip-' +
                resp[0][i] +
                '"></span><span class="icon-line line-long animate-linelong-' +
                resp[0][i] +
                '"></span></div>';
            } else {
              //La tarea no está marcada como terminada

              sub_circle_display =
                '<div id="subcheckmark-' +
                resp[0][i] +
                '" class="default-checkmark" onclick="animate_subtask_done(' +
                resp[1][i].PKTarea +
                "," +
                resp[0][i] +
                ')"></div>';
            }

            elementText = sub_circle_display;
          }

          if (resp.Tipo == 16) {
            id_progreso = resp[0][i];
            progreso = resp[1][i][id_progreso];
            specialClass = "pad-18px";
            elementText =
              '<div class="progress"><div class="progress-bar subbar-' +
              resp[0][i] +
              " updatesub-bar-" +
              resp[2][i].PKTarea +
              '" role="progressubbar" style="width:' +
              progreso +
              '%;background-color:#40a09d;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>' +
              '<span class="color-primary mt-1 updatesub-bar-text-' +
              resp[2][i].PKTarea +
              '">' +
              progreso +
              "%</span>";
          }

          if (resp.Tipo == 3 || resp.Tipo == 11) {
            $("#index-" + (i + 1)).append(
              "<div class='columna-tarea columna-fecha text-center item-name co_" +
                resp.PKColumnaProyecto +
                " " +
                specialClass +
                "' style='order: " +
                (resp.Orden + 1) +
                "'>" +
                elementText +
                "</div>"
            );
          } else {
            $("#index-" + (i + 1)).append(
              "<div class='columna-tarea text-center item-name co_" +
                resp.PKColumnaProyecto +
                " " +
                specialClass +
                "' style='order: " +
                (resp.Orden + 1) +
                "'>" +
                elementText +
                "</div>"
            );
          }

          if (resp.Tipo == 7) {
            columnFlag.push(resp[0][i]);
          }

          if (resp.Tipo == 11) {
            getFlat(resp[0][i]);
          }
          if (resp.Tipo == 3) {
            getFlatDate(resp[0][i]);
          }
        } //fIN DEL FOR

        var cant = columnasArray.length;
        var count = indexArray.length;
        columnasArray = [];

        //Actualizando el array de las columnas.
        for (i = 0; i < cant; i++) {
          var cajaDeColumnas = $("#columnas-principales-etapa-" + tablas[i]);
          var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
          var children = columnasEtapas.toArray(); //Transformando a Array
          columnasArray.push(children);
        }
        //Actualizando el array de la información de las tareas.
        indexArray = [];
        for (i = 0; i < numTareas.length; i++) {
          var index = $("#index-" + (i + 1));
          var count = index.children();
          var children = count.toArray();
          indexArray.push(children);
        }

        if (columnFlag.length !== 0) {
          getFlag(columnFlag);
        }
      }
      let coord = $(".columna_" + resp.PKColumnaProyecto).offset();
      document.getElementById("boardContent").scrollLeft += coord.left;
    },
    error: function (error) {},
  });
}

function editColumn(id, id_columna) {
  let name = "";
  name = $("#column-id-text-" + id).val();
  $.ajax({
    url: lite,
    dataType: "json",
    data: {
      clase: "edit_data",
      funcion: "editColumn",
      id_columna: id_columna,
      nombre: name,
    },
    success: function (resp) {
      if (resp == "ok") {
        $(".column-name-" + id_columna).val(name);
        //$('#column-id-text-'+id).val(name);
        $("#column-id-text-" + id).blur();
      } else {
        //$('.column-name-'+id).removeAttr("value")
        $("#column-id-text-" + id).val(resp.Nombre);
        $("#column-id-text-" + id).blur();
      }
    },
    error: function (error) {},
  });
}

function eliminarColumna(id, tipo) {
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
      text: "Esta columna será eliminada junto con sus datos asociados",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar columna</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: lite,
          data: {
            clase: "elim_data",
            funcion: "elimColumn",
            id: id,
            tipo: tipo,
          },
          dataType: "json",
          success: function (resp) {
            if (resp.update == "ok") {
              delete_column_element(id);
            } else {
              if (resp.updateV == "si") {
                $.each(resp[0], function (i) {
                  if (resp[0][i].Estado == 1) {
                    $(".update-checkmark-" + resp[0][i].FKTarea).removeClass(
                      "check-undone"
                    );
                    $(".update-checkmark-" + resp[0][i].FKTarea).addClass(
                      "check-done"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).removeClass(
                      "default-checkmark"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).addClass(
                      "success-checkmark"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).attr(
                      "onclick",
                      "animate_task_undone(" +
                        resp[0][i].FKTarea +
                        "," +
                        resp[0][i].PKVerificacion +
                        ")"
                    );
                  }

                  if (resp[0][i].Estado == 0) {
                    $(".update-checkmark-" + resp[0][i].FKTarea).removeClass(
                      "check-done"
                    );
                    $(".update-checkmark-" + resp[0][i].FKTarea).addClass(
                      "check-undone"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).removeClass(
                      "success-checkmark"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).addClass(
                      "default-checkmark"
                    );
                    $("#checkmark-" + resp[0][i].PKVerificacion).attr(
                      "onclick",
                      "animate_task_done(" +
                        resp[0][i].FKTarea +
                        "," +
                        resp[0][i].PKVerificacion +
                        ")"
                    );
                  }
                });
              }

              if (resp.updateP == "si") {
                $.each(resp[1], function (i) {
                  $(".update-bar-" + resp[1][i].PKTarea).width(
                    resp[1][i].progreso + "%"
                  );
                  $(".update-bar-text-" + resp[1][i].PKTarea).html(
                    resp[1][i].progreso + "%"
                  );
                });
              }

              delete_column_element(id);
            }
          },
          error: function (error) {},
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}

function delete_column_element(id) {
  //remover los elementos del DOM
  $(".columna_" + id).remove();
  $(".co_" + id).remove();
  //rellenar el array de columnas y elementos de las tareas.
  var cant = columnasArray.length;
  var count = indexArray.length;
  columnasArray = [];
  //Actualizando el array de las columnas.
  for (i = 0; i < cant; i++) {
    var cajaDeColumnas = $("#columnas-principales-etapa-" + tablas[i]); //lengthEtapas
    var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
    var children = columnasEtapas.toArray(); //Transformando a Array
    columnasArray.push(children);
  }
  //Actualizando el array de la información de las tareas.
  indexArray = [];
  for (i = 0; i < numTareas.length; i++) {
    var index = $("#index-" + (i + 1));
    var count = index.children();
    var children = count.toArray();
    indexArray.push(children);
  }

  lobby_notify(
    "¡Columna eliminada!",
    "notificacion_error.svg",
    "error",
    "chat/"
  );
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR TAREAS        ######*/
/**********************************************************/

function addTask(id_etapa, color) {
  let handle_task = "sort_task";
  let sort_task = $(".sort_alert");
  if (sort_task.length > 0) {
    handle_task = "sort_alert";
  }
  $.ajax({
    url: lite,
    dataType: "json",
    data: {
      clase: "add_data",
      funcion: "addTask",
      id_etapa: id_etapa,
      id_proyecto: idProyecto,
    },
    success: function (resp) {
      if (resp == -1) {
        lobby_notify(
          "Sólo el encargado del proyecto puede agregar tareas.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      } else {
        let loadChat = "";
        numTask = indexArray.length + 1;
        var theColor = document.getElementById("input-group-" + id_etapa).style
          .backgroundColor;
        if (resp.length != undefined) {
          for (i = 0; i < resp[1].length; i++) {
            var newId = "index-" + resp[1][i].Orden;
            $("#tarea-" + resp[1][i].PKTarea + " .index").attr("id", newId);
          }

          if (lite_lock != 1) {
            loadChat =
              "<i class='chat_icon imgActive' onclick='loadChat(" +
              resp[0][0][0].FKTarea +
              ",1)' data-toggle='chatTip' data-placement='top' title='' data-original-title='Chat'></i>" +
              "<span class='count-chatTask-" +
              resp[0][0][0].FKTarea +
              " badge badge-pill badge-counter badge-circle contadorChat' onclick='loadChat(" +
              resp[0][0][0].FKTarea +
              ",1)'>0</span>";
          }

          $("#tabla-id-" + resp[0][0][0].FKEtapa).append(
            "<div id='tarea-" +
              resp[0][0][0].FKTarea +
              "' class='pos-rel hideTarea contenedor et_id_" +
              id_etapa +
              " task' data-ord=" +
              resp[0][0][0].FKTarea +
              ">" +
              "<i id='opt-task-" +
              resp[0][0][0].FKTarea +
              "' class='backColor_" +
              id_etapa +
              " " +
              handle_task +
              "'style='background:" +
              theColor +
              ";'></i>" +
              "<div class='encabezado-tarea titulo-item ml-1 modal-opt-task-" +
              resp[0][0][0].FKTarea +
              "'>" +
              '<span id="task-tip-' +
              resp[0][0][0].FKTarea +
              '" class="pos-abs task-tip-content d-no"></span>' +
              "<input id='task-name-" +
              resp[0][0][0].FKTarea +
              "' class='task-name-dots' style='flex:1;padding:0;' onkeydown='editTaskEnter(" +
              resp[0][0][0].FKTarea +
              ")' onfocusout='editTask(" +
              resp[0][0][0].FKTarea +
              ")' onclick='show_garbage_tak(" +
              resp[0][0][0].FKTarea +
              ")' onmouseenter='show_task_tip(" +
              resp[0][0][0].FKTarea +
              ")' onmouseleave='hide_task_tip(" +
              resp[0][0][0].FKTarea +
              ")' value='" +
              resp[0][0][0].Tarea +
              "''>" +
              "<div id='task-garbage-" +
              resp[0][0][0].FKTarea +
              "' class='task-garbage-icon imgHover imgActive' onclick='eliminarTarea(" +
              resp[0][0][0].FKTarea +
              ")'>" +
              "</div>" +
              "<div class='d-flex'>" +
              "<div class='d-flex' style='padding:4px;'>" +
              "<i class='subTask-identifier-" +
              resp[0][0][0].FKTarea +
              " subtarea_icon imgActive cursorPointer' onclick='loadSubTask(" +
              resp[0][0][0].FKTarea +
              ")' style='margin-right:15px;' data-toggle='subtareasTip' data-placement='top' title='' data-original-title='Subtareas'></i>" +
              "<span class='subTask-identifier-" +
              resp[0][0][0].FKTarea +
              " count-subtask-" +
              resp[0][0][0].FKTarea +
              " badge badge-pill badge-counter badge-circle contadorSubtareas' onclick='loadSubTask(" +
              resp[0][0][0].FKTarea +
              ")'>0</span>" +
              loadChat +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div"
          );
          $('[data-toggle="subtareasTip"]').tooltip();
          $('[data-toggle="chatTip"]').tooltip();

          $("#tarea-" + resp[0][0][0].FKTarea).append(
            "<div id='index-" +
              resp[0][0][0].tOrden +
              "' class='index' style='display: flex;'></div>"
          );

          $.each(resp[0][0], function (i) {
            specialClass = "";
            paddingClass = "";

            var textToShow = print_elements(resp[0][0][i]);

            if (resp[0][0][i].Tipo == 3 || resp[0][0][i].Tipo == 11) {
              $("#index-" + resp[0][0][0].tOrden).append(
                "<div class='columna-tarea columna-fecha text-center item-name co_" +
                  resp[0][0][i].PKColumnaProyecto +
                  " " +
                  specialClass +
                  "'  style='order: " +
                  (resp[0][0][i].cOrden - 1) +
                  "'>" +
                  textToShow +
                  "</div>"
              );
            } else {
              $("#index-" + resp[0][0][0].tOrden).append(
                "<div class='columna-tarea text-center item-name co_" +
                  resp[0][0][i].PKColumnaProyecto +
                  " " +
                  specialClass +
                  "'  style='order: " +
                  (resp[0][0][i].cOrden - 1) +
                  "'>" +
                  textToShow +
                  "</div>"
              );
            }

            if (resp[0][0][i].Tipo == 7) {
              var input = document.getElementById("phone-" + resp[0][0][i].id);
              window.intlTelInput(input, {
                initialCountry: "mx",
                utilsScript: "../../../js/build/js/utils.js?1590403638580",
              });
            }

            if (resp[0][0][i].Tipo == 11) {
              getFlat(resp[0][0][i].id);
            }

            if (resp[0][0][i].Tipo == 3) {
              getFlatDate(resp[0][0][i].id);
            }

            cuenta_idElementos_columna++;
          });

          var index = $("#index-" + resp[0][0][0].tOrden);
          var count = index.children();
          var children = count.toArray();
          indexArray.push(children);
          numTareas.push(children);
        } else {
          for (i = 0; i < resp[0].length; i++) {
            var newId = "index-" + resp[0][i].Orden;
            $("#tarea-" + resp[0][i].PKTarea + " .index").attr("id", newId);
          }

          if (lite_lock != 1) {
            loadChat =
              "<i class='chat_icon imgActive' onclick='loadChat(" +
              resp["id_tarea"] +
              ",1)' data-toggle='chatTip' data-placement='top' title='' data-original-title='Chat'></i>" +
              "<span class='count-chatTask-" +
              resp["id_tarea"] +
              " badge badge-pill badge-counter badge-circle contadorChat' onclick='loadChat(" +
              resp["id_tarea"] +
              ",1)'>0</span>";
          }

          var permiso = "";
          $("#tabla-id-" + resp["id_etapa"]).append(
            "<div id='tarea-" +
              resp["id_tarea"] +
              "' class='pos-rel hideTarea contenedor et_id_" +
              resp["id_etapa"] +
              " task' data-ord=" +
              resp["id_tarea"] +
              ">" +
              "<i id='opt-task-" +
              resp["id_tarea"] +
              "' class='backColor_" +
              resp["id_etapa"] +
              " " +
              handle_task +
              "' style='background-color:" +
              theColor +
              ";'></i>" +
              "<div class='encabezado-tarea titulo-item ml-1 modal-opt-task-" +
              resp["id_tarea"] +
              "'>" +
              "<span id='task-tip-" +
              resp["id_tarea"] +
              "' class='pos-abs task-tip-content d-no'></span>" +
              "<input id='task-name-" +
              resp["id_tarea"] +
              "' class='task-name-dots' style='flex:1;padding:0;' onkeydown='editTaskEnter(" +
              resp["id_tarea"] +
              ")' onfocusout='editTask(" +
              resp["id_tarea"] +
              ")' onclick='show_garbage_tak(" +
              resp["id_tarea"] +
              ")' onmouseenter='show_task_tip(" +
              resp["id_tarea"] +
              ")' onmouseleave='hide_task_tip(" +
              resp["id_tarea"] +
              ")' value='" +
              resp["nombre"] +
              "' " +
              permiso +
              ">" +
              "<div id='task-garbage-" +
              resp["id_tarea"] +
              "' class='task-garbage-icon imgHover imgActive' onclick='eliminarTarea(" +
              resp["id_tarea"] +
              ")'></div>" +
              "<div class='d-flex'>" +
              "<div class='d-flex' style='padding:4px;'>" +
              "<i class='subTask-identifier-" +
              resp["id_tarea"] +
              " subtarea_icon imgActive cursorPointer' onclick='loadSubTask(" +
              resp["id_tarea"] +
              ")' style='margin-right:15px;' data-toggle='subtareasTip' data-placement='top' title='' data-original-title='Subtareas'></i>" +
              "<span class='subTask-identifier-" +
              resp["id_tarea"] +
              " count-subtask-" +
              resp["id_tarea"] +
              " badge badge-pill badge-counter badge-circle contadorSubtareas' onclick='loadSubTask(" +
              resp["id_tarea"] +
              ")'>0</span>" +
              loadChat +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>"
          );
          $('[data-toggle="subtareasTip"]').tooltip();
          $('[data-toggle="chatTip"]').tooltip();

          $("#tarea-" + resp["id_tarea"]).append(
            "<div id='index-" +
              resp["orden"] +
              "' class='index' style='display: flex;'></div>"
          );

          var index = $("#index-" + resp["orden"]);
          var children = index.toArray();
          numTareas.push(children);
          indexArray.push(children);
        }
      }
    },
    error: function (error) {},
  });
}

function editTaskEnter(id) {
  let wage = document.getElementById("task-name-" + id);
  wage.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {
      let name = $("#task-name-" + id).val();
      $.ajax({
        url: lite,
        dataType: "json",
        data: {
          clase: "edit_data",
          funcion: "editTask",
          id_tarea: id,
          nombre: name,
        },
        success: function (resp) {
          if (resp == "ok") {
            $("#task-name-" + id).val(name);
            $("#task-name-" + id).blur();
          } else {
            $("#task-name-" + id).val(resp.Tarea);
            $("#task-name-" + id).blur();
          }
        },
        error: function (error) {},
      });
    }
  });
}

function editTask(id) {
  setTimeout(function () {
    $("#task-garbage-" + id).hide();
  }, 300);

  let name = $("#task-name-" + id).val();
  $.ajax({
    url: lite,
    dataType: "json",
    data: {
      clase: "edit_data",
      funcion: "editTask",
      id_tarea: id,
      nombre: name,
    },
    success: function (resp) {
      if (resp == "ok") {
        $("#task-name-" + id).val(name);
        $("#task-name-" + id).blur();
      } else if (resp == -1) {
        lobby_notify(
          "Sólo el encargado del proyecto o responsable pueden modificar esta tarea.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      } else {
        $("#task-name-" + id).val(resp.Tarea);
        $("#task-name-" + id).blur();
        lobby_notify(
          "¡El nombre de la Tarea no puede ir vacío!",
          "warning_circle.svg",
          "warning",
          "timdesk/"
        );
      }
    },
    error: function (error) {},
  });
}

function show_garbage_tak(id) {
  $("#task-garbage-" + id).show();
}

function eliminarTarea(id) {
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
      text: "Esta tarea será eliminada junto con sus datos asociados",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar tarea</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: lite,
          data: {
            clase: "elim_data",
            funcion: "elimTask",
            id_tarea: id,
            id_proyecto: idProyecto,
          },
          dataType: "json",
          success: function (resp) {
            if (resp == "ok") {
              numTareas.splice(0, 1);
              $("#tarea-" + id).remove();

              indexArray = [];
              for (i = 0; i < numTareas.length; i++) {
                var index = $("#index-" + (i + 1));
                var count = index.children();
                var children = count.toArray();
                indexArray.push(children);
              }
            } else {
              for (i = 0; i < resp.length; i++) {
                var newId = "index-" + resp[i].Orden;
                $("#tarea-" + resp[i].PKTarea + " .index").attr("id", newId);
              }

              numTareas.splice(0, 1);
              $("#tarea-" + id).remove();

              indexArray = [];
              for (i = 0; i < numTareas.length; i++) {
                var index = $("#index-" + (i + 1));
                var count = index.children();
                var children = count.toArray();
                indexArray.push(children);
              }
            }
            $("#subTask-" + id).remove();
            lobby_notify(
              "¡Tarea eliminada!",
              "notificacion_error.svg",
              "error",
              "chat/"
            );
          },
          error: function (error) {},
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}

/**********************************************************/
/*######    AGREGAR, ELIMINAR, EDITAR ETAPAS        ######*/
/**********************************************************/

$("#agregarEtapa").click(function () {
  agregarGrupo();
});

function agregarGrupo() {
  $.ajax({
    url: lite,
    data: { clase: "add_data", funcion: "addGroup", id_proyecto: idProyecto },
    dataType: "json",
    success: function (resp) {
      if (resp != -1) {
        let enter = "enter";
        let doit = "doit";

        $("#boardContent").prepend(
          '<div id="etapa-' +
            resp.Orden +
            '" class="pr-0 hideEtapa container-fluid group_' +
            resp.PKEtapa +
            ' grupo" data-group=' +
            resp.PKEtapa +
            ">" +
            '<div class="contenedor groupBox" style="flex-direction:column">' +
            '<div class="backColor_' +
            resp.PKEtapa +
            ' encabezado-grupo titulo-etapa estilo-etapa d-flex align-items-center" style="background-color:#1c4587">' +
            '<span id="group-tip-' +
            resp.PKEtapa +
            '" class="pos-abs group-tip-content d-no" style="font-family: system-ui;"></span>' +
            '<input id="input-group-' +
            resp.PKEtapa +
            '" type="text" class="backColor_' +
            resp.PKEtapa +
            ' group-name-dots" style="background-color:#1c4587; padding-left: 52px; color: #ffffff;" onkeydown="editarGrupo(' +
            resp.PKEtapa +
            "," +
            caracter2 +
            enter +
            caracter2 +
            ')" onfocusout="editarGrupo(' +
            resp.PKEtapa +
            "," +
            caracter2 +
            doit +
            caracter2 +
            ')" onclick="show_garbage_group(' +
            resp.PKEtapa +
            ')" onmouseenter=group_tip(' +
            resp.PKEtapa +
            ") onmouseleave=group_tip_hidden(" +
            resp.PKEtapa +
            ') value="' +
            resp.Etapa +
            '">' +
            '<div id="group-garbage-' +
            resp.PKEtapa +
            '" class="task-garbage-icon-blanco imgHover imgActive" onclick="eliminarGrupo(' +
            resp.PKEtapa +
            ')"></div>' +
            '<div id="append-' +
            resp.PKEtapa +
            '" class="opt-menu d-flex">' +
            '<i id="drag-group-' +
            resp.PKEtapa +
            '" class="opt_group_sort_icon" onclick="getDrag(' +
            resp.PKEtapa +
            ')"></i><i id="opt-group-' +
            resp.PKEtapa +
            '" class="opt-menu-icon" onclick="getCondense(' +
            resp.PKEtapa +
            ')"></i>' +
            "</div>" +
            "<button id=" +
            resp.PKEtapa +
            ' class="btnColorPicker btn ignore-elements imgActive picker_' +
            resp.PKEtapa +
            '" data-color="#1c4587" data-toggle="coloresTip" title="" data-original-title="Selecciona un color"></button>' +
            "</div></div>"
        );
        $('[data-toggle="coloresTip"]').tooltip();

        $("#etapa-" + resp.Orden).append(`
          <div id="tabla-id-${resp.PKEtapa}" class="items order_ta_${resp.Orden}" data-tab="${resp.PKEtapa}" style="overflow-x: scroll;">
            <div class="d-flex pt-2 pb-1 disabled">
              <span class="header titulo-item et_id_${resp.PKEtapa}" style="margin-left: 32px;">Tareas</span>
              <div id="columnas-principales-etapa-${resp.PKEtapa}" class="etapas order_${resp.Orden} d-flex"></div>
            </div>
          </div>
          <div class="mb-5 agregarTarea et_id_${resp.PKEtapa}">
            <a class="pointer" onclick="addTask('${resp.PKEtapa}', '#1c4587')">+ Agregar Tarea</a>
          </div>
        `);

        if (resp[0].length != 0) {
          icon = columns_counter + 1;

          for (j = 0; j < resp[0].length; j++) {
            $("#columnas-principales-etapa-" + resp.PKEtapa).append(
              "<div class='columna-tarea text-center header columna_" +
                resp[0][j].PKColumnaProyecto +
                " et_id_" +
                resp.PKEtapa +
                "' data-pos='" +
                resp[0][j].PKColumnaProyecto +
                "' onmouseenter='showIcon(" +
                caracter +
                "icon_0" +
                icon +
                caracter +
                "," +
                caracter +
                "column-id-text-" +
                icon +
                caracter +
                ")' onmouseleave='hideIcon(" +
                caracter +
                "icon_0" +
                icon +
                caracter +
                ")' style='order: " +
                (resp.Orden - 1) +
                "'>" +
                "<div class='icon_i'>" +
                "<span class='icon icon_0" +
                icon +
                "' onmouseenter='getSortable()'></span>" +
                "</div>" +
                "<div id='opciones_n" +
                icon +
                "' class='icon_r'>" +
                "<span class='icon_row icon_0" +
                icon +
                "' onclick='eliminarColumna(" +
                resp[0][j].PKColumnaProyecto +
                "," +
                resp[0][j].tipo +
                ")' style='display:none;'></span>" +
                "</div>" +
                "<span id='tip_icon_0" +
                icon +
                "' class='pos-abs column-tip-content d-no'></span>" +
                "<input id='column-id-text-" +
                icon +
                "' class='text-square text-center column-name-" +
                resp[0][j].PKColumnaProyecto +
                "' style='background: transparent;' onfocusout='editColumn(" +
                icon +
                "," +
                resp[0][j].PKColumnaProyecto +
                ")' value='" +
                resp[0][j].nombre +
                "'>" +
                "</div>"
            );

            icon++;
          }
          columns_counter = icon - 1;
        }

        if (resp[1].length != 0) {
          for (i = 0; i < resp[1].length; i++) {
            newId = "etapa-" + resp[1][i]["Orden"];
            $(".group_" + resp[1][i]["PKEtapa"]).attr("id", newId);
            $(
              "#columnas-principales-etapa-" + resp[1][i]["PKEtapa"]
            ).removeClass();
            $("#columnas-principales-etapa-" + resp[1][i]["PKEtapa"]).addClass(
              "etapas order_" + resp[1][i]["Orden"] + " d-flex"
            );
            $("#tabla-id-" + resp[1][i]["PKEtapa"]).removeClass();
            $("#tabla-id-" + resp[1][i]["PKEtapa"]).addClass(
              "items order_ta_" + resp[1][i]["Orden"]
            );
          }
        }

        $PKEtapa = parseInt(resp.PKEtapa);
        tablas.push($PKEtapa);

        var cajaDeColumnas = $("#columnas-principales-etapa-" + resp.PKEtapa);
        var columnasEtapas = cajaDeColumnas.children();
        var children = columnasEtapas.toArray();
        columnasArray.push(children);
        getTablas(tablas);
      } else {
        lobby_notify(
          "Sólo el encargado del proyecto puede agregar etapas",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      }
    },
    error: function (error) {},
  });
}

function eliminarGrupo(id) {
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
      text: "Este grupo será eliminado junto con sus tareas asociadas",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar etapa</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: lite,
          dataType: "json",
          data: {
            clase: "elim_data",
            funcion: "elimGroup",
            id_etapa: id,
            id_proyecto: idProyecto,
          },
          success: function (resp) {
            $(".group_" + id).remove();
            lobby_notify(
              "¡Etapa eliminada!",
              "notificacion_error.svg",
              "error",
              "chat/"
            );
            if (resp.accion == "actualizar") {
              for (i = 0; i < resp[0].length; i++) {
                newId = "etapa-" + resp[0][i]["Orden"];
                $(".group_" + resp[0][i]["PKEtapa"]).attr("id", newId);
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).removeClass();
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).addClass("etapas order_" + resp[0][i]["Orden"] + " d-flex");
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).removeClass();
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).addClass(
                  "items order_ta_" + resp[0][i]["Orden"]
                );
              }
            }

            if (resp.accion == "actualizarTareas") {
              for (i = 0; i < resp[0].length; i++) {
                newId = "etapa-" + resp[0][i]["Orden"];
                $(".group_" + resp[0][i]["PKEtapa"]).attr("id", newId);
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).removeClass();
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).addClass("etapas order_" + resp[0][i]["Orden"] + " d-flex");
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).removeClass();
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).addClass(
                  "items order_ta_" + resp[0][i]["Orden"]
                );
              }

              for (i = 0; i < resp[1].length; i++) {
                //Actualiza el id "index-n" que se relaciona al orden de las tareas en la BBDD y cálcula el cambio según se muevan las columnas
                var newId = "index-" + resp[1][i].Orden;
                $("#tarea-" + resp[1][i].PKTarea + " .index").attr("id", newId);
              }
              var remove = resp.numTareas;
              numTareas.splice(0, remove);

              indexArray = [];
              for (i = 0; i < numTareas.length; i++) {
                var index = $("#index-" + (i + 1));
                var count = index.children();
                var children = count.toArray();
                indexArray.push(children);
              }
            }

            if (resp.accion == "eEtapaATareas") {
              var remove = resp.numTareas;
              numTareas.splice(0, remove);

              indexArray = [];
              for (i = 0; i < numTareas.length; i++) {
                var index = $("#index-" + (i + 1));
                var count = index.children();
                var children = count.toArray();
                indexArray.push(children);
              }
            }

            if (resp.accion == "actualizarArray") {
              for (i = 0; i < resp[0].length; i++) {
                newId = "etapa-" + resp[0][i]["Orden"];
                $(".group_" + resp[0][i]["PKEtapa"]).attr("id", newId);
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).removeClass();
                $(
                  "#columnas-principales-etapa-" + resp[0][i]["PKEtapa"]
                ).addClass("etapas order_" + resp[0][i]["Orden"] + " d-flex");
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).removeClass();
                $("#tabla-id-" + resp[0][i]["PKEtapa"]).addClass(
                  "items order_ta_" + resp[0][i]["Orden"]
                );
              }

              var remove = resp.numTareas;
              numTareas.splice(0, remove); //Remueve las tareas del array numTareas

              indexArray = [];
              for (i = 0; i < numTareas.length; i++) {
                var index = $("#index-" + (i + 1));
                var count = index.children();
                var children = count.toArray();
                indexArray.push(children);
              }
            }

            var number = id;
            var index = tablas.indexOf(number);
            if (index > -1) {
              tablas.splice(index, 1);
            }

            //Actualiza array de las columnas
            columnasArray = [];
            for (i = 0; i < tablas.length; i++) {
              var cajaDeColumnas = $(
                "#columnas-principales-etapa-" + tablas[i]
              );
              var columnasEtapas = cajaDeColumnas.children(); //hijos del div con el id columnas-principales-etapa- 1, 2, 3 etc
              var children = columnasEtapas.toArray(); //Transformando a Array
              columnasArray.push(children);
            }

            getDrag();
          },
          error: function (error) {},
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
      }
    });
}

function show_garbage_group(id) {
  $("#group-garbage-" + id).show();
}

function editarGrupo(id, texto) {
  if (texto == "enter") {
    document
      .getElementById("input-group-" + id)
      .addEventListener("keydown", function (e) {
        let name = $("#input-group-" + id).val();
        if (e.keyCode === 13) {
          new_group_name(id, name);
        }
      });
  } else {
    let name = $("#input-group-" + id).val();
    new_group_name(id, name);
    setTimeout(function () {
      $("#group-garbage-" + id).hide();
    }, 100);
  }
}

function removeHandler(id) {
  document.getElementById("input-group-" + id).removeEventListener("keydown");
}

function new_group_name(id, name) {
  $.ajax({
    url: lite,
    dataType: "json",
    data: {
      clase: "edit_data",
      funcion: "editGroup",
      id_etapa: id,
      nombre: name,
    },
    success: function (resp) {
      if (resp == "ok") {
        $(".color_" + id).val(name);
        $("#input-group-" + id).blur();
      } else if (resp == -1) {
        lobby_notify(
          "Sólo el encargado puede cambiar el nombre de la etapa.",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      } else {
        $("#input-group-" + id).val(resp.Etapa);
        $("#input-group-" + id).blur();
      }
    },
    error: function (error) {},
  });
}

/**********************************************************/
/*######           FUNCIONES VARIAS                 ######*/
/**********************************************************/

function print_elements(array, idEmpresa = 0) {
  var textToShow = "";
  var activo = "";
  if (array.Tipo == 1) {
    specialClass = "div_r_" + array.id + " pos-rel";
    if (array.permiso == 0) {
      activo = "";
    } else {
      activo = "onclick='getLead(" + array.id + ")'";
    }

    if (array.Texto == null) {
      textToShow =
        "<i id='no-lead-" +
        array.id +
        "' class='noLead-icon imgHover imgActive cursorPointer' data-placement='top' data-toggle='leadTip-" +
        array.id +
        "' title='Agregar responsable' " +
        activo +
        " onmouseenter='activeToolTip(" +
        array.id +
        ")'></i>";
    } else {
      var textoDividido = array.Texto.split(" ");
      textoDividido = textoDividido.filter((item) => item.length !== "");
      var imgAvt =
        array.imagen === null
          ? "../../../img/timUser.png"
          : "../../empresas/archivos/" + idEmpresa + "/img/" + array.imagen;

      if (textoDividido.length > 1) {
        var str = array.Texto.split(" ")[0] + " " + array.Texto.split(" ")[1];
      } else {
        var str = array.Texto.split(" ")[0];
      }
      textToShow =
        "<div id='lead-" +
        array.id +
        "' class='lead-container cursorPointer' " +
        activo +
        " onmouseenter='activeToolTip(" +
        array.id +
        ")' data-toggle='leadTip-" +
        array.id +
        "' data-placement='top' title='" +
        array.Texto +
        "'><span class='lead-img'><img width='30px' src='" +
        imgAvt +
        "'></span> <span class='lead-resp'>" +
        str +
        "</span></div>";
    }
  }
  if (array.Tipo == 2) {
    specialClass = "no-padding pos-rel estado-tarea-" + array.id;

    if (array.Texto == "" || array.Texto == " ") {
      paddingClass = "pad-18px";
    } else {
      paddingClass = "pad-15px";
    }

    if (array.permiso == 0) {
      activo = "";
    } else {
      activo =
        "onclick='getColor(" + array.id + "," + array.PKColumnaProyecto + ")'";
    }

    textToShow =
      "<div style='height: 100%; width: 100%;' class='buttons lighten' " +
      activo +
      "><div id='btn-color-" +
      array.id +
      "' class='d-flex justify-content-center align-items-center blob-btn " +
      paddingClass +
      " padding-point-" +
      array.PKColorColumna +
      " tarea-color-" +
      array.FKTarea +
      "' style='height: 100%; background:" +
      array.color +
      "' ><span id='btn-text-" +
      array.id +
      "' class='white-bold text-to-show-" +
      array.PKColorColumna +
      "'>" +
      array.Texto +
      "</span></div></div>";
  }

  if (array.Tipo == 3) {
    //Si es de tipo "Fecha"

    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }
    if (array.Texto == null) {
      array.Texto = "Seleccionar fecha";
    }
    if (array.Texto == "1970-01-01") {
      array.Texto = "Seleccionar fecha";
    }
    console.log(array.Texto);
    textToShow =
      "<input id='fecha-" +
      array.id +
      "' type='date' name='txtFecha' class='form-control flatpickr' step='1' style='border: 1px solid #ffffff;' onchange='getFecha(" +
      array.id +
      ")' value=" +
      array.Texto +
      " " +
      activo +
      ">";
    textToShow =
      "<input class='fecha-" +
      array.id +
      "' " +
      "id='fecha-" +
      array.id +
      "' name='txtFecha' placeholder='Selecciona el rango de fechas' class='form-control' step='1' style='border: 1px solid #ffffff;' data-fecha='" +
      array.id +
      "' onchange='getFecha(" +
      array.id +
      ")' value=" +
      array.Texto +
      " " +
      activo +
      ">";
  }

  if (array.Tipo == 4) {
    if (array.permiso == 0) {
      activo = "";
    } else {
      activo =
        "onclick='getLink(" +
        array.id +
        "," +
        caracter +
        array.Direccion +
        caracter +
        "," +
        caracter +
        array.Texto +
        caracter +
        ")'";
    }

    specialClass = "link-" + array.id + " pos-rel";
    textToShow =
      "<div class='pad-7 hypervinculo-" +
      array.id +
      " cursorPointer' " +
      activo +
      " style='height:40px;'><a class='hyper-text-dots' href='" +
      array.Direccion +
      "' target='_blank'>" +
      array.Texto +
      "</a></div>";
  }
  if (array.Tipo == 5) {
    textToShow =
      "<div onclick='copyText(" +
      array.id +
      "," +
      cuenta_idElementos_columna +
      ")'><input id='copy-text-" +
      array.id +
      "-" +
      cuenta_idElementos_columna +
      "' class='input-copy-text cursorPointer' type='text' value=" +
      array.id +
      "></input></div>";
  }

  if (array.Tipo == 6) {
    if (array.permiso == 0) {
      activo = "";
    } else {
      activo =
        "onclick='getMenuDesplegable(" +
        array.id +
        "," +
        array.PKColumnaProyecto +
        ")'";
    }

    specialClass = "menud-" + array.id + " pos-rel"; //
    textToShow =
      "<div id='menud-" +
      array.id +
      "' class='menudesplegable cursorPointer sizeDiv' " +
      activo +
      " style='cursor:pointer'></div>";
    consultarElementosSelected(array.id, function (resp) {
      for (i = 0; i < resp.length; i++) {
        var cont = 0;
        if (i >= 2) {
          cont = resp.length - 2;
          $("#menud-" + array.id).append(
            "<div class='recuadro_mas'><label id='recuadro_mas_" +
              resp[i].PKEtiqueta +
              "'>+" +
              cont +
              "</label></div>"
          );
          i = resp.length;
        } else {
          $("#menud-" + array.id).append(
            "<div class='recuadro'><label class='label_selected_" +
              resp[i].PKEtiqueta +
              "'>" +
              resp[i].Nombre +
              "</label></div>"
          );
        }
      }
    });
  }

  if (array.Tipo == 7) {
    if (array.Telefono !== " ") {
      var valueAttr = "value=" + array.Telefono;
    } else {
      var valueAttr = "";
    }

    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }

    textToShow =
      '<input id="phone-' +
      array.id +
      '" name="phone" type="tel" class="form-input border-animation set-4 phone" onfocusout="getNumber(' +
      array.id +
      ')" ' +
      valueAttr +
      " " +
      activo +
      ">";
  }

  if (array.Tipo == 8) {
    //Números
    specialClass = "numeric-options-" + array.id + " pos-rel";
    let valueAttrN;
    let left_symbol = "";
    let right_symbol = "";
    let class_side = "";
    if (array.Numero !== 0) {
      valueAttrN = "value=" + array.Numero;
    } else {
      valueAttrN = "";
    }

    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }

    //Configurando el lado en el que se mostrará el símbolo:
    if (array.Lugar == 0) {
      //Lado izquierdo
      left_symbol =
        '<div class="input-group-prepend" style="width:28px;margin-right:-7px;"><span class="input-group-text numeric-symbol-style numeric-symbol-' +
        array.PKColumnaProyecto +
        " side-left-" +
        array.PKColumnaProyecto +
        '">' +
        array.Simbolo +
        "</span></div>";
      class_side = "timlid-input-group-left";
    } else {
      //Lado derecho
      right_symbol =
        '<span class="input-group-text numeric-symbol-style numeric-symbol-' +
        array.PKColumnaProyecto +
        " side-right-" +
        array.PKColumnaProyecto +
        '">' +
        array.Simbolo +
        "</span>";
      class_side = "timlid-input-group-right";
    }

    textToShow =
      '<div class="change-lr change-left-to-right-' +
      array.PKColumnaProyecto +
      '"><span class="symbol-show-' +
      array.PKColumnaProyecto +
      '"></span></div><div class="input-group input-group-numeric ' +
      class_side +
      " numeric-column-" +
      array.PKColumnaProyecto +
      '" data-symbol=' +
      array.id +
      ">" +
      left_symbol +
      '<input id="number-' +
      array.id +
      '" type="number" class="form-control border-animation set-4 phone text-center" style="width:75%;" onfocusout="set_numeric_value(' +
      array.id +
      ')"' +
      valueAttrN +
      " " +
      activo +
      '><div class="input-group-append right-side-numbers-' +
      array.id +
      '" style="width:0px;">' +
      right_symbol +
      '<span class="input-group-text cursorPointer down-arrow attr-' +
      array.id +
      '" onclick="change_data_number(' +
      array.id +
      "," +
      caracter2 +
      array.Simbolo +
      caracter2 +
      "," +
      array.PKColumnaProyecto +
      ')"></span></div></div>';
  }

  if (array.Tipo == 9) {
    //Verificación
    let circle_display = "";
    specialClass =
      "circle-display check-options-" +
      array.id +
      " update-checkmark-" +
      array.FKTarea +
      "";

    if (array.Estado == 1) {
      //Que la tarea está marcada como terminada

      if (array.permiso == 0) {
        activo = "";
      } else {
        activo =
          'onclick="animate_task_undone(' +
          array.FKTarea +
          "," +
          array.id +
          ')"';
      }

      circle_display =
        '<div id="checkmark-' +
        array.id +
        '" class="success-checkmark pos-rel" ' +
        activo +
        '><span class="icon-line line-tip animate-linetip-' +
        array.id +
        '"></span><span class="icon-line line-long animate-linelong-' +
        array.id +
        '"></span></div>';
    } else {
      //La tarea no está marcada como terminada

      if (array.permiso == 0) {
        activo = "";
      } else {
        activo =
          'onclick="animate_task_done(' + array.FKTarea + "," + array.id + ')"';
      }

      circle_display =
        '<div id="checkmark-' +
        array.id +
        '" class="default-checkmark" ' +
        activo +
        "></div>";
    }

    textToShow = circle_display;
  }

  if (array.Tipo == 10) {
    //Progreso
    specialClass = "pad-18px";
    textToShow =
      '<div class="progress"><div class="progress-bar bar-' +
      array.id +
      " update-bar-" +
      array.FKTarea +
      '" role="progressbar" style="width: ' +
      array.Progreso +
      '%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>' +
      '<span class="color-primary mt-1 update-bar-text-' +
      array.FKTarea +
      '">' +
      array.Progreso +
      "%</span>";
  }

  if (array.Tipo == 11) {
    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }
    console.log({ caracter, array });
    textToShow =
      '<input placeholder="Selecciona el rango de fechas" class="flatpickr rank-' +
      array.id +
      ' cursorPointer" type="text" value=' +
      caracter +
      array.Rango +
      caracter +
      ' style="font-size:13px;" data-rank=' +
      array.id +
      " " +
      activo +
      ">";
  }

  if (array.Tipo == 12) {
    //Texto largo

    if (array.permiso == 0) {
      activo = "";
    } else {
      activo = 'data-target="#modalTextElement"';
    }

    textToShow =
      '<div class="square-text-element-' +
      array.id +
      ' cursorPointer pad-4" data-toggle="modal" ' +
      activo +
      " data-whatever=" +
      array.id +
      " data-texttask=" +
      array.FKTarea +
      '><p class="text-task-element" style="margin-bottom:0;">' +
      array.Texto +
      "</p></div>";
  }

  if (array.Tipo == 13) {
    let valueAttrNs;
    if (array.Numero !== 0) {
      valueAttrNs = "value=" + array.Numero;
    } else {
      valueAttrNs = "";
    }

    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }

    textToShow =
      '<div><input class="text-center simple-number-' +
      array.id +
      '" type="number" placeholder="123" onfocusout="set_simple_number(' +
      array.id +
      ')" ' +
      valueAttrNs +
      " " +
      activo +
      "></div>";
  }

  if (array.Tipo == 14) {
    //Texto corto
    if (array.Texto !== 0) {
      valueAttrNt = "value=" + caracter + array.Texto + caracter;
    } else {
      valueAttrNt = "";
    }

    if (array.permiso == 0) {
      activo = "disabled";
    } else {
      activo = "";
    }

    textToShow =
      '<div class="form-group-simple-text"><input type="text" class="form-input border-animation set-4 simple-text-' +
      array.id +
      '" maxlength="20" placeholder="Añade una nota corta" onfocusout="set_simple_text(' +
      array.id +
      ')" ' +
      valueAttrNt +
      " " +
      activo +
      '><span class="border-line-animation top-bottom"></span><span class="border-line-animation left-right"></span></div>';
  }

  if (array.Tipo == 15) {
    //Verificación **SUBTAREA**
    let sub_circle_display = "";
    specialClass =
      "circle-display sub-check-options-" +
      array.id +
      " sub-update-checkmark-" +
      array.FKTarea +
      "";

    if (array.Terminada == 1) {
      //Que la tarea está marcada como terminada

      if (array.permiso == 0) {
        activo = "";
      } else {
        activo =
          'onclick="animate_subtask_undone(' +
          array.FKTarea +
          "," +
          array.id +
          ')"';
      }

      sub_circle_display =
        '<div id="subcheckmark-' +
        array.id +
        '" class="sub-success-checkmark pos-rel" ' +
        activo +
        '><span class="icon-line line-tip animate-linetip-' +
        array.id +
        '"></span><span class="icon-line line-long animate-linelong-' +
        array.id +
        '"></span></div>';
    } else {
      //La tarea no está marcada como terminada

      if (array.permiso == 0) {
        activo = "";
      } else {
        activo =
          'onclick="animate_subtask_done(' +
          array.FKTarea +
          "," +
          array.id +
          ')"';
      }

      sub_circle_display =
        '<div id="subcheckmark-' +
        array.id +
        '" class="default-checkmark" ' +
        activo +
        "></div>";
    }

    textToShow = sub_circle_display;
  }

  if (array.Tipo == 16) {
    //Progreso subtarea
    specialClass = "pad-18px";
    textToShow =
      '<div class="progress"><div class="progress-bar subbar-' +
      array.id +
      " updatesub-bar-" +
      array.FKTarea +
      '" role="progressubbar" style="width: ' +
      array.Progreso +
      '%;background-color:#40a09d;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">' +
      "</div>" +
      "</div>" +
      '<span class="color-primary mt-1 updatesub-bar-text-' +
      array.FKTarea +
      '">' +
      array.Progreso +
      "%</span>";
  }
  return textToShow;
}

function getLink(id, direccion, texto) {
  //Mostrar modal para link
  $("#boardContent").animate({ scrollTop: $("#boardContent").height() }, 1000);
  let value_texto = "";
  let value_link = "";
  if (texto != " ") {
    value_texto = "value=" + caracter + texto + caracter;
  }
  if (direccion != " ") {
    value_link = "value=" + direccion;
  }
  $(".form-group-link").remove();
  $(".link-" + id).append(
    "<div class='form-group-link pos-abs'><label for='link'>Direccion web</label><br><input id='txt_link' type='text' class='form-control'  placeholder='www.ejemplo.com' autocomplete='off' " +
      value_link +
      " ><br><label for='textlink'>Texto a mostrar</label><br><input id='txt_texto' type='text' class='form-control'  placeholder='Página' " +
      value_texto +
      "><br><button type='button' id='btn-ok' class='btn btn-primary' onclick=editarHipervinculo(" +
      id +
      ")>OK</button></div>"
  );

  varContainer = ".form-group-link";
}

function editarHipervinculo(id) {
  event.preventDefault();
  var valor1 = $("#txt_link").val();
  var valor2 = $("#txt_texto").val();

  let buleanLink = validateUrl(valor1);

  if (buleanLink) {
    $.ajax({
      url: lite,
      data: {
        clase: "admin_data",
        funcion: "setHipervinculo",
        valor1: valor1,
        valor2: valor2,
        id: id,
      },
      dataType: "json",

      success: function (resp) {
        $(".hypervinculo-" + id).attr(
          "onclick",
          "getLink(" +
            id +
            "," +
            caracter +
            valor1 +
            caracter +
            "," +
            caracter +
            valor2 +
            caracter +
            ")"
        );
        $("#txt_link").val(valor1);
        $("#txt_texto").val(valor2);
        $(".link-" + id + " a").attr("href", valor1);
        $(".link-" + id + " a").text(valor2);

        $(".form-group-link").remove();
      },
      error: function (error) {},
    });
  } else {
    lobby_notify(
      "¡El formato de la URL no es válido! Ejemplo: https://www.tuurl.com",
      "warning_circle.svg",
      "warning",
      "timdesk/"
    );
  }
}

function validateUrl(value) {
  return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(
    value
  );
}

function getFlag(array) {
  for (var i = 0; i < array.length; i++) {
    input = document.getElementById("phone-" + array[i]);
    configTel = window.intlTelInput(input, {
      initialCountry: "mx",
      preferredCountries: ["mx", "us", "ca"],

      utilsScript: "../../../js/build/js/utils.js?1590403638580",
    });

    arrayConfigTel.push(configTel);
  }
}

function getNumber(id) {
  let getIndex;
  for (i = 0; i < arrayConfigTel.length; i++) {
    let iti = arrayConfigTel[i].telInput.id;
    if (iti == "phone-" + id) {
      getIndex = i;
      i = arrayConfigTel.length;
    }
  }
  var validate = $("#phone-" + id).val();
  if (validate !== "") {
    var number = arrayConfigTel[getIndex].getNumber(
      intlTelInputUtils.numberFormat.E164
    );

    $.ajax({
      url: lite,
      data: {
        clase: "admin_data",
        funcion: "setPhoneNumber",
        number: number,
        id: id,
      },
      dataType: "json",
      success: function (resp) {
        arrayConfigTel[getIndex].setNumber(number);
      },
      error: function (error) {},
    });
  }
}

function getCondense(id) {
  //"Contraer" las tareas dentro de un grupo (etapa)
  $(".et_id_" + id).addClass("d-no");
  $(".group_" + id).addClass("condenseGroup");
  $("#opt-group-" + id).removeAttr("onclick");
  $("#opt-group-" + id).attr("onclick", "noCondense(" + id + ")");
  $("#opt-group-" + id).addClass("rotate180");
  $(".subTask").remove();
  //$('.group_'+id+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+id+'" class="no-condense-icon" onclick="noCondense('+id+')"></i>');
  //$('#append-'+id).css('left', '-49px');
}

function noCondense(id) {
  //Mostrar las tareas ocultas de las etapas.
  $(".et_id_" + id).removeClass("d-no");
  $(".group_" + id).removeClass("condenseGroup");
  $("#opt-group-" + id).removeAttr("onclick");
  $("#opt-group-" + id).attr("onclick", "getCondense(" + id + ")");
  $("#opt-group-" + id).removeClass("rotate180");
  //$('.group_'+id+' .groupBox .titulo-etapa').prepend('<i id="opt-group-'+id+'" class="opt_group_icon1" onclick="getCondense('+id+')"></i>');
  //$('#append-'+id).css('left', '-25px');
}

function noCondense2(id) {
  //Ya no me acuerdo si es funcional todavía.
  $(".et_id_" + id).removeClass("d-no");
  $(".group_" + id).removeClass("condenseGroup");
  $("#opt-group-" + id).remove();
  $(".group_" + id + " .groupBox .titulo-etapa").prepend(
    '<i id="opt-group-' +
      id +
      '" class="opt_group_icon1" style="display:none;" onclick="getCondense(' +
      id +
      ')"></i>'
  );

  for (var i = 0; i < tablas.length; i++) {
    $("#drag-group-" + tablas[i]).removeClass("opt_group_icon2");
    $("#drag-group-" + tablas[i]).addClass("opt_group_sort_icon");
  }
}

function showAllTask() {
  //Opción en la parte superior derecha para mostrar todas las tareas ocultas
  $(".open_rows_icon").addClass("close_rows_icon");
  $(".close_rows_icon").removeClass("open_rows_icon");
  $(".close_rows_icon").removeAttr("onclick");
  $(".close_rows_icon").attr("onclick", "hideAllTask()");
  $(".close_rows_icon").css("transform", "rotate(180deg)");
  $("#tipVO").attr("data-original-title", "Ocultar tareas");
  $("#show-hide-tasks-tip").html("Ocultar tareas");

  for (var i = 0; i < tablas.length; i++) {
    $(".group_" + tablas[i]).removeClass("condenseGroup");
    $(".task").removeClass("d-no");
    $(".header").removeClass("d-no");
    $(".agregarTarea").removeClass("d-no");

    $("#opt-group-" + tablas[i]).removeAttr("onclick");
    $("#opt-group-" + tablas[i]).attr(
      "onclick",
      "getCondense(" + tablas[i] + ")"
    );
    $("#opt-group-" + tablas[i]).removeClass("rotate180");
  }
}

function hideAllTask() {
  //Opción en la parte superior derecha para ocultar todas las tareas
  $(".subTask").remove();
  $(".close_rows_icon").addClass("open_rows_icon");
  $(".open_rows_icon").removeClass("close_rows_icon");
  $(".open_rows_icon").removeAttr("onclick");
  $(".open_rows_icon").attr("onclick", "showAllTask()");
  $(".open_rows_icon").css("transform", "rotate(0deg)");
  $("#tipVO").attr("data-original-title", "Mostrar tareas");
  $("#show-hide-tasks-tip").html("Mostrar tareas");

  for (var i = 0; i < tablas.length; i++) {
    $(".group_" + tablas[i]).addClass("condenseGroup");
    $(".task").addClass("d-no");
    $(".header").addClass("d-no");
    $(".agregarTarea").addClass("d-no");

    $("#opt-group-" + tablas[i]).removeAttr("onclick");
    $("#opt-group-" + tablas[i]).attr(
      "onclick",
      "noCondense(" + tablas[i] + ")"
    );
    $("#opt-group-" + tablas[i]).addClass("rotate180");
  }
}

function show_task_tip1() {
  $("#show-hide-tasks-tip").removeClass("d-no");
  let classRow = $("#cerrarEtapas i").hasClass("close_rows_icon");
  if (classRow) {
    $("#show-hide-tasks-tip").html("Ocultar tareas");
  } else {
    $("#show-hide-tasks-tip").html("Mostrar tareas");
  }
}

function hide_task_tip1() {
  $("#show-hide-tasks-tip").addClass("d-no");
}

function getUnique(array) {
  //obtiene valores únicos de un array, para mandar la información del tipo de columnas en el proyecto
  var uniqueArray = []; //array vacío para el retorno
  // Loop through array values
  for (i = 0; i < array.length; i++) {
    if (uniqueArray.indexOf(array[i]) === -1) {
      uniqueArray.push(array[i]); //llenando el array vacío
    }
  }
  return uniqueArray; //devolviendo el array con los nuevos valores (no repetidos)
}

function seeProjects() {
  //Lista de los proyectos del usuario
  varContainer = ".form-group-projects";
  $(".project-opt").remove();
  $(".form-group-projects").removeClass("d-no");
  $(".form-group-projects").show();
  $.ajax({
    url: lite,
    data: { clase: "get_data", funcion: "getAllProjects" },
    dataType: "json",
    success: function (resp) {
      $.each(resp, function () {
        $("#projects-List").append(
          '<option class="project-opt" value=' +
            this.PKProyecto +
            ">" +
            this.Proyecto +
            "</option>"
        );
      });
    },
    error: function (error) {},
  });
}

function getLead(id) {
  //Lista de los usuarios para elegir líder o dejar sin líder tarea
  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const idProyecto = urlParams.get("id");
  $("#boardContent").animate({ scrollTop: $("#boardContent").height() }, 1000);

  $("#avatar-circle").remove();
  $.ajax({
    url: lite,
    data: { clase: "get_data", funcion: "getUsers", idProyecto },
    dataType: "json",
    success: function (resp) {
      if (resp.length != 0) {
        $.each(resp, function (index, item) {
          $("#leadList").append(
            "<option value=" +
              item.PKEmpleado +
              " data-img=" +
              item.imagen +
              " data-emp=" +
              item.empresa +
              ">" +
              item.nombre_empleado +
              "</option>"
          );
        });
        $("#leadList").append(
          '<option value="no">Dejar sin responsable</option>'
        );
      } else {
        $(".ss-option .ss-disabled").text("No hay usuarios para mostrar.");
      }
    },
    error: function (error) {},
  });
  $(".leadSelect").remove();
  $(".div_r_" + id).append(
    "<div class='leadSelect pos-abs'><select id='leadList' class='trigger-unset'><option value='0'>Seleccione un responsable</option></select></div>"
  );
  var seleccion = new SlimSelect({
    select: "#leadList",
    placeholder: "Seleccione un responsable",
    searchPlaceholder: "Buscar usuario",
    afterOpen: (info) => {},
    onChange: (info) => {
      var str = info.text.split(" ")[0] + " " + info.text.split(" ")[1];
      if (info.value == "no") {
        $.ajax({
          url: lite,
          data: { clase: "edit_data", funcion: "noLead", pkR: id },
          dataType: "json",
          success: function (resp) {
            $("#no-lead-" + id).remove();
            $("#lead-" + id).remove();
            $(".div_r_" + id).append(
              "<i id='no-lead-" +
                id +
                "' class='noLead-icon imgHover imgActive cursorPointer' onclick='getLead(" +
                id +
                ")' data-toggle='leadTip-" +
                id +
                "' title='Agregar responsable' onmouseenter='activeToolTip(" +
                id +
                ")'></i>"
            );

            $(".leadSelect").remove();
          },
          error: function (error) {},
        });
      } else {
        var imgUs =
          info.data.img === "null"
            ? "../../../img/timUser.png"
            : "../../empresas/archivos/" +
              info.data.emp +
              "/img/" +
              info.data.img;
        $.ajax({
          url: lite,
          data: {
            clase: "edit_data",
            funcion: "setLead",
            id: info.value,
            pkR: id,
          },
          dataType: "json",
          success: function (resp) {
            $("#lead-" + id).remove();
            $("#no-lead-" + id).remove();
            $(".div_r_" + id).append(
              "<div id='lead-" +
                id +
                "' class='lead-container cursorPointer' onclick='getLead(" +
                id +
                ")' data-toggle='leadTip-" +
                id +
                "' data-placement='top' title='" +
                str +
                "' onmouseenter='activeToolTip(" +
                id +
                ")'><span class='lead-img'><img width='30px' src=" +
                imgUs +
                "></span> <span class='lead-resp'>" +
                str +
                "</span></div>"
            );

            $(".leadSelect").remove();
            alertNotification();
          },
          error: function (error) {},
        });
      }
    },
  });
  varContainer = ".trigger-unset";
}

function getFecha(id) {
  var fecha = $("#fecha-" + id).val();
  $.ajax({
    url: lite,
    data: { clase: "admin_data", funcion: "getFecha", id: id, fecha: fecha },
    dataType: "json",
    success: function (resp) {},
    error: function (error) {},
  });
}

function activeToolTip(id) {
  $('[data-toggle="leadTip-' + id + '"]').tooltip();
}

function goAway() {
  //Desaparecer animación al abrir proyecto
  $(".goaway").fadeOut(2000, function () {
    $("#page-content-wrapper").append(
      '<div id="boardContent" class="board-content" style="min-height: 300px;"></div>'
    );
    $("#boardContent").fadeIn("fast");
  });
}

function set_simple_number(id) {
  let number = $(".simple-number-" + id).val();
  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "simple_number",
      id_element: id,
      number: number,
    },
    dataType: "json",
    success: function (respuesta) {},
    error: function (error) {},
  });
}

function set_simple_text(id) {
  let text = $(".simple-text-" + id).val();

  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "simple_text",
      id_element: id,
      text: text,
    },
    dataType: "json",
    success: function (respuesta) {},
    error: function (error) {},
  });
}

function edit_project(id, texto) {
  let wage = "";
  if (texto == "enter") {
  } else {
    let name = $("#project-number-" + id).val();
    new_project_name(idGlobal, name);
  }
}

function group_tip(id) {
  //comprobar que sean más de 29 caracteres
  let comprobar = $("#input-group-" + id).val();
  let contar = comprobar.length;
  if (contar >= 30) {
    //Elementos tienen asignado el estado
    $("#group-tip-" + id).html(comprobar);
    $("#group-tip-" + id).removeClass("d-no");
    $("#group-tip-" + id).addClass("d-in-block");
  }
}

function group_tip_hidden(id) {
  $("#group-tip-" + id).removeClass("d-in-block");
  $("#group-tip-" + id).addClass("d-no");
}

function new_project_name(id, name) {
  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "new_project_name",
      id: id,
      nombre: name,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta == "ok") {
        $("#project-number-" + id).val(name);
        $("#project-number-" + id).blur();
      } else if (respuesta == -1) {
        lobby_notify(
          "Sólo el encargado del proyecto puede cambiar el nombre",
          "notificacion_error.svg",
          "error",
          "chat/"
        );
      } else {
        $("#project-number-" + id).val(respuesta.Proyecto);
        $("#project-number-" + id).blur();
        lobby_notify(
          "¡El nombre del proyecto no puede ir vacío!",
          "warning_circle.svg",
          "warning",
          "timdesk/"
        );
      }
    },
    error: function (error) {},
  });
}

function lobby_notify(string, icono, classStyle, carpeta) {
  Lobibox.notify(classStyle, {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "center top", //or 'center bottom'
    icon: false,
    img: "../../../img/" + carpeta + icono,
    msg: string,
  });

  return;
}

function check_columns(id_project) {
  $.ajax({
    url: "php/funciones.php",
    data: {
      clase: "get_data",
      funcion: "verify_progress_columns",
      id_project: id_project,
    },
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.verificar == 1) {
        config_column_action(9, 0, "verificacion_tarea");
      } else {
        config_column_action(9, 1, "verificacion_tarea");
      }

      if (respuesta.progreso == 1) {
        config_column_action(10, 0, "progreso_tarea");
      } else {
        config_column_action(10, 1, "progreso_tarea");
      }

      if (respuesta.ver_sub == 1) {
        config_column_action(15, 0, "verificar_subtarea");
      } else {
        config_column_action(15, 1, "verificar_subtarea");
      }

      if (respuesta.pro_sub == 1) {
        config_column_action(16, 0, "progreso_subtarea");
      } else {
        config_column_action(16, 1, "progreso_subtarea");
      }
    },
    error: function (error) {},
  });
}

function config_column_action(id, buleano, tabla) {
  if (buleano == 0) {
    $("#column-type-" + id).addClass("info-not-allowed pos-rel");
    $("#column-type-" + id).attr(
      "onmouseenter",
      "info_not_allowed(" + id + ")"
    );
    $("#column-type-" + id).attr(
      "onmouseleave",
      "hide_not_allowed(" + id + ")"
    );
    $("#column-type-" + id).append(
      '<span class="pos-abs d-no not-allowed-' +
        id +
        ' not-allowed-tip">Sólo puedes agregar una columna de este tipo por proyecto</span>'
    );
    $("#column-type-" + id + " .img-column").attr(
      "style",
      "background:#d1d1d1;"
    );
    $("#column-type-" + id + " img").css("filter", "brightness(0.5)");
    $("#column-type-" + id + " .add-to-project").removeAttr("onclick");
    $("#column-type-" + id + " .add-to-project").removeClass();
  } else {
    $("#column-type-" + id).removeClass("info-not-allowed pos-rel");
    $("#column-type-" + id).removeAttr(
      "onmouseenter",
      "info_not_allowed(" + id + ")"
    );
    $("#column-type-" + id).removeAttr(
      "onmouseleave",
      "hide_not_allowed(" + id + ")"
    );
    $(".not-allowed-" + id).remove();
    $("#column-type-" + id + " .img-column").attr(
      "style",
      "background:#1C87A0;"
    );
    $("#column-type-" + id + " img").css("filter", "brightness(1)");
    $("#add-type-" + id).attr(
      "onclick",
      "getColumnM(" + id + ',"' + tabla + '")'
    );
    $("#add-type-" + id).addClass("cursorPointer add-to-project");
  }
}

function info_not_allowed(id_type) {
  $(".not-allowed-" + id_type).removeClass("d-no");
}

function hide_not_allowed(id_type) {
  $(".not-allowed-" + id_type).addClass("d-no");
}
/**********************************************************/
/*######               EVENTOS VARIOS               ######*/
/**********************************************************/

$(".opcionesColumna").click(function (event) {
  var clicked = $(event.target).text();
});

$("#search-input").keyup(function () {
  $("#sin-coincidencias").remove();
  var valor = $("#search-input").val();
  $.ajax({
    url: lite,
    data: {
      clase: "buscar_data",
      funcion: "buscar_tarea",
      usuarioInput: valor,
      id: idProyecto,
    },
    dataType: "json",

    success: function (respuesta) {
      $("#sin-coincidencias").remove();
      if (respuesta.length > 0) {
        $(".hideEtapa").hide();
        $(".hideTarea").hide();
        $.each(respuesta, function (i) {
          $(".group_" + respuesta[i].PKEtapa).show();
          $("#tarea-" + respuesta[i].PKTarea).show();
        });
      } else {
        $(".hideEtapa").hide();
        $(".hideTarea").hide();
        $("#boardContent").append(
          '<div id="sin-coincidencias" class="text-center"><img src="img/icons/fail.svg" width="80" height="80"></br></br><h1 class="h5 text-blutTim">No se encontraron coincidencias en la búsqueda</h1></div>'
        );
      }
    },
    error: function (error) {},
  });
});

getDrag();

function getFlat(id) {
  let element = $(".rank-" + id);
  let ccc = $(".rank-" + id).flatpickr(optionFlatpickr);
}

function getFlatDate(id) {
  let element = $(".fecha-" + id);
  let ccc = $(".fecha-" + id).flatpickr(SimpleoptionFlatpickr);
}

$("#modalTextElement").on("show.bs.modal", function (event) {
  let button = $(event.relatedTarget);
  let recipient = button.data("whatever");
  let taskInfo = button.data("texttask");
  let modal = $(this);
  modal.find(".modal-body textarea").val("");
  let texto = $(".square-text-element-" + recipient + " p").text();

  let taskTittle = $("#task-name-" + taskInfo).val();
  modal.find(".tittle-text-task").text("Texto de la tarea: " + taskTittle);

  modal.find(".modal-body textarea").val(texto);

  id_text_element = recipient;
});

$("#edit-text-task-element").click(function () {
  let new_text = $("#textAreaElement").val();
  $.ajax({
    url: lite,
    data: {
      clase: "edit_data",
      funcion: "edit_text_element",
      id_element: id_text_element,
      new_text: new_text,
    },
    dataType: "json",
    success: function (respuesta) {
      $("#textAreaElement").val(new_text);
      $(".square-text-element-" + id_text_element + " p").text(new_text);
      lobby_notify(
        "¡El texto se ha actualizado!",
        "checkmark.svg",
        "success",
        "timdesk/"
      );
      $("#modalTextElement").modal("hide");
    },
    error: function (error) {},
  });
});

$(document).on("mouseup", function (e) {
  if (!$(e.target).closest(varContainer).length) {
    if (varContainer == ".opcionesColumnaNumeros") {
      $(varContainer).remove();
    } else {
      $(varContainer).hide();
    }

    //$(varContainer).remove();
  }
  var tieneClase = $(".agregarColumna i").hasClass("close-icon");
  if (tieneClase) {
    $(".agregarColumna i").removeClass("close-icon");
    $(".agregarColumna i").addClass("plus-icon");
  }
  $("#boardContent").css("overflow", "auto");
});

$(document).on("click", ".limpiarFecha", function () {
  var id = this.id;

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
      text: "La fecha será borrada",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Limpiar fecha</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $("#fecha-" + id).val("");
        getFecha(id);
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
});

$(document).on("click", ".limpiarRangoFecha", function () {
  var id = this.id;

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
      text: "Las fechas serán borradas",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Limpiar fechas</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $(".rank-" + id).val("");
        getFlat(id);
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
});

$("#page-content-wrapper").click(function (e) {
  e.preventDefault();
  var $e = $(e.target);
  let id = $(this).attr("id");
  /* console.log("aria-describedby ", id); */
  if (
    !$e.is(
      `[aria-describedby="${id}"], [aria-describedby="${id}"] *, #${id}, #${id} *`
    )
  ) {
    $(`[aria-describedby="${id}"]`).popover("hide");
  }
  /* console.log("Click"); */
});
