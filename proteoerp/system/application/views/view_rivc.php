<?php
$container_bl=join('&nbsp;', $form->_button_container['BL']);
$container_br=join('&nbsp;', $form->_button_container['BR']);
$container_tr=join('&nbsp;', $form->_button_container['TR']);

if ($form->_status=='delete' || $form->_action=='delete' || $form->_status=='unknow_record'):
	echo $form->output;
else:

$campos=$form->template_details('itrivc');
$scampos  ='<tr id="tr_itrivc_<#i#>">';
$scampos .='<td class="littletablerow" align="left" ><b id="tipo_doc_val_<#i#>"></b>'.$campos['it_tipo_doc']['field'].'</td>';
$scampos .='<td class="littletablerow" align="left" >'.$campos['it_numero']['field'].'</td>';
$scampos .='<td class="littletablerow" align="left" ><b id="fecha_val_<#i#>"></b>'.$campos['it_fecha']['field'].'</td>';
$scampos .='<td class="littletablerow" align="right"><b id="gtotal_val_<#i#>"></b>'.$campos['it_gtotal']['field'].'</td>';
$scampos .='<td class="littletablerow" align="right"><b id="impuesto_val_<#i#>"></b>'.$campos['it_impuesto']['field'].'</td>';
$scampos .='<td class="littletablerow" align="right">'.$campos['it_reiva']['field'].'</td>';
$scampos .='<td class="littletablerow"><a href=# onclick="del_itrivc(<#i#>);return false;">'.img("images/delete.jpg").'</a></td></tr>';
$campos=$form->js_escape($scampos);

if(isset($form->error_string)) echo '<div class="alert">'.$form->error_string.'</div>';

