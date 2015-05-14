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

<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
<table width='100%'>
	<tr>
		<td class="littletablerowth"><?php echo $form->grupo->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->grupo->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->gasto->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->gasto->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->imueble->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->imueble->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->lectira->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->lectira->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->monto->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->monto->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->fecha->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->fecha->output; ?></td>
	</tr>
</table>
</fieldset>
<?php echo $form_end; ?>
