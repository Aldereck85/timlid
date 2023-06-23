<?php
  session_start();
  require_once('../../lib/TCPDF/tcpdf.php');
  require_once('../../include/db-conn.php');
  date_default_timezone_set('America/Mexico_City');

  $id = $_GET['id'];
  $hoy = getDate();
  $hoyCad = $hoy['mday'].'-'.$hoy['mon'].'-'.$hoy['year'];
  $fechaActual = date('d-m-Y',strtotime($hoyCad));
  $fechaActual = new DateTime($fechaActual);

  $stmt = $conn->prepare('SELECT * FROM empleados AS emp
                          LEFT JOIN estados_federativos AS ef ON emp.FKEstado = ef.PKEstado
                          LEFT JOIN estado_civil AS ec ON emp.FKEstadoCivil = ec.PKEstadoCivil
                          WHERE PKEmpleado =:id');
  $stmt->execute(array(':id'=>$id));
  $row = $stmt->fetch();

  $nombre = strtoupper($row['PrimerApellido'].' '.$row['SegundoApellido'].' '.$row['Nombres']);
  $origen = $row['Ciudad'].", ".$row['Estado'];
  $nFecha = date('d-m-Y', strtotime($row['FechaNacimiento']));
  $nFecha = new DateTime($nFecha);
  $dia = date('d',strtotime($row['FechaNacimiento']));
  $nMes = date('m',strtotime($row['FechaNacimiento']));
  $año = date('Y',strtotime($row['FechaNacimiento']));
  $edad = $fechaActual->diff($nFecha);
  $civil = $row['EstadoCivil'];

  switch ($nMes) {
    case '01':
      $mes = 'Enero';
    break;
    case '02':
      $mes = 'Febrero';
    break;
    case '03':
      $mes = 'Marzo';
    break;
    case '04':
      $mes = 'Abril';
    break;
    case '05':
      $mes = 'Mayo';
    break;
    case '06':
      $mes = 'Junio';
    break;
    case '07':
      $mes = 'Julio';
    break;
    case '08':
      $mes = 'Agosto';
    break;
    case '09':
      $mes = 'Septiembre';
    break;
    case '10':
      $mes = 'Octubre';
    break;
    case '11':
      $mes = 'Noviembre';
    break;
    case '12':
      $mes = 'Diciembre';
    break;
  }
  $fecha = $dia.' de '.$mes.' de '.$año;

  class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file ='../../img/prueba.PNG';

        $this->Image($image_file, 10, 5, 70, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


  // create new PDF document
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
/*
  // set document information
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('Nicola Asuni');
  $pdf->SetTitle('TCPDF Example 001');
  $pdf->SetSubject('TCPDF Tutorial');
  $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
*/
/*
  // set default header data
  $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
  $pdf->setFooterData(array(0,64,0), array(0,64,128));
*/

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

  // set default font subsetting mode
  $pdf->setFontSubsetting(true);

  // Set font
  // dejavusans is a UTF-8 Unicode font, if you only need to
  // print standard ASCII chars, you can use core fonts like
  // helvetica or times to reduce file size.
  $pdf->SetFont('helvetica', '', 12, '', true);

  // Add a page
  // This method has several options, check the source code documentation for more information.
  $pdf->AddPage();

  // set text shadow effect
  //$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

  // Set some content to print
  $html = '
  <p style="text-align:justify;font-weight:bold;">CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO DETERMINADO CON PERIODO DE PRUEBA INICIAL.</p>
  <p style="text-align:justify;">CONTRATO INDIVIDUAL DE TRABAJO POR TIEMPO IDETERMINADO CON PERIODO DE PRUEBA  INICIAL QUE CELEBRAN POR UNA PARTE <strong>GH ASISTENCIA S.A DE C.V</strong> REPRESENTADA EN ESTE ACTO POR SU REPRESENTANTE LEGAL LA QUIEN EN LO SUCESIVO SE LE DENOMINARÁ <strong>"EL PATRON"</strong> Y POR LA OTRA PARTE POR SU PROPIO DERECHO EL <strong>C. '.$nombre.'</strong>, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ <strong>"EL TRABAJADOR"</strong> MISMOS QUE SE OBLIGAN AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS:</p>
  <p style="text-align:center;font-weight:bold;">DECLARACIONES:</p>
  <p style="text-align:justify;"><strong>I.- Declara EL PATRON, por su propio derecho:</strong></p>
  <p style="text-align:justify;"><strong>a)</strong> GH ASISTENCIA, S.A. DE C.V. ser una persona moral legalmente constituida según las leyes mexicanas, según consta en instrumentó publico debidamente registrada.</p>
  <p style="text-align:justify;"><strong>b)</strong> Que está debidamente inscrito en el Registro Federal de Contribuyentes bajo la clave <strong>GAS190304RY0</strong>.</p>
  <p style="text-align:justify;"><strong>c)</strong> Que de manera preponderante tiene como objeto social, la prestación del servicio de transporte de personal y escolar.</p>
  <p style="text-align:justify;"><strong>d)</strong> Que requiere de los servicios de personal apto para el desarrollo de sus actividades, y de modo especial para el puesto <strong><u>jefe de recursos humanos</u></strong>, desempeñando las funciones inherentes al puesto.</p>
  <p style="text-align:justify;"><strong>e)</strong> Que requiere de los servicios de <strong>"EL TRABAJADOR"</strong> EL <strong>C. '.$nombre.'</strong>, para el objeto de la prestación de sus servicios personales y subordinados, a cambio de un salario en los términos del artículo 20 de la Ley Federal del Trabajo.</p>

  <p style="text-align:justify;"><strong>II.- Declara EL TRABAJADOR, por su propio derecho:</strong></p>
