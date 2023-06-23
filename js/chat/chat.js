//Agregar multiples emails
$(function () {
  $("#enviarMails").multiple_emails({ position: "bottom" });
});

//Carga la actualización individual del enlace que se copia
if (idChatIndividual != "0") {
  $("#fluidModalRightSuccess").modal("toggle");

  $.ajax({
    url: rutare + "js/chat/functions/estadoUsuario.php",
    type: "POST",
    data: { Estado: 1 },
    success: function (data, status, xhr) {},
  });

  $.ajax({
    url: ruta + "../../js/chat/functions/cargarChat.php",
    type: "POST",
    data: { id: 0, idactualizacion: idChatIndividual, idtarea: 0, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      $("#chatIndividual").html(datos.html);
      $("#chatCompleto").hide();
      $(".tooltip_chat").tooltip();
      $("#tituloChat").html(datos.titulo);
      IDTareaChat = datos.FKTareaInd;
    },
  });
}

//Cargar el chat de una tarea cuando se copia
if (idChatTarea != "0") {
  $("#fluidModalRightSuccess").modal("toggle");

  loadChat(idChatTarea, 1);
}

//Muestra las actualizaciones agregadas a favoritos
function verFavoritos() {
  $("#VentanaActualizaciones").hide();
  $("#VentanaActividad").hide();
  $("#VentanaFavoritos").fadeIn();

  $.ajax({
    url: rutare + "js/chat/functions/estadoUsuario.php",
    type: "POST",
    data: { Estado: 1 },
    success: function (data, status, xhr) {},
  });

  $.ajax({
    url: ruta + "../../js/chat/functions/cargarChat.php",
    type: "POST",
    data: { id: 0, idactualizacion: 0, idtarea: IDTareaChat, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      $("#VentanaFavoritos").html(datos.html);
      $(".tooltip_chat").tooltip();
    },
  });
}

//Copia el link de la actualización
function copiarLink(id) {
  $("#copyp1").html(
    urlDireccionamiento +
      "index.php?id=" +
      idProyectoUrl +
      "&idIndividual=" +
      id
  );
  var text = $("#copyp1").get(0);
  var selection = window.getSelection();
  var range = document.createRange();
  range.selectNodeContents(text);
  selection.removeAllRanges();
  selection.addRange(range);
  //add to clipboard.
  document.execCommand("copy"); alert(range);

  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "right top especial140", //or 'center bottom'
    icon: false,
    img: ruta + "../../img/timdesk/checkmark.svg",
    msg: "¡Enlace copiado!",
  });
}

//Copia el link del chat de la actualización
function copiarLinkChat() {
  $("#copyp1").html(
    urlDireccionamiento +
      "index.php?id=" +
      idProyectoUrl +
      "&idTarea=" +
      IDTareaChat
  );
  var text = $("#copyp1").get(0);
  var selection = window.getSelection();
  var range = document.createRange();
  range.selectNodeContents(text);
  selection.removeAllRanges();
  selection.addRange(range);
  //add to clipboard.
  document.execCommand("copy");

  Lobibox.notify("success", {
    size: "mini",
    rounded: true,
    delay: 3000,
    delayIndicator: false,
    position: "right top especial110", //or 'center bottom'
    icon: false,
    img: ruta + "../../img/timdesk/checkmark.svg",
    msg: "¡Enlace de chat copiado!",
  });
}

//Asigna id al formulario para compartir actualizaciones
function cargarIDEnviar(id) {
  $("#idEnviar").val(id);
}

// Prevent bootstrap dialog from blocking focusin(Permite modificar en el tinymce)
$(document).on("focusin", function (e) {
  if (
    $(e.target).closest(
      ".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root"
    ).length
  ) {
    e.stopImmediatePropagation();
  }
});

function loadChat(id, modo) {
  IDTareaChat = id;

  $("#viewTaskInfo").modal("toggle");

  if (!$("#fluidModalRightSuccess").hasClass("show")) {
    $("#fluidModalRightSuccess").modal("toggle");
  }

  if (!$("#chatIndividual").hasClass("show")) {
    $("#chatIndividual").hide();
    $("#chatCompleto").show();
  }

  $("#VentanaActividad").hide();
  $("#VentanaFavoritos").hide();
  $("#VentanaActualizaciones").fadeIn();

  $.ajax({
    url: rutare + "js/chat/functions/estadoUsuario.php",
    type: "POST",
    data: { Estado: 1 },
    success: function (data, status, xhr) {},
    error: function (error) {},
  });

  $.ajax({
    url: ruta + "../../js/chat/functions/cargarChat.php",
    type: "POST",
    data: { id: id, idactualizacion: 0, idtarea: 0, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      if (modo == 2) {
        $("#chatCompleto").show();
        $("#chatIndividual").hide();
      }

      $("#tituloChat").html(datos.titulo);
      $("#NuevasActualizaciones").html(datos.html);
      $(".tooltip_chat").tooltip();
    },
    error: function (error) {
      console.log(error);
    },
  });
}

//Agrega una actualización a favoritos
function marcarFavorito(id) {
  $.ajax({
    url: ruta + "../../js/chat/functions/chatFavoritos.php",
    type: "POST",
    data: { IdActualizacion: id, idusuario: IDUsuario },
    success: function (data, status, xhr) {
      if (data == "exito") {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial75", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Actualización agregada a favoritos!",
        });

        var nuevospan =
          '<a class="dropdown-item" href="#" onclick="eliminarFavorito(' +
          id +
          ');"><img src="' +
          ruta +
          '../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Eliminar de favoritos</a>';
        $(".favorito_" + id).html(nuevospan);
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial40",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No fue posible agregar a favoritos la actualización",
        });
      }
    },
  });
}

//Elimina una actualización de favoritos
function eliminarFavorito(id) {
  $.ajax({
    url: ruta + "../../js/chat/functions/chatEliminarFavoritos.php",
    type: "POST",
    data: { IdActualizacion: id, idusuario: IDUsuario },
    success: function (data, status, xhr) {
      if (data == "exito") {
        Lobibox.notify("error", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial75",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Actualización eliminada de favoritos",
        });

        var nuevospan =
          '<a class="dropdown-item" href="#" onclick="marcarFavorito(' +
          id +
          ');"><img src="' +
          ruta +
          '../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Marcar como favorito</a>';
        $(".favorito_" + id).html(nuevospan);
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial40",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No es posible eliminar la actualización de favoritos",
        });
      }
    },
  });
}

//Funcion de me gusta para las actualizaciones
function megusta(element, id, localizacion) {
  var element = $(element).closest(".ventanas").attr("id");

  if (localizacion == 1) {
    var imagen1 = "like.svg";
    var imagen2 = "dislike.svg";
    var imagen3 = "like_click.svg";
  }

  if (localizacion == 2) {
    var imagen1 = "like_blue.svg";
    var imagen2 = "dislike_blue.svg";
    var imagen3 = "like_blue_click.svg";
  }

  $.ajax({
    url: ruta + "../../js/chat/functions/chatLikes.php",
    type: "POST",
    data: { IdActualizacion: id, idusuario: IDUsuario, Tipo: 1 },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      if (datos.res == "1") {
        $(".likeimage_" + id).attr("src", ruta + "../../img/chat/" + imagen1);
        $(".likeTitle_" + id).attr(
          "data-original-title",
          datos.likes_cantidad + " me gusta"
        );
      }
      if (datos.res == "2") {
        $(".likeimage_" + id).attr("src", ruta + "../../img/chat/" + imagen3);
        $(".likeTitle_" + id).attr(
          "data-original-title",
          datos.likes_cantidad + " me gusta"
        );
      }
      if (datos.res == "3") {
        //cuando ya tiene no me gusta
        $(".likeimage_" + id).attr("src", ruta + "../../img/chat/" + imagen3);
        $(".dislikeimage_" + id).attr(
          "src",
          ruta + "../../img/chat/" + imagen2
        );
        $(".likeTitle_" + id).attr(
          "data-original-title",
          datos.likes_cantidad + " me gusta"
        );
        $(".dislikeTitle_" + id).attr(
          "data-original-title",
          datos.dislikes_cantidad + " no me gusta"
        );
      }

      $(".tooltip_chat").tooltip();

      $("#" + element + " .tooltip_chat").tooltip("hide");
      $("#" + element + " .likeTitle_" + id)
        .tooltip()
        .mouseover();
    },
  });
}

