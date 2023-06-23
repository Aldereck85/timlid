<?php
session_start();
require_once('../lib/TCPDF/tcpdf.php');
require_once('../include/db-conn.php');

$id = $_GET['id'];
$codigo = $_GET['codigo'];
$FKUsuario = $_SESSION["PKUsuario"];
$FechaModificacion = date("Y-m-d");

$stmt = $conn->prepare('SELECT c.PKCotizacion, c.Subtotal, c.ImporteTotal, DATE_FORMAT(c.FechaIngreso, "%d/%m/%Y") as FechaIngreso, DATE_FORMAT(c.FechaVencimiento, "%d/%m/%Y") as FechaVencimiento, c.NotaCliente,c.NotaInterna, cl.NombreComercial, cl.Email, e.Nombres, e.PrimerApellido, e.SegundoApellido, c.codigoCotizacion FROM cotizacion as c INNER JOIN clientes as cl ON cl.PKCliente = c.FKCliente
  LEFT JOIN usuarios AS u ON c.FKUsuario = u.PKUsuario LEFT JOIN empleados_usuarios as eu ON eu.FKUsuario = u.PKUsuario LEFT JOIN empleados AS e ON eu.FKEmpleado = e.PKEmpleado WHERE c.PKCotizacion = :id');


$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch();

if($codigo != $row['codigoCotizacion']){
  header("Location: error.php");
}

$GLOBALS["referencia"] = $row['PKCotizacion'];
$GLOBALS["Subtotal"] = number_format($row['Subtotal'],2); 
$GLOBALS["ImporteTotal"] = number_format($row['ImporteTotal'],2); 
$GLOBALS["FechaIngreso"] = $row['FechaIngreso'];
$GLOBALS["FechaVencimiento"] = $row['FechaVencimiento'];
$GLOBALS["NotaCliente"] = $row['NotaCliente'];
$GLOBALS["NotaInterna"] = $row['NotaInterna'];
$GLOBALS["NombreComercial"] = $row['NombreComercial'];
$GLOBALS["Email"] = $row['Email'];

$GLOBALS["Vendedor"] = $row['Nombres'].' '.$row['PrimerApellido'].' '.$row['SegundoApellido'];

class PDF extends TCPDF {
    /**
     * Print chapter
     * @param $num (int) chapter number
     * @param $title (string) chapter title
     * @param $file (string) name of the file containing the chapter body
     * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
     * @public
     */
    //Page header
      public function Header() {

      // Logo
      $image_file = '../img/Logo-transparente.png';
      $this->Image($image_file, 10, 11, 33, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
      $this->Cell(40);

      // Set font
      $this->SetFont('helvetica', 'B', 14);
      // Title
      $this->Cell(30, 10, 'Cotización No. '.$GLOBALS["referencia"], 0, false, 'C', 0, '', 0, false, 'M', 'M');
      $this->Cell(50);
      $this->SetFont('Times','',12);
      $this->Cell(30,10,"Expedición: ".$GLOBALS["FechaIngreso"] ,0, false, 'C', 0, '', 0, false, 'M', 'M');
      }

    public function PrintChapter($num, $title, $file) {
      // add a new page
      $this->AddPage();
      // disable existing columns
      $this->resetColumns();
      // print chapter title
      $this->ChapterTitle($num, $title);
      // set columns
      $this->setEqualColumns(1, 57);
      // print chapter body
      $this->ChapterBody($file);
    }

    /**
     * Set chapter title
     * @param $num (int) chapter number
     * @param $title (string) chapter title
     * @public
     */
    public function ChapterTitle($num, $title) {
      $this->SetFont('helvetica', '', 14);
      $this->SetFillColor(200, 220, 255);
      $this->Cell(180, 6, 'Chapter '.$num.' : '.$title, 0, 1, '', 1);

      $this->Ln(4);
    }

    /**
     * Print chapter body
     * @param $file (string) name of the file containing the chapter body
     * @param $mode (boolean) if true the chapter body is in HTML, otherwise in simple text.
     * @public
     */
    public function ChapterBody($file) {
      $this->selectColumn();
      // get esternal file content
      $content = $file;//file_get_contents($file, false);
      // set font
      $this->SetFont('times', '', 9);
      $this->SetTextColor(50, 50, 50);
      // print content
      $this->writeHTML($content, true, false, true, false, 'J');

      $this->Ln();
    }

    // Page footer
    public function Footer() {
      $this->SetY(-20);
      // Set font
      $this->SetFont('helvetica', 'N', 12);

      $footer = '<table>
                <tr align="center">
                  <th colspan="2" width="100%"><b>Contacto:</b> '.$GLOBALS['Vendedor'].'</th>
                </tr>
                <tr align="center">
                  <th><b>Teléfono:</b> '.$GLOBALS['Vendedor'].'</th>
                  <th><b>Email:</b> '.$GLOBALS['Vendedor'].'</th>
                </tr>
                </table>';
      $this->writeHTML($footer, true, false, true, false, '');
      
      // Page number
      //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

$pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('GH MEDIC');
$pdf->SetTitle('Cotizacion');
$pdf->SetSubject('Cotizacion');
$pdf->SetKeywords('Cotizacion');
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
  require_once(dirname(__FILE__).'/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
//$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
$pdf->SetFont('Times','',12);
$total = 0;

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

/*
if(isset($_POST['txtId'])){
  ob_start();
  /*error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  $id = $_POST['txtId'];
  $stmt = $conn->prepare('SELECT p.Clave,p.Descripcion,u.Unidad_de_Medida,u.Piezas_por_Caja,c.Precio_Unitario,c.Cantidad
          FROM compras_tmp as c
          LEFT JOIN productos as p ON c.FKProducto = p.PKProducto
          LEFT JOIN unidad_medida as u ON p.FKUnidadMedida = u.PKUnidadMedida
          WHERE FKOrdenCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $rowCount = $stmt->rowCount();
}else if(isset($_GET['txtId'])){
  ob_start();
  /*error_reporting(E_ALL & ~E_NOTICE);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  $id = $_GET['txtId'];
  $stmt = $conn->prepare('SELECT p.Clave,p.Descripcion,u.Unidad_de_Medida,u.Piezas_por_Caja,c.Precio_Unitario,c.Cantidad
          FROM compras_productos as c
          LEFT JOIN productos as p ON c.FKProducto = p.PKProducto
          LEFT JOIN unidad_medida as u ON p.FKUnidadMedida = u.PKUnidadMedida
          WHERE FKOrdenCompra = :id');
  $stmt->bindValue(':id',$id);
  $stmt->execute();
  $rowCount = $stmt->rowCount();
}*/

$tblenc = '<table>
      <tr>
        <th width="100%"><b>Cliente:</b> '.$GLOBALS['NombreComercial'].'</th>
      </tr>
      <tr>
        <th width="50%"><b>Fecha de expedición:</b> '.$GLOBALS['FechaIngreso'].'</th>
        <th width="50%"><b>Fecha de vencimiento:</b> '.$GLOBALS['FechaVencimiento'].'</th>
      </tr>
    </table>';
$pdf->writeHTML($tblenc, true, false, true, false, '');

$tbl = '
<style>
  table {
    font-family: "Lucida Sans Unicode","Lucida Grande", Sans-Serif;
    font-size: 12px;
    margin: 45px;
    width: 480px;
    text-align: left;
    border: none;
    width: 100%;
    border-collapse: collapse;

  }
  th {
    font-size: 12px;
    font-weight: bold;
    padding: 8px;
    background-color: #b9c9fe;
    border-top: 2px solid #aabcfe;
    border-bottom: 1px solid #fff; color: #039;
    border-right: 1px solid #fff;
    text-align: center;
    height: 25px;
    line-height: 20px
  }
  td {
    font-size: 9px;
    padding: 4px;
    background-color: #e8edff;
    border-bottom: 1px solid #fff;
    color: #669;
    border-top: 1px solid transparent;
    border-right: 1px solid #fff;
    vertical-align: center;
    text-align: center;
    line-height: 20px;
  }
  tr:hover td {
    background: #d0dafd;
    color: #339;
  }
  .transparencia{
    border-bottom: 1px solid #fff;
    border-top: 1px solid #fff;
  }
</style>
  <table>
    <thead>
      <tr class="headers">
        <th width="9%">Clave</th>
        <th width="36%">Producto</th>
        <th width="10%">Cantidad</th>
        <th width="10%">Precio</th>
        <th width="11%">U. medida</th>
        <th width="12%">Impuestos</th>
        <th width="12%">Importe</th>
      </tr>
    </thead>
    <tbody>
';
$stmt = $conn->prepare('SELECT dc.FKProducto, dc.Cantidad, dc.Precio, p.ClaveInterna, p.Descripcion FROM detallecotizacion  as dc INNER JOIN productos as p ON p.PKProducto = dc.FKProducto WHERE dc.FKCotizacion= :id');
$stmt->execute(array(':id'=>$id));
$numero_productos = $stmt->rowCount();
$rowp = $stmt->fetchAll();

$impuestos = array();
$x = 0;

foreach ($rowp as $rp) {

    $stmt = $conn->prepare('SELECT i.PKImpuesto, i.Nombre, i.FKTipoImpuesto as TipoImpuesto, i.FKTipoImporte as TipoImporte,  i.Operacion, di.FKProducto, di.Tasa FROM detalleimpuesto  as di INNER JOIN impuesto as i ON i.PKImpuesto = di.FKImpuesto WHERE di.FKCotizacion= :id AND FKProducto = :idProducto');
    $stmt->execute(array(':id'=>$id, ':idProducto'=>$rp['FKProducto']));
    $rowi = $stmt->fetchAll();

    $totalProducto = $rp['Cantidad'] * $rp['Precio'];
    $tbl.=  '<tr>
              <td width="9%">'.$rp['Clave'].'</td>
              <td width="36%">'.$rp['Descripcion'].'</td>
              <td width="10%">'.$rp['Cantidad'].'</td>
              <td width="10%">'.$rp['Precio'].'</td>
              <td width="11%">Pieza</td>
              <td width="12%">';

          $contImpuestos = 1;
          $numImpuestos = count($rowi);
          foreach ($rowi as $ri) { 
            $IniImpuesto = explode(" ", $ri['Nombre']);
            $Identificador = $IniImpuesto[0]."_".$ri['TipoImpuesto']."_".$ri['TipoImporte']."_".$ri['PKImpuesto']."_".$ri['FKProducto'];
            
            if($ri['TipoImporte'] == 1){
              $tas = "%";
            }
            if($ri['TipoImporte'] == 2 || $ri['TipoImporte'] == 3){
              $tas = "";
            }

            //print_r($impuestos);

            //echo "id impuesto :".$ri['PKImpuesto']."//";
            $found_key = array_search($ri['PKImpuesto'], array_column($impuestos, 0));
          
            if($found_key > -1){
                $impuestos[$found_key][0] = $ri['PKImpuesto']; 

                if($ri['TipoImporte'] == 1)
                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + (($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100));
                else
                  $impuestos[$found_key][1] = $impuestos[$found_key][1] + $ri['Tasa'];

                $impuestos[$found_key][2] = $ri['Nombre']; 
                $impuestos[$found_key][3] = $ri['TipoImpuesto']; 
                $impuestos[$found_key][4] = $ri['Operacion'];
            }
            else{
                $impuestos[$x][0] = $ri['PKImpuesto']; 
                if($ri['TipoImporte'] == 1)
                  $impuestos[$x][1] = ($rp['Cantidad'] * $rp['Precio']) * ($ri['Tasa'] / 100);
                else
                  $impuestos[$x][1] = $ri['Tasa'];

                $impuestos[$x][2] = $ri['Nombre']; 
                $impuestos[$x][3] = $ri['TipoImpuesto']; 
                $impuestos[$x][4] = $ri['Operacion']; 
                $x++;
            }
            
             $tbl.= $ri['Nombre']." ".$ri['Tasa'].$tas;
             if($contImpuestos != $numImpuestos)
              $tbl.= "<br>";
             $contImpuestos++;
          }


    $tbl.=   '</td>
            <td width="12%">'.number_format($totalProducto,2).'</td>
          </tr>';
    }

$tbl.= '<tr>
          <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td width="21%">Subtotal:</td>
          <td width="24%" style="text-align: right;">$ '.$GLOBALS["Subtotal"].'</td>
        </tr>
        <tr>
          <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
          <td width="21%">Impuestos:</td>
          <td width="24%"></td>
        </tr>';

        //print_r($impuestos);
        foreach ($impuestos as $imp) {
            $IniImpuesto = explode(" ", $imp[2]);
            $tbl.= '<tr>
                    <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
                    <td width="21%" style="text-align: right;">'.$imp[2].'</td>
                    <td width="24%" style="text-align: right;">$ '.number_format($imp[1],2).'</td>
                 </tr>';

        }

$tbl .= '<tr>
            <td width="55%" style="background-color: none;border-bottom: 1px solid #fff; border-top: 1px solid #fff;"></td>
            <td width="21%">Total:</td>
            <td width="24%" style="text-align: right;">$ '.$GLOBALS["ImporteTotal"].'</td>
          </tr>';

$tbl.= '</table>';
echo $tbl;
$pdf->writeHTML($tbl, true, false, true, false, '');

$text = 'Nota: ';
$pdf->Cell(100,0,$text);
$pdf->Ln(5);
$text = $GLOBALS["NotaCliente"];
$pdf->Cell(100,0,$text);

ob_end_clean();
$pdf->Output('cotizacion_'.$GLOBALS["referencia"].'.pdf', 'D');
?>
