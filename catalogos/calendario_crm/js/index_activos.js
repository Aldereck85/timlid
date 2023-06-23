let id;
let nombre;
$(document).ready(function () {

    initDataTable();
    initSelectEstado();
    loadSelectSelle();
    loadState();
    initSelect();
    initSelectPropietarioVendedor();

    $('#resultado').hide();


    $('#nav-tab-activos').click(function () {
        $("#tblContactosNuevos").DataTable().ajax.reload();
    });

    $('#empresaModal').on('change', function () {
        $('#empresaModal').removeClass("is-invalid");
        $("#invalid-nombre-comercial").css("display", "none");
    });

    $('#emailModal').on('change', function () {
        $('#empresaModal').removeClass("is-invalid");
        $("#invalid-email").css("display", "none");
    });

    $('#razon_socialModal').on('change', function () {
        $('#razon_socialModal').removeClass("is-invalid");
        $("#invalid-razon-social").css("display", "none");
    });

    $('#rfcModal').on('change', function () {
        $('#rfcModal').removeClass("is-invalid");
        $("#invalid-rfc").css("display", "none");
    });


    $('#codigo_postalModal').on('change', function () {
        $('#codigo_postalModal').removeClass("is-invalid");
        $("#invalid-codigo-postal").css("display", "none");
    });

    $('#agregarCliente').click(function (e) {
        e.preventDefault();
        if ($("#formCrearCliente")[0].checkValidity()) {

            var validate_empresa = $("#invalid-nombre-comercial").css("display") === "block" ? false : true;
            var validate_email = $("#invalid-email").css("display") === "block" ? false : true;
            var validate_razon_social = $("#invalid-razon-social").css("display") === "block" ? false : true;
            var validate_rfc = $("#invalid-rfc").css("display") === "block" ? false : true;
            var validate_codigo_postal = $("#invalid-codigo-postal").css("display") === "block" ? false : true;

            var accion = 'CrearCliente';

            var id = $('#contacto_id').val();
            var nombre = $('#nombreModal').val();
            var apellido = $('#apellidoModal').val();
            var puesto = $('#puestoModal').val();
            var celular = $('#celularModal').val();

            var empresa = $('#empresaModal').val();
            var medio_contacto_id = $('#campaniaModal').val();
            var vendedor = $('select[name=propietario] option').filter(':selected').val();
            var telefono = $('#telefonoModal').val();
            var email = $('#emailModal').val();

            var monto_credito = $('#montoModal').val();
            var dias_credito = $('#diasModal').val();

            var razon_social = $('#razon_socialModal').val();
            var rfc = $('#rfcModal').val();
            var municipio = $('#municipioModal').val();
            var colonia = $('#coloniaModal').val();
            var calle = $('#calleModal').val();
            var numero_exterior = $('#numero_exteriorModal').val();
            var numero_interior = $('#numero_interiorModal').val();
            var codigo_postal = $('#codigo_postalModal').val();
            var pais = $('#paisModal').val();
            var estado = $('select[name=estado] option').filter(':selected').val();

            if (validate_empresa && validate_email && validate_razon_social && validate_rfc && validate_codigo_postal) {
                $.ajax({
                    method: 'POST',
                    url: 'app/controladores/CrearClienteController.php',
                    data: {
                        accion: accion,
                        id: id,
                        nombre: nombre,
                        apellido: apellido,
                        puesto: puesto,
                        celular: celular,
                        empresa: empresa,
                        medio_contacto_id: medio_contacto_id,
                        vendedor: vendedor,
                        telefono: telefono,
                        email: email,
                        monto_credito: monto_credito,
                        dias_credito: dias_credito,
                        razon_social: razon_social,
                        rfc, rfc,
                        municipio: municipio,
                        colonia: colonia,
                        calle: calle,
                        numero_exterior: numero_exterior,
                        numero_interior: numero_interior,
                        codigo_postal: codigo_postal,
                        pais: pais,
                        estado: estado
                    },
                    //dataType: 'json',
                    success: function (data) {
                        response = JSON.parse(data);
                        if (response['success'] == true) {
                            id = response['cliente_id'];
                            nombre = response['nombre'];
                            confirmNuevoCliente(id, nombre);
                            return;
                        } else if (response['error'] == true) {
                            if (response['tipo'] == 'campos') {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 5000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: response['message'],
                                });
                                return;
                            } else if (response['tipo'] == 'rfc') {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 5000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: response['message'],
                                });
                                return;
                            } else if (response['tipo'] == 'contact_client') {
                                Lobibox.notify("error", {
                                    size: "mini",
                                    rounded: true,
                                    delay: 5000,
                                    delayIndicator: false,
                                    position: "center top",
                                    icon: true,
                                    img: "../../img/timdesk/warning_circle.svg",
                                    msg: response['message'],
                                });
                                confirmCreateClient();
                                return;
                            }

                        }

                    }

                });
            }
        } else {

            if (!$('#empresaModal').val()) {
                $('#invalid-nombre-comercial').css('display', 'block');
                $("#empresaModal").addClass("is-invalid");
            }

            if (!$('#emailModal').val()) {
                $('#invalid-email').css('display', 'block');
                $("#empresaModal").addClass("is-invalid");
            }

            if (!$('#razon_socialModal').val()) {
                $('#invalid-razon-social').css('display', 'block');
                $("#razon_socialModal").addClass("is-invalid");
            }
            if (!$('#rfcModal').val()) {
                $('#invalid-rfc').css('display', 'block');
                $("#rfcModal").addClass("is-invalid");
            }
            if (!$('#codigo_postalModal').val()) {
                $('#invalid-codigo-postal').css('display', 'block');
                $("#codigo_postalModal").addClass("is-invalid");
            }

        }
    });

    $("#rfcModal").on("keypress", function () {
        $input = $(this);
        setTimeout(function () {
            $input.val($input.val().toUpperCase());
        }, 25);
    })

    $('#btnCreateContactCliente').click(function () {

        var accion = 'CrearContactosCliente';

        var contacto_id = $('#contacto_id').val();

        var nombre = $('#nombreModal').val();
        var apellido = $('#apellidoModal').val();
        var puesto = $('#puestoModal').val();
        var celular = $('#celularModal').val();
        var telefono = $('#telefonoModal').val();
        var email = $('#emailModal').val();

        var facturacion = $('#Check1:checked').prop("checked") ? 1 : 0;
        var complemento = $('#Check2:checked').prop("checked") ? 1 : 0;
        var avisos = $('#Check3:checked').prop("checked") ? 1 : 0;
        var pagos = $('#Check4:checked').prop("checked") ? 1 : 0;


        $.ajax({
            method: 'POST',
            url: 'app/controladores/CrearClienteController.php',
            data: {
                accion: accion,
                contacto_id: contacto_id,
                facturacion: facturacion,
                complemento: complemento,
                avisos: avisos,
                pagos: pagos,
                nombre: nombre,
                apellido: apellido,
                puesto: puesto,
                celular: celular,
                telefono: telefono,
                email: email,

            },
            // dataType: 'json',
            success: function (data) {
                response = JSON.parse(data);

                if (response['success'] == true) {
                    Lobibox.notify("success", {
                        size: "mini",
                        rounded: true,
                        delay: 3100,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: response['message'],
                    });
                    $("#ModalContact").modal("hide");
                    $('#tblContactosNuevos').DataTable().ajax.reload();
                    return;
                } else if (response['error'] == true) {
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
                    $("#ModalContact").modal("hide");
                    return;
                }
            }
        });

    });


    var max_chars = 250;

    $('#contador').html(max_chars);

    $('#motivo').keyup(function () {
        var chars = $(this).val().length;
        var diff = max_chars - chars;
        $('#contador').html(diff);
    });

    $('#btnEliminarContacto').click(function () {

        var accion = 'eliminarContacto';
        var motivo = $('#motivo').val();
        var id = $('#id').val();

        $.ajax({
            method: 'POST',
            url: 'app/controladores/ContactoController.php',
            data: {
                accion: accion, motivo: motivo, id: id
            },
            //dataType: 'json',
            success: function (data) {

                response = JSON.parse(data);

                Lobibox.notify("success", {
                    size: "mini",
                    rounded: true,
                    delay: 3100,
                    delayIndicator: false,
                    position: "center top",
                    icon: true,
                    img: "../../img/timdesk/warning_circle.svg",
                    msg: response['message'],
                });
                $("#InactivarLead").modal("hide");
                $('#motivo').val('');
                $('#id').val('');
                $('#tblContactosNuevos').DataTable().ajax.reload();

            },
            error: function () {
                if (response['error'] = true) {

                    if (response['tipo'] = 'delete_prospecto') {
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
                    if (response['tipo'] = 'campo') {
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

    });

    $('#btnAgregarCliente').click(function () {

        var accion = 'AgregarContactoCliente';
        var campania = $('#campaniaModal').val();
        var email = $('#emailModal').val();
        var id = 30;

        $.ajax({
            method: 'POST',
            url: 'app/controladores/ContactoController.php',
            data: {
                accion: accion, campania: campania, email: email, id: id
            },
            dataType: 'json',
            success: function (response) {
                if (response.error) {
                    Lobibox.notify("error", {
                        size: "mini",
                        rounded: true,
                        delay: 3100,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: response.error,
                    });
                    return;
                } else {
                    Lobibox.notify("error", {
                        size: "mini",
                        rounded: true,
                        delay: 3100,
                        delayIndicator: false,
                        position: "center top",
                        icon: true,
                        img: "../../img/timdesk/warning_circle.svg",
                        msg: response.message,
                    });
                    return;
                }
            }
        });
    });


});

function initSelectMedios() {
    new SlimSelect({
        select: "#campaniaModal",
        placeholder: 'Seleccione un medio de campaña',
        searchPlaceholder: 'Buscar medios',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function initSelectPropietarioVendedor() {
    PropietarioVendedor = new SlimSelect({
        select: "#propietarioModalVendedor",
        placeholder: 'Seleccione un vendedor',
        searchPlaceholder: 'Buscar vendedor',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function initSelectEstado() {
    Estados = new SlimSelect({
        select: "#estadoModal",
        placeholder: 'Seleccione un estado federativo',
        searchPlaceholder: 'Buscar estado federativo',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}


function initDataTable() {
    let idioma_espanol = {
        sProcessing: 'Procesando...',
        sZeroRecords: 'No se encontraron resultados',
        sEmptyTable: 'Ningún dato disponible en esta tabla',
        sSearch: '<img src="../../img/timdesk/buscar.svg" width="20px" />',
        sLoadingRecords: 'Cargando...',
        searchPlaceholder: 'Buscar...',
        oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: '<img src="../../img/icons/pagination.svg" width="20px"/>',
            sPrevious: '<img src="../../img/icons/pagination.svg" width="20px" style="transform: scaleX(-1)"/>'
        },
      searchBuilder: {
            add: 'Filtros',
            condition: 'Condición',
            conditions: {
                string: {
                    contains: 'Contiene',
                    empty: 'Vacio',
                    endsWith: 'Finaliza con',
                    equals: 'Igual',
                    not: 'Diferente',
                    notEmpty: 'No vacío',
                    startsWith: 'Comienza con',
                },
                date: {
                    after: 'Después de',
                    before: 'Antes de',
                    between: 'Entre',
                    empty: 'Vacio',
                    equals: 'Igual',
                    not: 'Diferente',
                    notBetween: 'No está entre',
                    notEmpty: 'No vacío'
                },
                number: {
                    between: 'Between',
                    empty: 'Vacio',
                    equals: 'Igual',
                    gt: 'Mayor que',
                    gte: 'Mayor o igual que',
                    lt: 'Menor que',
                    lte: 'Menor o igual que',
                    not: 'Diferente',
                    notBetween: 'No está entre',
                    notEmpty: 'No vacío',
                },
                array: {
                    contains: 'Contiene',
                    empty: 'Vacio',
                    equals: 'Igual',
                    not: 'Diferente',
                    notEmpty: 'No vacío',
                    without: 'Sin'
                }
            },
            clearAll: 'Limpiar',
            deleteTitle: 'Eliminar',
            data: 'Columna',
            leftTitle: 'Izquierda',
            logicAnd: '+',
            logicOr: 'o',
            rightTitle: 'Derecha',
            title: {
                0: 'Filtros',
                _: 'Filtros (%d)'
            },
            value: 'Opción',
            valueJoiner: 'et'
        }
    }


    let table = $("#tblContactosNuevos")
        .DataTable({
            language: idioma_espanol,
            dom: "QBlfrtip",
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i>',
                className: "btn btn-info datatables-btn float-left mx-2",
                titleAttr: 'Excel',
            },
                {
                    extend: 'collection',
                    text: 'Listas',
                    className: "btn btn-success datatables-btn float-left mx-2",
                    autoClose: true,
                    buttons: [
                        {
                            text: 'Abril',
                            action: function (e, dt, node, config) {
                                stored = {
                                    criteria: [
                                        {
                                            condition: 'between',
                                            data: 'Último contacto',
                                            value: ['2021-04-01', '2021-04-30']
                                        },
                                        {
                                            condition: '=',
                                            data: 'Estado Lead',
                                            value: ['Nuevo']
                                        }
                                    ],
                                    logic: 'AND',
                                };

                                $('#tblContactosNuevos').DataTable().searchBuilder.rebuild(stored);
                                let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Abril"></span><button id="dtsb-save-2" class="dtsb-button" type="button">Editar Lista</button></div>'
                                $(table.searchBuilder.container()).append(div);
                                reset_icons();
                                reset_listeners();

                            }
                        },
                        {
                            text: 'Nuevo',
                            action: function (e, dt, node, config) {
                                stored = {
                                    criteria: [
                                        {
                                            condition: '=',
                                            data: 'Estado Lead',
                                            value: ['Nuevo']
                                        }
                                    ],
                                    logic: 'AND',
                                };

                                $('#tblContactosNuevos').DataTable().searchBuilder.rebuild(stored);
                                let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista: <input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15" value="Nuevo"></span><button id="dtsb-save-1" class="dtsb-button" type="button">Editar Lista</button></div>'
                                $(table.searchBuilder.container()).append(div);
                                reset_icons();
                                reset_listeners();
                            }
                        }
                    ]
                }
            ],
            colReorder: true,
            searchBuilder: {
                columns: [2, 3, 4, 5, 6, 7],
            },
            scrollX: true,
            lengthChange: false,
            info: false,
            ajax: {
                type: 'POST',
                url: "app/controladores/ContactoController.php",
                data: {accion: "verContactosActivos"},
                dataSrc: "",

            },
            //data: data,
            paging: true,
            pageLength: 10,
            columns: [
                {
                    'data': 'id',
                    className: "hide_column"
                },
                {

                    'data': 'contacto_id',
                    className: "hide_column"
                },

                {
                    'data': 'contacto',
                    "render": function (data, type, row, meta) {
                        var url = 'editar_contacto.php?id=' + row.contacto_id + '';
                        return '<a href="' + url + '">' + row.nombre + ' ' + row.apellido + '</a>';
                    }
                },
                {
                    'data': 'empresa',

                },
                {
                    'data': 'email'
                },
                {
                    'data': 'medio_contacto_campania'
                },
                {
                    'data': 'propietario'
                },
                {
                    'data': 'tipo',
                    "render": function (data, type, row, meta) {
                        if (data == null) {
                            return 'Prospecto';
                        } else {
                            return 'Cliente';
                        }
                    }
                },
                {
                    'data': 'estatus',
                    "render": function (data, type, row, meta) {

                        if (data == '2') {
                            return '<h6><span class="badge badge-danger">Inactivo</span></h6>'
                        } else if (data == '1') {
                            return `<h6><span class="badge badge-success">Activo</span></h6>`;
                        }
                    }
                },
                {
                    'data': 'tipo',
                    "render": function (data, type, row, meta) {
                        if (data == null) {
                            return `<button class="btn btn-sm" id="' + row.contacto_id +'" onclick="obtenerCliente(this)" ><i class="far fa-thumbs-up text-success" ></i></button>
                                    <button class="btn btn-sm" data-toggle="modal" data-target="#InactivarLead" id="' + row.id +'"  onclick="eliminarProspecto(this)"  >
                                    <i class="far fa-thumbs-down text-warning"></i>
                                    </button>`;
                        } else {
                            return `<span></span>
                                    <button class="btn btn-sm" data-toggle="modal" data-target="#InactivarLead" id="' + row.id +'"  onclick="eliminarProspecto(this)">
                                    <i class="far fa-thumbs-down text-warning"></i>
                                    </button>`;
                        }
                    }

                },
            ],
            order: [
                [2, 'asc']
            ]
        });

    reset_listeners();
}

function add_searchbuilder() {
    $(".dtsb-add").on("click", function () {
        reset_icons();

        if ($(".dtsb-list-edit").length == 0) {
            let div = '<div class="dtsb-list-edit"><span style="margin-right: 10px">Nombre de la Lista:</span><input class="dtsb-value dtsb-input" id="dtsb-input-list" type="text" maxlength="15">';
            div += '<button id="dtsb-save-1" class="dtsb-button" type="button" style="margin-left: 10px">Crear Lista</button></div>'
            $($('#tblContactosNuevos').DataTable().searchBuilder.container()).append(div);
        }
        reset_listeners();
    });
}

function left_right_searchbuilder(edit = false) {
    $(".dtsb-left, .dtsb-right, .dtsb-clearAll, .dtsb-clearGroup").on("click", function () {
        reset_listeners();
        reset_icons();
    });
}

function delete_searchbuilder(edit = false) {
    $(".dtsb-delete").on("click", function () {
        reset_listeners();
        if ($('.dtsb-criteria').length == 0) {
            $(".dtsb-list-edit").remove();
        }
    });
}

function reset_icons() {
    $(".dtsb-clearGroup").html('<i class="fas fa-times"></i>');
    $(".dtsb-delete").html('<i class="fas fa-times"></i>');
    $(".dtsb-right").html('<i class="fas fa-angle-right"></i>');
    $(".dtsb-left").html('<i class="fas fa-angle-left"></i>');
}

function reset_listeners() {
    delete_searchbuilder();
    left_right_searchbuilder();
    add_searchbuilder();
}

function obtenerCliente(obj) {
    var id = $(obj).closest('tr').find("td:nth-child(2)").html();
    var accion = 'VerificarContacto';

    $.ajax({
        method: 'POST',
        url: 'app/controladores/CrearClienteController.php',
        data: {
            accion: accion, id: id
        },
        dataType: 'json',
        success: function (response) {
            if (response) {
                $('#contacto_id').val(response.id);
                $('#nombreModal').val(response.nombre);
                $('#apellidoModal').val(response.apellido);
                $('#puestoModal').val(response.puesto);
                $('#celularModal').val(response.celular);

                $('#empresaModal').val(response.empresa);
                $('#campaniaModal').val(response.medio_contacto_campania_id);
                PropietarioVendedor.set(response.empleado_id);
                $('#telefonoModal').val(response.telefono);
                $('#emailModal').val(response.email);
                Estados.set(response.estado_id);
                $('#estadoModal').change();

                $('#ActivarLead').modal('show');

            }
        }
    });

}


function confirmNuevoCliente(id,nombre_cliente) {

    Swal.fire({
        title: "¡Registro éxitoso!",
        icon: "success",
        html:
            "<label>¿Quieres darle siguiento en el módulo de clientes a: "
            + nombre_cliente + "?</label>",
        width: "600px",
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
        reverseButtons: true,
        customClass: {
            actions: "d-flex justify-content-around",
            confirmButton: "btn-custom btn-custom--border-blue btn-aceptar",
            cancelButton: "btn-custom btn-custom--border-blue",
        },
        buttonsStyling: false,
        allowEnterKey: false,
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../../catalogos/clientes/catalogos/clientes/editar_cliente.php?c=" + id;
        } else if (result.isDismissed) {
            $('#ActivarLead').modal('hide');
            $("#tblContactosNuevos").DataTable().ajax.reload();
        }
    });
}
function confirmCreateClient() {
    var empresa = $('#empresaModal').val();
    if (confirm('¿Quieres crearlo como contacto del cliente: ' + empresa + '?')) {
        $('#ActivarLead').modal('toggle');
        $('#ClientContact').html(empresa);
        $('#ModalContact').modal('show');
    }
}

function eliminarProspecto(obj) {
    var rowID = $(obj).attr('id');
    var id = $(obj).closest('tr').find('td:first').html();
    $('#id').val(id);
    $('#InactivarLead').modal('show');
}

function loadSelectSelle() {

    var accion = 'CargarPropietarios';

    $.ajax({
        url: "app/controladores/CrearClienteController.php",
        data: {
            accion: accion
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            $('#propietarioModalVendedor').empty();
            $.each(response, function (key, value) {
                $("#propietarioModalVendedor").append('<option value=' + value.empleado_id + '>' + value.nombre_empleado + '</option>');
            });
        }
    });

}


function loadState() {

    var accion = 'CargarEstados';

    $.ajax({
        url: "app/controladores/CrearClienteController.php",
        data: {
            accion: accion
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            $.each(response, function (key, value) {
                $("#estadoModal").append('<option value=' + value.estado_id + '>' + value.estado + '</option>');
            });
        }
    });

}


function clearInputs() {

    $('#contacto_id').val('');
    $('#nombreModal').val('');
    $('#apellidoModal').val('');
    $('#puestoModal').val('');
    $('#celularModal').val('');

    $('#empresaModal').val('');
    $('#campaniaModal').val('');
    $('#propietarioModal').val(0);
    $('#telefonoModal').val('');
    $('#emailModal').val('');

    $('#montoModal').val('');
    $('#diasModal').val('');

    $('#razon_socialModal').val('');
    $('#rfcModal').val('');
    $('#municipioModal').val('');
    $('#coloniaModal').val('');
    $('#calleModal').val('');
    $('#numero_exteriorModal').val('');
    $('#numero_interiorModal').val('');
    $('#codigo_postalModal').val('');
    $('#paisModal').val(0);
    $('#estadoModal').val(0);

}

function initSelect() {
    Propietario = new SlimSelect({
        select: "#propietarioModal",
        placeholder: 'Seleccione un vendedor',
        searchPlaceholder: 'Buscar vendedor',
        allowDeselect: false,
        deselectLabel: '<span class="">✖</span>',
    });
}

function rfcValido(rfc, aceptarGenerico = true) {
    const re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
    var validado = rfc.match(re);

    if (!validado)  //Coincide con el formato general del regex?
        return false;

    //Separar el dígito verificador del resto del RFC
    const digitoVerificador = validado.pop(),
        rfcSinDigito = validado.slice(1).join(''),
        len = rfcSinDigito.length,

        //Obtener el digito esperado
        diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
        indice = len + 1;
    var suma,
        digitoEsperado;

    if (len == 12) suma = 0
    else suma = 481; //Ajuste para persona moral

    for (var i = 0; i < len; i++)
        suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);
    digitoEsperado = 11 - suma % 11;
    if (digitoEsperado == 11) digitoEsperado = 0;
    else if (digitoEsperado == 10) digitoEsperado = "A";

    //El dígito verificador coincide con el esperado?
    // o es un RFC Genérico (ventas a público general)?
    if ((digitoVerificador != digitoEsperado)
        && (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000"))
        return false;
    else if (!aceptarGenerico && rfcSinDigito + digitoVerificador == "XEXX010101000")
        return false;
    return rfcSinDigito + digitoVerificador;
}


function validarInput(input) {
    var rfc = input.value.trim().toUpperCase();

    var rfcCorrecto = rfcValido(rfc);

    if (rfcCorrecto) {
        $('#resultado').hide();
    } else {
        $('#resultado').show();
        $('#resultado').html('El rfc: ' + rfc + ' es invalido');
    }
    if (rfc == '') {
        $('#resultado').hide();
    }
}

