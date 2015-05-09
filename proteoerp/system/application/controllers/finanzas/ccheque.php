<?php
class ccheque extends Controller {
	var $titp='Cambio efectivo por otros Medios de Pago';
	var $tits='Cambio efectivo por otros Medios de Pago';
	var $url ='finanzas/ccheque/';

	function ccheque(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'CCHEQUE', $ventana=0 );
		//$this->datasis->modulo_id('A00',1);
	}

	function index(){
		$this->instalar();
		redirect($this->url.'jqdatag');
	}

	function jqdatag(){

		$grid = $this->defgrid();
		$param['grids'][] = $grid->deploy();

		$bodyscript = $this->bodyscript($param['grids'][0]['gridname']);
		#Set url
		$grid->setUrlput(site_url($this->url.'setdata/'));
		$WestPanel = '
		<div id="LeftPane" class="ui-layout-west ui-widget ui-widget-content">
			<div class="otros">
			<table id="west-grid">
			<tr><td>
				<td><div class="tema1 a1"><a style="width:190px" href="#" id="a1"><span class="ui-button-text">'.image('print.png','Formato PDF',array('title' => 'Formato PDF', 'border'=>'0')).' &nbsp;&nbsp;Imprimir</span></a></div></td>
			</td></tr>
			</table>
			</div>
		</div> <!-- #LeftPane -->';

		$grid->wbotonadd(array('id'=>'a1', 'img'=>'assets/default/images/print.png',  'alt' => 'Formato PDF', 'label'=>'Imprimir'));
		$WestPanel = $grid->deploywestp();


		$adic = array(
			array('id'=>'fedita' , 'title'=>'Agregar cambio forma de pago'),
			array('id'=>'fshow'  , 'title'=>'Mostrar registro' ),
			array('id'=>'fborra' , 'title'=>'Anula Factura'    )
		);
		$SouthPanel = $grid->SouthPanel($this->datasis->traevalor('TITULO1'), $adic);

		$param['WestPanel']  = $WestPanel;
		//$param['EastPanel']  = $EastPanel;
		$param['SouthPanel'] = $SouthPanel;
		$param['tema1']      = 'darkness';
		$param['bodyscript'] = $bodyscript;
		$param['tabs']       = false;
		$param['encabeza']   = $this->titp;
		$this->load->view('jqgrid/crud2',$param);
	}

	function bodyscript($grid0){
		$bodyscript = '<script type="text/javascript">';
		$ngrid = '#newapi'.$grid0;

		$bodyscript .= $this->jqdatagrid->bsshow('ccheque', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'ccheque', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'ccheque', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('ccheque', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '320', '450' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '320', '450' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '320', '450' );

		$bodyscript .='
		jQuery("#a1").click( function(){
			var id = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
			if (id)	{
				var ret = jQuery("#newapi'.$grid0.'").jqGrid(\'getRowData\',id);
				window.open(\''.base_url().'formatos/ver/CCHEQUE/\'+id, \'_blank\', \'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=((screen.availHeight/2)-400), screeny=((screen.availWidth/2)-300)\');
			}else{
				$.prompt("<h1>Por favor Seleccione un Movimiento</h1>");
			}
		});';

		$bodyscript .= '});';

		$bodyscript .= '</script>';

		return $bodyscript;

		//$bodyscript = '
		//<script type="text/javascript">
		//$(function() {
		//	$("input:submit, a, button", ".otros").button();
		//});
		//</script>';
		//return $bodyscript;
	}


	function defgrid( $deployed = false ){
		$i = 1;

		$grid  = new $this->jqdatagrid;

		$link  = site_url('ajax/buscascli');
		$afterhtml = '<div id=\"aaaaaa\">Nombre <strong>"+ui.item.nombre+" </strong>RIF/CI <strong>"+ui.item.rifci+" </strong><br>Direccion <strong>"+ui.item.direc+"</strong></div>';
		$auto = $grid->autocomplete( $link, 'cod_cli', 'aaaaa', $afterhtml );

		$grid->addField('cod_cli');
		$grid->label('Cliente');
		$grid->params(array(
				'width'       => 60,
				'hidden'      => 'true',
				'editable'    => 'true',
				'edittype'    => "'text'",
				'editrules'   => '{ edithidden:true, required:true }',
				'editoptions' => '{'.$auto.'}'
			)
		);

		$grid->addField('nombre');
		$grid->label('Nombre Cliente');
		$grid->params(array(
				'width'    => 180,
				'editable' => 'false',
				'edittype' => "'text'"
			)
		);

		$grid->addField('numero');
		$grid->label('N&uacute;mero');
		$grid->params(array(
				'align'    => "'center'",
				'width'    => 70,
				'editable' => 'false',
				'edittype' => "'text'"
			)
		);

		$grid->addField('fecha');
		$grid->label('Fecha');
		$grid->params(array(
				'width'       => 80,
				'search'      => 'true',
				'editable'    => 'true',
				'edittype'    => "'text'",
				'editrules'   => '{ required:true,date:true}',
				'formoptions' => '{ label:"Fecha" }'
			)
		);

		$grid->addField('tipo');
		$grid->label('Tipo');
		$grid->params(array(
				'align'    => "'center'",
				'width'         => 40,
				'editable'      => 'true',
				'edittype'      => "'select'",
				'editrules'     => '{ required:true }',
				'editoptions'   => '{ dataUrl: "'.base_url().'ajax/ddtarjeta"}',
				'stype'         => "'text'"
				//'searchoptions' => '{ dataUrl: "ddtarjeta", sopt: ["eq", "ne"]}'
			)
		);

		$grid->addField('num_ref');
		$grid->label('Nro.Documento');
		$grid->params(array(
				'width'       => 100,
				'editable'    => 'true',
				'edittype'    => "'text'",
				'editrules'    => '{required:true}',
				'editoptions' => '{ size:20, maxlength: 12 }',
			)
		);

		$grid->addField('monto');
		$grid->label('Monto');
		$grid->params(array(
				'width'         => 100,
				'editable'      => 'true',
				'align'         => "'right'",
				'edittype'      => "'text'",
				'search'        => 'true',
				'editrules'     => '{ required:true }',
				'editoptions'   => '{ size:10, maxlength: 10 }',
				'formatter'     => "'number'",
				'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
			)
		);

		$grid->addField('banco');
		$grid->label('Banco');
		$grid->params(array(
				'width'         => 40,
				'hidden'        => 'true',
				'editable'      => 'true',
				'edittype'      => "'select'",
				'editrules'     => '{ edithidden:true, required:true }',
				'editoptions'   => '{ dataUrl: "'.base_url().'ajax/ddbanco"}',
				'stype'         => "'tsxt'",
			)
		);

		$grid->addField('nombanc');
		$grid->label('Nombre del Banco');
		$grid->params(array(
				'width'         => 140,
				'editable'      => 'false',
				'edittype'      => "'text'",
				'search'        => 'true'
			)
		);

		$grid->addField('cuentach');
		$grid->label('Cta Corriente');
		$grid->params(array(
				'align'       => "'center'",
				'width'       => 150,
				'editable'    => 'true',
				'edittype'    => "'text'",
				'editrules'   => '{required:false}',
				'editoptions' => '{ size:20, maxlength: 20 }',
			)
		);


		$grid->addField('cajero');
		$grid->label('Cajero');
		$grid->params(array('width'         => 120,
							'hidden'        => 'true',
							'editable'      => 'true',
							'edittype'      => "'select'",
							'editrules'     => '{ edithidden: true, required:true }',
							'editoptions'   => '{ dataUrl: "'.base_url().'ajax/ddcajero"}',
							'stype'         => "'select'",
							'searchoptions' => '{ dataUrl: "'.base_url().'ajax/ddcajero", sopt: ["eq", "ne"]}'
			)
		);


		$grid->addField('nomcajero');
		$grid->label('Nombre Cajero');
		$grid->params(array('width'         => 120,
							'editable'      => 'false',
							'edittype'      => "'text'"
			)
		);

		$grid->addField('us_nombre');
		$grid->label('Nombre de Uusario');
		$grid->params(array('width'     => 140,
							'editable'  => 'false',
							'edittype'  => "'text'",
							'search'    => 'true'
			)
		);

		$grid->addField('estampa');
		$grid->label('Estampa');
		$grid->params(array('width'    => 80,
							'search'   => 'false',
							'editable' => 'false',
							'edittype' => "'text'"
			)
		);

		$grid->addField('hora');
		$grid->label('Hora');
		$grid->params(array('width'     => 60,
							'editable'  => 'false',
							'edittype'  => "'text'",
							'search'    => 'false'
			)
		);

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'    => "'center'",
			'frozen'   => 'true',
			'width'    => 60,
			'editable' => 'false',
			'search'   => 'false'
		));

		$grid->showpager(true);
		$grid->setWidth('');
		$grid->setHeight('290');
		$grid->setTitle($this->titp);
		$grid->setfilterToolbar(true);
		//$grid->setToolbar('true, "top"');
		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) {var res = $.parseJSON(a.responseText);
					$.prompt(res.mensaje,{
						submit: function(e,v,m,f){
							window.open(\''.base_url().'formatos/ver/CCHEQUE/\'+res.id, \'_blank\', \'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=((screen.availHeight/2)-400), screeny=((screen.availWidth/2)-300)\');
						}
					});
					return [true, a ];}}'
					);
		$grid->setAfterSubmit("$.prompt('Respuesta:'+a.responseText); return [true, a ];");


		#show/hide navigations buttons
		$grid->setAdd(   $this->datasis->sidapuede('CCHEQUE','INCLUIR%' ));
		$grid->setEdit(  $this->datasis->sidapuede('CCHEQUE','MODIFICA%'));
		$grid->setDelete($this->datasis->sidapuede('CCHEQUE','BORR_REG%'));
		$grid->setSearch($this->datasis->sidapuede('CCHEQUE','BUSQUEDA%'));
		$grid->setRowNum(30);

		$grid->setBarOptions('addfunc: cchequeadd, editfunc: cchequeedit, delfunc: cchequedel, viewfunc: cchequeshow');

		$grid->setShrinkToFit('false');
		#export buttons
		//$grid->setPdf(true,array('title' => 'Test pdf'));

		#Set url
		$grid->setUrlput(site_url($this->url.'setdata/'));

		#GET url
		$grid->setUrlget(site_url($this->url.'getdata/'));

		if ($deployed) {
			return $grid->deploy();
		} else {
			return $grid;
		}

	}

	/**
	* Get data result as json
	*/
	function getdata(){
		$tabla = 'view_ccheque';

		$filters = $this->input->get_post('filters');
		$mWHERE = array();

		$grid       = $this->jqdatagrid;
		$mWHERE = $grid->geneTopWhere($tabla);

		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$response   = $grid->getData('view_ccheque', array(array()), array(), false, $mWHERE );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	/**
	* Put information
	*/
	function setData(){
		//$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = intval($this->input->post('id'));

		$data = $_POST;
		unset($data['oper']);
		unset($data['id']);
		$data['cobrador']  = $data['cajero'];
		$data['f_factura'] = $data['fecha'];
		unset($data['cajero']);

		if($oper == 'add'){
			if(false == empty($data)){
				if(!is_numeric($data['monto'])){ return false;}else{ $data['monto']=floatval($data['monto']); }

				$data['tipo_doc']  = 'CC';
				$data['f_factura'] = $data['fecha'];
				$data['usuario']   = $this->secu->usuario();
				$data['estampa']   = date('Ymd');
				$data['hora']      = date('H:i:s');
				$data['transac']   = $this->datasis->fprox_numero('ntransa') ;
				$data['numero']    = $this->datasis->fprox_numero('nccheque');
				$this->db->insert('sfpa', $data);
				$id = $this->db->insert_id();

				// DESCARGA EL EFECTIVO
				$data['tipo'] = 'EF';
				$data['monto'] = -1*$data['monto'];
				$this->db->insert('sfpa', $data);

			}
			logusu('SFPA',"Agrega Cambio de medio de pago por efectivo: id=${id}, ".$data['numero'].", monto=".$data['monto']);
			echo "{\"id\":\"${id}\",\"mensaje\":\"Registro Agregado\"}";
			return;

		}elseif($oper == 'edit'){
			if(!is_numeric($data['monto'])){ return false;}else{ $data['monto']=floatval($data['monto']); }

			$row=$this->datasis->damerow("SELECT transac,numero FROM sfpa WHERE id=${id}");
			if(empty($row)){ return false; }
			$transac = trim($row['transac']);
			$numero  = trim($row['numero']);
			if(empty($transac)){ return false; }
			$data['tipo_doc']  = 'CC';
			$data['f_factura'] = $data['fecha'];
			$data['usuario']   = $this->secu->usuario();
			$data['estampa']   = date('Ymd');
			$data['hora']      = date('H:i:s');
			$this->db->where('id', $id);
			$this->db->update('sfpa', $data);

			// DESCARGA EL EFECTIVO
			$data['tipo']  = 'EF';
			$data['monto'] = -1*$data['monto'];

			$this->db->where('transac', $transac);
			$this->db->where('tipo', 'EF');
			$this->db->update('sfpa', $data);

			logusu('SFPA',"Edita Cambio de medio de pago por efectivo: id=${id}, ".$numero.", monto=".$data['monto']);
			echo 'Registro Guardado';
			return;

		} elseif($oper == 'del') {
			// revisa si el cheque se cobro
			if($id>0){
				$transac = trim($this->datasis->dameval("SELECT transac FROM sfpa WHERE id=${id}"));
				if(empty($transac)){ return false; }
				$this->db->simple_query("DELETE FROM sfpa WHERE transac='${transac}' ");
				logusu('sfpa',"Cambio de Cheque ${id} ELIMINADO");
				echo 'Registro Eliminado';
			}
			return;
		}
	}


	function dataedit(){
		$this->rapyd->load('dataedit','dataobject');
		$script= "
		$(function() {
			$('#fecha').datepicker({dateFormat:'dd/mm/yy'});
			$('.inputnum').numeric('.');
				$('#cod_cli').autocomplete({
					delay: 600,
					autoFocus: true,
					source: function(req, add){
						$.ajax({
							url:  '".site_url('ajax/buscascli')."',
							type: 'POST',
							dataType: 'json',
							data: {'q':req.term},
							success:
								function(data){
									var sugiere = [];
									if(data.length==0){
										$('#sclinombre').val('');
										$('#sclinombre_val').text('');
										$('#sclirifci').val('');
										$('#sclirifci_val').text('');

									}else{
										$.each(data,
											function(i, val){
												sugiere.push( val );
											}
										);
									}
									add(sugiere);
								},
						})
					},
					minLength: 2,
					select: function( event, ui ) {
						var tdirec;
						$('#cod_cli').attr('readonly', 'readonly');

						$('#sclinombre').val(ui.item.nombre);
						$('#sclinombre_val').text(ui.item.nombre);
						$('#sclirifci').val(ui.item.rifci);
						$('#sclirifci_val').text(ui.item.rifci);

						$('#cod_cli').val(ui.item.cod_cli);

						setTimeout(function() {  $('#cod_cli').removeAttr('readonly'); }, 1500);
					}
				});
		});";

		$do = new DataObject('sfpa');
		$do->pointer('scli' ,'scli.cliente=sfpa.cod_cli','scli.nombre AS sclinombre, scli.rifci AS sclirifci','left');

		$edit = new DataEdit('',$do);
		$edit->script($script,'modify');
		$edit->script($script,'create');
		$edit->on_save_redirect=false;

		$edit->back_url = site_url($this->url.'filteredgrid');

		$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');
		$edit->pre_process( 'insert','_pre_insert' );
		$edit->pre_process( 'update','_pre_update' );
		$edit->pre_process( 'delete','_pre_delete' );

		$edit->cod_cli = new inputField('Cliente','cod_cli');
		$edit->cod_cli->rule='required|existescli';
		$edit->cod_cli->size =7;
		$edit->cod_cli->maxlength =5;

		$edit->sclirifci = new inputField('','sclirifci');
		$edit->sclirifci->pointer= true;
		$edit->sclirifci->size =14;
		$edit->sclirifci->type='inputhidden';
		$edit->sclirifci->maxlength =12;
		$edit->sclirifci->in ='cod_cli';

		$edit->sclinombre = new inputField('','sclinombre');
		$edit->sclinombre->pointer= true;
		$edit->sclinombre->size =14;
		$edit->sclinombre->type='inputhidden';
		$edit->sclinombre->maxlength =12;

		$edit->fecha = new dateonlyField('Fecha','fecha');
		$edit->fecha->rule='required|chfecha';
		$edit->fecha->calendar=false;
		$edit->fecha->size =10;
		$edit->fecha->maxlength =8;
		$edit->fecha->insertValue=date('Y-m-d');

		$edit->tipo = new  dropdownField('Tipo', 'tipo');
		$edit->tipo->option('','Seleccionar');
		$edit->tipo->options('SELECT tipo, nombre FROM tarjeta WHERE activo=\'S\' AND tipo NOT IN ("EF", "DE", "NC","RI","IR","RP") ORDER BY nombre');
		$edit->tipo->rule   = 'required';
		$edit->tipo->style  = 'width:150px;';

		$edit->num_ref = new inputField('Nro.Documento','num_ref');
		$edit->num_ref->rule='required';
		$edit->num_ref->size =14;
		$edit->num_ref->maxlength =12;

		$edit->banco = new dropdownField('Banco','banco');
		$edit->banco->option('','Seleccionar');
		$edit->banco->options("SELECT cod_banc, CONCAT(cod_banc,' ',nomb_banc) banco FROM tban WHERE cod_banc<>'CAJ' ORDER BY nomb_banc");
		$edit->banco->rule   = 'required';
		$edit->banco->style  = 'width:250px;';

		$edit->cuentach = new inputField('Cta Corriente','cuentach');
		$edit->cuentach->rule='';
		$edit->cuentach->size =24;
		$edit->cuentach->maxlength =22;

		$edit->monto = new inputField('Monto','monto');
		$edit->monto->rule='required|mayorcero|numeric';
		$edit->monto->css_class='inputnum';
		$edit->monto->size =20;
		$edit->monto->maxlength =18;

		$edit->cobrador = new dropdownField('Cajero','cobrador');
		$edit->cobrador->option('','Seleccionar');
		$edit->cobrador->options('SELECT cajero, CONCAT(cajero, \' \', nombre) nombre FROM scaj ORDER BY nombre');
		$edit->cobrador->rule   = 'required';
		$edit->cobrador->style  = 'width:200px;';

		$edit->usuario = new autoUpdateField('usuario',$this->secu->usuario(),$this->secu->usuario());
		$edit->estampa = new autoUpdateField('estampa' ,date('Ymd'), date('Ymd'));
		$edit->hora    = new autoUpdateField('hora',date('H:i:s'), date('H:i:s'));
		$edit->tipo_doc= new autoUpdateField('tipo_doc','CC', 'CC');

		$edit->build();

		if($edit->on_success()){
			$rt=array(
				'status' =>'A',
				'mensaje'=>'Registro guardado',
				'pk'     =>$edit->_dataobject->pk
			);
			echo json_encode($rt);
		}else{
			echo $edit->output;
		}
	}

	function _pre_insert($do){
		$fecha   = $do->get('fecha');
		$transac = $this->datasis->fprox_numero('ntransa') ;
		$numero  = $this->datasis->fprox_numero('nccheque');

		$do->set('transac'  ,$transac);
		$do->set('numero'   ,$numero );
		$do->set('f_factura',$fecha  );

		$do->error_message_ar['pre_ins']='';
		return true;
	}

	function _pre_update($do){
		$do->error_message_ar['pre_upd']='';
		return true;
	}

	function _pre_delete($do){
		$do->error_message_ar['pre_del']='';
		return true;
	}

	function _post_insert($do){
		$primary  = implode(',',$do->pk);
		$numero   = $do->get('numero');
		$monto    = $do->get('monto');
		$cod_cli  = $do->get('cod_cli');
		$fecha    = $do->get('fecha');
		$tipo     = $do->get('tipo');
		$num_ref  = $do->get('num_ref');
		$banco    = $do->get('banco');
		$cuentach = $do->get('cuentach');
		$cobrador = $do->get('cobrador');
		$usuario  = $do->get('usuario');
		$estampa  = $do->get('estampa');
		$hora     = $do->get('hora');
		$tipo_doc = $do->get('tipo_doc');
		$transac  = $do->get('transac');

		$data=array();
		$data['numero']   = $numero;
		$data['monto']    = (-1)*$monto;
		$data['cod_cli']  = $cod_cli;
		$data['fecha']    = $fecha;
		$data['f_factura']= $fecha;
		$data['tipo']     = 'EF';
		$data['num_ref']  = $num_ref;
		$data['banco']    = $banco;
		$data['cuentach'] = $cuentach;
		$data['cobrador'] = $cobrador;
		$data['usuario']  = $usuario;
		$data['estampa']  = $estampa;
		$data['hora']     = $hora;
		$data['tipo_doc'] = $tipo_doc;
		$data['transac']  = $transac;

		$sql = $this->db->insert_string('sfpa', $data);
		$ban = $this->db->simple_query($sql);
		if($ban==false){ memowrite($sql,'sfac'); }

		logusu('sfpa',"Agrega Cambio de medio de pago por efectivo: id=${primary}, ${numero}, monto= ${monto}");
	}

	function _post_update($do){
		$primary = implode(',',$do->pk);
		$numero   = $do->get('numero');
		$monto    = $do->get('monto');
		$cod_cli  = $do->get('cod_cli');
		$fecha    = $do->get('fecha');
		$tipo     = $do->get('tipo');
		$num_ref  = $do->get('num_ref');
		$banco    = $do->get('banco');
		$cuentach = $do->get('cuentach');
		$cobrador = $do->get('cobrador');
		$usuario  = $do->get('usuario');
		$estampa  = $do->get('estampa');
		$hora     = $do->get('hora');
		$tipo_doc = $do->get('tipo_doc');
		$transac  = $do->get('transac');

		$data=array();
		$data['monto']    = (-1)*$monto;
		$data['cod_cli']  = $cod_cli;
		$data['fecha']    = $fecha;
		$data['num_ref']  = $num_ref;
		$data['banco']    = $banco;
		$data['cuentach'] = $cuentach;
		$data['cobrador'] = $cobrador;
		$data['usuario']  = $usuario;
		$data['estampa']  = $estampa;
		$data['hora']     = $hora;
		$data['transac']  = $transac;

		$this->db->where('transac', $transac);
		$this->db->where('tipo'   , 'EF');
		$this->db->where('numero' , $numero);
		$this->db->update('sfpa'  , $data);

		logusu('sfac',"Edita Cambio de medio de pago por efectivo: id=${primary}, ${numero}, monto=${monto}");
	}

	function _post_delete($do){
		$primary = implode(',',$do->pk);
		$numero  = $do->get('numero');
		$monto   = $do->get('monto');
		$numero  = $do->get('numero');
		$transac = $do->get('transac');

		$dbnumero = $this->db->escape($numero );
		$dbtransac= $this->db->escape($transac);

		$sql = "DELETE FROM sfpa WHERE transac=${dbtransac} AND numero=${dbnumero} AND tipo_doc='CC'";
		$ban = $this->db->simple_query($sql);
		if($ban==false){ memowrite($sql,'sfac'); }

		logusu('sfpa',"Elimino Cambio de medio de pago por efectovo ${primary}, ${numero}, monto=${monto}");
	}

	function instalar(){
		$campos=$this->db->list_fields('sfpa');
		if(!in_array('id',$campos)){
			$this->db->simple_query('ALTER TABLE sfpa ADD COLUMN id INT(11) NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
		}

		if(!in_array('deposito',$campos)){
			$this->db->simple_query('ALTER TABLE sfpa ADD COLUMN deposito CHAR(12) NULL DEFAULT NULL ');
		}

		if(!in_array('cuentach',$campos)){
			$this->db->query('ALTER TABLE sfpa ADD COLUMN cuentach CHAR(22) NULL DEFAULT NULL ');
		}

		if (!$this->db->table_exists('view_ccheque')){
			$mSQL="CREATE ALGORITHM=UNDEFINED DEFINER=`".$this->db->username."`@`".$this->db->hostname."` SQL SECURITY DEFINER VIEW `view_ccheque` AS select `sfpa`.`id` AS `id`,`sfpa`.`fecha` AS `fecha`,`sfpa`.`numero` AS `numero`,`sfpa`.`tipo` AS `tipo`,concat(`sfpa`.`tipo`,' ',`tarjeta`.`nombre`) AS `ntarjeta`,`sfpa`.`num_ref` AS `num_ref`,`sfpa`.`monto` AS `monto`,`sfpa`.`banco` AS `banco`,concat(`sfpa`.`banco`,' ',`tban`.`nomb_banc`) AS `nombanc`,`sfpa`.`cod_cli` AS `cod_cli`,concat(trim(`scli`.`nombre`),' (',`scli`.`cliente`,')') AS `nombre`,`sfpa`.`cobrador` AS `cajero`,if(isnull(`scaj`.`cajero`),'CAJA PRINCIPAL',concat(`scaj`.`cajero`,' ',`scaj`.`nombre`)) AS `nomcajero`,`sfpa`.`cuentach` AS `cuentach`,`sfpa`.`status` AS `status`,`sfpa`.`usuario` AS `usuario`,concat(`sfpa`.`usuario`,' ',`usuario`.`us_nombre`) AS `us_nombre`,`sfpa`.`estampa` AS `estampa`,`sfpa`.`hora` AS `hora`,`scli`.`dire11` AS `dire11`,`scli`.`dire12` AS `dire12`,`scli`.`telefono` AS `telefono`,`scli`.`rifci` AS `rifci`,`scli`.`ciudad1` AS `ciudad1`, `sfpa`.`transac` AS `transac` from (((((`sfpa` left join `scli` on((`sfpa`.`cod_cli` = `scli`.`cliente`))) left join `usuario` on((`sfpa`.`usuario` = `usuario`.`us_codigo`))) left join `tban` on((`sfpa`.`banco` = `tban`.`cod_banc`))) left join `scaj` on((`sfpa`.`cobrador` = `scaj`.`cajero`))) left join `tarjeta` on((`sfpa`.`tipo` = `tarjeta`.`tipo`))) where ((`sfpa`.`tipo_doc` = 'CC') and (`sfpa`.`tipo` <> 'EF')) order by `sfpa`.`id` desc";
			$this->db->simple_query($mSQL);
		}
	}
}