<p style="text-align:justify;"><strong>a)</strong> Bajo protesta de decir verdad y para los efectos legales a que haya lugar, los siguientes datos:</p>

<dd><p style="text-align:justify;">Originario de: '.$origen.'.<br>
Fecha de nacimiento: '.$fecha.'.<br>
Edad: '.$edad->y.' años.<br>
Estado civil: '.$civil.'<br>
con domicilio particular en: '.$row['Direccion'].' # '.$row['NumeroExterior'].' '.$row['Interior'].' col '.$row['Colonia'].', '.$origen.', CP.'.$row['CP'].'.<br>
CURP: '.$row['CURP'].'<br>
RFC: '.$row['RFC'].'.
  </dd></p>

<p style="text-align:justify;"><strong>b)</strong> QUE MANIFIESTA BAJO PROTESTA DE DECIR VERDAD, que tiene los conocimientos y capacidades suficientes, así como la práctica, experiencia e interés necesarios para el desempeño del trabajo que ha solicitado.</p>
<p style="text-align:justify;"><strong>c)</strong> <strong>"EL TRABAJADOR"</strong> manifiesta que tiene la capacidad y aptitudes para desarrollar las actividades inherentes al puesto a desarrollar.</p>
<p style="text-align:justify;"><strong>d)</strong> <strong>"EL TRABAJADOR"</strong> esta conforme en desempeñar los requerimientos de ‘’EL PATRON’’ y en aceptar las condiciones generales de trabajo sobre las cuales prestará sus servicios personales.</p>
<p style="text-align:justify;"><strong>e)</strong> <strong>"EL TRABAJADO"</strong> manifiesta además bajo su más estricta responsabilidad que no padece enfermedad crónica o de tipo infectocontagiosa, esto para efecto de tener las prevenciones necesarias y evitar propagación de algún tipo de enfermedad en la fuente de trabajo.
<br>En virtud de lo anterior, las partes están de acuerdo en sujetarse dentro del presente contrato al tenor de las siguientes.</p>

<p style="text-align:center;"><strong>CLÁUSULAS:</strong></p>
<p style="text-align:justify;"><strong>PRIMERA. -</strong> Se denominará en lo sucesivo a la Ley Federal del Trabajo como "LA LEY", al referirse al presente documento como "EL CONTRATO", y a los que suscriben como "LAS PARTES".</p>
<p style="text-align:justify;"><strong>SEGUNDA.-</strong> En base a lo dispuesto por el Artículo 39-B de la Ley Federal del Trabajo, “EL PATRON” contrata a “EL TRABAJADOR” por un periodo de capacitación de  89 días a partir del inicio de la relación de trabajo, periodo que iniciará precisamente el día de la celebración del presente contrato y concluye el día  30  DE OCTUBRE DEL 2020, dicha prueba se realiza con la finalidad de que demuestre las habilidades y conocimientos necesarios para las actividades propias del puesto de jefe de recursos humanos, mismas actividades que se señalan de manera enunciativa mas no limitativa, estando de acuerdo ambas partes en que puedan cambiarse o modificarse según necesidades de <strong>"EL PATRON"</strong>.</p>
<p style="text-align:justify;"><strong>EL TRABAJADOR</strong> se obliga, a realizar dichas actividades con cuidado y esmero apropiados, cumpliendo en todo momento con los requisitos y lineamientos que el puesto requiere.</p>
<p style="text-align:justify;">Lo anterior de conformidad con los Artículos 39 B, 39 C, 39 D y 39 E de la Ley Federal del Trabajo vigente y con la única intención de verificar que <strong>"EL TRABAJADOR"</strong> cumple con los requisitos y conocimientos necesarios para el desarrollo del trabajo contratado.</p>
<p style="text-align:justify;">Al término del periodo de capacitación, según lo establece el Artículo 39-B de no acreditar el trabajador que satisface los requisitos, conocimientos y la competencia necesaria para desarrollar las labores, a juicio del patrón, tomando en cuenta la opinión de la Comisión Mixta de Productividad, Capacitación y Adiestramiento en los términos de esta Ley, así como la naturaleza de la categoría o puesto, se dará por terminada la relación de trabajo, sin responsabilidad para el patrón.</p>

