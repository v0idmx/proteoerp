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
		<td class="littletablerowth"><?php echo $form->codigo->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->codigo->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->descrip->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->descrip->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->fcompra->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->fcompra->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->ncompra->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->ncompra->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->incorpora->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->incorpora->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->vidautil->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->vidautil->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->residual->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->residual->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->costo->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->costo->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->depacum->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->depacum->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->valorl->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->valorl->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->valora->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->valora->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->afectacont->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->afectacont->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->ubica->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->ubica->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->ficha->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->ficha->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->cuentaa->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->cuentaa->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->cuentad->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->cuentad->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->cuentac->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->cuentac->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->serial1->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->serial1->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->serial2->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->serial2->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->serial3->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->serial3->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->matricula->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->matricula->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->responsable->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->responsable->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->status->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->status->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->origen->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->origen->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->retiro->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->retiro->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->motivo->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->motivo->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->idgr->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->idgr->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->idfami->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->idfami->output; ?></td>
	</tr>
	<tr>
		<td class="littletablerowth"><?php echo $form->id->label;  ?></td>
		<td class="littletablerow"  ><?php echo $form->id->output; ?></td>
	</tr>
</table>
</fieldset>
<?php echo $form_end; ?>
