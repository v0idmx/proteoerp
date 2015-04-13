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
		<td class="littletablerowth"><?php echo $form->almacen->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->almacen->output; ?></td>
	</tr><tr>
		<td class="littletablerowth"><?php echo $form->fecha->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->fecha->output; ?></td>
	</tr><tr>
		<td class="littletablerowth"><?php echo $form->status->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->status->output; ?></td>
	</tr><tr>
		<td class="littletablerowth"><?php echo $form->instrucciones->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->instrucciones->output; ?></td>
	</tr>
</table>
</fieldset>
<?php echo $form_end; ?>