//Funcion de no me gusta para las actualizaciones
function nomegusta(element, id, localizacion) {
  var element = $(element).closest(".ventanas").attr("id");

  if (localizacion == 1) {
    var imagen1 = "like.svg";
    var imagen2 = "dislike.svg";
    var imagen4 = "dislike_click.svg";
  }

  if (localizacion == 2) {
    var imagen1 = "like_blue.svg";
    var imagen2 = "dislike_blue.svg";
    var imagen4 = "dislike_blue_click.svg";
  }

  $.ajax({
    url: ruta + "../../js/chat/functions/chatLikes.php",
    type: "POST",
    data: { IdActualizacion: id, idusuario: IDUsuario, Tipo: 2 },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      if (datos.res == "1") {
        $(".dislikeimage_" + id).attr(
          "src",
          ruta + "../../img/chat/" + imagen2
        );
        $(".dislikeTitle_" + id).attr(
          "data-original-title",
          datos.dislikes_cantidad + " no me gusta"
        );
      }
      if (datos.res == "2") {
        $(".dislikeimage_" + id).attr(
          "src",
          ruta + "../../img/chat/" + imagen4
        );
        $(".dislikeTitle_" + id).attr(
          "data-original-title",
          datos.dislikes_cantidad + " no me gusta"
        );
      }
      if (datos.res == "4") {
        $(".dislikeimage_" + id).attr(
          "src",
          ruta + "../../img/chat/" + imagen4
        );
        $(".likeimage_" + id).attr("src", ruta + "../../img/chat/" + imagen1);
        $(".likeTitle_" + id).attr(
          "data-original-title",
          datos.likes_cantidad + " me gusta"
        );
        $(".dislikeTitle_" + id).attr(
          "data-original-title",
          datos.dislikes_cantidad + " no me gusta"
        );
      }

      $(".tooltip_chat").tooltip();

      $("#" + element + " .tooltip_chat").tooltip("hide");
      $("#" + element + " .dislikeTitle_" + id)
        .tooltip()
        .mouseover();
    },
  });
}

//Funcion de responder
function responder(element, id) {
  var element = $(element).closest(".ventanas").attr("id");

  tinymce.remove("#" + element + " .responderClass");
  $(".responderUnico").html("");

  var nuevo_input =
    '<br><input type="text" name="inputResponder_' +
    id +
    '" id="inputResponder_' +
    id +
    '" class="form-control responderClass" value="">' +
    '<div class="row">' +
    '<div class="col-md-12"><center><button type="button" class="btnesp first espCancelar btnCancelarActualizacionResponder marginEsp" name="btnCancelarActualizacionResponder" id="' +
    id +
    '"><span class="displayEsp">Cancelar</span></button>' +
    '<input type="file" name="file-2" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} archivos seleccionados" multiple   />' +
    '<label for="file-2" class="no-margin" id="responder-file-chat-' +
    id +
    '">' +
    '<span class="iborrainputfile ajuste">Archivos</span>' +
    "</label>" +
    '<button type="button" class="btnesp first espAgregar btnEditarActualizacionResponder" name="btnEditarActualizacionResponder" id="' +
    id +
    '"><span class="displayEsp">Responder</span></button></div></center></div>';

  $("#" + element + " #responder_" + id).html(nuevo_input);

  tinymce.init({
    selector: "#inputResponder_" + id,
    language: "es_MX",
    language_url: tinymceurl + "lang/es_MX.js",
    relative_urls: false,
    remove_script_host: false,
    document_base_url: urlGlobal,
    plugins:
      "autoresize autolink directionality visualblocks image link media table hr advlist lists imagetools textpattern noneditable emoticons",
    menubar: false,
    statusbar: true,
    toolbar:
      "mybutton bold italic underline strikethrough | numlist bullist | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | table | emoticons | image media link | ltr rtl",
    external_plugins: {
      flags: tinymceurl + "plugins/flags/plugin.js",
    },
    /* without images_upload_url set, Upload tab won't show up*/
    images_upload_url: ruta + "../../js/chat/functions/postAcceptor.php",
    file_picker_types: "file image media",
    automatic_uploads: true,
    media_dimensions: false,
    media_url_resolver: function (data, resolve /*, reject*/) {
      urlVideo = getId(data.url.trim());
      var embedHtml =
        '<p style="text-align: center;" data-mce-style="text-align: center;"><iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
        urlVideo +
        '"  frameborder="1" allowfullscreen class="youtube-video"></iframe></p>';
      resolve({ html: embedHtml });
    },
    /* we override default upload handler to simulate successful upload*/
    images_upload_handler: function (blobInfo, success, failure) {
      var xhr, formData;

      xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open("POST", ruta + "../../js/chat/functions/postAcceptor.php");

      xhr.onload = function () {
        var json;

        if (xhr.status != 200) {
          failure("HTTP Error: " + xhr.status);
          return;
        }

        json = JSON.parse(xhr.responseText);

        if (!json || typeof json.location != "string") {
          failure("Invalid JSON: " + xhr.responseText);
          return;
        }

        success(json.location);
      };

      formData = new FormData();
      formData.append("file", blobInfo.blob(), blobInfo.filename());

      xhr.send(formData);
    },
    content_style:
      "img {bottom: 0; left: 0; margin: auto; max-width: 100%;height: auto;} .mymention{ font-weight: 600; color: #1c87a0;} .overlay-esp{ display: none !important;}",
    /*   image_class_list: [
          {title: 'img-responsive', value: 'img-responsive'}
        ],*/
    setup: function (ed) {
      ed.on("keydown", function (e) {
        if (e.keyCode == 13) {
          text = tinyMCE.editors[$("#inputResponder_" + id).attr("id")]
            .getContent({ format: "text" })
            .trim(); //

          var separators = [" ", "\\\n"];
          var tokens = text.split(new RegExp(separators.join("|"), "g"));
          var indice = tokens.length;

          for (i = 0; i < indice; i++) {
            if (i == indice - 1) {
              myId = getId(tokens[i].trim());
              if (myId != "error") {
                tokens[i] =
                  '<iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
                  myId +
                  '" frameborder="1" allowfullscreen class="youtube-video"></iframe>';
                var $body = $(tinymce.activeEditor.getBody());
                $body.find("p:last").html("");
                $body.find("p:last").before(tokens[i]);
                /*    tinymce.activeEditor.execCommand('mceInsertNewLine');
                          tinymce.activeEditor.execCommand('mceInsertContent', false, tokens[i]);*/
              }
            }
          }
        }
      });
    },
  });

  $(".tooltip_chat").tooltip("hide");
  event.stopPropagation();
}

//Muestra las actualizaciones
function verActualizaciones() {
  $("#VentanaActividad").hide();
  $("#VentanaFavoritos").hide();
  $("#VentanaActualizaciones").fadeIn();
}

//Muestra y carga la actividad de esa tarea
function verActividad() {
  $("#VentanaActualizaciones").hide();
  $("#VentanaFavoritos").hide();

  $.ajax({
    url: ruta + "../../js/chat/functions/cargarActividad.php",
    type: "POST",
    data: { IDTarea: IDTareaChat, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      $("#VentanaActividad").html(datos.html);

      $.getScript(ruta + "../../js/chat/filtable.js?dev", function () {
        $(function () {
          $("#data_actividad").filtable({ controlPanel: $(".table-filters") });
        });
      });
    },
  });

  $("#VentanaActividad").fadeIn();
}

$("body").on("click", ".modal-dialog", function (e) {
  if ($(e.target).hasClass("modal-dialog")) {
    var hidePopup = $(e.target.parentElement).attr("id");

    $("#" + hidePopup).modal("hide");
  }
});

