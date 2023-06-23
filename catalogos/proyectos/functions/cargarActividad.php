<?php
session_start();

if(isset($_SESSION["Usuario"])){

    require_once('../../../include/db-conn.php');

    $idtarea = $_POST['IDTarea'];

    $stmt = $conn->prepare('SELECT ca.Tipo, ca.Fecha, CONCAT(e.Primer_Nombre," ", e.Apellido_Paterno) as nombreempleado, cc1.nombre as columnaorigen, cc1.color as columnaorigencolor, cc2.nombre as columnadestino, cc2.color as columnadestinocolor FROM chat_actividad as ca INNER JOIN usuarios as u ON u.PKUsuario = ca.FKUsuario INNER JOIN empleados as e ON e.PKEmpleado = u.FKEmpleado LEFT JOIN colores_columna as cc1 ON cc1.PKColorColumna = ca.FKColorColumnaOrigen LEFT JOIN colores_columna as cc2 ON cc2.PKColorColumna = ca.FKColorColumnaDestino WHERE ca.FKTarea = :idtarea ORDER BY PKChat_actividad Desc');
    $stmt->bindValue(':idtarea',$idtarea);
    $stmt->execute();
    $row = $stmt->fetchAll();
    $nc = $stmt->rowCount();

    //Filtro de etapas
    $stmt = $conn->prepare('SELECT cc2.nombre as columnadestino FROM chat_actividad as ca LEFT JOIN colores_columna as cc1 ON cc1.PKColorColumna = ca.FKColorColumnaOrigen LEFT JOIN colores_columna as cc2 ON cc2.PKColorColumna = ca.FKColorColumnaDestino WHERE ca.FKTarea = :idtarea AND ca.Tipo = 1 ORDER BY columnadestino Asc');
    $stmt->bindValue(':idtarea',$idtarea);
    $stmt->execute();
    $filtros = $stmt->fetchAll();
    

    $json = new \stdClass();

    if($nc > 0){
      $html = ''.
      '<div class="panel panel-default">'.
        '<div class="panel-body">'.
          '<div class="col-md-12">'.
            '<div class="btn-group">'.
                '<span><img src="../../img/chat/filtrar_actividad.svg" class="img-responsive" width="15px">'.
                  '<span class="panel-header">Filtros</span>'.
                    '<button type="button" id="filtros" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../../img/chat/filtros_expandir.svg" class="img-responsive" width="15px" style="position: relative; left: 20px;"></button>'.
                      '<ul class="dropdown-menu dropdown-menu ellipsis dropdown-menu-filtros" role="menu" aria-labelledby="dropdownMenu" style="width: 460px;">'.
                        '<div class="row">'.
                          '<div class="col-md-4">'.
                            '<span style="color:#fff;font-weight:800;">Fecha:</span>'.
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="hoy" style="width: 90%;">Hoy</button>'.
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="semana" style="width: 90%;">Semana</button>'.
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="mes" style="width: 90%;">Mes</button>'.
                              '<button type="button" class="btn-mdb btn-light-blue btn-filter btn-sm-mdb" data-target="año" style="width: 90%;">Año</button>'.
                              '<button type="button" class="btn-mdb btn-mdb-color btn-filter btn-sm-mdb" data-target="" style="width: 90%;">Todos</button>'.
                            '</div>'.
                            '<div class="col-md-4">'.
                              '<span style="color:#fff;font-weight:800;">Tipo:</span>'.
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="estado" style="width: 90%;">Estado</button>'.
                              '<button type="button" class="btn-mdb btn-cyan btn-filter-tipo btn-sm-mdb" data-target="creado" style="width: 90%;">Creado</button>'.
                              '<button type="button" class="btn-mdb btn-mdb-color btn-filter-tipo btn-sm-mdb" data-target="" style="width: 90%;">Todos</button>'.
                            '</div>'.
                            '<div class="col-md-4">'.
                              '<span style="color:#fff;font-weight:800;">Etapa actual:</span>';


                              foreach ($filtros as $f) {
                                 $html .= '<button type="button" class="btn-mdb btn-blue-grey btn-filter-etapa btn-sm-mdb" data-target="'.strtolower($f['columnadestino']).'" style="width: 90%;">'.$f['columnadestino'].'</button>';
                              }
                              
                              $html .= '<button type="button" class="btn-mdb btn-mdb-color btn-filter-etapa btn-sm-mdb" data-target="" style="width: 90%;">Todos</button>'.
                            '</div>'.
                          '</div>'.
                      '</ul>'.
                  '</span>'.
                '</div>'.
              '</div>'.
              '<div class="table-filters" style="display:none;">'.
                '<input type="text" class="input-text" id="filter-date" data-filter-col="1">'.
                '<input type="text" class="input-text" id="filter-type" data-filter-col="2">'.
                '<input type="text" class="input-text" id="filter-phase" data-filter-col="5">'.
              '</div>'.
              '<div class="table-container">'.
                '<table id="data_actividad" class="table table-filter tableEspecialAct">';

                 $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

                foreach ($row as $r){

                  //Fecha formato
                  $orgFecha = $r['Fecha'];

                  $division = explode(" ", $orgFecha);

                  $divisionFecha = explode("-", $division[0]);
                  $hora = date("h:i A", strtotime($division[1]));
                  $mes_nombre_ini = $mes[$divisionFecha[1]-1];
                  $fecha = $divisionFecha[2]." de ".$mes_nombre_ini." ".$divisionFecha[0]." ".$hora;

                  //diferencia de los tiempos de la fecha
                  $diferencia = strtotime(date("Y-m-d H:i:s")) - strtotime($orgFecha); 
                  
                  if($diferencia < 60){
                      $tiempo = "Ahora";
                  }
                  elseif($diferencia > 59 && $diferencia < 3600){
                      $tiempo = round($diferencia/60)."m";
                  }
                  elseif($diferencia > 3599 && $diferencia < 86400){
                      $tiempo = round($diferencia/3600)."h";
                  }
                  elseif($diferencia > 86399){
                      $tiempo = round($diferencia/86400)."d";
                  }

                  //llenar etiquetas
                  $etiqueta = "";
                  if($diferencia > 18144000){
                    //etiqueta año
                    $etiqueta .= "año "; 
                  }
                  if($diferencia <= 18144000){
                    //etiqueta mes
                    $etiqueta .= "mes "; 
                  }
                  if($diferencia <= 604800){
                    //etiqueta semana
                    $etiqueta .= "semana "; 
                  }
                  if($diferencia <= 86400){
                    //etiqueta hoy
                    $etiqueta .= "hoy "; 
                  }

                  $html .= 
                      '<tr>'.
                      '<td width="30%">'.
                        '<div class="row izquierdo">'.
                            '<img src="../../img/chat/calendario.svg" class="img-responsive calendario tooltip_chat" width="20px" data-toggle="tooltip" title="" data-original-title="'.$fecha.'" />'. 
                             '<span style="width:20px;text-align: center;">'.$tiempo."</span>".
                            '<img src="../../img/chat/users.svg" class="user-img img-responsive usuario-tabla tooltip_chat" width="33px" data-toggle="tooltip" title="" data-original-title="'.$r['nombreempleado'].'" />'.
                        '</div>'.
                      '</td>'.
                      '<td style="display: none;">'.$etiqueta.'</td>';

                        if($r['Tipo'] == 1){

                          $html .=
                          '<td width="10%">'.
                              '<div class="estado-tabla" align="center">'.
                                '<div>'.
                                  '<img src="../../img/chat/cambio_estado.svg" class="img-responsive" width="20px" height="20px" style="margin-top: 3px;">'.
                                '</div>'.
                                'Estado'.
                              '</div>'.
                          '</td>'.
                          '<td width="28%">'.
                              '<div class="fases-tabla" style="width: 100%;">'.
                                '<span class="estado-actividad tooltip_chat" data-toggle="tooltip" title="" data-original-title="'.$r['columnaorigen'].'" style="background-color:'.$r['columnaorigencolor'].' !important;">'.$r['columnaorigen'].'</span>'.
                              '</div>'.
                          '</td>'.
                          '<td width="4%">'.
                            '<i class="fas fa-chevron-right"></i>'.
                          '</td>'.
                          '<td width="28%">'.
                            '<div class="fases-tabla" style="width: 100%;">'.
                                '<span class="estado-actividad tooltip_chat" data-toggle="tooltip" title="" data-original-title="'.$r['columnadestino'].'" style="background-color:'.$r['columnadestinocolor'].' !important;">'.$r['columnadestino'].'</span>'.
                            '</div>'.
                          '</td>';
                        }
                        else{
                          $html .=
                         '<td width="10%">'.
                            '<div class="fases-tabla">'.
                                '<center><i class="fas fa-plus"></i> Creado</center>'.
                            '</div>'.
                          '</td>'.
                          '<td width="28%">'.
                          '</td>'.
                          '<td width="4%">'.
                          '</td>'.
                          '<td width="28%">'.
                            '<div class="fases-tabla">'.
                            '</div>'.
                          '</td>';
                        }


                        $html .=
                        '</tr>';

                }


                $html .= 
                '</table>'.
              '</div>'.
            '</div>'.
          '</div>';
        }
        else{
          $html = '<br><br>
                  <div class="row">
                    <div class="col-md-12">
                        <center><h4>AÚN NO HAY NINGUNA ACTIVIDAD</h4></center>
                    </div>
                  </div>';
        }

        $json->html = $html;

        $json = json_encode($json);
        echo $json;

}

?>