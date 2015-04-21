<?php

class Sinvaudit extends Controller {
	var $mModulo = 'SINVAUDIT';
	var $titp    = 'Auditoria de inventario';
	var $tits    = 'Auditoria de inventario';
	var $url     = 'inventario/sinvaudit/';

	function Sinvaudit(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'SINVAUDIT', $ventana=0, $this->titp  );
	}

	function index(){
		$this->datasis->creaintramenu(array('modulo'=>'332','titulo'=>'Auditoria de inventario','mensaje'=>'Auditoria de inventario','panel'=>'REGISTRO','ejecutar'=>'inventario/sinvaudit','target'=>'popu','visible'=>'S','pertenece'=>'3','ancho'=>900,'alto'=>600));
		$this->datasis->modintramenu( 800, 600, substr($this->url,0,-1) );
		redirect($this->url.'jqdatag');
	}

	//***************************
	//Layout en la Ventana
	//
	//***************************
	function jqdatag(){

		$grid = $this->defgrid();
		$grid->setHeight('185');
		$param['grids'][] = $grid->deploy();

		$grid1   = $this->defgridit();
		$grid1->setHeight('190');
		$param['grids'][] = $grid1->deploy();

		// Configura los Paneles
		$readyLayout = $grid->readyLayout2( 212, 220, $param['grids'][0]['gridname'],$param['grids'][1]['gridname']);

		//Funciones que ejecutan los botones
		$bodyscript = $this->bodyscript( $param['grids'][0]['gridname'], $param['grids'][1]['gridname'] );

		//Botones Panel Izq
		//$grid->wbotonadd(array('id'=>'imprime',  'img'=>'assets/default/images/print.png','alt' => 'Reimprimir', 'label'=>'Informe'));
		$grid->wbotonadd(array('id'=>'finaliza',  'img'=>'assets/default/images/34.png','alt' => 'Finalizar', 'label'=>'Finalizar'));
		$WestPanel = $grid->deploywestp();

		//Panel Central
		$centerpanel = $grid->centerpanel( $id = "radicional", $param['grids'][0]['gridname'], $param['grids'][1]['gridname'] );

		$adic = array(
			array('id'=>'fedita',  'title'=>'Agregar/Editar Registro'),
			array('id'=>'fshow' ,  'title'=>'Mostrar Registro'),
			array('id'=>'fborra',  'title'=>'Eliminar Registro')
		);
		$SouthPanel = $grid->SouthPanel($this->datasis->traevalor('TITULO1'), $adic);

		$param['WestPanel']    = $WestPanel;
		$param['script']       = script('plugins/jquery.ui.autocomplete.autoSelectOne.js');
		$param['readyLayout']  = $readyLayout;
		$param['SouthPanel']   = $SouthPanel;
		$param['listados']     = $this->datasis->listados('SINVAUDIT', 'JQ');
		$param['otros']        = $this->datasis->otros('SINVAUDIT', 'JQ');
		$param['centerpanel']  = $centerpanel;
		$param['temas']        = array('proteo','darkness','anexos1');
		$param['bodyscript']   = $bodyscript;
		$param['tabs']         = false;
		$param['encabeza']     = $this->titp;
		$param['tamano']       = $this->datasis->getintramenu( substr($this->url,0,-1) );
		$this->load->view('jqgrid/crud2',$param);

	}

	//***************************
	//Funciones de los Botones
	//***************************
	function bodyscript( $grid0, $grid1 ){
		$bodyscript = '		<script type="text/javascript">';

		$bodyscript .= '
		function sinvauditadd(){
			$.post("'.site_url($this->url.'dataedit/create').'",
			function(data){
				$("#fedita").html(data);
				$("#fedita").dialog( "open" );
			})
		};';

		$bodyscript .= '
		function sinvauditedit(){
			var id     = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
			if(id){
				var ret    = $("#newapi'.$grid0.'").getRowData(id);
				mId = id;
				$.post("'.site_url($this->url.'dataedit/modify').'/"+id, function(data){
					$("#fedita").html(data);
					$("#fedita").dialog( "open" );
				});
			} else {
				$.prompt("<h1>Por favor Seleccione un Registro</h1>");
			}
		};';

		$bodyscript .= '
		function sinvauditshow(){
			var id     = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
			if(id){
				var ret    = $("#newapi'.$grid0.'").getRowData(id);
				mId = id;
				$.post("'.site_url($this->url.'dataedit/show').'/"+id, function(data){
					$("#fshow").html(data);
					$("#fshow").dialog( "open" );
				});
			} else {
				$.prompt("<h1>Por favor Seleccione un Registro</h1>");
			}
		};';

		$bodyscript .= '
		function sinvauditdel() {
			var id = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
			if(id){
				if(confirm(" Seguro desea eliminar el registro?")){
					var ret    = $("#newapi'.$grid0.'").getRowData(id);
					mId = id;
					$.post("'.site_url($this->url.'dataedit/do_delete').'/"+id, function(data){
						try{
							var json = JSON.parse(data);
							if (json.status == "A"){
								apprise("Registro eliminado");
								jQuery("#newapi'.$grid0.'").trigger("reloadGrid");
							}else{
								apprise("Registro no se puede eliminado");
							}
						}catch(e){
							$("#fborra").html(data);
							$("#fborra").dialog( "open" );
						}
					});
				}
			}else{
				$.prompt("<h1>Por favor Seleccione un Registro</h1>");
			}
		};';
		//Wraper de javascript
		$bodyscript .= '
		$(function() {
			$("#dialog:ui-dialog").dialog( "destroy" );
			var mId = 0;
			var montotal = 0;
			var ffecha = $("#ffecha");
			var grid = jQuery("#newapi'.$grid0.'");
			var s;
			var allFields = $( [] ).add( ffecha );
			var tips = $( ".validateTips" );
			s = grid.getGridParam(\'selarrrow\');
			';

		$bodyscript .= '
		$("#finaliza").click(function(){
			$.post("'.site_url($this->url.'finalizar').'",
			function(data){
				alert(data);
			});
		});';

		$bodyscript .= '
		$("#fedita").dialog({
			autoOpen: false, height: 500, width: 700, modal: true,
			buttons: {
				"Guardar": function() {
					var bValid = true;
					var murl = $("#df1").attr("action");
					allFields.removeClass( "ui-state-error" );
					$.ajax({
						type: "POST", dataType: "html", async: false,
						url: murl,
						data: $("#df1").serialize(),
						success: function(r,s,x){
							try{
								var json = JSON.parse(r);
								if (json.status == "A"){
									apprise("Registro Guardado");
									$( "#fedita" ).dialog( "close" );
									grid.trigger("reloadGrid");
									'.$this->datasis->jwinopen(site_url('formatos/ver/SINVAUDIT').'/\'+json.pk.id+\'/id\'').';
									return true;
								} else {
									apprise(json.mensaje);
								}
							}catch(e){
								$("#fedita").html(r);
							}
						}
					})
				},
				"Cancelar": function() {
					$("#fedita").html("");
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				$("#fedita").html("");
				allFields.val( "" ).removeClass( "ui-state-error" );
			}
		});';

		$bodyscript .= '
		$("#fshow").dialog({
			autoOpen: false, height: 500, width: 700, modal: true,
			buttons: {
				"Aceptar": function() {
					$("#fshow").html("");
					$( this ).dialog( "close" );
				},
			},
			close: function() {
				$("#fshow").html("");
			}
		});';

		$bodyscript .= '
		$("#fborra").dialog({
			autoOpen: false, height: 300, width: 400, modal: true,
			buttons: {
				"Aceptar": function() {
					$("#fborra").html("");
					jQuery("#newapi'.$grid0.'").trigger("reloadGrid");
					$( this ).dialog( "close" );
				},
			},
			close: function() {
				jQuery("#newapi'.$grid0.'").trigger("reloadGrid");
				$("#fborra").html("");
			}
		});';

		$bodyscript .= '});';
		$bodyscript .= '</script>';
		return $bodyscript;
	}

	//***************************
	//Definicion del Grid y la Forma
	//***************************
	function defgrid( $deployed = false ){
		$i      = 1;
		$editar = 'false';

		$grid  = new $this->jqdatagrid;

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false',
			'hidden'        => 'true'
		));


		$grid->addField('id_sinv');
		$grid->label('Id_sinv');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0 }',
			'hidden'        => 'true'
		));


		$grid->addField('status');
		$grid->label('Estatus');
		$grid->params(array(
			'align'         => "'center'",
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));


		$grid->addField('codigo');
		$grid->label('C&oacute;digo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 150,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:15, maxlength: 15 }',
		));

		$grid->addField('descrip');
		$grid->label('Descripci&oacute;n');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:40, maxlength: 40 }',
		));

		$grid->addField('contado');
		$grid->label('Contado');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'true',
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));

		$grid->addField('corte');
		$grid->label('Corte');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 140,
			'edittype'      => "'text'",
		));


		$grid->addField('existen');
		$grid->label('Existen');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }',
			'hidden'        => 'true'
		));


		$grid->addField('estampa');
		$grid->label('Estampa');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 140,
			'edittype'      => "'text'",
		));


		$grid->addField('usuario');
		$grid->label('Usuario');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->showpager(true);
		$grid->setWidth('');
		$grid->setHeight('290');
		$grid->setTitle($this->titp);
		$grid->setfilterToolbar(true);
		$grid->setToolbar('false', '"top"');

		$grid->setOnSelectRow('
			function(id){
				if (id){
					jQuery(gridId2).jqGrid("setGridParam",{url:"'.site_url($this->url.'getdatait/').'/"+id+"/", page:1});
					jQuery(gridId2).trigger("reloadGrid");
				}
			},
			afterInsertRow:
			function( rid, aData, rowe){
				if(aData.status == "P"){
					$(this).jqGrid( "setCell", rid, "status","", {color:"#FFFFFF", background:"#166D05" });
				}
			}

		');

		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setAfterSubmit("$('#respuesta').html('<span style=\'font-weight:bold; color:red;\'>'+a.responseText+'</span>'); return [true, a ];");

		#show/hide navigations buttons
		//$grid->setAdd(    $this->datasis->sidapuede('SINVAUDIT','INCLUIR%' ));
		//$grid->setEdit(   $this->datasis->sidapuede('SINVAUDIT','MODIFICA%'));
		//$grid->setDelete( $this->datasis->sidapuede('SINVAUDIT','BORR_REG%'));
		$grid->setAdd(false);
		$grid->setEdit(false);
		$grid->setDelete(false);
		$grid->setSearch( $this->datasis->sidapuede('SINVAUDIT','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions('addfunc: sinvauditadd, editfunc: sinvauditedit, delfunc: sinvauditdel, viewfunc: sinvauditshow');

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
	* Busca la data en el Servidor por json
	*/
	function getdata(){
		$grid       = $this->jqdatagrid;

		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('sinvaudit');

		$response   = $grid->getData('sinvaudit', array(array()), array(), false, $mWHERE, 'id','desc' );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	/**
	* Guarda la Informacion
	*/
	function setData(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = $this->input->post('id');
		$data   = $_POST;
		$mcodp  = 'id';
		$check  = 0;
		$dbid   = $this->db->escape($data[$mcodp]);

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$check = $this->datasis->dameval("SELECT COUNT(*) AS cana FROM sinvaudit WHERE ${mcodp}=${dbid}");
				if ( $check == 0 ){
					$this->db->insert('sinvaudit', $data);
					echo "Registro Agregado";

					logusu('sinvaudit',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$posibles=array('contado');
			foreach($data as $ind=>$val){
				if(!in_array($ind,$posibles)){
					echo 'Campo no permitido ('.$ind.')';
					return false;
				}
			}

			$data['status'] = 'C';
			$this->db->where('id', $id);
			$this->db->update('sinvaudit', $data);

			logusu('sinvaudit','Conteo introducido');
			echo "${mcodp} Modificado";


		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM sinvaudit WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM sinvaudit WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM sinvaudit WHERE id=$id ");
				logusu('sinvaudit',"Registro ????? ELIMINADO");
				echo "Registro Eliminado";
			}
		};
	}

	//***************************
	//Definicion del Grid y la Forma
	//***************************
	function defgridit( $deployed = false ){
		$i      = 1;
		$editar = 'false';

		$grid  = new $this->jqdatagrid;

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));


		/*$grid->addField('id_sinvaudit');
		$grid->label('Id_sinvaudit');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0 }',
			'hidden'        => 'false'
		));*/


		$grid->addField('almacen');
		$grid->label('Almacen');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->addField('existen');
		$grid->label('Existen');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }',
			'hidden'        => 'true'
		));


		/*$grid->addField('despacho');
		$grid->label('Despacho');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));


		$grid->addField('reparto');
		$grid->label('Reparto');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));*/


		/*$grid->addField('pendiente');
		$grid->label('Pendiente');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));*/


		$grid->addField('contado');
		$grid->label('Contado');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'true',
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));

		$grid->setShrinkToFit('false');
		#Set url
		$grid->setUrlput(site_url($this->url.'setdatait/'));

		#GET url
		$grid->setUrlget(site_url($this->url.'getdatait/'));

		if ($deployed) {
			return $grid->deploy();
		} else {
			return $grid;
		}
	}

	/**
	* Busca la data en el Servidor por json
	*/
	function getdatait($id = 0){
		if($id === 0){
			$id = $this->datasis->dameval("SELECT MAX(id) FROM sinvaudit");
		}
		if(empty($id)) return "";
		$grid    = $this->jqdatagrid;
		$mSQL    = "SELECT * FROM itsinvaudit WHERE id_sinvaudit=${id} ";
		$response= $grid->getDataSimple($mSQL);
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	/**
	* Guarda la Informacion
	*/
	function setDatait(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = intval($this->input->post('id'));
		$data   = $_POST;
		$check  = 0;
		$dbid   = $this->db->escape($data[$mcodp]);

		if(empty($id)) return false;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			echo 'Deshabilitado';
		}elseif($oper == 'edit'){
			$id_sinvaudit = intval($this->datasis->dameval("SELECT id_sinvaudit FROM sinvaudit WHERE ${mcodp}=${dbid}"));

			$posibles=array('conteo');
			foreach($data as $ind=>$val){
				if(!in_array($ind,$posibles)){
					echo 'Campo no permitido ('.$ind.')';
					return false;
				}
			}

			$this->db->where('id',$id);
			$this->db->update('itsinvaudit',array('contado'=>$data['contado']));

			$this->db->where('id',$id_sinvaudit);
			$this->db->update('sinvaudit',array('status'=>'D'));

		} elseif($oper == 'del') {
			echo 'Deshabilitado';
		}
	}

	//***********************************
	// DataEdit
	//***********************************

	function dataedit(){
		$this->rapyd->load('dataobject','datadetails');
		$script= '
		$(function() {
			$("#fecha").datepicker({dateFormat:"dd/mm/yy"});
			$(".inputnum").numeric(".");
		});
		';

		$do = new DataObject('sinvaudit');

		$do->rel_one_to_many('itsinvaudit','itsinvaudit','numero');
		$edit = new DataDetails($this->tits, $do );

		$edit->script($script,'modify');
		$edit->script($script,'create');
		$edit->on_save_redirect=false;

		$edit->back_url = site_url($this->url.'filteredgrid');

		$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');
		$edit->pre_process('insert', '_pre_insert' );
		$edit->pre_process('update', '_pre_update' );
		$edit->pre_process('delete', '_pre_delete' );

		$edit->id_sinv = new inputField('Id_sinv','id_sinv');
		$edit->id_sinv->rule='integer';
		$edit->id_sinv->css_class='inputonlynum';
		$edit->id_sinv->size =13;
		$edit->id_sinv->maxlength =11;

		$edit->status = new inputField('Status','status');
		$edit->status->rule='';
		$edit->status->size =3;
		$edit->status->maxlength =1;

		$edit->codigo = new inputField('Codigo','codigo');
		$edit->codigo->rule='';
		$edit->codigo->size =17;
		$edit->codigo->maxlength =15;

		$edit->corte = new dateonlyField('Corte','corte');
		$edit->corte->rule='chfecha';
		$edit->corte->calendar=false;
		$edit->corte->size =10;
		$edit->corte->maxlength =8;

		$edit->existen = new inputField('Existen','existen');
		$edit->existen->rule='numeric';
		$edit->existen->css_class='inputnum';
		$edit->existen->size =12;
		$edit->existen->maxlength =10;

		$edit->estampa = new autoUpdateField('estampa' ,date('Ymd'), date('Ymd'));

		$edit->usuario = new autoUpdateField('usuario',$this->session->userdata('usuario'),$this->session->userdata('usuario'));


		//******************************************************************
		// Detalle
		$edit->id_sinvaudit = new inputField('Id_sinvaudit','id_sinvaudit_<#i#>');
		$edit->id_sinvaudit->rule='integer';
		$edit->id_sinvaudit->css_class='inputonlynum';
		$edit->id_sinvaudit->size =13;
		$edit->id_sinvaudit->maxlength =11;
		$edit->id_sinvaudit->rel_id ='itsinvaudit';

		$edit->almacen = new inputField('Almacen','almacen_<#i#>');
		$edit->almacen->rule='';
		$edit->almacen->size =52;
		$edit->almacen->maxlength =50;
		$edit->almacen->rel_id ='itsinvaudit';

		$edit->existen = new inputField('Existen','existen_<#i#>');
		$edit->existen->rule='numeric';
		$edit->existen->css_class='inputnum';
		$edit->existen->size =15;
		$edit->existen->maxlength =13;
		$edit->existen->rel_id ='itsinvaudit';

		$edit->despacho = new inputField('Despacho','despacho_<#i#>');
		$edit->despacho->rule='numeric';
		$edit->despacho->css_class='inputnum';
		$edit->despacho->size =15;
		$edit->despacho->maxlength =13;
		$edit->despacho->rel_id ='itsinvaudit';

		$edit->reparto = new inputField('Reparto','reparto_<#i#>');
		$edit->reparto->rule='numeric';
		$edit->reparto->css_class='inputnum';
		$edit->reparto->size =15;
		$edit->reparto->maxlength =13;
		$edit->reparto->rel_id ='itsinvaudit';

		$edit->pendiente = new inputField('Pendiente','pendiente_<#i#>');
		$edit->pendiente->rule='numeric';
		$edit->pendiente->css_class='inputnum';
		$edit->pendiente->size =15;
		$edit->pendiente->maxlength =13;
		$edit->pendiente->rel_id ='itsinvaudit';

		$edit->contado = new inputField('Contado','contado_<#i#>');
		$edit->contado->rule='numeric';
		$edit->contado->css_class='inputnum';
		$edit->contado->size =15;
		$edit->contado->maxlength =13;
		$edit->contado->rel_id ='itsinvaudit';

		//******************************************************************

		$edit->buttons('add_rel');

		$edit->build();

		if($edit->on_success()){
			$rt=array(
				'status' =>'A',
				'mensaje'=>'Registro guardado',
				'pk'     =>$edit->_dataobject->pk
			);
			echo json_encode($rt);
		}else{
			$conten['form']  =& $edit;
			$this->load->view('view_sinvaudit', $conten);
		}
	}

	function _pre_insert($do){
		$do->error_message_ar['pre_ins']='';
		return true;
	}

	function _pre_update($do){
		$do->error_message_ar['pre_upd']='';
		return true;
	}

	function _pre_delete($do){
		$do->error_message_ar['pre_del']='';
		return false;
	}

	function _post_insert($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Creo $this->tits $primary ");
	}

	function _post_update($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Modifico $this->tits $primary ");
	}

	function _post_delete($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Elimino $this->tits $primary ");
	}

	function finalizar(){
		$mSQL="UPDATE sinvaudit SET status='F' WHERE status='C'";
		$this->db->simple_query($mSQL);
		echo 'Auditorias finalizadas';
	}

	function instalar(){
		if (!$this->db->table_exists('sinvaudit')) {
			$mSQL="CREATE TABLE `sinvaudit` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_sinv` int(11) DEFAULT '0',
			  `status` char(1) DEFAULT 'P',
			  `codigo` varchar(15) DEFAULT NULL,
			  `corte` datetime DEFAULT NULL,
			  `existen` decimal(10,2) DEFAULT '0.00',
			  `estampa` datetime DEFAULT CURRENT_TIMESTAMP,
			  `usuario` varchar(50) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id_sinv` (`id_sinv`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Auditoria inventario'";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('sinvaudit');
		//if(!in_array('<#campo#>',$campos)){ }

		if (!$this->db->table_exists('itsinvaudit')) {
			$mSQL="CREATE TABLE `itsinvaudit` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `id_sinvaudit` int(11) DEFAULT NULL,
			  `almacen` varchar(50) DEFAULT NULL,
			  `existen` decimal(13,3) DEFAULT NULL,
			  `despacho` decimal(13,3) DEFAULT NULL,
			  `reparto` decimal(13,3) DEFAULT NULL,
			  `pendiente` decimal(13,3) DEFAULT NULL,
			  `contado` decimal(13,3) DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id_sinvaudit` (`id_sinvaudit`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('itsinvaudit');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}