//Agregar archivos a las actualizaciones
var btnArchivo = 0;
$("#file-2").click(function () {
  $("#file-2").change(function () {
    var fd = new FormData();
    var files = [];
    files = $("#file-2")[0].files[0];

    if (files.size > post_max_size) {
      Lobibox.notify("warning", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "right top especial80",
        icon: false,
        img: ruta + "../../img/chat/notificacion_error.svg",
        msg: "El archivo no puede pesar más de " + post_max_size_limite + "B",
      });
      fd.delete("file");
      $("#file-2").val("");
      return;
    }

    var nombrearchivo = files.name;
    var extension = nombrearchivo
      .substr(nombrearchivo.lastIndexOf(".") + 1)
      .toLowerCase();

    if (
      extension == "jpg" ||
      extension == "jpeg" ||
      extension == "gif" ||
      extension == "png" ||
      extension == "tiff" ||
      extension == "tif" ||
      extension == "bmp" ||
      extension == "svg"
    ) {
      if (files.size > 4000000) {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial80",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "La imagen no puede pesar más de 4 MB",
        });
        fd.delete("file");
        $("#file-2").val("");
        return;
      }
    }

    fd.append("file", files);

    $.ajax({
      url: ruta + "../../js/chat/functions/upload.php",
      type: "post",
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      enctype: "multipart/form-data",
      xhr: function () {
        //upload Progress
        uploadBar();
        var xhr = $.ajaxSettings.xhr();
        if (xhr.upload) {
          xhr.upload.addEventListener(
            "progress",
            function (event) {
              var percent = 0;
              var position = event.loaded || event.position;
              var total = event.total;
              if (event.lengthComputable) {
                percent = Math.ceil((position / total) * 100);
              }
              //update progressbar
              $(".progress-bar").css("width", +percent + "%");
              $(".status").text(percent + "%");
            },
            true
          );
        }
        return xhr;
      },
      success: function (response) {
        var datos = JSON.parse(response);

        if (datos.res == 1) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "right top especial80",
            icon: false,
            img: ruta + "../../img/chat/notificacion_error.svg",
            msg: "La imagen no puede pesar más de 4 MB",
          });
        }
        if (datos.res == 2) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "right top especial80",
            icon: false,
            img: ruta + "../../img/chat/notificacion_error.svg",
            msg:
              "El archivo no puede pesar más de " + post_max_size_limite + "B",
          });
        }
        if (datos.res == 3) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "right top especial75",
            icon: false,
            img: ruta + "../../img/chat/notificacion_error.svg",
            msg:
              "Los archivos no pueden pesar más de " +
              post_max_size_limite +
              "B",
          });
        }
        if (datos.res == 4) {
          /*var $body = $(tinymce.activeEditor.getBody());
                     $body.find('p:last').html('');*/
          tinyMCE.activeEditor.selection.select(
            tinyMCE.activeEditor.getBody(),
            true
          );
          tinyMCE.activeEditor.selection.collapse(false);

          if (
            extension == "doc" ||
            extension == "docx" ||
            extension == "xlsx" ||
            extension == "xls" ||
            extension == "pptx" ||
            extension == "ppt" ||
            extension == "pdf"
          ) {
            var agregado =
              "<p style='text-align: center;' data-mce-style='text-align: center;'><iframe src='https://docs.google.com/viewer?url=" +
              urlGlobal +
              "functions/" +
              datos.location.trim() +
              "&embedded=true' style='width:100%; height:300px;' frameborder='0'></iframe></p>";
          } else {
            if (
              extension == "jpg" ||
              extension == "jpeg" ||
              extension == "gif" ||
              extension == "png" ||
              extension == "tiff" ||
              extension == "tif" ||
              extension == "bmp" ||
              extension == "svg"
            ) {
              var agregado =
                "<div class='container-Vin' style='text-align: center;' data-mce-style='text-align: center;'>" +
                "<img src='" +
                urlGlobal +
                "functions/" +
                datos.location.trim() +
                "' class='imageUnique' />" +
                "<span class='overlay-Vin overlay-esp'>" +
                "<a download='" +
                datos.location.trim() +
                "' href='" +
                urlGlobal +
                "functions/" +
                datos.location.trim() +
                "' class='icon-Vin'>" +
                "<img src='../../../tasksManager/img/chat/download.png' alt='Avatar' width='40'>" +
                "</a>" +
                "</span>" +
                "</div><br>";
            } else {
              var agregado =
                "<p style='text-align: center;' data-mce-style='text-align: center;'><a href='" +
                urlGlobal +
                "functions/" +
                datos.location.trim() +
                "'>" +
                nombrearchivo +
                "</a></p>";
            }
          }

          tinymce.activeEditor.execCommand("mceInsertContent", false, agregado);

          Lobibox.notify("success", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "right top especial130", //or 'center bottom'
            icon: false,
            img: ruta + "../../img/timdesk/checkmark.svg",
            msg: "¡Archivo cargado!",
          });
        }
        if (datos.res == 0) {
          Lobibox.notify("warning", {
            size: "mini",
            rounded: true,
            delay: 3000,
            delayIndicator: false,
            position: "right top especial95",
            icon: false,
            img: ruta + "../../img/chat/notificacion_error.svg",
            msg: "No se pudo subir el archivo",
          });
        }

        setTimeout(uploadBar, 1000);
      },
    });

    fd.delete("file");
    $("#file-2").val("");
  });

  btnArchivo = 1;
});

function uploadBar() {
  $("#uploadBar").modal("toggle");
}

//funcion para reconocer enlaces de youtube
function getId(url) {
  var regExp =
    /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
  var match = url.match(regExp);

  if (match && match[2].length == 11) {
    return match[2];
  } else {
    return "error";
  }
}

//boton para agregar la actualizacion
$("#txtActualizacion").on("click", function (e) {
  tinymce.init({
    selector: "#txtActualizacion",
    language: "es_MX",
    language_url: tinymceurl + "lang/es_MX.js",
    relative_urls: false,
    remove_script_host: false,
    document_base_url: urlGlobal,
    plugins:
      "autoresize autolink directionality visualblocks image link media table hr advlist lists imagetools textpattern noneditable emoticons",
    menubar: false,
    statusbar: true,
    toolbar:
      "mybutton bold italic underline strikethrough | numlist bullist | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | table | emoticons | image media link | ltr rtl",
    external_plugins: {
      flags: tinymceurl + "plugins/flags/plugin.js",
    },
    /* without images_upload_url set, Upload tab won't show up*/
    images_upload_url: ruta + "../../js/chat/functions/postAcceptor.php",
    file_picker_types: "file image media",
    automatic_uploads: true,
    media_dimensions: false,
    media_url_resolver: function (data, resolve /*, reject*/) {
      urlVideo = getId(data.url.trim());
      var embedHtml =
        '<p style="text-align: center;" data-mce-style="text-align: center;"><iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
        urlVideo +
        '"  frameborder="1" allowfullscreen class="youtube-video"></iframe></p>';
      resolve({ html: embedHtml });
    },
    /* we override default upload handler to simulate successful upload*/
    images_upload_handler: function (blobInfo, success, failure) {
      var xhr, formData;

      xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open("POST", ruta + "../../js/chat/functions/postAcceptor.php");

      xhr.onload = function () {
        var json;
        //alert(xhr.status);
        if (xhr.status == 600) {
          failure("La imagen no puede ser más grande de 4MB.");
          return;
        }

        if (xhr.status == 400) {
          failure("La imagen sólo puede ser .jpg .gif .png.");
          return;
        }

        if (xhr.status != 200) {
          failure("HTTP Error: " + xhr.status);
          return;
        }

        json = JSON.parse(xhr.responseText);

        if (!json || typeof json.location != "string") {
          failure("Invalid JSON: " + xhr.responseText);
          return;
        }

        success(json.location);
      };

      formData = new FormData();
      formData.append("file", blobInfo.blob(), blobInfo.filename());

      xhr.send(formData);
    },
    content_style:
      "img {bottom: 0; left: 0; margin: auto; max-width: 100%;height: auto;} .mymention{ font-weight: 600; color: #1c87a0;} .overlay-esp{ display: none !important;}",
    setup: function (ed) {
      ed.on("keydown", function (e) {
        if (e.keyCode == 13) {
          text = tinyMCE.editors[$("#txtActualizacion").attr("id")]
            .getContent({ format: "text" })
            .trim(); //

          var separators = [" ", "\\\n"];
          var tokens = text.split(new RegExp(separators.join("|"), "g"));
          var indice = tokens.length;

          for (i = 0; i < indice; i++) {
            if (i == indice - 1) {
              myId = getId(tokens[i].trim());
              if (myId != "error") {
                tokens[i] =
                  ' <p style="text-align: center;" data-mce-style="text-align: center;"><iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
                  myId +
                  '" frameborder="1" allowfullscreen class="youtube-video"></iframe></p>';
                var $body = $(tinymce.activeEditor.getBody());
                $body.find("p:last").html("");
                $body.find("p:last").before(tokens[i]);
                /* tinymce.activeEditor.execCommand('mceInsertNewLine');
                          tinymce.activeEditor.execCommand('mceInsertContent', false, tokens[i]);*/
              }
            }
          }
        }
      });
    },
  });

  $("#botonesNuevaActualizacion").css("display", "flex");
  event.stopPropagation();
});

