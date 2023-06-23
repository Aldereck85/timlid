<?php
  require_once('../../../include/db-conn.php');
  $output = "";
  $drownbox = "";
  $count = 1;
  if(isset($_POST['id'])){
    $id = $_POST['id'];
  }
  $stmt = $conn->prepare('SELECT rtask.FKUsuario,task.Tarea, task.PKTarea, dateTask.Fecha ,crono.FechaTermino, pri.Prioridad, CONCAT(emp.Primer_Nombre," ",emp.Segundo_Nombre," ",emp.Apellido_Paterno," ",emp.Apellido_Materno) AS employe FROM tareas AS task
                          LEFT JOIN responsables_tarea AS rtask ON task.PKTarea = rtask.FKTarea
                          LEFT JOIN usuarios AS user ON rtask.FKUsuario = user.PKUsuario
                          LEFT JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado
                          LEFT JOIN cronograma_tarea AS crono ON task.PKTarea = crono.FKTarea
                          LEFT JOIN fecha_tarea AS dateTask ON task.PKTarea = dateTask.FKTarea
                          LEFT JOIN prioridad_tareas AS pri ON task.PKTarea = pri.FKTarea
                          WHERE task.FKProyecto = :id');
  $stmt->execute(array(':id'=>$id));
  $rowCount = $stmt->rowCount();
  $stmta = $conn->prepare('SELECT rtask.FKUsuario FROM tareas AS task
                          LEFT JOIN responsables_tarea AS rtask ON task.PKTarea = rtask.FKTarea');
  $stmta->execute();


  $stmt1 = $conn->prepare('SELECT user.PKUsuario, CONCAT(emp.Primer_Nombre," ",emp.Segundo_Nombre," ",emp.Apellido_Paterno," ",emp.Apellido_Materno) AS employe FROM usuarios AS user
                          INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado');
  $stmt1->execute();

  $drownbox1 = '<select class="custom-select custom-select-sm cmbUser" name="cmbUser" id="cmbUser">
              <option value="">Seleccione un usuario...</option>';

  while($row1 = $stmt1->fetch()){
    $drownbox1 .= '<option value="'.$row1['PKUsuario'].'">'.$row1['employe'].'</option>';
  }
  $drownbox1 .= '</select>';





  $date1 = '<input class="form-control form-control-sm date" type="text" id="date" placeholder="Ingrese la fecha de entrega...">';

  $output .= '
    <div class="table-responsive">
      <table class="table table-striped table-sm" id="data_table">
        <thead>
          <tr>
            <th width="5%"></th>
            <th width="30%">Nombre de tarea</th>
            <th width="20%">Responsable</th>
            <th width="20%">Fecha de entrega</th>
            <th width="20%">Prioridad</th>
            <th width="5%"></th>
          </tr>
        </thead>
        <tbody>';
        //<td><button class="btn btn-danger btn-sm btn-circle" name="btn_delete" id="btn_delete" data-id4="'.$row['PKTarea'].'"><i class="fas fa-times"></i></button></td>
  if($stmt->rowCount() > 0)
  {
    if(isset($_POST['data'])){
      $output .= '<tr>
                  <td class="text-center"><i class="fas fa-ellipsis-h"></td>
                  <td id="task_name" contenteditable></td>
                  <td id="responsable">'.$drownbox1.'</td>
                  <td id="fecha_entrega">'.$date1.'</td>
                  <td id="prioridad"><select class="custom-select custom-select-sm cmbPriority" name="cmbPriority" id="cmbPriority">
                    <option value="">Seleccion un usuario...</option>
                    <option value="1">Alta</option>
                    <option value="2">Media</option>
                    <option value="3">Baja</option>
                  </select></td>
                  <td style="text-align:center;"><a href="#" name="btn_add" id="btn_add"><i class="fas fa-plus" style="color:green;"></i></a></td>
                  </tr>';
    }
    while($row = $stmt->fetch()){
      if($row['Fecha'] != null || $row['Fecha'] != ""){
        $fecha = date('d/m/Y',strtotime($row['Fecha']));
      }else{
        $fecha = date('d/m/Y',strtotime($row['FechaTermino']));
      }

      $date = '<input class="form-control form-control-sm date" type="text" id="date'.$row['PKTarea'].'" value="'.$fecha.'">';
      $output .= '<tr>
                  <td class="text-center"><a href="#" data-toggle="modal" data-target="#ModalAgregar" data-id="'.$row['PKTarea'].'" id="showModalAdd"><i class="fas fa-ellipsis-h"></i><a></td>
                  <input type="hidden" id="txtTarea" value="'.$row['Tarea'].'">
                  <td class="task_name" data-id1="'.$row['PKTarea'].'" contenteditable>'.$row['Tarea'].'</td>
                  <td class="responsable" data-id2="'.$row['PKTarea'].'">
                  <select class="custom-select custom-select-sm cmbUser" name="cmbUser" id="cmbUser'.$row['PKTarea'].'" >
                              <option value="">Seleccione un usuario...</option>';
      $stmt1 = $conn->prepare('SELECT user.PKUsuario, CONCAT(emp.Primer_Nombre," ",emp.Segundo_Nombre," ",emp.Apellido_Paterno," ",emp.Apellido_Materno) AS employe FROM usuarios AS user
                              INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado');
      $stmt1->execute();
      while($row1 = $stmt1->fetch()){
        $output .= '<option value="'.$row1['PKUsuario'].'"';
        if($row1['PKUsuario'] == $row['FKUsuario']){
          $output .= 'selected>'.$row1['employe'].'</option></selected>';
        }else{
          $output .= '>'.$row1['employe'].'</option></selected>';
        }
      }
      $output .=  '</td>
                  <td class="fecha_entrega" data-id3="'.$row['PKTarea'].'">'.$date.'</td>
                  <td class="prioridad" data-id4="'.$row['PKTarea'].'">
                  <select class="custom-select custom-select-sm cmbPriority" name="cmbPriority" id="cmbPriority'.$row['PKTarea'].'">
                    <option value="">Seleccion un usuario...</option>
                    <option value="1"';if($row['Prioridad'] == 1) $output .= "selected";

      $output .=    '>Alta</option>
                    <option value="2"';if($row['Prioridad'] == 2) $output .= "selected";
      $output .=    '>Media</option>
                    <option value="3"';if($row['Prioridad'] == 3) $output .= "selected";
      $output .=    '>Baja</option>
                  </select>
                  </td>';

      $output .=  '<td style="text-align:center;"><a class="btn_delete" href="#" name="btn_delete" id="btn_delete" data-id4="'.$row['PKTarea'].'"><i class="fas fa-times " style="color:red;"></i></a></td>
                  </tr>';

      $count++;
    }



  }else
  {

    if(isset($_POST['data'])){
      $output .= '
                  <tr>
                  <td><i class="fas fa-ellipsis-h"></td>
                  <td id="task_name" contenteditable></td>
                  <td id="responsable">'.$drownbox1.'</td>
                  <td id="fecha_entrega">'.$date1.'</td>
                  <td id="prioridad"><select class="custom-select custom-select-sm cmbPriority" name="cmbPriority" id="cmbPriority">
                    <option value="">Seleccion un usuario...</option>
                    <option value="1">Alta</option>
                    <option value="2">Media</option>
                    <option value="3">Baja</option>
                  </select></td>
                  <td style="text-align:center;"><a href="#" name="btn_add" id="btn_add"><i class="fas fa-plus" style="color:green;"></i></a></td>
                  </tr>';
    }else{
      $output .=  '<tr>
                    <td colspan="6" style="text-align:center;">No se encontraron datos.</td>
                  </tr>
      ';
    }
  }
  $output .= '</tbody></table>
    </div>';

  echo $output;

?>