<p style="text-align:justify;">Si al concluir la prueba inicial, a juicio de <strong>"EL PATRÓN"</strong>, <strong>"EL TRABAJADOR"</strong> demostró habilidades, conocimientos y aptitudes adecuadas para el desempeño del puesto, le reconocerá la antigüedad del presente contrato y se considerará al presente contrato como por tiempo indeterminado.</p>

<p style="text-align:justify;">Ambas partes están de acuerdo en que se entiende por requisitos, los de índole física, psicológica, de conocimientos, de habilidad y de actitud, que necesite manifestar que posee el ocupante del puesto, mediante los exámenes diseñados para el caso y sobre todo, mediante el logro de las metas, objetivos y estándares que se le asignen.</p>

<p style="text-align:justify;">El contrato obliga a lo expresamente pactado, por lo que la duración del mismo será la que se señala en la presente cláusula, por lo que al vencerse su término, las partes lo darán por terminado en forma definitiva como lo establecen los artículos 31, 35, 37, 39–B, y demás relativos o aplicables de la Ley Federal del Trabajo, sin ninguna responsabilidad para cualquiera de las partes o en su caso se podrá volver por tiempo indeterminado cuando se cumplan los lineamientos expresados en el párrafo quinto  de la presente cláusula, es decir que terminada la prueba inicial, a juicio de <strong>"EL PATRON"</strong>, <strong>"EL TRABAJADOR"</strong> demostró la competencia, los conocimientos, habilidades y aptitudes para desempeñar el puesto para el cual fue contratado.</p>

<p style="text-align:justify;">Durante el período de capacitación el trabajador disfrutará del salario, la garantía de la seguridad social y de las prestaciones de la categoría o puesto que desempeñe.</p>

<p style="text-align:justify;"><strong>TERCERA.-</strong> Los Contratantes se reconocen expresamente la personalidad con la que se ostentan, para todos los efectos legales a que haya lugar.</p>

<p style="text-align:justify;"><strong>CUARTA.-</strong> El presente contrato obliga a las partes a lo expresamente pactado en los términos del artículo 31 de la Ley Federal del Trabajo, por lo que su duración será la que se señala en la cláusula segunda que antecede.</p>

<p style="text-align:justify;"><strong>QUINTA.-</strong> La prestación de los servicios de <strong>"EL TRABAJADOR"</strong> conforme al puesto de <strong><u>jefe de recursos humanos</u></strong> consistirá en todas y cada una de las cuales sean necesarias para el buen funcionamiento de su trabajo.</p>

<p style="text-align:justify;"><strong>SEXTA.-</strong> El lugar de la prestación de los servicios de <strong>"EL TRABAJADOR"</strong> será el domicilio de <strong>"EL PATRON"</strong> señalado en el inciso a) de las declaraciones de <strong>"EL PATRON"</strong> o el <strong>UBICADO EN LA FINCA MARCADA CON EL NÚMERO 5297 INT 43 DE LA CARRETERA GUADALAJARA A TEPIC, EN EL PARQUE INDUSTRIAL NOGALES EN GUADALAJARA JALISCO</strong>  y/o  el lugar que <strong>"EL PATRON"</strong> le establezca, tal y como se señala en los siguiente párrafos.</p>

<p style="text-align:justify;">Mas sin embargo <strong>"EL PATRÓN"</strong> podrá cambiar a <strong>"EL TRABAJADOR"</strong>, de lugar de trabajo, horario o actividad, siempre y cuando se le respete categoría y salario.</p>

<p style="text-align:justify;">En este caso el <strong>"EL PATRÓN"</strong> le comunicará con anticipación la remoción del lugar, horario o actividad indicándole la forma y lugar en que se desempeñará.</p>

<p style="text-align:justify;">Para el caso que en el nuevo lugar de prestación de servicios que le fuera asignado variará el horario de labores, <strong>"EL TRABAJADOR"</strong> acepta allanarse a dicha modalidad.</p>

<p style="text-align:justify;">Por lo cual <strong>"EL TRABAJADOR"</strong>, se compromete a ejecutar sus labores en los centros de trabajo ya citados, pero a su vez toma en consideración que <strong>"EL PATRÓN"</strong> pudiera tener instalaciones a nivel nacional o internacional, incluyendo en diversos lugares del país y de la ciudad en que se ubica <strong>"EL TRABAJADOR"</strong>, por lo cual éste se compromete a desempeñar sus labores en los lugares establecidos anteriormente o en cualquiera de las oficinas, sucursales o instalaciones presentes o futuras de <strong>"EL PATRÓN"</strong>, en el área metropolitana de la ciudad citada o en cualquier lugar de la República Mexicana en que sea comisionado de acuerdo a las necesidad del trabajo, por lo que <strong>EL TRABAJADOR</strong> otorga desde ahora su más amplio consentimiento, para realizar posibles viajes de trabajo; en relación a esta cláusula es voluntad de las partes el que se respete lo establecido en el artículo 30 de Ley Federal del Trabajo.</p>

