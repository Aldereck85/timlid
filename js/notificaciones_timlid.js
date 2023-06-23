function loadAlertsNoti(element, usuario, ruta, edit) {
  var html = "";
  var noNoti;

  $.ajax({
    url: ruta + "../php_notificaciones/funciones.php",
    data: {
      clase: "get_data",
      funcion: "get_notiTotal",
      data: usuario,
      ruta: ruta,
    },
    dataType: "json",
    success: function (respuesta) {

      //console.log("longitud:",respuesta.length);
      html +=
        '<li id="notificationContainer" class="nav-item dropdown no-arrow mx-1">' +
        '<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
        '<img src="../' +
        ruta +
        'img/notificaciones/ICONO ALERTAS_Mesa de trabajo 1.svg" width="25px">' +
        '<span id="contadorTareas" class="badge badge-pill badge-counter badge-circle">' +
        respuesta.length +
        "</span>" +
        "</a>";

      //console.log("respuesta desde get_notiTotal:",respuesta);
      //console.log("numero de notificaciones: ",parseInt($('#contadorTareas').text()));
      var noNotificaciones = respuesta.length;
      html +=
        '<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">' +
        '<h3 class="dropdown-header" style="background:#006dd9;color:white;text-transform: none !important;letter-spacing: 2px;font-size:14px;">Notificaciones</h3>';

      //console.log("tipo de notificacion:",respuesta[0]['FKTipoNotificacion']);

      if (noNotificaciones > 0) {
        var contador = 0;
        $.each(respuesta, function (i) {
          switch (respuesta[i]["FKTipoNotificacion"]) {
            case 1:
              date = new Date(respuesta[i]["FechaCreacion"]);
              //console.log("date:",date);
              day = ("0" + date.getDate()).slice(-2);
              month = ("0" + (date.getMonth() + 1)).slice(-2);
              year = date.getFullYear();

              fecha = day + "/" + month + "/" + year;
              idNoti = respuesta[i]["PKTareaNotificacion"];
              tarea = respuesta[i]["Tarea"];
              idProyecto = respuesta[i]["PKProyecto"];
              proyecto = respuesta[i]["Proyecto"];
              mensaje =
                "Se te asignó la tarea " + tarea + " del proyecto " + proyecto;
              html +=
                '<a class="dropdown-item d-flex align-items-center notification tasks-noti" href="#" data-id="' +
                idNoti +
                '" data-project="' +
                idProyecto +
                '">' +
                '<div class="mr-3">' +
                '<div class="icon-circle">' +
                '<img src="../' +
                ruta +
                'img/notificaciones/ICONO TAREAS_Mesa de trabajo 1.svg" width="25px">' +
                "</div>" +
                "</div>" +
                '<div id="notification-latest">' +
                '<div id="fechaTarea" class="date-notification">' +
                fecha +
                "</div>" +
                '<span id="tarea" class="font-weight-bold">' +
                mensaje +
                "</span>" +
                "</div>" +
                "</a>";
              break;
            case 2:
              date = new Date(respuesta[i]["FechaCreacion"]);
              //console.log("date:",date);
              day = ("0" + date.getDate()).slice(-2);
              month = ("0" + (date.getMonth() + 1)).slice(-2);
              year = date.getFullYear();

              fecha = day + "/" + month + "/" + year;
              idNoti = respuesta[i]["PKChat_Notificaciones"];
              tarea = respuesta[i]["Tarea"];
              idTarea = respuesta[i]["PKTarea"];
              idProyecto = respuesta[i]["PKProyecto"];
              proyecto = respuesta[i]["Proyecto"];
              mensaje =
                "Tienes una nueva conversación en la tarea " +
                tarea +
                " del proyecto " +
                proyecto;

              html +=
                '<a class="dropdown-item d-flex align-items-center notification chats-noti" href="#" data-id1="' +
                idNoti +
                '" data-project="' +
                idProyecto +
                '" data-task="' +
                idTarea +
                '">' +
                '<div class="mr-3">' +
                '<div class="icon-circle">' +
                '<img src="../' +
                ruta +
                'img/notificaciones/ICONO CHAT_Mesa de trabajo 1.svg" width="25px">' +
                "</div>" +
                "</div>" +
                '<div id="notification-latest">' +
                '<div id="fechaTarea" class="date-notification">' +
                fecha +
                "</div>" +
                '<span id="tarea" class="font-weight-bold">' +
                mensaje +
                "</span>" +
                "</div>" +
                "</a>";
              break;
            case 3:
              date = new Date(respuesta[i]["FechaCreacion"]);
              //console.log("date:",date);
              day = ("0" + date.getDate()).slice(-2);
              month = ("0" + (date.getMonth() + 1)).slice(-2);
              year = date.getFullYear();

              fecha = day + "/" + month + "/" + year;
              subTarea = respuesta[i]["SubTarea"];
              tarea = respuesta[i]["Tarea"];
              idTarea = respuesta[i]["PKTarea"];
              idNoti = respuesta[i]["PKSubTareaNotificacion"];
              idproyecto = respuesta[i]["PKProyecto"];
              proyecto = respuesta[i]["Proyecto"];
              mensaje =
                "Se te asignó la subtarea " +
                subTarea +
                " de la tarea " +
                tarea +
                " del proyecto " +
                proyecto;

              html +=
                '<a class="dropdown-item d-flex align-items-center notification subtasks-noti" href="#" data-id="' +
                idNoti +
                '" data-project="' +
                idproyecto +
                '">' +
                '<div class="mr-3">' +
                '<div class="icon-circle">' +
                '<img src="../' +
                ruta +
                'img/notificaciones/ICONO SUBTAREAS_azul-01.svg" width="25px">' +
                "</div>" +
                "</div>" +
                '<div id="notification-latest">' +
                '<div id="fechaTarea" class="date-notification">' +
                fecha +
                "</div>" +
                '<span id="tarea" class="font-weight-bold">' +
                mensaje +
                "</span>" +
                "</div>" +
                "</a>";
              break;

            case 4:
              date = new Date(respuesta[i]["FechaCreacion"]);
              //console.log("date:",date);
              day = ("0" + date.getDate()).slice(-2);
              month = ("0" + (date.getMonth() + 1)).slice(-2);
              year = date.getFullYear();

              fecha = day + "/" + month + "/" + year;
              verificacion = respuesta[i]["PKVerificacion"];
              tarea = respuesta[i]["Tarea"];
              idTarea = respuesta[i]["PKTarea"];
              idNoti = respuesta[i]["PKVerificacionNotificacion"];
              idProyecto = respuesta[i]["PKProyecto"];
              proyecto = respuesta[i]["Proyecto"];
              mensaje =
                "Se verificó la tarea " + tarea + " del proyecto " + proyecto;

              html +=
                '<a class="dropdown-item d-flex align-items-center notification checks" href="#" data-id="' +
                idNoti +
                '" data-project="' +
                idProyecto +
                '">' +
                '<div class="mr-3">' +
                '<div class="icon-circle">' +
                '<img src="../' +
                ruta +
                'img/notificaciones/ICONO CHECK_AZUL-01.svg" width="25px">' +
                "</div>" +
                "</div>" +
                '<div id="notification-latest">' +
                '<div id="fechaTarea" class="date-notification">' +
                fecha +
                "</div>" +
                '<span id="tarea" class="font-weight-bold">' +
                mensaje +
                "</span>" +
                "</div>" +
                "</a>";

              break;
          }
          contador++;
        });
      } else {
        html +=
          '<a class="dropdown-item d-flex align-items-center notification" href="#">' +
          '<div class="mr-3">' +
          '<div class="icon-circle bg-white">' +
          '<img src="../' +
          ruta +
          'img/notificaciones/ICONO ALERTA_Mesa de trabajo 1.svg" width="25px">' +
          "</div>" +
          "</div>" +
          '<div id="notification-latest">' +
          '<span id="tarea" class="font-weight-bold">No hay notificaciones nuevas </span>' +
          "</div>" +
          "</a>";
      }

      html +=
        '<div class="show-center-noti">' +
        '<a href="' +
        ruta +
        'central_notificaciones/">Ir al centro de notificaciones</a>' +
        "</div>" +
        "</div>" +
        "</li>";

      $("#" + element + "").html(html);
    },
    error: function (error) {
      console.log(error);
    },
  });

  //console.log(html);
  //$('#'+element+'').html(html);
}

