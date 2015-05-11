<?php
/**
 * ProteoERP
 *
 * @autor    Andres Hocevar
 * @license  GNU GPL v3
*/
//ob_start('comprimir_pagina');

$container_bl = join('&nbsp;', $form->_button_container['BL']);
$container_br = join('&nbsp;', $form->_button_container['BR']);
$container_tr = join('&nbsp;', $form->_button_container['TR']);

if ($form->_status=='delete' || $form->_action=='delete' || $form->_status=='unknow_record'):
	echo $form->output;
else:

if(isset($form->error_string)) echo '<div class="alert">'.$form->error_string.'</div>';

echo $form_begin;

if($form->getstatus()!='show'){
?>
<script type="text/javascript">
var objptasa=<?php echo $json_ptasa; ?>;

$(function(){
	$(".inputnum").numeric(".");

	$('form').submit(function() {
		var r=confirm("Confirma guardar las transacciones?");
		return r;
	});

	$('#fecha').datepicker({   dateFormat: "dd/mm/yy" });
	$('#posdata').datepicker({ dateFormat: "dd/mm/yy" });

	$('#montasa'  ).focus(function (){ invasdif('montasa','tasa'     ,Number($("#ptasa"     ).val())/100); });
	$('#monredu'  ).focus(function (){ invasdif('monredu','reducida' ,Number($("#preducida" ).val())/100); });
	$('#monadic'  ).focus(function (){ invasdif('monadic','sobretasa',Number($("#padicional").val())/100); });
	$('#tasa'     ).focus(function (){ invasdif('montasa','tasa'     ,Number($("#ptasa"     ).val())/100); });
	$('#reducida' ).focus(function (){ invasdif('monredu','reducida' ,Number($("#preducida" ).val())/100); });
	$('#sobretasa').focus(function (){ invasdif('monadic','sobretasa',Number($("#padicional").val())/100); });
	$('#exento'   ).focus(function (){ invasdif('exento' ,'E'        ,0);     });


	$('#montasa').keyup(function (){
		var ptasa = Number($("#ptasa").val())/100;
		var base  = Number($('#montasa').val());
		$('#tasa').val(roundNumber(base*ptasa,2));
		calretiva();
	});

	$('#monredu').keyup(function (){
		var ptasa = Number($("#preducida").val())/100;
		var base  = Number($('#monredu').val());
		$('#reducida').val(roundNumber(base*ptasa,2));
		calretiva();
	});

	$('#monadic').keyup(function (){
		var ptasa = Number($("#padicional").val())/100;
		var base  = Number($('#monadic').val());
		$('#sobretasa').val(roundNumber(base*ptasa,2));
		calretiva();
	});

	$('#tasa').keyup(function (){
		var ptasa    = Number($("#ptasa").val())/100;
		var impuesto = Number($('#tasa').val());
		$('#montasa').val(roundNumber(impuesto*ptasa,2));
		calretiva();
	});

	$('#reducida').keyup(function (){
		var ptasa    = Number($("#preducida").val())/100;
		var impuesto = Number($('#reducida').val());
		$('#monredu').val(roundNumber(impuesto*ptasa,2));
		calretiva();
	});

	$('#sobretasa').keyup(function (){
		var ptasa    = Number($("#padiciona").val())/100;
		var impuesto = Number($('#sobretasa').val());
		$('#monadic').val(roundNumber(impuesto*ptasa,2));
		calretiva();
	});

	$('#cod_prv').autocomplete({
		delay: 600,
		autoFocus: true,
		source: function( req, add){
			$.ajax({
				url:  "<?php echo site_url('ajax/buscasprv'); ?>",
				type: "POST",
				dataType: 'json',
				data: {"q":req.term},
				success:
					function(data){
						var sugiere = [];
						if(data.length==0){
							$('#nombre').val('');
							$('#nombre_val').text('');
							$('#proveed').val('');
							$('#sprvreteiva').val('0');
						}else{
							$.each(data,
								function(i, val){
									sugiere.push( val );
								}
							);
						}
						add(sugiere);
					},
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$('#cod_prv').attr('readonly', 'readonly');
			$('#nombre').val(ui.item.nombre);
			$('#nombre_val').text(ui.item.nombre);
			$('#cod_prv').val(ui.item.proveed);
			//$('#sprvreteiva').val(ui.item.reteiva);
			setTimeout(function(){ $('#cod_prv').removeAttr('readonly'); }, 1500);
		}
	});

	$('#afecta').autocomplete({
		delay: 600,
		autoFocus: true,
		source: function( req, add){
			var cod_prv = $('#cod_prv').val();
			if(cod_prv == ''){
				$('#cod_prv').focus();
				$('#afecta').val('');
			}else{
				$.ajax({
					url:  "<?php echo site_url('ajax/buscaafectasprm'); ?>",
					type: "POST",
					dataType: 'json',
					data: {'q':req.term, 'sprv':cod_prv},
					success:
						function(data){
							var sugiere = [];
							if(data.length==0){
								$('#afecta').val('');
								$('#montasa'  ).val('');
								$('#monredu'  ).val('');
								$('#monadic'  ).val('');
								$('#tasa'     ).val('');
								$('#reducida' ).val('');
								$('#sobretasa').val('');

							}else{
								$.each(data,
									function(i, val){
										sugiere.push( val );
									}
								);
							}
							add(sugiere);
						},
				});
			}
		},
		minLength: 2,
		select: function( event, ui ) {
			$('#afecta').attr('readonly', 'readonly');
			$('#afecta').val(ui.item.serie);
			$('#montasa'  ).val(ui.item.montasa  );
			$('#monredu'  ).val(ui.item.monredu  );
			$('#monadic'  ).val(ui.item.monadic  );
			$('#tasa'     ).val(ui.item.tasa     );
			$('#reducida' ).val(ui.item.reducida );
			$('#sobretasa').val(ui.item.sobretasa);

			setTimeout(function(){ $('#afecta').removeAttr('readonly'); }, 1500);
		}
	});

	totaliza();
});

function marcariva(){
	var arr  = $('input[name^="riva_"]');
	jQuery.each(arr, function(){
		nom=this.name;
		pos=this.name.lastIndexOf('_');
		if(pos>0){
			ind   = this.name.substring(pos+1);
			val   = this.value;
			if(val=='N'){
				$('#tr_itccli_'+ind).css("background-color", "#FFFF28");
				$('#tr_itccli_'+ind).attr("title", 'No se le realizo retención');
			}else if(val=='V'){
				$('#tr_itccli_'+ind).css("background-color", "#FFCF62");
				$('#tr_itccli_'+ind).attr("title", 'Período vencido para devolver retención');
			}else{
				$('#tr_itccli_'+ind).css("background-color",  'transparent');
			}
		}
	});
}

function desmarcariva(){
	var arr  = $('input[name^="riva_"]');
	jQuery.each(arr, function(){
		nom=this.name;
		pos=this.name.lastIndexOf('_');
		if(pos>0){
			ind   = this.name.substring(pos+1);
			$('#tr_itccli_'+ind).css("background-color",  'transparent');
			$('#tr_itccli_'+ind).removeAttr('title');
		}
	});
}

function calretiva(){
	var montasa   = Number($('#montasa'  ).val());
	var monredu   = Number($('#monredu'  ).val());
	var monadic   = Number($('#monadic'  ).val());
	var tasa      = Number($('#tasa'     ).val());
	var reducida  = Number($('#reducida' ).val());
	var sobretasa = Number($('#sobretasa').val());
	var noret     = 0;
	var tasatot   = 0;

	var arr  = $('input[name^="riva_"]');
	jQuery.each(arr, function(){
		nom=this.name;
		pos=this.name.lastIndexOf('_');
		if(pos>0){
			ind  = this.name.substring(pos+1);
			val  = this.value;
			i    = parseInt(ind);
			num  = Number($('#abono_'+ind).val());
			monto= Number($('#monto_'+ind).val());

			if(val=='N' || val=='V'){
				//noret = noret+num*objivas[i].tasa     /monto;
				//noret = noret+num*objivas[i].reducida /monto;
				//noret = noret+num*objivas[i].sobretasa/monto;
			}
		}
	});

	if(noret>=tasa+reducida+sobretasa){
		tasatot=0;
	}else{
		tasatot=tasa+reducida+sobretasa-noret;
	}

	//$('#reteiva').val(roundNumber(tasatot*1,2));
}

function invasdif(base,iva,ptasa){
	var total     = Number($('#monto').val());
	var exento    = Number($('#exento').val());
	var sobretasa = Number($('#sobretasa').val());
	var reducida  = Number($('#reducida').val());
	var tasa      = Number($('#tasa').val());
	var monadic   = Number($('#monadic').val());
	var monredu   = Number($('#monredu').val());
	var montasa   = Number($('#montasa').val());
	var basactual = Number($('#'+base).val());
	var ivaactual = Number($('#'+iva).val());
	if(isNaN(exento   )) exento   =0;
	if(isNaN(sobretasa)) sobretasa=0;
	if(isNaN(reducida )) reducida =0;
	if(isNaN(tasa     )) tasa     =0;
	if(isNaN(monadic  )) monadic  =0;
	if(isNaN(monredu  )) monredu  =0;
	if(isNaN(montasa  )) montasa  =0;
	if(isNaN(ptasa    )) ptasa    =0;
	if(isNaN(basactual)) basactual=0;
	if(isNaN(ivaactual)) ivaactual=0;

	var itota = sobretasa+exento+reducida+tasa+monadic+monredu+montasa-basactual-ivaactual;
	var diff  = total-itota;

	var bbase = diff/(1+ptasa);
	var iiva  = bbase*ptasa;

	$('#'+base).val(roundNumber(bbase,2));
	if(iva!='E'){
		$('#'+iva).val(roundNumber(iiva,2));
	}

	calretiva();
}

function totaliza(){
	var stota =0;
	var mascara= "PAGA "+$('#afecta').val();

	var actualmontasa  = Number($('#montasa'  ).val());
	var actualmonredu  = Number($('#monredu'  ).val());
	var actualmonadic  = Number($('#monadic'  ).val());
	var actualtasa     = Number($('#tasa'     ).val());
	var actualreducida = Number($('#reducida' ).val());
	var actualsobretasa= Number($('#sobretasa').val());
	var actualexento   = Number($('#exento'   ).val());
	var actualtot      = actualmontasa+actualmonredu+actualmonadic+actualtasa+actualreducida+actualsobretasa+actualexento;

	$('#monto').val(roundNumber(actualtot  ,2));
	$('#monto_val').text(nformat(actualtot ,2));
	if(actualtot >0){
		$("#observa1").val(mascara);
	}else{
		$("#observa1").val('');
	}
}

function chapltasa(){
	var ind = $("#apltasa option:selected").index();
	$('#ptasa'     ).val(roundNumber(objptasa[ind][0],2));
	$('#preducida' ).val(roundNumber(objptasa[ind][1],2));
	$('#padicional').val(roundNumber(objptasa[ind][2],2));
	$('#ptasa_val'     ).text(nformat(objptasa[ind][0],2));
	$('#preducida_val' ).text(nformat(objptasa[ind][1],2));
	$('#padicional_val').text(nformat(objptasa[ind][2],2));

	var base = 0;
	var general  = Number($('#montasa').val())+Number($('#tasa').val());
	var reducido = Number($('#monredu').val())+Number($('#reducida').val());
	var adicional= Number($('#monadic').val())+Number($('#sobretasa').val());

	base = roundNumber(general*100/(100+objptasa[ind][0]),2);
	$('#montasa').val(base);
	$('#tasa').val(roundNumber(general-base,2));

	base = roundNumber(reducido*100/(100+objptasa[ind][1]),2);
	$('#monredu').val(base);
	$('#reducida').val(roundNumber(reducido-base,2));

	base = roundNumber(adicional*100/(100+objptasa[ind][2]),2);
	$('#monadic').val(base);
	$('#sobretasa').val(roundNumber(adicional-base,2));

}

</script>
<?php } ?>
<?php
echo $title;
?>
<table align='center' width="100%">
	<tr>
		<td><?php echo $form->cod_prv->label.'*'.$form->cod_prv->output.$form->nombre->output; ?></td>
	</tr>
</table>

<p style='text-align:center'>
<table>
	<tr>
		<td>
		<table>
			<tr>
				<td><?php echo $form->afecta->label;   ?>*</td>
				<td><?php echo $form->afecta->output;  ?></td>
			</tr><tr>
				<td><?php echo $form->fecha->label;    ?>*</td>
				<td><?php echo $form->fecha->output;   ?></td>
			</tr><tr>
				<td><?php echo $form->serie->label;    ?>*</td>
				<td><?php echo $form->serie->output;   ?></td>
			</tr><tr>
				<td><?php echo $form->nfiscal->label;  ?>*</td>
				<td><?php echo $form->nfiscal->output; ?></td>
			</tr><tr>
				<td><?php echo $form->codigo->label;   ?>*</td>
				<td><?php echo $form->codigo->output;  ?></td>
			</tr><tr>
				<td><?php echo $form->depto->label;    ?>*</td>
				<td><?php echo $form->depto->output;   ?></td>
			</tr>

		</table>
		</td>

		<td>
		<table id='ncadic' style='background:#F2E69D;margin-left:auto;margin-right:auto;'>
			<tr>
				<td class="littletableheaderdet">Tasa</td>
				<td class="littletableheaderdet">Base</td>
				<td class="littletableheaderdet">Impuesto</td>
			</tr><tr>
				<td colspan='3' align="center">Aplicar tasa de fecha <?php echo $form->apltasa->output;  ?></td>
			</tr><tr>
				<td align="right"><?php echo $form->ptasa->output;      ?></td>
				<td align="right"><?php echo $form->montasa->output;    ?></td>
				<td align="right"><?php echo $form->tasa->output;       ?></td>
			</tr><tr>
				<td align="right"><?php echo $form->preducida->output;  ?></td>
				<td align="right"><?php echo $form->monredu->output;    ?></td>
				<td align="right"><?php echo $form->reducida->output;   ?></td>
			</tr><tr>
				<td align="right"><?php echo $form->padicional->output; ?></td>
				<td align="right"><?php echo $form->monadic->output;    ?></td>
				<td align="right"><?php echo $form->sobretasa->output;  ?></td>
			</tr><tr>
				<td align="right">Exento</td>
				<td align="right"><?php echo $form->exento->output;  ?></td>
				<td align="right"></td>
			</tr><tr>
				<td align="center" colspan='3'><?php echo  $form->reteiva->label.' '.$form->reteiva->output;?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</p>
<table width="100%">
	<tfoot>
	<tr>
		<td align="right" style='font-size: 1.6em;'><b><?php echo $form->monto->label; ?></b></td>
		<td align="right" style='font-size: 1.6em;font-weight: bold;'><?php echo $form->monto->output; ?></td>
	</tr>
	</tfoot>
</table>

<?php echo $container_br.$container_bl;?>

<table width='100%'>
	<tr>
		<td align='center'><b>Concepto:</b><br><?php echo $form->observa1->output.$form->observa2->output; ?></td>
	</tr>
</table>
<?php echo $form_end; ?>

<?php endif; ?>
<?php
/*
ob_end_flush();
// Función para eliminar todos los espacios en blanco
function comprimir_pagina($buffer) {
    $busca = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');
    $reemplaza = array('>','<','\\1');
    return preg_replace($busca, $reemplaza, $buffer);
}*/
?>
