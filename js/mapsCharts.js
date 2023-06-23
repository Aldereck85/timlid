var wwidth = $(window).width();//Anchura de la pantalla
var wMap, hMap;
get_width_height(wwidth);
  /*
  if (wwidth <= 900) {
    wMap = 400;
    hMap = 200;
  }
  */


var dataArray2 = [
      ['States','Estado','Ventas']
];
$.ajax({
    url:"../catalogos/dashboard/consulta.php",
    success:function(data){
      //console.log(JSON.parse(data));
      estados = JSON.parse(data);
      $.each(estados, function(i){
        //console.log(estados[i])
        dataArray2.push(estados[i]);
      })
      //console.log(dataArray2);
      
    },
    error:function(error){
      console.log(error);
    }
});

$(window).resize(function(){
  var wwidth = $(window).width();
  get_width_height(wwidth);
  //console.log(wwidth);
});

function get_width_height(dato){
  //console.log('entro a la get_w_h')
    if (dato>=1600) {
      wMap=650;
    }
    if (dato>1500 && dato<1600) {
      wMap=610;
    }
    if (dato>1300 && dato<1500) {
      wMap=490;
    }
    if (dato>1200 && dato<1300) {
      wMap=445;
    }
    if (dato>970 && dato<1200) {
      wMap=340;
    }
    if (dato>=900 && dato<970) {
      wMap=610;
    }
    if (dato>=700 && dato<900) {
      wMap=595;
    }
    if (dato>=500 && dato<700) {
      wMap=440;
    }
    if (dato<500) {
      wMap=310;
    }
    google.charts.load('current', {
      'packages':['geochart'],
      // Note: you will need to get a mapsApiKey for your project.
      // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
      'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
    });
    google.charts.setOnLoadCallback(drawRegionsMap);
}



function drawRegionsMap() {
  var data = google.visualization.arrayToDataTable(dataArray2);
  var options = {
    region: 'MX',
    resolution: 'provinces',
    width:wMap,
    keepAspectRatio:true

  };

  var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
  chart.draw(data,options);
}