//agregar actualizaciones
$("#btnAgregarActualizacion").on("click", function (e) {
  console.log("El chat");
  var texto = tinyMCE.editors[$("#txtActualizacion").attr("id")]
    .getContent()
    .trim();

  if (texto.trim() === "") {
    return;
  }

  var IDAlertas = $(
    tinyMCE.editors[$("#txtActualizacion").attr("id")].getContent().trim()
  ).find(".mymention");
  var arrayAlertas = [];

  if (IDAlertas.length > 0) {
    for (var x = 0; x < IDAlertas.length; x++) {
      arrayAlertas[x] = IDAlertas[x]["dataset"].mentionId;
    }
  } else {
    arrayAlertas[0] = -1;
  }

  if ($("#noexistenactualizaciones").length) {
    $("#noexistenactualizaciones").remove();
  }

  $.ajax({
    url: ruta + "../../js/chat/functions/agregarActualizacion.php",
    type: "POST",
    data: {
      Texto: texto,
      IDUsuario: IDUsuario,
      IDAlertas: arrayAlertas,
      IDTarea: IDTareaChat,
    },
    success: function (data, status, xhr) {
      console.log(data);
      var datos = JSON.parse(data);
      if (parseInt(datos.res) > 0) {
        var agregarLista =
          '<div class="actualizacionCard actualizacion_' +
          datos.res +
          '">' +
          '<div class="row">' +
          '<div class="col-md-10">' +
          '<div class="wrapper-actualizacion">' +
          '<div class="col-md-12 col-xs-12">' +
          '<div style="float: left;">' +
          '<span data-toggle="tooltip" class="tooltip_chat" title="' +
          nombreusuario +
          '">' +
          '<img src="' +
          ruta +
          '../../img/chat/users.svg" class="user-img img-responsive" width="25px">' +
          "</span>" +
          '<span class="nombre-usuario">' +
          nombreusuario +
          "</span>" +
          '<span data-toggle="tooltip" class="tooltip_chat estado-activo estado-circulo" data-original-title="Activo"></span>' +
          "</div>" +
          '<div style="float: right;">' +
          '<img src="' +
          ruta +
          '../../img/chat/reloj.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="' +
          datos.fecha +
          '" /><span class="panel-header">Ahora</span> ' +
          '<button type="button" id="recordatorioDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
          '<img src="' +
          ruta +
          '../../img/chat/alertas.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="Recordatorio" />' +
          "</button> " +
          '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="recordatorioDesplegable">' +
          '<li style="color:#fff;font-weight:800;">Alerta en:</li>' +
          '<li><a class="dropdown-item" href="#" onclick="agregarAlerta(' +
          "'20m'" +
          "," +
          datos.res +
          ');">20 minutos</a></li>' +
          '<li><a class="dropdown-item" href="#" onclick="agregarAlerta(' +
          "'1h'" +
          "," +
          datos.res +
          ');">1 hora</a></li>' +
          '<li><a class="dropdown-item" href="#" onclick="agregarAlerta(' +
          "'3h'" +
          "," +
          datos.res +
          ');">3 horas</a></li>' +
          '<li><a class="dropdown-item" href="#" onclick="agregarAlerta(' +
          "'T'" +
          "," +
          datos.res +
          ');">Mañana</a></li>' +
          '<li><a class="dropdown-item" href="#" onclick="agregarAlerta(' +
          "'W'" +
          "," +
          datos.res +
          ');">La próxima semana</a></li>' +
          "</div>" +
          '<span><button type="button" id="botonDesplegable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
          '<img src="' +
          ruta +
          '../../img/chat/menu_desplegable.svg" class="img-responsive" width="15px">' +
          "</button>" +
          '<div class="dropdown-menu dropdown-menu-right" aria-labelledby="botonDesplegable">' +
          '<a class="dropdown-item" href="#" onclick="anclarChat(' +
          datos.res +
          ');"><img src="' +
          ruta +
          '../../img/chat/anclar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Anclar a la parte superior</a>' +
          '<a class="dropdown-item" href="#" onclick="copiarLink(' +
          datos.res +
          ');"><img src="' +
          ruta +
          '../../img/chat/copiar_enlace.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Copiar enlace de actualización</a>' +
          '<a class="dropdown-item" href="#" onclick="editarActualizacion(this,' +
          datos.res +
          ',1);"><img src="' +
          ruta +
          '../../img/chat/editar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 2px;"/> Editar actualización</a>' +
          '<a class="dropdown-item" href="#" onclick="eliminarActualizacion(' +
          datos.res +
          ');"><img src="' +
          ruta +
          '../../img/chat/eliminar.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;bottom: 2px;"/> Eliminar actualización</a>' +
          '<a class="dropdown-item" href="#" data-toggle="modal" data-target="#centralCompartirActualizacion" onclick="cargarIDEnviar(' +
          datos.res +
          ');"><img src="' +
          ruta +
          '../../img/chat/compartir.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Compartir actualización</a>' +
          '<span class="favorito_' +
          datos.res +
          '"><a class="dropdown-item" href="#" onclick="marcarFavorito(' +
          datos.res +
          ');"><img src="' +
          ruta +
          '../../img/chat/favorito.svg" width="18px" class="img-responsive" style="position: relative; right: 4px;"/> Marcar como favorito</a></span>' +
          "</div>" +
          "</span>" +
          "</div>" +
          "<br><br>" +
          '<div class="textoActualizacion_' +
          datos.res +
          ' estiloTextoMCE">' +
          texto +
          "</div>" +
          "</div><br>" +
          '<div class="wrapper-visto">' +
          '<div class="visto">' +
          '<a href="#" class="visto-sin" data-toggle="modal" data-target="#verUsuariosVisto" onclick="mostrarUsuariosVistos(' +
          datos.res +
          ')">' +
          '<img src="' +
          ruta +
          '../../img/chat/visto.svg" class="img-responsive ver" width="20px" /> Visto por 1' +
          "</a>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          '<div class="col-md-2">' +
          '<div class="media-ask">' +
          '<div class="media-top">' +
          '<span data-toggle="tooltip" title="Responder" class="space_comment_up tooltip_chat">' +
          '<a href="#" onclick="responder(this,' +
          datos.res +
          ')">' +
          '<img src="' +
          ruta +
          '../../img/chat/ask.svg" class="user-img img-responsive fill-white" width="16px" />' +
          "</a>" +
          "</span>" +
          '<span data-toggle="tooltip" class="tooltip_chat likeTitle_' +
          datos.res +
          '" title="0 me gusta">' +
          '<a href="#" onclick="megusta(this,' +
          datos.res +
          ',1)">' +
          '<img src="' +
          ruta +
          '../../img/chat/like.svg" class="user-img img-responsive likeimage_' +
          datos.res +
          '" width="16px" /><br>' +
          "</a>" +
          "</span>" +
          '<span data-toggle="tooltip" title="0 no me gusta" class="space_comment_bt tooltip_chat dislikeTitle_' +
          datos.res +
          '">' +
          '<a href="#" onclick="nomegusta(this,' +
          datos.res +
          ',1)">' +
          '<img src="' +
          ruta +
          '../../img/chat/dislike.svg" class="user-img img-responsive dislikeimage_' +
          datos.res +
          '" width="16px" /><br>' +
          "</a>" +
          "</span>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          '<div class="responder agregarResponder_' +
          datos.res +
          '">' +
          "</div>" +
          '<div id="responder_' +
          datos.res +
          '" class="responderUnico">' +
          "</div>" +
          '<br class="espacio_' +
          datos.res +
          '">';

        $("#NuevasActualizaciones").prepend(agregarLista);
        tinymce.remove("#txtActualizacion");
        $("#txtActualizacion").val("");
        $("#botonesNuevaActualizacion").css("display", "none");
        $(".tooltip_chat").tooltip();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial110", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Actualización agregada!",
        }); /*110px*/
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial80",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se pudo agregar la actualización",
        }); /*80px*/
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

//boton cancelar de agregar actualizaciones
$(document).on("click", ".btnCancelarActualizacion", function (e) {
  id = this.id;

  tinymce.remove("#txtActualizacion");
  $("#botonesNuevaActualizacion").css("display", "none");
});

//eliminar actualizacion
function eliminarActualizacion(idActualizacion) {
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
      title: "¿Deseas eliminar esta actualización?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter">Eliminar actualización</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: ruta + "../../js/chat/functions/eliminarActualizacion.php",
          type: "POST",
          data: { IDActualizacion: idActualizacion },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $(".actualizacion_" + idActualizacion).remove();
              $(".espacio_" + idActualizacion).remove();
              $(".agregarResponder_" + idActualizacion).remove();

              if ($(".actualizacionCard ").length == 0) {
                $("#NuevasActualizaciones").append(
                  '<br><center><span id="noexistenactualizaciones">AÚN NO EXISTEN ACTUALIZACIONES</span></center>'
                );
              }

              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "right top especial125",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Actualización borrada",
              });
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "right top especial75",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "No se pudo borrar la actualización",
              });
            }
          },
        });

        $(".tooltip_chat").tooltip("hide");
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