<p style="text-align:justify;"><strong>"EL TRABAJADOR"</strong>, se obliga expresamente para que a juicio del <strong>"PATRÓN"</strong>, y sin aviso previo se puedan variar las condiciones de trabajo, en cuanto al puesto, lugar de trabajo PRESENTE O FUTURO y horario a desempeñar, siempre que le sea respetado su salario y categoría, sin que esto signifique violación alguna al contrato en su perjuicio, ya que el propósito de este contrato es pactar que <strong>"EL TRABAJADOR"</strong> se obliga a desempeñar cualquiera de los puestos existentes en la fuente de trabajo y sus domicilios o sucursales, o bien, en cualquiera de los domicilios de sus clientes o usuarios de los servicios de la empresa, siempre que así lo requiera <strong>"EL PATRÓN"</strong> ya sea por las necesidades del servicio o por cualquier otra causa.</p>

<p style="text-align:justify;">Para el inicio de las relaciones de trabajo, la parte trabajadora se obliga a ejecutar su trabajo personal con la intensidad, cuidado y esmero apropiados al siguiente puesto:</p>

<p style="text-align:justify;"><strong><u>Jefe de recursos humanos</u></strong>, teniendo como principales objetivos, realizar las tareas inherentes a su puesto. Así como todas y cada una de las actividades que le estén relacionadas a dicho puesto, mismas que a su vez se señalan de manera enunciativa mas no limitativa.</p>

<p style="text-align:justify;"><strong>"EL TRABAJADOR"</strong> se somete al <strong>"PATRÓN"</strong>, bajo su dirección, dependencia y subordinación, así como también se obliga a cumplir las órdenes e instrucciones que reciba, en todo lo concerniente al trabajo, que precisamente, consistirá en las establecidas en las políticas de descripción de puesto, estando obligado expresamente a cumplir dicha actividad en los turnos, horarios y lugares presentes o futuros que <strong>"EL PATRÓN"</strong> le especifique por escrito o de manera verbal; y además de las actividades normalmente asignadas al <strong>"TRABAJADOR"</strong>, se espera que lleve a cabo otras que sean proporcionales con su posición y responsabilidades, principalmente deberá cumplir con la subordinación de su superior jerárquico, dichas actividades se señalan a continuación de forma enunciativa, mas no limitativa:</p>

<p style="text-align:justify;"><ul><li>Ejercer su mejor juicio.</li>
<li>Proteger los activos de la empresa de no ser desperdiciados.</li>
<li>Seguir y mantener e implementar los planes de negocios y presupuestos procedimientos y direcciones establecidas por <strong>"EL PATRÓN"</strong>, las cuales pueden ser modificadas periódicamente.</li>
<li>Cumplir con las disposiciones de la Ley Federal del Trabajo en vigor.</li>
<li>Tratar a los clientes, proveedores y colaboradores del patrón, con todo respeto y cortesía.</li></ul></p>

<p style="text-align:justify;">Las partes se obligan a dar cumplimiento en lo que a cada una les corresponde a los artículos 2 y 3 de la Ley Federal del Trabajo y que tienen que ver con las disposiciones relativas a la eliminación de la discriminación, respeto de los derechos humanos y a las libertades fundamentales en el ámbito laboral y a evitar en todas las conductas de hostigamiento y acoso sexual.</p>

<p style="text-align:justify;">Siendo una causa especial de rescisión sin responsabilidad para <strong>"EL PATRÓN"</strong>, si esta condicionante no es cumplida por "<strong>EL TRABAJADOR"</strong>, de acuerdo a lo establecido por el artículo 47, fracción XV y en relación al artículo 134, fracción IV, de la Ley Federal del Trabajo.</p>

<p style="text-align:justify;">Como parte de las actividades a realizar y de las obligaciones por parte de <strong>"EL TRABAJADOR"</strong>, este está obligado a atender con esmero y cortesía a los clientes, proveedores y compañeros de trabajo del establecimiento, <strong>"EL TRABAJADOR"</strong> se compromete a siempre procurar un ambiente de trabajo adecuado para el centro de trabajo teniendo la responsabilidad de respetar y conducirse de forma profesional hacia sus subordinados directos, siempre procurando los intereses del <strong>"PATRON"</strong>, teniendo estrictamente prohibido rescindir cualquier relación laboral a sus subordinados.</p>

