<?php
  require_once('../../../include/db-conn.php');
  $output = "No hay datos";
  if(isset($_POST['id'])){
    $id = $_POST['id'];

    $output = "";

    $drownbox = "";
    $count = 1;
    $stmt = $conn->prepare('SELECT task.FKUsuario,task.SubTarea, task.PKSubTarea, CONCAT(emp.Primer_Nombre," ",emp.Segundo_Nombre," ",emp.Apellido_Paterno," ",emp.Apellido_Materno) AS employe FROM subtareas AS task
                            LEFT JOIN usuarios AS user ON task.FKUsuario = user.PKUsuario
                            LEFT JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado
                            WHERE task.FKTarea = :id');
    $stmt->execute(array(':id'=>$id));
    $rowCount = $stmt->rowCount();

    $stmta = $conn->prepare('SELECT subtask.FKUsuario FROM subtareas AS subtask');
    $stmta->execute();

    $stmt1 = $conn->prepare('SELECT user.PKUsuario, CONCAT(emp.Primer_Nombre," ",emp.Segundo_Nombre," ",emp.Apellido_Paterno," ",emp.Apellido_Materno) AS employe FROM usuarios AS user
                            INNER JOIN empleados AS emp ON user.FKEmpleado = emp.PKEmpleado');
    $stmt1->execute();

    $drownbox1 = '<select class="custom-select custom-select-sm cmbUser" name="cmbUserSub" id="cmbUserSub">
                <option value="">Seleccione un usuario...</option>';

    while($row1 = $stmt1->fetch()){
      $drownbox1 .= '<option value="'.$row1['PKUsuario'].'">'.$row1['employe'].'</option>';
    }
    $drownbox1 .= '</select>';



    //$date1 = '<input class="form-control" type="date" id="date">';

    $output .= '
      <div class="table-responsive">
        <table class="table table-striped table-sm" id="data_table">
          <thead>
            <tr>
              <th width="5%"></th>
              <th width="45%">Nombre de tarea</th>
              <th width="45%">Responsable</th>
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
                    <td id="subtask_name" contenteditable></td>
                    <td id="sub_responsable">'.$drownbox1.'</td>
                    <td style="text-align:center;"><a href="#" name="btn_addsub" id="btn_addsub"><i class="fas fa-plus" style="color:green;"></i></a></td>
                    </tr>';
      }
      while($row = $stmt->fetch()){

        $output .= '<tr>
                    <td class="text-center"><i class="fas fa-ellipsis-h"></i><a></td>
                    <input type="hidden" id="txtTarea" value="'.$row['SubTarea'].'">
                    <td class="subtask_name" data-id1="'.$row['PKSubTarea'].'" contenteditable>'.$row['SubTarea'].'</td>
                    <td class="sub_responsable" data-id2="'.$row['PKSubTarea'].'">
                    <select class="custom-select custom-select-sm cmbUser" name="cmbUserSub" id="cmbUserSub'.$row['PKSubTarea'].'" >
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
                    <td style="text-align:center;"><a class="btn_delete_sub" href="#" name="btn_delete_sub" id="btn_delete_sub" data-id3="'.$row['PKSubTarea'].'"><i class="fas fa-times " style="color:red;"></i></a></td>
                    </tr>';
        $count++;
      }



    }else
    {

      if(isset($_POST['data'])){
        $output .= '
                    <tr>
                    <td><i class="fas fa-ellipsis-h"></td>
                    <td id="subtask_name" contenteditable></td>
                    <td id="sub_responsable">'.$drownbox1.'</td>
                    <td style="text-align:center;"><a href="#" name="btn_addsub" id="btn_addsub"><i class="fas fa-plus" style="color:green;"></i></a></td>
                    </tr>';
      }else{
        $output .=  '<tr>
                      <td colspan="5" style="text-align:center;">No se encontraron datos.</td>
                    </tr>
        ';
      }
    }
    $output .= '</tbody></table>
      </div>';

    //echo $output;
  }
  echo $output;
?>