//Boton para modificar tiny mce en actualizaciones
var TextoOriginal;
var id_old = -1;
var textoGuardado;
function editarActualizacion(element, idActualizacion, modo) {
  if (id_old == idActualizacion && $(".txtActualizacionEdit").length > 0) {
    return;
  }

  var element = $(element).closest(".ventanas").attr("id");
  var texto = $(".textoActualizacion_" + idActualizacion)
    .html()
    .trim();
  TextoOriginal = texto;

  if ($(".txtActualizacionEdit").length) {
    $("#" + element + " .textoActualizacion_" + id_old).html(textoGuardado);
  }
  id_old = idActualizacion;

  tinymce.remove(".txtActualizacionEdit");
  $(".txtActualizacionEdit").remove();
  $(".editarBotones").remove();
  //$('#' + element + ' #' + id).remove();
  //$("#file-chat-" + id).remove();
  textoGuardado = TextoOriginal;

  var nuevo_input =
    '<input type="text" name="txtActualizacionEdit_' +
    idActualizacion +
    '" id="txtActualizacionEdit_' +
    idActualizacion +
    '" class="form-control txtActualizacionEdit" placeholder="Escribir una actualización..." value="">' +
    '<div class="row editarBotones">' +
    '<div class="col-md-12"><center><button type="button" class="btnesp first espCancelar botonCancelar ajustebtnCanc" name="btnCancelarActualizacion" id="' +
    idActualizacion +
    '"><span class="displayEsp">Cancelar</span></button>' +
    '<input type="file" name="file-2" id="file-2" class="inputfile inputfile-2" data-multiple-caption="{count} archivos seleccionados" multiple   />' +
    '<label for="file-2" class="no-margin ';

  if (modo != 2) {
    if (pagina == "scrum") {
      nuevo_input += "ajusteSubirArchivosScrum";
    } else {
      nuevo_input += "ajusteSubirArchivosTim";
    }
  } else {
    nuevo_input += "ajusteSubirArchivosR";
  }

  nuevo_input +=
    '" id="file-chat-' +
    idActualizacion +
    '">' +
    '<span class="iborrainputfile ajusteact">Archivos</span>' +
    "</label>" +
    '<button type="button" class="btnesp first espAgregar marPosModificar botonActualizar" name="btnEditarActualizacion" id="' +
    idActualizacion +
    '"><span class="displayEsp">Modificar</span></button></center></div></div>';

  $("#" + element + " .textoActualizacion_" + idActualizacion).html(
    nuevo_input
  );

  tinymce.init({
    selector: "#" + element + " #txtActualizacionEdit_" + idActualizacion,
    language: "es_MX",
    language_url: tinymceurl + "lang/es_MX.js",
    relative_urls: false,
    remove_script_host: false,
    document_base_url: urlGlobal,
    plugins:
      "autoresize autolink directionality visualblocks image link media table hr advlist lists imagetools textpattern noneditable emoticons",
    menubar: false,
    statusbar: true,
    toolbar:
      "mybutton bold italic underline strikethrough | numlist bullist | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | table | emoticons | image media link | ltr rtl",
    external_plugins: {
      flags: tinymceurl + "plugins/flags/plugin.js",
    },
    /* without images_upload_url set, Upload tab won't show up*/
    images_upload_url: ruta + "../../js/chat/functions/postAcceptor.php",
    file_picker_types: "file image media",
    automatic_uploads: true,
    media_dimensions: false,
    media_url_resolver: function (data, resolve /*, reject*/) {
      urlVideo = getId(data.url.trim());
      var embedHtml =
        '<p style="text-align: center;" data-mce-style="text-align: center;"><iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
        urlVideo +
        '"  frameborder="1" allowfullscreen class="youtube-video"></iframe></p>';
      resolve({ html: embedHtml });
    },
    images_upload_handler: function (blobInfo, success, failure) {
      var xhr, formData;

      xhr = new XMLHttpRequest();
      xhr.withCredentials = false;
      xhr.open("POST", ruta + "../../js/chat/functions/postAcceptor.php");

      xhr.onload = function () {
        var json;

        if (xhr.status != 200) {
          failure("HTTP Error: " + xhr.status);
          return;
        }

        json = JSON.parse(xhr.responseText);

        if (!json || typeof json.location != "string") {
          failure("Invalid JSON: " + xhr.responseText);
          return;
        }

        success(json.location);
      };

      formData = new FormData();
      formData.append("file", blobInfo.blob(), blobInfo.filename());

      xhr.send(formData);
    },
    content_style:
      "img {bottom: 0; left: 0; margin: auto; max-width: 100%;height: auto;} .mymention{ font-weight: 600; color: #1c87a0;} .overlay-esp{ display: none !important;}",
    /*   image_class_list: [
          {title: 'img-responsive', value: 'img-responsive'}
        ],*/
    setup: function (ed) {
      ed.on("init", function (ed) {
        tinyMCE.activeEditor.setContent(texto);
      }),
        ed.on("keydown", function (e) {
          if (e.keyCode == 13) {
            text = tinyMCE.editors[
              $("#txtActualizacionEdit_" + idActualizacion).attr("id")
            ]
              .getContent({ format: "text" })
              .trim(); //

            var separators = [" ", "\\\n"];
            var tokens = text.split(new RegExp(separators.join("|"), "g"));
            var indice = tokens.length;

            for (i = 0; i < indice; i++) {
              if (i == indice - 1) {
                myId = getId(tokens[i].trim());
                if (myId != "error") {
                  tokens[i] =
                    '<iframe width="100%" height="auto" src="//www.youtube.com/embed/' +
                    myId +
                    '" frameborder="1" allowfullscreen class="youtube-video"></iframe>';
                  var $body = $(tinymce.activeEditor.getBody());
                  $body.find("p:last").html("");
                  $body.find("p:last").before(tokens[i]);
                }
              }
            }
          }
        });
    },
  });

  tinyMCE.activeEditor.setContent(texto);
  if (modo == 2) {
    $(".responderEventos").css("display", "flex");
    $(".responderEventos_" + idActualizacion).css("display", "none");
  }
}

//Boton para actualizar Actualizaciones
$(document).on("click", ".botonActualizar", function (e) {
  var id = this.id;

  var element = $(this).closest(".ventanas").attr("id");

  var texto =
    tinyMCE.editors[
      $("#" + element + " #txtActualizacionEdit_" + id).attr("id")
    ].getContent();

  if (texto.trim() === "") {
    return;
  }

  var IDAlertas = $(
    tinyMCE.editors[$("#txtActualizacionEdit_" + id).attr("id")]
      .getContent()
      .trim()
  ).find(".mymention");
  var arrayAlertas = [];

  if (IDAlertas.length > 0) {
    for (var x = 0; x < IDAlertas.length; x++) {
      arrayAlertas[x] = IDAlertas[x]["dataset"].mentionId;
    }
  } else {
    arrayAlertas[0] = -1;
  }

  var myData = {
    IdActualizacion: id,
    Texto: texto,
    IDAlertas: arrayAlertas,
    IDUsuario: IDUsuario,
  };

  $.ajax({
    url: ruta + "../../js/chat/functions/editarActualizacion.php",
    type: "POST",
    data: myData,
    success: function (data, status, xhr) {
      if (data == "exito") {
        tinymce.remove("#" + element + " #txtActualizacionEdit_" + id);
        $("#" + element + " #txtActualizacionEdit_" + id).remove();
        $("#" + element + " #" + id).remove();
        $("#file-chat-" + id).remove();
        $(".responderEventos_" + id).css("display", "flex");
        $(".textoActualizacion_" + id).html(texto);

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial100", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Actualización modificada!",
        });
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial75",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se pudo modificar la actualización",
        });
      }
    },
  });
});

//boton cancelar de modificar actualizaciones
$(document).on("click", ".botonCancelar", function (e) {
  id = this.id;

  var element = $(this).closest(".ventanas").attr("id");

  tinymce.remove("#" + element + " #txtActualizacionEdit_" + id);
  $("#" + element + " #txtActualizacionEdit_" + id).remove();
  $(".editarBotones").remove();
  $("#file-chat-" + id).remove();
  $("#" + element + " .textoActualizacion_" + id).html(TextoOriginal);
  $("#" + element + " .responderEventos_" + id).css("display", "flex");
});

//boton cancelar de responder actualizaciones
$(document).on("click", ".btnCancelarActualizacionResponder", function (e) {
  id = this.id;

  var element = $(this).closest(".ventanas").attr("id");

  tinymce.remove("#" + element + " #inputResponder_" + id);
  $("#" + element + " #responder_" + id).html("");
});