<p style="text-align:justify;"><strong>SÉPTIMA.-</strong> La duración de la jornada de trabajo será la necesaria para el desempeño de las actividades de <strong>"EL TRABAJADOR"</strong> en los términos de la Ley Federal del Trabajo. Siendo esta la de laborar de lunes a sábados, con el siguiente horario: 9:00 a 14:00 horas y de 15:03 a 18:00 horas por lo que constituye una jornada de 48 horas por semana.</p>

<p style="text-align:justify;">Cuando el horario de labores sea discontinuo <strong>"EL TRABAJADOR"</strong> tendrá derecho a una hora de descanso para salir de la negociación y tomar alimentos; procurando que dichos horarios sean escalonados con los demás colaboradores, con la finalidad de que las áreas de trabajo siempre estén en funcionamiento.</p>

<p style="text-align:justify;">La duración de la jornada de trabajo a que se sujetará el empleado será la señalada en la ley de la materia de acuerdo a los horarios que en su concreto caso se le indique por parte de la empresa o quien esta designe.</p>

<p style="text-align:justify;">En términos del artículo 59 de la Ley Federal del Trabajo, las partes convienen que <strong>"EL PATRÓN"</strong> podrá modificar el horario según las necesidades de la empresa, siempre que se observen los límites legales. Asimismo, en caso de ser necesario <strong>"EL PATRÓN"</strong> podrá cambiar la jornada o el día de descanso semanal, previo aviso por escrito de <strong>"EL TRABAJADOR"</strong>.</p>

<p style="text-align:justify;"><strong>"EL TRABAJADOR"</strong> únicamente podrá laborar tiempo extraordinario cuando <strong>"EL PATRÓN"</strong> se lo indique, en el entendido que al <strong>"TRABAJADOR"</strong> le está prohibido laborar tiempo extra y para que esto suceda es requisito indispensable que <strong>"EL PATRÓN"</strong> si tiene interés en que <strong>"EL TRABAJADOR"</strong> labore horas extras, le otorgue por escrito una orden firmada en ese sentido en donde se especifique las horas y los días a laborar, en el entendido que a falta de este escrito no está <strong>"EL TRABAJADOR"</strong> autorizado para laborarlas y por lo tanto no se le cubrirán. Para el caso de computar el tiempo extraordinario laborado deberá <strong>"EL TRABAJADOR"</strong> recabar y conservar la orden referida a fin de que en su momento quede debidamente pagado el tiempo extra laborado; la falta de presentación de esa orden sólo es imputable a <strong>"EL TRABAJADOR"</strong>.</p>

<p style="text-align:justify;"><strong>OCTAVA.-</strong> <strong>"EL TRABAJADOR"</strong> percibirá por la prestación de sus servicios como salario diario integrado la cantidad neta de $ 476.97 (CUATROCIENTOS SETENTA Y SEIS PESOS 97/100 M.N.). Los cuáles serán cubiertos por transferencia bancaria y/o en su defecto efectivo y en moneda nacional de curso legal. El pago del salario se hará en el domicilio de la empresa, en semanas vencidas, para lo cual <strong>"EL TRABAJADOR"</strong> deberá firmar el recibo correspondiente por este pago.</p>

<p style="text-align:justify;">Del salario anterior <strong>"EL PATRÓN"</strong> hará por cuenta de <strong>"EL TRABAJADOR"</strong> las deducciones legales correspondientes, particularmente las que se refieren a impuestos sobre la Renta, Seguro Social, cuota sindical, etcétera. Asimismo, se harán las aportaciones al IMSS, Infonavit, SAR y Hacienda en los términos de las legislaciones respectivas.</p>

<p style="text-align:justify;"><strong>"EL TRABAJADOR"</strong> se obliga a firmar en los días de pago de salario, un recibo a favor de la empresa por la totalidad de los sueldos devengados y demás percepciones hasta la fecha de pago, entendiéndose que el otorgamiento del mismo implicara su conformidad en que el monto recibido cubre el trabajo desempeñado sin que pueda exigir posteriormente su pago de prestación alguna, ya que cualquier cantidad a la que creyera tener derecho deberá exigirla precisamente al otorgar el recibo correspondiente, por lo que este constituirá un amplio finiquito otorgado por el empleado a la empresa.</p>

<p style="text-align:justify;"><strong>"LAS PARTES"</strong> están de acuerdo y, por lo tanto, convienen que en el salario estipulado en la presente cláusula, se encuentra incluido el pago correspondiente al séptimo día y los días de descanso obligatorio. La empresa realizara la deducción y retención de impuestos de dicho salario bruto, de conformidad con las disposiciones aplicables, así mismo se excluyen como integrantes del salario mencionado en la presente cláusula, los premios y/o bonos eventuales apegados a Ley Federal del Trabajo, así como los que menciona el Articulo 27 de la Ley del Seguro Social, ya que están sujetos a ciertos requisitos de implementación y cumplimiento.</p>

