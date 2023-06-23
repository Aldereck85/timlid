function agregarEtapa(){
    var accion = "guardarEtapa";
    var etapa = $('#etapa').val();
    var orden = $('#orden').val();

    $.ajax({
        url:'app/controladores/EtapasNegocioController.php',
        method:'POST',
        data:{
            etapa:etapa,
            orden:orden,
            accion:accion,
        },
        success:function (data){
            alert(JSON.parse(data));
        }

    });
}