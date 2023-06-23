jQuery(document).ready(function(){

    var empleado = $("#txtId").val();
    var semana = $("#txtSem").val();
    var turno = $("#txtTurno").val();
    paginacion(empleado,semana,turno);
});

function paginacion(empleado,semana,turno){
    console.log(turno);
    var url = 'paginarNomina.php';
    $.ajax({
        type:'POST',
        url:url,
        data:'empleado='+empleado+'&semana='+semana+'&turno='+turno,
        success:function(data){
            var array = eval(data);
            $('#pagination').html(array[0]);
        }
    });
    return false;
}