<p style="text-align:justify;"><strong>NOVENA.-</strong> <strong>"EL TRABAJADOR"</strong> recibirá el pago de su salario en el domicilio del lugar de la prestación de sus servicios.<br>
<strong>"EL PATRÓN"</strong> pagará su salario a <strong>"EL TRABAJADOR"</strong> los días sábados de cada semana, en caso de ser día inhábil se pagará el día inmediato anterior hábil a la fecha señalada.</p>

<p style="text-align:justify;">En caso de que se llegare a implementar un programa de pago electrónico en donde la empresa realice transferencias electrónicas como nómina a una cuenta bancaria de <strong>"EL TRABAJADOR"</strong>, este autoriza desde este momento dicho proceso y servirá de comprobante de pago de nóminas y demás percepciones, los comprobantes bancarios que acrediten dicha transferencia de fondos, sin que esto lo releve de la obligación de firmar sus nóminas.</p>

<p style="text-align:justify;"><strong>DÉCIMA.-</strong> <strong>"EL TRABAJADOR"</strong> tendrá un día de descanso por cada seis de trabajo, dicho día de descanso con pago de salario diario correspondiente, conviniéndose que dicho días será el domingo de cada semana o bien el que <strong>"EL PATRÓN"</strong> indique.</p>

<p style="text-align:justify;"><strong>DÉCIMA PRIMERA.-</strong> Cuando <strong>"EL TRABAJADOR"</strong> por razones administrativas tenga que laborar el día domingo, <strong>"EL PATRÓN"</strong> le pagará, además de su salario, 25% (veinticinco por ciento) como prima dominical sobre el salario ordinario devengado. Independientemente del día de descanso semanal, al que tendrá derecho.</p>

<p style="text-align:justify;"><strong>DÉCIMA SEGUNDA.-</strong> Quedan establecidos como días de descanso obligatorio los señalados en el artículo 74 de <strong>"LA LEY"</strong>, que a la letra dicen:<br>
<dd>Artículo 74. Son días de descanso obligatorio:<br>
I. El 1o. de enero;<br>
II. El primer lunes de febrero en conmemoración del 5 de febrero;<br>
III. El tercer lunes de marzo en conmemoración del 21 de marzo;<br>
IV. El 1o. de mayo;<br>
V. El 16 de septiembre;<br>
VI. El tercer lunes de noviembre en conmemoración del 20 de noviembre;<br>
VII. El 1o. de diciembre de cada seis años, cuando corresponda a la transmisión del Poder Ejecutivo Federal;<br>
VIII. El 25 de diciembre, y<br>
IX. El que determinen las leyes federales y locales electorales, en el caso de elecciones ordinarias, para efectuar la jornada electoral.</dd></p>

<p style="text-align:justify;">En caso de que <strong>"EL TRABAJADOR"</strong> labore en alguno de estos días se le cubrirá la prima correspondiente de acuerdo a la Ley Federal del Trabajo.</p>

<p style="text-align:justify;"><strong>DÉCIMA TERCERA.-</strong> <strong>"EL TRABAJADOR"</strong> tendrá derecho a disfrutar de un periodo anual de vacaciones según lo establecido en el artículo 76 de <strong>"LA LEY"</strong> tomando en consideración la antigüedad del trabajador, así como a disfrutar del salario que le corresponda. De igual modo recibirá la Prima Vacacional respectiva, equivalente al 25% del importe pagado por concepto de vacaciones de conformidad a lo que dispone el artículo 80 de <strong>"LA LEY"</strong>.</p>

<p style="text-align:justify;"><strong>DÉCIMA CUARTA.-</strong> <strong>"EL TRABAJADOR"</strong> tendrá derecho a recibir por parte de <strong>"EL PATRÓN"</strong>, antes del día 20 de diciembre de cada año, el importe correspondiente a quince días de salario como pago del aguinaldo a que se refiere el artículo 87 de <strong>"LA LEY"</strong>, o su parte proporcional por fracción de año.</p>

<p style="text-align:justify;"><strong>DÉCIMA QUINTA.-</strong> <strong>"EL TRABAJADOR"</strong> acepta someterse a los exámenes médicos que periódicamente establezca <strong>"EL PATRÓN"</strong> en los términos del artículo 134 fracción X de <strong>"LA LEY"</strong>, a fin de mantener en forma óptima sus facultades físicas e intelectuales, para el mejor desempeño de sus funciones.</p>

<p style="text-align:justify;">El médico que practique los reconocimientos será designado y retribuido por <strong>"EL PATRÓN"</strong>.</p>

