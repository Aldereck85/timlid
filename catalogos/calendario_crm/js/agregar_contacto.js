
let response;
$(document).ready(function () {
    initSelect();
    initSelectMedios();
    initSelectEstados();
    loadPropietarios();

    $('#btnCancelContacto').click(function () {
        window.location.href = "../contactos/";
    });
    $('#nombre').on('change', function () {
        $('#nombre').removeClass("is-invalid");
        $("#invalid-nombre").css("display", "none");
    });

    $('#apellido').on('change', function () {
        $('#apellido').removeClass("is-invalid");
        $("#invalid-apellido").css("display", "none");
    });

    $('#email').on('change', function () {
        $('#email').removeClass("is-invalid");
        $("#invalid-email").css("display", "none");
    });

    $('#btnGuardarContacto').click(function (e) {
        e.preventDefault();
        if ($("#formAgregarContactos")[0].checkValidity()) {

            //var validate_company = $("#invalid-empresa").css("display") === "block" ? false : true;
            //var validate_owner = $("#invalid-empresa").css("display") === "block" ? false : true;
            var validate_name = $("#invalid-nombre").css("display") === "block" ? false : true;
            var validate_lastname = $("#invalid-apellido").css("display") === "block" ? false : true;
            var validate_email = $("#invalid-email").css("display") === "block" ? false : true;


            var accion = 'agregarContacto';
            var nombre = $('#nombre').val();
            var apellido = $('#apellido').val();
            var empresa = $('#empresa').val();
            var email = $('#email').val();
            var puesto = $('#puesto').val();
            var telefono = $('#telefono').val();
            var celular = $('#celular').val();
            var propietario = $('#propietario').val();
            var estado_id = $('select[name=estado] option').filter(':selected').val();
            var medio_contacto_id = $('#campania').val();

            if (validate_name && validate_lastname && validate_email) {

                $.ajax({
                    type: "POST",
                    url: "app/controladores/ContactoController.php",
                    data: {
                        accion: accion, nombre: nombre, apellido: apellido, empresa: empresa,
                        email: email, puesto: puesto, telefono: telefono, celular: celular, propietario: propietario,
                        estado_id: estado_id, medio_contacto_campania_id: medio_contacto_id
                    },
                    //dataType: 'json',
                    success: function (data) {

                        response = JSON.parse(data);

                        if (response['success'] == true) {
                            Lobibox.notify("success", {
                                size: "mini",
                                rounded: true,
                                delay: 5000,
                                delayIndicator: false,
                                position: "center top",
                                icon: true,
                                img: "../../img/timdesk/warning_circle.svg",
                                msg: response['message'],
                            });
                            window.location.href = "../contactos/";
                            return;
                        }
                        if (response['error'] == true) {
                            if (response['tipo'] = 'name_contact') {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3100,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: response['message'],
                                });
                                return;
                            }
                            if (response['tipo'] = 'email_contact') {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 3100,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: response['message'],
                                });
                                return;
                            }
                        }
                    }

                });
            }
        } else {

            /* if (!$("#empresa").val()) {
               $("#invalid-empresa").css("display", "block");
               $("#empresa").addClass("is-invalid");
             }*/

            /*  if (!$('#propietario').val()) {
                $('#invalid-propietario').css('display', 'block');
                $("#propietario").addClass("is-invalid");
              }*/

            if (!$('#nombre').val()) {
                $('#invalid-nombre').css('display', 'block');
                $("#nombre").addClass("is-invalid");
            }

            if (!$('#apellido').val()) {
                $('#invalid-apellido').css('display', 'block');
                $("#apellido").addClass("is-invalid");
            }

            if (!$('#email').val()) {
                $('#invalid-email').css('display', 'block');
                $("#email").addClass("is-invalid");
            }

        }
    });


});

function initSelect() {
    new SlimSelect({
        select: "#propietario",
        placeholder: 'Seleccione un vendedor',
        searchPlaceholder: 'Buscar vendedor',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function initSelectMedios() {
    new SlimSelect({
        select: "#campania",
        placeholder: 'Seleccione un medios',
        searchPlaceholder: 'Buscar medios',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function initSelectEstados() {
    new SlimSelect({
        select: "#estado",
        placeholder: 'Seleccione un Estados',
        searchPlaceholder: 'Buscar Estados',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function loadPropietarios() {
    var accion = 'cargarPropietarios';

    $.ajax({
        url: "app/controladores/ContactoController.php",
        data: {
            accion: accion
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            $("#propietario").empty();
            $.each(response, function (key, value) {
                $("#propietario").append('<option value=' + value.id + '>' + value.nombre + '</option>');
            });
        }
    });
}




