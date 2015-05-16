<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
echo $form_scripts;
echo $form_begin;

if(isset($form->error_string)) echo '<div class="alert">'.$form->error_string.'</div>';
if($form->_status <> 'show'){ ?>

<script language="javascript" type="text/javascript">
</script>
<?php } ?>
<?php echo $form->grupo->output; ?>
<?php echo $form->anomes->output; ?>
<?php echo $form->longi->output; ?>

<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
<table width='100%'>
	<tr>
		<td class="littletablerowth"><?php echo $form->gasto->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->gasto->output; ?></td>
		<td class="littletablerow"  ><?php echo $nomgru; ?></td>
		<td class="littletablerow"  ><div onclick='calcular()'>Calcular</div></td>
	</tr>
</table>
</fieldset>
<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
<table width='100%'>
	<tr style='background:#E09B07'>
		<th >Inmueble</th>
		<th >Alicuota/Lectura</th>
		<th >Monto</th>
	</tr>
	<?php for( $i = 0; $i< $longi; $i++ ) {
		$obj1 = "inmueble_$i";
		$obj2 = "lectura_$i";
		$obj3 = "monto_$i";
		$obj4 = "descrip_$i";
	?>
	<tr id='tr_itstra_<?php echo $i; ?>'>
		<td class="littletablerow"              ><?php echo $form->$obj1->output.$form->$obj4->output ?></td>
		<td class="littletablerow" align="right"><?php echo $form->$obj2->output ?></td>
		<td class="littletablerow" align="right"><?php echo $form->$obj3->output ?></td>
	</tr>
	<?php } ?>
	<tr id='totales'>
		<td class="littletablerow" align="right">Total</td>
		<td class="littletablerow" align="right"><div id="sumalectu"></td>
		<td class="littletablerow" align="right"><div id="sumamonto"></div></td>
	</tr>
</table>
</fieldset>
<?php echo $form_end; ?>