<p style="text-align:justify;"><strong>DÉCIMA SEXTA.-</strong> <strong>"EL TRABAJADOR"</strong> deberá integrarse a los Planes, Programas y Comisiones Mixtas de Capacitación y Adiestramiento, así como a los de Seguridad e Higiene en el Trabajo que tiene constituidos <strong>"EL PATRÓN"</strong>, tomando parte activa dentro de los mismos según los cursos establecidos y medidas preventivas de riesgos de trabajo.</p>

<p style="text-align:justify;"><strong>DÉCIMA SÉPTIMA.-</strong> <strong>"EL TRABAJADOR"</strong> deberá observar y cumplir todo lo contenido en el Reglamento Interior de Trabajo con que cuenta <strong>"EL PATRÓN"</strong> y que tiene fijado en las áreas de mayor visibilidad.</p>

<p style="text-align:justify;"><strong>DÉCIMA OCTAVA.-</strong> <strong>"EL TRABAJADOR"</strong> acepta y por ende queda establecido que cuando por razones convenientes para <strong>"EL PATRÓN"</strong> éste modifique el horario de trabajo, podrá desempeñar su jornada en el que quede establecido ya que sus actividades al servicio de <strong>"EL PATRÓN"</strong> son prioritarias y no se contraponen a otras que pudiere llegar a desarrollar.</p>

<p style="text-align:justify;"><strong>DÉCIMA NOVENA.-</strong> Ambas partes declaran que conocen sus obligaciones y prohibiciones, por lo que respecta a <strong>"EL PATRÓN"</strong> establecidas en los artículos 132 y 133 de la Ley Federal del Trabajo, y por lo que refiere a <strong>"EL TRABAJADOR"</strong> señaladas en los artículos 134 y 135 del ordenamiento legal antes citado.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA.-</strong> Las partes pactan expresamente conforme a lo establecido en el artículo 31 de la Ley Federal del Trabajo, que será un motivo para rescindir el contrato laboral, sin responsabilidad para <strong>"EL PATRÓN"</strong>, que <strong>"EL TRABAJADOR"</strong> utilice y/o navegue en el internet y/o en los medios de comunicación electrónicos para asuntos personales o por mera distracción de forma excesiva, indebida o por tiempo prolongado.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA PRIMERA.-</strong> <strong>"EL TRABAJADOR"</strong> deberá presentarse puntualmente a sus labores en el horario de trabajo establecido y firmar las listas de asistencia acostumbradas o checar su tarjeta de asistencia en el reloj checador diariamente. En caso de retraso o falta de asistencia injustificada podrá <strong>"EL PATRÓN"</strong> imponerle cualquier corrección disciplinaria de las que contempla el Reglamento Interior de Trabajo o <strong>"LA LEY"</strong>.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA SEGUNDA.-</strong> <strong>"EL TRABAJADOR"</strong>, está obligado a guardar estricta reserva de la información, procedimientos y todos aquellos hechos y actos que con motivo de su trabajo sean de su conocimiento, por lo tanto, se obliga a no utilizar ni por si, ni por interpósita persona, en su beneficio o en beneficio de terceras personas, ya sea directa o indirectamente la información, actos y demás hechos que sean de su conocimiento en especial toda aquella información, procedimientos, secretos comerciales, industriales, etc. que se encuentren protegidos por la ley, incluso después de concluida la relación de trabajo.<br>
Todas las invenciones, ideas, mejoras, etc., patentables o no, desarrolladas por el empleado con motivo de sus actividades o empleo al servicio de la empresa, serán siempre propiedad de esta última, en términos de lo dispuesto por la fracción II, del artículo 163 de la Ley Federal del Trabajo.</p>

<p style="text-align:justify;"><strong>"EL TRABAJADOR"</strong> deberá guardar absoluta confidencialidad sobre asuntos que le sean encomendados o cualquier otra información que en razón de sus funciones, llegase a tener en su poder y a usarla exclusivamente en beneficio de la empresa, debiendo guardar expresa reserva sobre la información privilegiada que pudiera tener en su poder.</p>

<p style="text-align:justify;">La limitación establecida en la presente cláusula estará vigente incluso después de concluida la relación de trabajo hasta por un término de cinco años, salvo aquellas relacionadas con la confidencialidad de secretos industriales, la cual será válida y exigible hasta que esa información deje de tener dicha naturaleza.</p>

<p style="text-align:justify;"><strong>"EL PATRON"</strong> está de acuerdo con entregar la información confidencial a <strong>"EL TRABAJADOR"</strong> que se encuentra contenida en los medios materiales que se refieren a continuación, los cuales se señalan de manera enunciativa mas no limitativa, así como la información que se relacione directa o indirectamente con dichos medios materiales, tales como:</p>