$(document).on("click", ".tasks-noti", function () {
  var usuario = $("#txtUsuario").val();
  var ruta = $("#txtRuta").val();
  var edit = $("#txtEdit").val();
  var idData = $(this).data("id");
  var data = "table=tasks&id=" + idData;
  var idProject = $(this).data("project");
  //console.log("idProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
  $.ajax({
    method: "POST",
    data: data,
    url: edit + "functions/edit_notification.php",
    success: function () {
      window.location.href =
        ruta + "tareas/timDesk/index.php?id=" + idProject + "";
      //console.log("tareas/timDesk/index.php?id="+idProject+"");
    },
  });
});

$(document).on("click", ".chats-noti", function () {
  var usuario = $("#txtUsuario").val();
  var ruta = $("#txtRuta").val();
  var edit = $("#txtEdit").val();
  var idData = $(this).data("id1");
  var data = "table=chats&id=" + idData;
  var idProject = $(this).data("project");
  var idTask = $(this).data("task");
  //console.log("idTarea: "+idTask+"\nidProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
  $.ajax({
    method: "POST",
    data: data,
    url: edit + "functions/edit_notification.php",
    success: function () {
      window.location.href =
        "<?=$rutes;?>tareas/timDesk/index.php?id=" +
        idProject +
        "&idTarea=" +
        idTask +
        "";
      //console.log("tareas/timDesk/index.php?id="+idProject+"&idTarea="+idTask+"");
    },
  });
});

$(document).on("click", ".subtasks-noti", function () {
  var usuario = $("#txtUsuario").val();
  var ruta = $("#txtRuta").val();
  var edit = $("#txtEdit").val();
  var idData = $(this).data("id");
  var data = "table=subtasks&id=" + idData;
  var idProject = $(this).data("project");
  //console.log("idProyecto: "+idProject+"\nData: "+data+"\nidData: "+idData);
  $.ajax({
    method: "POST",
    data: data,
    url: edit + "functions/edit_notification.php",
    success: function (data) {
      window.location.href =
        ruta + "tareas/timDesk/index.php?id=" + idProject + "";
      //console.log("tareas/timDesk/index.php?id="+idProject+"");
      //console.log(data);
    },
  });
});

$(document).on("click", ".checks", function () {
  var usuario = $("#txtUsuario").val();
  var ruta = $("#txtRuta").val();
  var edit = $("#txtEdit").val();
  var idData = $(this).data("id");
  var data = "table=checks&id=" + idData;
  var idProject = $(this).data("project");

  $.ajax({
    method: "POST",
    data: data,
    url: edit + "functions/edit_notification.php",
    success: function (data) {
      window.location.href =
        ruta + "tareas/timDesk/index.php?id=" + idProject + "";
      //console.log("tareas/timDesk/index.php?id="+idProject+"");
      //console.log(data);
    },
  });
});
