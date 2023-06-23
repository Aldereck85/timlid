var Notificaciones = {};
function NoSpamLobibox(id){
    if(!Notificaciones.hasOwnProperty(id)){
        Notificaciones[id] = false;
        setTimeout(function(){ ( Notificaciones[id] = true); }, 3000);
    }else if(Notificaciones[id] == true){
        Notificaciones[id] = false;
        setTimeout(function(){ (Notificaciones[id] = true); }, 3000);
        console.log("pass to false");
    }
    
  }