<p style="text-align:justify;"><ul><li>Bases de datos personales.</li>
<li>Base de datos de los clientes.</li>
<li>Documentos reservados de dominio de la empresa.</li>
<li>Lista de clientes.</li>
<li>Contraseñas de las distintas instituciones públicas y privadas.</li></ul></p>

<p style="text-align:justify;">Así mismo, <strong>"EL TRABAJADOR"</strong> se obliga a dar el cuidado estricto y cumplir con la normatividad de las leyes de privacidad, como el aviso de privacidad de la empresa o las que resulten por el manejo de información confidencial de la empresa o la información de terceros.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA TERCERA.-</strong> El <strong>"TRABAJADOR"</strong> asume la responsabilidad del resguardo, cuidado, obligación de entrega, así como la correcta administración de la herramienta o equipo a su resguardo por el puesto asignado como pueden ser:
<ul><li>Llaves de los establecimientos o vehículos a su resguardo o por motivos de la actividad cotidiana de su puesto y/o actividades tuviera a su cargo.</li>
<li>Contraseñas y/o Passports que se le proporcionen, esto por y con motivos de sus actividades de su puesto, mismas de administración de su patrón o de empresas externas para su correcto desempeño.</li>
<li>En el caso que “EL PATRÓN” le otorgue un teléfono celular o que tenga a su disposición alguna línea telefónica.</li>
<li>En el caso que “EL PATRÓN” le otorgue cualquier equipo electrónico, proyectores, Tablet o Computadora laptop o alguna otra para su correcto desempeño del puesto asignado.</li>
<li>Administración de la caja chica efectivo, cheques, vales, entre otros.</li></ul></p>

<p style="text-align:justify;"><strong>VIGÉSIMA CUARTA.-</strong> La parte trabajadora manifiesta haber recibido copia del Contrato Colectivo de Trabajo, del Reglamento Interior de Trabajo y del Anexo donde se describe su puesto, por tanto, se obliga a observar lo establecido en los mismos.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA QUINTA.-</strong> <strong>"EL TRABAJADOR"</strong> manifiesta que se ha puesto a su disposición de manera previa a la firma del presente acuerdo de voluntades el Aviso de Privacidad Integral de <strong>"EL PATRÓN"</strong>, a quien otorga expresamente su consentimiento para que sus datos personales sean utilizados en los términos y finalidades previstos en el Aviso de Privacidad. Es obligación de <strong>"EL PATRON"</strong> cumplir con lo dispuesto por la Ley Federal de Protección de Datos Personales y su Reglamento, así como a dar tratamiento a los datos personales que le sean transmitidos en base a lo establecido en su Aviso de Privacidad.</p>

<p style="text-align:justify;"><strong>VIGÉSIMA SEXTA.-</strong> El presente contrato anula y deja sin efectos cualquier otro contrato, convenio o acuerdo celebrado con anterioridad entre las partes. Consecuentemente, las cláusulas de este contrato y los acuerdos que deriven del mismo serán los únicos y válidos y exigibles entre las partes.<br>
Las partes convienen que lo no previsto en este contrato, se regirá por la <strong>"LEY FEDERAL DEL TRABAJO"</strong> o bien por lo prescrito en el Contrato Colectivo de Trabajo, así como por el Reglamento Interior de Trabajo; y para su interpretación, observancia, ejecución y cumplimiento se someten expresamente a la competencia y jurisdicción de la JUNTA LOCAL DE CONCILIACIÓN Y ARBITRAJE CON SEDE EN LA CIUDAD DE GUADALAJARA, JALISCO.</p>

<p style="text-align:justify;">LEÍDO QUE FUE EL PRESENTE CONTRATO POR QUIENES EN ÉL INTERVIENEN LO RATIFICAN E IMPUESTOS DE SU CONTENIDO LO SUSCRIBEN POR DUPLICADO QUEDANDO EN PODER DE CADA PARTE.</p>


<p style="text-align:center;"><strong>EN GUADALAJARA, JALISCO A 06 DÍAS DEL MES DE OCTUBRE DEL 2020.</strong></p>

<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"EL PATRON"</strong>
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"EL TRABAJADOR"</strong></p>
</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>
<p>_______________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________</p>
<p><strong>C. HELIODORO HERNANDEZ MUÑOZ.</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>C. '.$nombre.'.</strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Representante legal de&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Por su propio derecho.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
&nbsp;&nbsp;&nbsp;<strong>GH ASISTENCIA, S.A. DE C.V.</strong></p>

</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br>

<p>_______________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________</p>

<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TESTIGO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TESTIGO</p>

  ';

  // Print text using writeHTML()
  $pdf->writeHTML($html, true, 0, true, true);

  // ---------------------------------------------------------

  // Close and output PDF document
  // This method has several options, check the source code documentation for more information.
  $pdf->Output($nombre.'.pdf', 'I');

  //============================================================+
  // END OF FILE
  //============================================================+

?>