//Boton para responder Actualizaciones
$(document).on("click", ".btnEditarActualizacionResponder ", function (e) {
  var id = this.id;
  var element = $(this).closest(".ventanas").attr("id");

  var texto = tinyMCE.editors[
    $("#" + element + " #inputResponder_" + id).attr("id")
  ]
    .getContent()
    .trim();

  if (texto.trim() === "") {
    return;
  }

  var IDAlertas = $(
    tinyMCE.editors[$("#inputResponder_" + id).attr("id")].getContent().trim()
  ).find(".mymention");
  var arrayAlertas = [];

  if (IDAlertas.length > 0) {
    for (var x = 0; x < IDAlertas.length; x++) {
      arrayAlertas[x] = IDAlertas[x]["dataset"].mentionId;
    }
  } else {
    arrayAlertas[0] = -1;
  }

  $.ajax({
    url: ruta + "../../js/chat/functions/responderActualizacion.php",
    type: "POST",
    data: {
      Texto: texto,
      IDUsuario: IDUsuario,
      IDAlertas: arrayAlertas,
      IDTarea: IDTareaChat,
      IDChatPadre: id,
    },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      console.log(datos);
      if (parseInt(datos.res) > 0) {
        tinymce.remove("#" + element + " #inputResponder_" + id);
        $("#" + element + " #txtActualizacionEdit_" + id).remove();
        $("#" + element + " #responder_" + id).html("");

        /*Agregar elemento al chat*/
        var nuevo_responder =
          "" +
          '<div class="actualizacionRespuestaCard actualizacion_' +
          datos.res +
          '">' +
          '<div class="row">' +
          '<div class="col-md-12" style="position: relative;">' +
          '<div class="wrapper-responder">' +
          '<div class="col-md-12 col-xs-12">' +
          '<div style="float: left;">' +
          '<span data-toggle="tooltip" title="" class="tooltip_chat" data-original-title="' +
          nombreusuario +
          '">' +
          '<img src="' +
          ruta +
          '../../img/chat/users.svg" class="user-img img-responsive" width="25px">' +
          "</span>" +
          '<span class="nombre-usuario">' +
          nombreusuario +
          "</span>" +
          '<span data-toggle="tooltip" class="tooltip_chat estado-activo estado-circulo" data-original-title="Activo"></span>' +
          "</div>" +
          '<div style="float: right;">' +
          '<span class="reloj-responder"><img src="' +
          ruta +
          '../../img/chat/reloj.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="' +
          datos.fecha +
          '"><span class="panel-header">Ahora</span></span>' +
          '<button type="button" id="editarRespuesta" onclick="editarActualizacion(this,' +
          datos.res +
          ',2)">' +
          '<img src="' +
          ruta +
          '../../img/timdesk/edit.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="Editar respuesta">' +
          "</button>" +
          '<button type="button" id="eliminarEspecial" class="botonCancelar eliminarEspecial" onclick="eliminarActualizacion(' +
          datos.res +
          ');">' +
          '<img src="' +
          ruta +
          '../../img/timdesk/delete.svg" class="img-responsive tooltip_chat" width="15px" data-toggle="tooltip" title="" data-original-title="Eliminar respuesta">' +
          "</button>" +
          "</div>" +
          "<br><br>" +
          '<div class="textoActualizacion_' +
          datos.res +
          ' estiloTextoMCE">' +
          texto +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          '<div class="row responderEventos responderEventos_' +
          datos.res +
          '">' +
          '<div class="col-md-12" style="display: flex;">' +
          '<div class="col-md-4 text-center">' +
          '<span data-toggle="tooltip" title="" class="tooltip_chat likeTitle_' +
          datos.res +
          '" data-original-title="0 me gusta">' +
          '<a href="#" class="sin-enlace" onclick="megusta(this,' +
          datos.res +
          ',2)">' +
          '<img src="' +
          ruta +
          '../../img/chat/like_blue.svg" class="user-img img-responsive likeimage_' +
          datos.res +
          '" width="16px"><br>' +
          "</a>" +
          "</span>" +
          "</div>" +
          '<div class="col-md-4 text-center">' +
          '<span data-toggle="tooltip" title="" class="tooltip_chat dislikeTitle_' +
          datos.res +
          '" data-original-title="0 no me gusta">' +
          '<a href="#" class="sin-enlace" onclick="nomegusta(this,' +
          datos.res +
          ',2)">' +
          '<img src="' +
          ruta +
          '../../img/chat/dislike_blue.svg" class="user-img img-responsive dislikeimage_' +
          datos.res +
          '" width="16px"><br>' +
          "</a>" +
          "</span>" +
          "</div>" +
          '<div class="col-md-4">' +
          '<div class="visto">' +
          '<a href="#" class="visto-sin" data-toggle="modal" data-target="#verUsuariosVisto" onclick="mostrarUsuariosVistos(' +
          datos.res +
          ')">' +
          '<img src="' +
          ruta +
          '../../img/chat/visto.svg" class="img-responsive ver" width="20px"> Visto por 1' +
          "</a>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>" +
          "</div>";

        $(".agregarResponder_" + id).append(nuevo_responder);

        $(".tooltip_chat").tooltip();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial80", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Respuesta agregada a la actualización!",
        });
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial90",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Falló la respuesta a la actualización",
        });
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
});

/*BUSCAR EN ACTIVIDAD*/

$(document).ready(function () {
  $(document).on("click", ".btn-filter", function (e) {
    var fecha = $(this).data("target").toLowerCase();

    $("#filter-date").val(fecha);
    $("#filter-date").trigger("hashchange");
  });

  $(document).on("click", ".btn-filter-tipo", function (e) {
    var tipo = $(this).data("target").toLowerCase();

    $("#filter-type").val(tipo);
    $("#filter-type").trigger("hashchange");
  });

  $(document).on("click", ".btn-filter-etapa", function (e) {
    var etapa = $(this).data("target").toLowerCase();

    $("#filter-phase").val(etapa);
    $("#filter-phase").trigger("hashchange");
  });
});

//Boton para compartir Actualizaciones
$(document).on("click", "#btnEnviarActualizacion", function (e) {
  var id = $("#idEnviar").val();

  if (
    $("#enviarMails").val().trim() == "[]" ||
    $("#enviarMails").val().trim() == ""
  ) {
    Lobibox.notify("warning", {
      size: "mini",
      rounded: true,
      delay: 3000,
      delayIndicator: false,
      position: "center top", //or 'center bottom'
      icon: false,
      img: ruta + "../../img/chat/notificacion_error.svg",
      msg: "Agrega al menos un correo electrónico",
    });
    return;
  }

  $("#btnEnviarActualizacion").prop("disabled", true);
  $("#loading").show();

  var emails = $("#enviarMails").val().trim();

  $.ajax({
    url: ruta + "../../js/chat/functions/compartirActualizaciones.php",
    type: "POST",
    data: { ChatId: id, IDUsuario: IDUsuario, Emails: emails },
    success: function (data, status, xhr) {
      if (data == "1") {
        $("#centralCompartirActualizacion").modal("toggle");
        $("#btnEnviarActualizacion").prop("disabled", false);
        $("#loading").hide();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Correos electrónicos enviados!",
        });
      } else {
        $("#btnEnviarActualizacion").prop("disabled", false);
        $("#loading").hide();

        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se pudieron enviar los correos.",
        });
      }
    },
  });
});

/*******************************SISTEMA DE ALERTAS***********************************/
function agregarAlerta(tiempo, idactualizacion) {
  var myData = {
    IDUsuario: IDUsuario,
    Tiempo: tiempo,
    IDActualizacion: idactualizacion,
  };

  $.ajax({
    url: ruta + "../../js/chat/functions/agregarAlarma.php",
    type: "POST",
    data: myData,
    success: function (data, status, xhr) {
      if (data != "0" && data != "1") {
        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial70", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "Alerta agregada el " + data,
        });
      }
      if (data == "0") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial100",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se puede agregar la alerta",
        });
      }
      if (data == "1") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "right top especial25",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se puede agregar la alerta, ya esta una alerta en esa hora.",
        });
      }
    },
  });
}

$(document).ready(function () {
  setInterval(notificarAlertas, 10000);

  function notificarAlertas() {
    var myData = { IDUsuario: IDUsuario };

    $.ajax({
      url: ruta + "../../js/chat/functions/notificarAlarma.php",
      type: "POST",
      data: myData,
      success: function (data, status, xhr) {
        var datos = JSON.parse(data);
        if (datos.nc > 0) {
          alertify.alert(
            "¡ALERTA!",
            "<center>La alerta que programaste es ahora, puedes ir a revisarla.</center>",
            function () {
              //datos.FKChat  se usara para redirigir  a la tarea a revisarla
              $(location).attr(
                "href",
                urlDireccionamiento +
                  "index.php?id=" +
                  idProyectoUrl +
                  "&idIndividual=" +
                  datos.FKChat
              );
            }
          );
        }
      },
    });
  }
});

/*******************************FIN SISTEMA DE ALERTAS***********************************/

function anclarChat(id) {
  $.ajax({
    url: ruta + "../../js/chat/functions/anclarChat.php",
    type: "POST",
    data: { IDActualizacion: id },
    success: function (data, status, xhr) {
      $("#VentanaActualizaciones .espacio_" + id).prependTo(
        "#NuevasActualizaciones"
      );
      $("#VentanaActualizaciones .agregarResponder_" + id).prependTo(
        "#NuevasActualizaciones"
      );
      $("#VentanaActualizaciones .actualizacion_" + id).prependTo(
        "#NuevasActualizaciones"
      );

      Lobibox.notify("success", {
        size: "mini",
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        position: "right top especial115", //or 'center bottom'
        icon: false,
        img: ruta + "../../img/timdesk/checkmark.svg",
        msg: "¡Actualización anclada!",
      });
    },
  });
}