echo $form_begin;
if($form->_status!='show'){ ?>

<script language="javascript" type="text/javascript">
var itrivc_cont =<?php echo $form->max_rel_count['itrivc']; ?>;

$(function(){
	$(".inputnum").numeric(".");
	totalizar();
	for(var i=0;i < <?php echo $form->max_rel_count['itrivc']; ?>;i++){
		autocod(i.toString());
	}
	
	$('#cod_cli').autocomplete({
		source: function( req, add){
			$.ajax({
				url:  "<?php echo site_url('finanzas/rivc/buscascli'); ?>",
				type: "POST",
				dataType: "json",
				data: "q="+req.term,
				success:
					function(data){
						var sugiere = [];
						$.each(data,
							function(i, val){
								sugiere.push( val );
							}
						);
						add(sugiere);
					},
			})
		},
		minLength: 1,
		select: function( event, ui ) {
			$('#nombre').val(ui.item.nombre);
			$('#nombre_val').text(ui.item.nombre);
			$('#rif').val(ui.item.rifci);
			$('#rif_val').text(ui.item.rifci);
			//$('#cod_cli').val(ui.item.cod_cli);
		}
	});

});

function totalizar(){
	var gtotal    =0;
	var impuesto  =0;
	var reiva     =0;
	var itreiva   =0;
	var itimpuesto=0;
	var itgtotal  =0;

	var arr=$('input[name^="reiva_"]');
	jQuery.each(arr, function() {
		nom=this.name
		pos=this.name.lastIndexOf('_');
		if(pos>0){
			ind       = this.name.substring(pos+1);
			ittipo_doc= $("#tipo_doc_"+ind).val();

			itreiva   = Number(Math.abs(this.value));
			itimpuesto= Number(Math.abs($("#impuesto_"+ind).val()));
			itgtotal  = Number(Math.abs($("#gtotal_"+ind).val()));
			if(ittipo_doc=='D'){
				itreiva   = (-1)*itreiva;
				itimpuesto= (-1)*itimpuesto;
				itgtotal  = (-1)*itgtotal;
			}

			impuesto=impuesto+itimpuesto;
			reiva   =reiva+itreiva;
			gtotal  =gtotal+itgtotal;
		}
	});
	
	$("#reiva").val(roundNumber(reiva,2));
	$("#impuesto").val(roundNumber(impuesto,2));
	$("#gtotal").val(roundNumber(gtotal,2));

	$('#reiva_val').text(nformat(reiva,2));
	$('#impuesto_val').text(nformat(impuesto,2));
	$('#gtotal_val').text(nformat(gtotal,2));
}

function add_itrivc(){
	var htm = <?php echo $campos; ?>;
	can = itrivc_cont.toString();
	con = (itrivc_cont+1).toString();
	htm = htm.replace(/<#i#>/g,can);
	htm = htm.replace(/<#o#>/g,con);
	$("#__INPL__").after(htm);
	$("#reva_"+can).numeric(".");
	autocod(can);
	$('#numero_'+can).focus();

	itrivc_cont=itrivc_cont+1;
}


function del_itrivc(id){
	id = id.toString();
	$('#tr_itrivc_'+id).remove();
	totalizar();
}

//Agrega el autocomplete
function autocod(id){
	$('#numero_'+id).autocomplete({
		source: function( req, add){
			$.ajax({
				url:  "<?php echo site_url('finanzas/rivc/buscasfac'); ?>",
				type: "POST",
				dataType: "json",
				data: "q="+encodeURIComponent(req.term)+"&scli="+encodeURIComponent($("#cod_cli").val()),
				success:
					function(data){
						var sugiere = [];
						$.each(data,
							function(i, val){
								sugiere.push( val );
							}
						);
						add(sugiere);
					},
			})
		},
		minLength: 0,
		select: function( event, ui ) {
			$('#tipo_doc_'+id).val(ui.item.tipo_doc);
			$('#tipo_doc_val_'+id).text(ui.item.tipo_doc);

			$('#numero_'+id).val(ui.item.numero);

			$('#gtotal_'+id).val(ui.item.gtotal);
			$('#gtotal_val_'+id).text(nformat(ui.item.gtotal,2));

			$('#impuesto_'+id).val(ui.item.impuesto);
			$('#impuesto_val_'+id).text(nformat(ui.item.impuesto,2));

			$('#reiva_'+id).val(ui.item.reiva);

			//$('#fecha_val_'+id).text(ui.item.fecha);
			$('#fecha_'+id).val(ui.item.fecha);

			totalizar();
		}
	});
}
</script>
<?php } ?>

<table width='100%' align='center'>

	<?php if($form->_status=='show'){ ?>
	<tr>
		<td valign="bottom">
			<a href="javascript:void(0);" onclick="window.open('/proteoerp/formatos/verhtml/RIVC/<?php echo $form->get_from_dataobjetct('id'); ?>', '_blank', 'width=800, height=600, scrollbars=Yes, status=Yes, resizable=Yes, screenx='+((screen.availWidth/2)-400)+',screeny='+((screen.availHeight/2)-300)+'');">
			<img src="/proteoerp/images/reportes.gif" alt="Imprimir Documento" title="Imprimir Documento" border="0" height="30">						</a>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align=right>
			<?php echo $container_tr; ?>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%"  style="margin:0;width:100%;" cellspacing='2' cellpadding='2'>
				<tr>
					<td colspan=11 class="littletableheader">Encabezado</td>
				</tr>
				<tr>
					<td>
					<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
						<table>
							<tr>
								<td class="littletablerowth"><?php echo $form->nrocomp->label  ?>*</td>
								<td class="littletablerow">  <?php echo $form->periodo->output.$form->nrocomp->output ?></td>
							</tr><tr>
								<td class="littletablerowth"><?php echo $form->emision->label  ?>*</td>
								<td class="littletablerow">  <?php echo $form->emision->output ?></td>
							</tr><tr>
								<td class="littletablerowth"><?php echo $form->fecha->label  ?>*</td>
								<td class="littletablerow">  <?php echo $form->fecha->output ?></td>
							</tr>
						</table>
					</fieldset>
					</td><td>
					<fieldset  style='border: 1px outset #FEB404;background: #FFFCE8;'>
						<table>
							<tr>
								<td class="littletablerowth"><?php echo $form->cod_cli->label  ?>*</td>
								<td class="littletablerow">  <?php echo $form->cod_cli->output ?></td>
							</tr><tr>
								<td class="littletablerowth"><?php echo $form->rif->label  ?>*</td>
								<td class="littletablerow">  <b id='rif_val'><?php echo $form->rif->value; ?></b><?php echo $form->rif->output ?></td>
							</tr><tr>
								<td class="littletablerowth"><?php echo $form->nombre->label  ?></td>
								<td class="littletablerow">  <b id='nombre_val'><?php echo $form->nombre->value ?></b><?php echo $form->nombre->output ?></td>
							</tr>
						</table>
					</fieldset>
					</td>
				</tr>
			</table>
		</tr>
	<tr>
</table>

		<div style='overflow:auto;border: 1px solid #9AC8DA;background: #FAFAFA;height:200px'>
		<table width='100%'>
			<tr id='__INPL__'>
				<th bgcolor='#7098D0'>Tipo    </th>
				<th bgcolor='#7098D0'>N&uacute;mero</th>
				<th bgcolor='#7098D0'>Fecha</th>
				<th bgcolor='#7098D0'>Monto Factura</th>
				<th bgcolor='#7098D0'>Impuesto</th>
				<th bgcolor='#7098D0'>Monto retenido</th>
				<?php if($form->_status!='show') {?>
					<th bgcolor='#7098D0'>&nbsp;</th>
				<?php } ?>
			</tr>

			<?php for($i=0;$i<$form->max_rel_count['itrivc'];$i++) {
				$it_tipo_doc = "it_tipo_doc_$i";
				$it_numero   = "it_numero_$i";
				$it_gtotal   = "it_gtotal_$i";
				$it_impuesto = "it_impuesto_$i";
				$it_reiva    = "it_reiva_$i";
				$it_fecha    = "it_fecha_$i";
			?>

			<tr id='tr_itrivc_<?php echo $i; ?>'>
				<td class="littletablerow" align="left" >
				<b id='tipo_doc_val_<?php echo $i ?>'><?php echo $form->$it_tipo_doc->value; ?></b>
				<?php echo $form->$it_tipo_doc->output; ?>
				</td>
				<td class="littletablerow" align="left" ><?php echo $form->$it_numero->output;   ?></td>
				<td class="littletablerow" >
				<?php echo $form->$it_fecha->output;   ?>
				</td>
				<td class="littletablerow" align="right">
				<b id='gtotal_val_<?php echo $i ?>'><?php echo $form->$it_gtotal->value; ?></b>
				<?php echo $form->$it_gtotal->output;   ?>
				</td>
				<td class="littletablerow" align="right">
				<b id='impuesto_val_<?php echo $i ?>'><?php echo $form->$it_impuesto->value; ?></b>
				<?php echo $form->$it_impuesto->output; ?>
				</td>
				<td class="littletablerow" align="right"><?php echo $form->$it_reiva->output;    ?></td>
				<?php if($form->_status!='show') {?>
				<td class="littletablerow">
					<a href='#' onclick='del_itrivc(<?php echo $i ?>);return false;'><?php echo img("images/delete.jpg");?></a>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</table>
		</div>
		<?php echo $container_bl ?>
		<?php echo $container_br ?>
		<br>

<table  width="100%" style="margin:0;width:100%;" > 
	<tr>
		<td colspan=10 class="littletableheader">Totales</td>      
	</tr>
	<tr>
		<td class="littletablerowth" align='right'><?php echo $form->gtotal->label;    ?></td>
		<td class="littletablerow"   align='right'><b id='gtotal_val'><?php echo nformat($form->gtotal->value);     ?></b><?php echo $form->gtotal->output;   ?></td>
		<td class="littletablerowth" align='right'><?php echo $form->impuesto->label;  ?></td>
		<td class="littletablerow"   align='right'><b id='impuesto_val'><?php echo nformat($form->impuesto->value); ?></b><?php echo $form->impuesto->output; ?></td>
		<td class="littletablerowth" align='right'><?php echo $form->reiva->label; ?></td>
		<td class="littletablerow"   align='right'><b id='reiva_val'><?php echo nformat($form->reiva->value);       ?></b><?php echo $form->reiva->output;    ?></td>
	</tr>
</table>

	  <td>
	<tr>
<table>
<?php echo $form_end?>

<?php if($form->_status=='show'){ ?>
<br>
<table  width="100%" style="margin:0;width:100%;" > 
	<tr>
		<td colspan=10 class="littletableheader">Documentos relacionados</td>
	</tr>
	<!-- <tr>
		<th class="littletablerowth" >Cliente</th>
		<th class="littletablerowth" >Tipo</th>
		<th class="littletablerowth" >Numero</th>
		<th class="littletablerowth" >Concepto</th>
		<th class="littletablerowth" align='right'>Monto</th>
	</tr> -->
	<?php
	$transac=$form->get_from_dataobjetct('transac');
	$mSQL='SELECT cod_cli, nombre,tipo_doc, numero, monto, observa1 FROM smov WHERE transac='.$this->db->escape($transac);
	$query = $this->db->query($mSQL);
	if ($query->num_rows() > 0){
		foreach ($query->result() as $row){
	?>
	<tr>
		<td class="littletablerowth" ><?php echo $row->cod_cli.' '.$row->nombre;    ?></td>
		<td class="littletablerowth" align='center'><?php echo $row->tipo_doc; ?></td>
		<td class="littletablerow"   ><?php echo $row->numero;   ?></td>
		<td class="littletablerowth" ><?php echo $row->observa1; ?></td>
		<td class="littletablerow"   align='right'><?php echo nformat($row->monto);?></td>
	</tr>
	<?php
		}
	}?>
</table>
<?php  } ?>

<?php endif; ?>
