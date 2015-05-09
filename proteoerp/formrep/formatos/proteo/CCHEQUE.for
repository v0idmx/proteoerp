<?php
if(count($parametros)==0) show_error('Faltan parametros ');
$id = $parametros[0];
$mSQL = $this->db->query("SELECT * FROM view_ccheque WHERE id=${id}");
if($mSQL->num_rows()==0) show_error('Registro no encontrado');

$row = $mSQL->row();
$fecha    = dbdate_to_human($row->fecha);
$numero   = $row->numero;

$cod_cli  = $row->cod_cli;
$nombre   = $row->nombre;
$direc    = $row->dire11;
$dire1    = $row->dire12;
$telefono = $row->telefono;
$rifci    = $row->rifci;
$ciudad   = $row->ciudad1;

$monto    = $row->monto;
$tipo     = $row->tipo;
$num_ref  = $row->num_ref;
$nombanc  = $row->nombanc;
$tarjeta  = $row->ntarjeta;

$nomcajero  = $row->nomcajero;
$usuario  = $row->us_nombre;
?>
<html>
<head>
<title>Comprobante de Cambio de Cheque <?php echo $numero ?></title>
<link rel="STYLESHEET" href="<?php echo $this->_direccion ?>/assets/default/css/formatos.css" type="text/css" />

</head>
<body>
<script type="text/php">
if ( isset($pdf) ) {

  $font = Font_Metrics::get_font("verdana");
  $size = 6;
  $color = array(0,0,0);
  $text_height = Font_Metrics::get_font_height($font, $size);

  $foot = $pdf->open_object();

  $w = $pdf->get_width();
  $h = $pdf->get_height();

  // Draw a line along the bottom
  $y = $h - $text_height - 24;
  $pdf->line(16, $y, $w - 16, $y, $color, 0.5);

  $pdf->close_object();
  $pdf->add_object($foot, "all");

  $text = "PP {PAGE_NUM} de {PAGE_COUNT}";

  // Center the text
  $width = Font_Metrics::get_text_width("PP 1 de 2", $font, $size);
  $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);

}
</script>
<div id="body">
<table style="width: 100%;">
	<thead>
	<tr>
		<td>
			<div id="section_header">
			<table style="width: 100%;" class="header">
				<tr>
					<td width=140 rowspan="2"><img src="<?php echo site_url('supervisor/logo/traer/logo127.jpg') ?>"></td>
					<td><h1 style="text-align: center"><?php echo $this->datasis->traevalor('TITULO1'); ?></h1></td>
				</tr>
				<tr>
					<td><div class="page" style="font-size: 7pt;text-align: center">
						<?php echo $this->datasis->traevalor('TITULO2').'<br>'.$this->datasis->traevalor('TITULO3'); ?> <br>
						<b>RIF: <?php echo $this->datasis->traevalor('RIF'); ?></b>
						</div>
					</td>
				</tr>
			</table>
			</div>
			<div class="page" style="font-size: 9pt">
			<table style="width: 100%;" class="header">
				<tr>
					<td><h1 style="text-align: left">Cambio por Efectivo</h1></td>
					<td><h1 style="text-align: right">N&uacute;mero: <?php echo $numero ?></h1></td>
				</tr>
			</table>
			<table style="width: 100%; font-size: 11pt;">
				<tr>
					<td width="70" >Fecha:</td>
					<td colspan="2"><strong><?php echo $fecha; ?></strong></td>
				</tr>
				<tr>
					<td>Cliente:</td>
					<td> <strong><?php echo $nombre; ?></strong></td>
					<td>Rif: <strong><?php echo $rifci; ?></strong></td>
				</tr>
				<tr>
					<td>Direcci&oacute;n:</td>
					<td> <strong><?php echo $direc; ?></strong></td>
					<td>Ciudad: <strong><?php echo $ciudad; ?></strong></td>
				</tr>
				<tr>
					<td></td>
					<td><strong><?php echo $dire1; ?></strong></td>
					<td>Tel&eacute;fono: <strong><?php echo $telefono; ?></strong></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br><br></td></tr>
	</thead>
	<tbody>
	<tr>
		<td>
			<table width='80%' align='center'>
				<tr>
					<td colspan="2" align="center">He recibido de <?php echo $this->datasis->traevalor('TITULO1'); ?> la cantidad de </td>
				</tr><tr>
					<td colspan='2' align="center" style="font-size:28px" ><br><b>##<?php echo 'Bs.'.nformat($monto); ?>##</b></td>
				</tr><tr>
					<td colspan="2" align="center"><br>por concepto de cambio del siguiente medio por efectivo </td>
				</tr><tr>
					<td width="90" >Banco:</td>
					<td><strong><?php echo $nombanc; ?></strong></td>
				</tr>
				<tr>
					<td>Medio de pago:</td>
					<td><strong><?php echo $tarjeta; ?></strong></td>
				</tr>
				<tr>
					<td>N&uacute;mero:</td>
					<td><strong><?php echo $num_ref; ?></strong></td>
				</tr>
			</table>
		</td>
	</tr><tr>
		<td><br><br>Declaro que recibi conforme:</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<td>
		<div id="content">
		<div class="page" style="font-size: 7pt">
		<table class="change_order_items">
			<tr>
				<td style="text-align:center;"><strong>Firma del Cliente:</strong></td>
				<td style="text-align:center;"><strong>Firma del Cajero:</strong></td>
				<td style="text-align:center;"><strong>Firma Autorizada:</strong></td>
			</tr><tr>
				<td style="text-align:center;"><br><br><br><br><br></td>
				<td style="text-align:center;"><br><br><br></strong></td>
				<td style="text-align:center;"><br><br><br></strong></td>
			</tr><tr>
				<td style="text-align:center;"><b><?php echo $nombre; ?></b></td>
				<td style="text-align:center;"><b><?php echo $nomcajero; ?></b></td>
				<td style="text-align:center;"><b><?php echo $usuario; ?></b></td>
			</tr>
		</table>
		</div>
		</div></td>
	</tr>
	</tfoot>
</table>
</div>

</body>
</html>