var elementoListener;
var idEquipoGlb;
function desplegarEquipo(element, idEquipo, idProyecto) {
  var actividad = $(element).attr("class");
  idEquipoGlb = idEquipo;

  $(".tooltip_chat").tooltip("hide");
  $(".contraerEquipo").attr(
    "src",
    ruta + "../../img/chat/menu_desplegable.svg"
  );
  $(".contraerEquipo").attr("data-original-title", "Desplegar");

  if (actividad == "color-blue enlaceIntegrantes inactivo") {
    $(".enlaceIntegrantes").attr(
      "class",
      "color-blue enlaceIntegrantes inactivo"
    );
    $(".mostrarIntegrantesClass").hide();
    $(element).attr("class", "color-blue enlaceIntegrantes activo");
    $("#imgEquipo_" + idEquipo).attr(
      "src",
      ruta + "../../img/chat/menu_contraer.svg"
    );
    $("#imgEquipo_" + idEquipo).attr("data-original-title", "Contraer");
    $(".tooltip_chat").tooltip();

    $.ajax({
      url: ruta + "../../js/chat/functions/desplegarEquipo.php",
      type: "POST",
      data: { idEquipo: idEquipo, idProyecto: idProyecto, ruta: ruta },
      success: function (data, status, xhr) {
        var datos = JSON.parse(data);
        $("#mostrarIntegrantes_" + idEquipo).fadeIn(2000);
        $("#mostrarIntegrantes_" + idEquipo).html(datos.html1);
        $("#idEncargado").val(datos.idEncargado);
        $("#claveEncargado").val(datos.claveEncargado);

        $.getScript(ruta + "../../js/chat/jquery.sumoselect.js", function () {
          $(function () {
            $(".search_test").SumoSelect({
              search: true,
              searchText: "Ingresa el nombre del usuario",
            });
          });
        });

        $(".tooltip_chat").tooltip();
      },
    });
  } else {
    $(element).attr("class", "color-blue enlaceIntegrantes inactivo");
    $("#imgEquipo_" + idEquipo).attr(
      "src",
      ruta + "../../img/chat/menu_desplegable.svg"
    );
    $("#mostrarIntegrantes_" + idEquipo).hide();
  }
}

function eliminarChat() {
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
      title: "¿Deseas eliminar el chat de esta tarea?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar tarea</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: ruta + "../../js/chat/functions/eliminarChat.php",
          type: "POST",
          data: { idTarea: IDTareaChat, IDUsuario: IDUsuario },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#fluidModalRightSuccess").modal("toggle");

              swalWithBootstrapButtons.fire(
                "!Eliminado!",
                "Has eliminado el chat",
                "success"
              );
            }
            if (data == "negado") {
              swalWithBootstrapButtons.fire(
                "Alerta",
                "Solo el responsable del proyecto puede eliminar el chat de la tarea.",
                "error"
              );
            }
            if (data != "exito" && data != "negado") {
              swalWithBootstrapButtons.fire(
                "Alerta",
                "Se perdió la conexión, vuelve a intentarlo por favor.",
                "error"
              );
            }
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function eliminarIntegranteEquipo(id) {
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
      title: "¿Deseas eliminar al integrante del equipo?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter">Eliminar integrante</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        var ids = id.split("_");
        var index = ids[0];
        var idUsuario = ids[1];
        var idEquipo = ids[2];

        $.ajax({
          url: ruta + "../../js/chat/functions/eliminarIntegranteEquipo.php",
          type: "POST",
          data: {
            idEquipo: idEquipo,
            idUsuario: idUsuario,
            idProyecto: idProyectoUrl,
          },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#idusuario_" + idUsuario).remove();
              $("select#agregarIntegrantesUn_" + idEquipo)[0].sumo.enableItem(
                index
              );

              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Integrante eliminado del equipo",
              });
            }
            if (data == "existe") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "No puedes eliminar del equipo al encargado del proyecto.",
              });
            }
            if (data == "fallo") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Ocurrió un error al eliminar.",
              });
            }
          },
        });

        $(".tooltip_chat").tooltip("hide");
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

$(document).on("click", "#agregarIntegranteBtn", function (e) {
  var id = $("#agregarIntegrantesUn_" + idEquipoGlb + " option:selected").val();
  var ids = id.split("_");
  var idEquipo = ids[0];
  var idUsuario = ids[1];
  var index = ids[2];

  $.ajax({
    url: ruta + "../../js/chat/functions/agregarIntegrante.php",
    type: "POST",
    data: {
      idEquipo: idEquipo,
      idUsuario: idUsuario,
      idProyecto: idProyectoUrl,
    },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      if (datos.estado == "exito") {
        var integranteNuevo =
          '<div class="row elemento-equipo" id="idusuario_' +
          idUsuario +
          '">' +
          '<span class="col-md-8">' +
          '<span class="tooltip_chat" data-toggle="tooltip" title="' +
          datos.nombreEmpleado +
          '"><img src="' +
          ruta +
          '../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span> ' +
          ' <a href="#" class="color-blue">' +
          datos.nombreEmpleado +
          "</a>" +
          "</span>" +
          '<span class="col-md-4" align="right">' +
          '<a href="#" class="tooltip_chat" data-toggle="tooltip" title="' +
          datos.nombreEmpleado +
          ' miembro del equipo" id="0" onclick="asignarEncargado(' +
          "this,'" +
          index +
          "_" +
          idProyectoUrl +
          "_" +
          idUsuario +
          "_" +
          idEquipo +
          "'" +
          ');">' +
          '<img src="' +
          ruta +
          '../../img/chat/estrella_inactiva.png" id="estrella_' +
          idUsuario +
          '" class="img-responsive quitarestrella">' +
          "</a> " +
          ' <a href="#" id="eliminarAct_' +
          idUsuario +
          '" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="Eliminar miembro" onclick="eliminarIntegranteEquipo(' +
          "'" +
          index +
          "_" +
          idUsuario +
          "_" +
          idEquipo +
          "'" +
          ');"><img src="' +
          ruta +
          '../../img/chat/eliminar_usuario.png" class="img-responsive"></a>' +
          "</span>" +
          "</div>";
        //\''.$clave.'_'.$ri['PKUsuario'].'_'.$idEquipo.'\'
        $("#equipo_" + idEquipo).append(integranteNuevo);

        //disables the item at index 2
        $("select#agregarIntegrantesUn_" + idEquipo)[0].sumo.disableItem(index);
        $(".tooltip_chat").tooltip();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "¡Integrante agregado al equipo!",
        });
      }
      if (datos.estado == "existe") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se puede agregar a este usuario por que ya se agregó como integrante del proyecto.",
        });
      }
      if (datos.estado == "fallo") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Ocurrió un error al agregar.",
        });
      }
    },
  });
});

function asignarEncargado(element, id) {
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
      title: "¿Deseas seleccionar un nuevo líder del proyecto?",
      html: '<span class="yellow-cl">Al hacerlo perderás los privilegios del líder del proyecto</span>',
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Aceptar</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        var modo = $(element).attr("id");
        var ids = id.split("_");

        if (modo == 0) {
          var idClave = ids[0];
          var idProyecto = ids[1];
          var idUsuario = ids[2];
          var idEquipo = ids[3];
        } else {
          var idClave = ids[0];
          var idProyecto = ids[1];
          var idUsuario = ids[2];
        }

        $.ajax({
          url: ruta + "../../js/chat/functions/asignarEncargado.php",
          type: "POST",
          data: { idProyecto: idProyecto, idUsuario: idUsuario },
          success: function (data, status, xhr) {
            if (data == "exito") {
              idEncargado = $("#idEncargado").val();
              claveEncargado = $("#claveEncargado").val();

              $(".quitarestrella").attr(
                "src",
                ruta + "../../img/chat/estrella_inactiva.png"
              );
              $("#estrella_" + idUsuario).attr(
                "src",
                ruta + "../../img/chat/estrella_activa.png"
              );

              $("#idEncargado").val(idUsuario);
              $("#claveEncargado").val(idClave);

              $("#centralModalSuccess").modal("toggle");

              Lobibox.notify("success", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/timdesk/checkmark.svg",
                msg: "¡Has seleccionado un nuevo encargado del proyecto!",
              });
            }
            if (data == "fallo") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: " Ocurrió un error al seleccionar al encargado del proyecto.",
              });
            }
          },
        });
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

function mostrarEquipos() {
  var encargado;
  $.ajax({
    url: ruta + "../../js/chat/functions/mostrarEquipos.php",
    type: "POST",
    data: { idProyecto: idProyectoUrl, IDUsuario: IDUsuario, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);

      if (datos.encargado < 1) {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Sólo el líder del proyecto puede modificar a los integrantes.",
        });
      } else {
        $("#mostrarEquipos").html(datos.html);

        $.getScript(ruta + "../../js/chat/jquery.sumoselect.js", function () {
          $(function () {
            $(".search_equipos").SumoSelect({
              search: true,
              searchText: "Ingresa el equipo",
            });
          });
        });

        $("#centralModalSuccess").modal("toggle");
      }
    },
  });

  $.ajax({
    url: ruta + "../../js/chat/functions/mostrarIntegrantes.php",
    type: "POST",
    data: { idProyecto: idProyectoUrl, ruta: ruta },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      $("#mostrarIntegrantes").html(datos.html);
      $("#idEncargado").val(datos.idEncargado);
      $("#claveEncargado").val(datos.claveEncargado);

      $.getScript(ruta + "../../js/chat/jquery.sumoselect.js", function () {
        $(function () {
          $(".search_integrantes").SumoSelect({
            search: true,
            searchText: "Ingresa el nombre del usuario",
          });
        });
      });
      $(".tooltip_chat").tooltip();
    },
  });
}

function mostrarUsuariosVistos(idActualizacion) {
  $.ajax({
    url: ruta + "../../js/chat/functions/mostrarUsuarios.php",
    type: "POST",
    data: { idActualizacion: idActualizacion, ruta: ruta },
    success: function (data, status, xhr) {
      console.log(data);
      $("#verUsuarios").html(data);
    },
    error: function (error) {
      console.log(error);
    },
  });
}

$(document).on("click", "#agregarEquipoBtn", function (e) {
  var id = $("#equiposAgregar option:selected").val();
  var ids = id.split("_");
  var idEquipo = ids[0];
  var index = ids[1];
  var nombreEquipo = $("#equiposAgregar option:selected").text();

  $.ajax({
    url: ruta + "../../js/chat/functions/agregarEquipo.php",
    type: "POST",
    data: { idEquipo: idEquipo, idProyecto: idProyectoUrl },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      if (datos.estado == "exito") {
        var equipoNuevo =
          "" +
          '<div id="equipoId_' +
          idEquipo +
          '" class="row" style="margin-bottom: 5px;">' +
          '<div class="col-md-12">' +
          '<a href="#" onclick="eliminarEquipoProy(' +
          idEquipo +
          "," +
          index +
          ')" ><span class="plus-team"><img src="' +
          ruta +
          '../../img/chat/eliminar_equipo.svg" class="img-responsive tooltip_chat" width="20px" style="margin-bottom: 3px;margin-right: 5px;" data-toggle="tooltip" title="Eliminar equipo" /></span> </a>' +
          '<a href="#" class="color-blue enlaceIntegrantes inactivo" onclick="desplegarEquipo(this,' +
          idEquipo +
          "," +
          idProyectoUrl +
          ')" id="0">' +
          '<img src="' +
          ruta +
          '../../img/chat/equipo.svg" class="img-responsive" width="20px" style="margin-bottom: 2px;margin-right: 5px;"> ' +
          nombreEquipo +
          '<span class="plus-team"> <img src="' +
          ruta +
          '../../img/chat/menu_desplegable.svg" class="img-responsive tooltip_chat" width="20px" style="margin-bottom: 2px;margin-right: 5px;" id="imgEquipo_' +
          idEquipo +
          '" data-toggle="tooltip" title="Desplegar"> </span>' +
          "</a>" +
          "</div>" +
          '<div class="col-md-12 mostrarIntegrantesClass" id="mostrarIntegrantes_' +
          idEquipo +
          '">' +
          "</div>" +
          "</div>";
        $("#nuevosEquipos").append(equipoNuevo);

        //disables the item at index 2
        $("select#equiposAgregar")[0].sumo.disableItem(index);
        $(".tooltip_chat").tooltip();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: ruta + "../../img/timdesk/checkmark.svg",
          msg: "Equipo agregado al proyecto!",
        });
      } else {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Ocurrió un error al agregar.",
        });
      }
    },
  });
});

function eliminarEquipoProy(idEquipo, index) {
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
      title: "¿Deseas eliminar al equipo del proyecto?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: '<span class="verticalCenter">Eliminar equipo</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: ruta + "../../js/chat/functions/eliminarEquipo.php",
          type: "POST",
          data: { idEquipo: idEquipo, idProyecto: idProyectoUrl },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#equipoId_" + idEquipo).remove();
              $("select#equiposAgregar")[0].sumo.enableItem(index);

              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Equipo eliminado del proyecto",
              });
            } else {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Ocurrió un error al eliminar.",
              });
            }
          },
        });

        $(".tooltip_chat").tooltip("hide");
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

$(document).on("click", "#agregarIntegranteInd", function (e) {
  var id = $("#integranteAgregar option:selected").val();
  var ids = id.split("_");
  var idUsuario = ids[0];
  var index = ids[1];
  var nombreEmpleado = $("#integranteAgregar option:selected").text().trim();

  $.ajax({
    url: ruta + "../../js/chat/functions/agregarIntegranteIndividual.php",
    type: "POST",
    data: { idUsuario: idUsuario, idProyecto: idProyectoUrl },
    success: function (data, status, xhr) {
      var datos = JSON.parse(data);
      if (datos.estado == "exito") {
        var integranteNuevo =
          '<div class="row elemento-equipo" id="idusuario_' +
          idUsuario +
          '">' +
          '<span class="col-md-8">' +
          '<span class="tooltip_chat" data-toggle="tooltip" title="' +
          nombreEmpleado +
          '"><img src="' +
          ruta +
          '../../img/chat/users.svg" class="user-img img-responsive" width="25px"></span> ' +
          ' <a href="#" class="color-blue">' +
          nombreEmpleado +
          "</a>" +
          "</span>" +
          '<span class="col-md-4" align="right">' +
          '<a href="#" class="tooltip_chat" data-toggle="tooltip" title="' +
          nombreEmpleado +
          ' miembro del proyecto" id="1" onclick="asignarEncargado(' +
          "this,'" +
          index +
          "_" +
          idProyectoUrl +
          "_" +
          idUsuario +
          "'" +
          ');">' +
          '<img src="' +
          ruta +
          '../../img/chat/estrella_inactiva.png" id="estrella_' +
          idUsuario +
          '" class="img-responsive quitarestrella">' +
          "</a> " +
          ' <a href="#" id="eliminarActInd_' +
          idUsuario +
          '" class="sin-enlace tooltip_chat" data-toggle="tooltip" title="Eliminar miembro" onclick="eliminarIntegranteProyecto(' +
          "'" +
          index +
          "_" +
          idUsuario +
          "_" +
          idProyectoUrl +
          "'" +
          ');"><img src="' +
          ruta +
          '../../img/chat/eliminar_usuario.png" class="img-responsive"></a>' +
          "</span>" +
          "</div>";

        $("#nuevoIntegranteProyecto").append(integranteNuevo);

        //disables the item at index 2
        $("select#integranteAgregar")[0].sumo.disableItem(index);
        $(".tooltip_chat").tooltip();

        Lobibox.notify("success", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top", //or 'center bottom'
          icon: false,
          img: "../../../img/timdesk/checkmark.svg",
          msg: "¡Integrante agregado al proyecto!",
        });
      }
      if (datos.estado == "existe") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "No se puede agregar al usuario por que ya se agregó a uno de los equipos del proyecto.",
        });
      }
      if (datos.estado == "fallo") {
        Lobibox.notify("warning", {
          size: "mini",
          rounded: true,
          delay: 3000,
          delayIndicator: false,
          position: "center top",
          icon: false,
          img: ruta + "../../img/chat/notificacion_error.svg",
          msg: "Ocurrió un error al agregar.",
        });
      }
    },
  });
});

function eliminarIntegranteProyecto(id) {
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
      title: "¿Deseas eliminar al integrante del proyecto?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText:
        '<span class="verticalCenter">Eliminar integrante</span>',
      cancelButtonText: '<span class="verticalCenter">Cancelar</span>',
      reverseButtons: false,
    })
    .then((result) => {
      if (result.isConfirmed) {
        var ids = id.split("_");
        var index = ids[0];
        var idUsuario = ids[1];
        var idProyecto = ids[2];

        $.ajax({
          url: ruta + "../../js/chat/functions/eliminarIntegranteProyecto.php",
          type: "POST",
          data: { idProyecto: idProyecto, idUsuario: idUsuario },
          success: function (data, status, xhr) {
            if (data == "exito") {
              $("#idusuario_" + idUsuario).remove();
              $("select#integranteAgregar")[0].sumo.enableItem(index);

              Lobibox.notify("error", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Integrante eliminado del proyecto",
              });
            }
            if (data == "existe") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top", //or 'center bottom'
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "No puedes eliminar del proyecto al encargado.",
              });
            }
            if (data == "fallo") {
              Lobibox.notify("warning", {
                size: "mini",
                rounded: true,
                delay: 3000,
                delayIndicator: false,
                position: "center top",
                icon: false,
                img: ruta + "../../img/chat/notificacion_error.svg",
                msg: "Ocurrió un error al eliminar.",
              });
            }
          },
        });

        $(".tooltip_chat").tooltip("hide");
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
      }
    });
}

//Agregar burbujas de texto en los elementos con tooltip
$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
  var url = new URL(window.location.href);
  var chatBool = !!url.searchParams.get("chat");
  if(chatBool) {
    var taskId = url.searchParams.get("task");
    loadChat(taskId, 1);
  }
});
