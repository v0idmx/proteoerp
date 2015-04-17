<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Prdo extends Controller {
	var $mModulo = 'PRDO';
	var $titp    = 'Orden de Produccion';
	var $tits    = 'Orden de Produccion';
	var $url     = 'inventario/prdo/';

	function Prdo(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->instalar();
		$this->datasis->modulo_nombre( 'PRDO', $ventana=0, $this->titp  );
	}

	function index(){
		//$this->datasis->creaintramenu(array('modulo'=>'000','titulo'=>'<#titulo#>','mensaje'=>'<#mensaje#>','panel'=>'<#panal#>','ejecutar'=>'<#ejecuta#>','target'=>'popu','visible'=>'S','pertenece'=>'<#pertenece#>','ancho'=>900,'alto'=>600));
		$this->datasis->modintramenu( 800, 600, substr($this->url,0,-1) );
		redirect($this->url.'jqdatag');
	}

	//******************************************************************
	// Layout en la Ventana
	//
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
		$grid->wbotonadd(array("id"=>"ordene",   "img"=>"images/engrana.png",  "alt" => "Orden Estimada", "label"=>"Orden Estimada"));
		//$grid->wbotonadd(array("id"=>"ordenr",   "img"=>"images/engrana.png",  "alt" => "Orden Real",     "label"=>"Orden Real"));
		//$grid->wbotonadd(array("id"=>"ordenr",   "img"=>"images/engrana.png",  "alt" => "Orden Real",     "label"=>"Orden Real"));

		$WestPanel = $grid->deploywestp();

		//Panel Central
		$centerpanel = $grid->centerpanel( $id = "radicional", $param['grids'][0]['gridname'], $param['grids'][1]['gridname'] );

		$adic = array(
			array('id'=>'fedita',  'title'=>'Agregar/Editar Registro'),
			array('id'=>'fshow' ,  'title'=>'Mostrar Registro'),
			array('id'=>'fborra',  'title'=>'Eliminar Registro')
		);
		$SouthPanel = $grid->SouthPanel($this->datasis->traevalor('TITULO1'), $adic);

		$param['WestPanel']   = $WestPanel;
		//$param['EastPanel'] = $EastPanel;
		$param['SouthPanel']  = $SouthPanel;
		$param['listados']    = $this->datasis->listados('PRDO', 'JQ');
		$param['otros']       = $this->datasis->otros('PRDO', 'JQ');
		$param['temas']       = array('proteo','darkness','anexos1');
		$param['bodyscript']  = $bodyscript;
		$param['tabs']        = false;
		$param['encabeza']    = $this->titp;
		$param['tamano']      = $this->datasis->getintramenu( substr($this->url,0,-1) );

		$param['script']       = script('plugins/jquery.ui.autocomplete.autoSelectOne.js');
		$param['readyLayout']  = $readyLayout;
		$param['centerpanel']  = $centerpanel;

		$this->load->view('jqgrid/crud2',$param);

	}

	//******************************************************************
	// Funciones de los Botones
	//
	function bodyscript( $grid0, $grid1 ){
		$bodyscript = '		<script type="text/javascript">';

		$bodyscript .= '
		function prdoadd(){
			$.post("'.site_url($this->url.'dataedit/create').'",
			function(data){
				$("#fedita").html(data);
				$("#fedita").dialog( "open" );
			})
		};';

		$bodyscript .= '
		function prdoedit(){
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
		function prdoshow(){
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
		function prdodel() {
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
									'.$this->datasis->jwinopen(site_url('formatos/ver/PRDO').'/\'+json.pk.id+\'/id\'').';
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

		$bodyscript .= '});'."\n";

		$bodyscript .= '
		$("#ordene").click(
			function(){
				window.open(\''.site_url('inventario/prdo/creaped/').'/\', \'_blank\', \'width=900, height=600, scrollbars=yes, status=yes, resizable=yes,screenx=((screen.availHeight/2)-300), screeny=((screen.availWidth/2)-400)\');
			}
		);
		';

		$bodyscript .= "\n</script>\n";
		$bodyscript .= "";
		return $bodyscript;
	}


	function bodyscript1( $grid0 ){
		$bodyscript = '<script type="text/javascript">';
		$ngrid = '#newapi'.$grid0;

		$bodyscript .= $this->jqdatagrid->bsshow('prdo', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'prdo', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'prdo', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('prdo', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '300', '400' );

		$bodyscript .= '});';


		$bodyscript .= '</script>';

		return $bodyscript;
	}


	//******************************************************************
	// Definicion del Grid o Tabla 
	//
	function defgrid( $deployed = false ){
		$i      = 1;
		$editar = "false";

		$grid  = new $this->jqdatagrid;

		$grid->addField('numero');
		$grid->label('Numero');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));

		$grid->addField('fecha');
		$grid->label('Fecha');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));

		$grid->addField('almacen');
		$grid->label('Almacen');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:4, maxlength: 4 }',
		));

		$grid->addField('status');
		$grid->label('Status');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:2, maxlength: 2 }',
		));

		$grid->addField('instrucciones');
		$grid->label('Instrucciones');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 250,
			'edittype'      => "'textarea'",
			'editoptions'   => "'{rows:2, cols:60}'",
		));

		$grid->addField('estampa');
		$grid->label('Estampa');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));

		$grid->addField('usuario');
		$grid->label('Usuario');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 120,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:12, maxlength: 12 }',
		));

/*
		$grid->addField('hora');
		$grid->label('Hora');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));

		$grid->addField('modificado');
		$grid->label('Modificado');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));
*/

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'hidden'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
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
			}'
		);

		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setAfterSubmit("$('#respuesta').html('<span style=\'font-weight:bold; color:red;\'>'+a.responseText+'</span>'); return [true, a ];");

		$grid->setOndblClickRow('');		#show/hide navigations buttons
		$grid->setAdd(    $this->datasis->sidapuede('PRDO','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('PRDO','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('PRDO','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('PRDO','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: prdoadd, editfunc: prdoedit, delfunc: prdodel, viewfunc: prdoshow");

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

	//******************************************************************
	// Busca la data en el Servidor por json
	//
	function getdata(){
		$grid       = $this->jqdatagrid;

		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('prdo');

		$response   = $grid->getData('prdo', array(array()), array(), false, $mWHERE, 'id', 'Desc' );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	//******************************************************************
	// Guarda la Informacion del Grid o Tabla
	//
	function setData(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = $this->input->post('id');
		$data   = $_POST;
		$mcodp  = "??????";
		$check  = 0;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$check = $this->datasis->dameval("SELECT count(*) FROM prdo WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('prdo', $data);
					echo "Registro Agregado";

					logusu('PRDO',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM prdo WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM prdo WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE prdo SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("prdo", $data);
				logusu('PRDO',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('prdo', $data);
				logusu('PRDO',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM prdo WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM prdo WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM prdo WHERE id=$id ");
				logusu('PRDO',"Registro ????? ELIMINADO");
				echo "Registro Eliminado";
			}
		};
	}


	//******************************************************************
	// Definicion del Grid y la Forma
	//
	function defgridit( $deployed = false ){
		$i      = 1;
		$editar = "false";

		$grid  = new $this->jqdatagrid;

/*
		$grid->addField('numero');
		$grid->label('Numero');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));
*/

		$grid->addField('pedido');
		$grid->label('Pedido');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 60,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));

		$grid->addField('codigo');
		$grid->label('Codigo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:15, maxlength: 15 }',
		));

		$grid->addField('descrip');
		$grid->label('Descrip');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:40, maxlength: 40 }',
		));


		$grid->addField('cana');
		$grid->label('Pedido');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 80,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));


		$grid->addField('ordenado');
		$grid->label('Ordenado');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 80,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));

/*
		$grid->addField('idpfac');
		$grid->label('Idpfac');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 0 }'
		));
*/

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'hidden'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
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
	function getdatait( $id = 0 )
	{
		if ($id === 0 ){
			$id = $this->datasis->dameval("SELECT MAX(id) FROM prdo");
		}
		if(empty($id)) return "";
		$numero   = $this->datasis->dameval("SELECT numero FROM prdo WHERE id=$id");
		$grid    = $this->jqdatagrid;
		$mSQL    = "SELECT * FROM itprdo WHERE numero='$numero' ";
		$response   = $grid->getDataSimple($mSQL);
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	/**
	* Guarda la Informacion
	*/
	function setDatait()
	{
	}


	//******************************************************************
	// Edicion 

	function dataedit(){
		$this->rapyd->load('dataedit');
		$script= '
		$(function() {
			$("#fecha").datepicker({dateFormat:"dd/mm/yy"});
			$(".inputnum").numeric(".");
		});
		';

		$edit = new DataEdit('', 'prdo');

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

		$edit->numero= new inputField('N&uacute;mero', 'numero');
		$edit->numero->mode='autohide';
		$edit->numero->size=10;
		$edit->numero->apply_rules=false; //necesario cuando el campo es clave y no se pide al usuario
		$edit->numero->when=array('show','modify');

		$edit->fecha = new dateonlyField('Fecha','fecha');
		$edit->fecha->rule='chfecha';
		$edit->fecha->calendar=false;
		$edit->fecha->size =10;
		$edit->fecha->maxlength =8;
		$edit->fecha->insertValue=date('Y-m-d');

		$edit->almacen = new  dropdownField ('Almacen', 'depo');
		$edit->almacen->options('SELECT ubica, CONCAT(ubica,\' \',ubides) nombre FROM caub ORDER BY ubica');
		$edit->almacen->rule = 'required';
		$edit->almacen->mode = 'autohide';
		$edit->almacen->style='width:145px;';
		$alma = $this->datasis->traevalor('ALMACEN');
		if(!empty($alma)){
			$edit->almacen->insertValue=$alma;
		}

		$edit->status = new inputField('Status','status');
		$edit->status->rule='';
		$edit->status->size =4;
		$edit->status->maxlength =2;

		$edit->instrucciones = new textareaField('Instrucciones','instrucciones');
		$edit->instrucciones->rule='';
		$edit->instrucciones->cols = 40;
		$edit->instrucciones->rows = 2;

		$edit->estampa = new autoUpdateField('estampa' ,date('Ymd'), date('Ymd'));
		$edit->usuario = new autoUpdateField('usuario',$this->session->userdata('usuario'),$this->session->userdata('usuario'));
		$edit->hora    = new autoUpdateField('hora',date('H:i:s'), date('H:i:s'));
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
			$this->load->view('view_prdo', $conten);
			//echo $edit->output;
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
		return true;
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

	function instalar(){
		if (!$this->db->table_exists('prdo')) {
			$mSQL="CREATE TABLE `prdo` (
			  `numero` varchar(8) NOT NULL DEFAULT '',
			  `fecha` date DEFAULT NULL,
			  `almacen` varchar(4) DEFAULT NULL COMMENT 'Almacen de descuento',
			  `status` char(2) DEFAULT '0' COMMENT 'Activa, Pausada, Finalizada',
			  `instrucciones` text,
			  `estampa` date NOT NULL DEFAULT '0000-00-00',
			  `usuario` varchar(12) NOT NULL DEFAULT '',
			  `hora` varchar(8) NOT NULL DEFAULT '',
			  `modificado` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `numero` (`numero`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Orden de Produccion'";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('prdo');
		//if(!in_array('<#campo#>',$campos)){ }


		if (!$this->db->table_exists('itprdo')) {
			$mSQL="CREATE TABLE itprdo (
				numero   VARCHAR(8) NOT NULL DEFAULT '',
				pedido   CHAR(8)        NULL DEFAULT NULL,
				codigo   CHAR(15)       NULL DEFAULT NULL,
				descrip  CHAR(40)       NULL DEFAULT NULL,
				cana     DECIMAL(12,3)  NULL DEFAULT '0.000',
				ordenado DECIMAL(12,3)  NULL DEFAULT '0.000',
				idpfac   INT(11)        NULL DEFAULT '0',
				id       INT(1) NOT NULL AUTO_INCREMENT,
				PRIMARY  KEY (`id`),
				INDEX numero (numero),
				INDEX pedido (pedido)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC COMMENT='Detalle de Ordenes de Produccion'";
			$this->db->query($mSQL);
		}

	}


	//******************************************************************
	// Genera Produccion a partir de los pedidos pendientes
	//
	function creaped() {

		$styles  = "\n<!-- Estilos -->\n";
		$styles .= style('rapyd.css');
		$styles .= style('ventanas.css');
		$styles .= style('themes/proteo/proteo.css');
		$styles .= style("themes/ui.jqgrid.css");
		$styles .= style("themes/ui.multiselect.css");
		$styles .= style('layout1.css');
		$styles .= '<link rel="stylesheet" href="'.base_url().'system/application/rapyd/elements/proteo/css/rapyd_components.css" type="text/css" />'."\n";


		$styles .= '
<style type="text/css">
	p {font-size:1em; margin: 1ex 0;}
	p.buttons {text-align:center;line-height:2.5em;}
	button {line-height: normal;}
	.hidden {display: none;}
	ul {z-index:100000;margin:1ex 0;padding:0;list-style:none;cursor:pointer;border:1px solid Black;width:15ex;position:	relative;}
	ul li {background-color: #EEE;padding: 0.15em 1em 0.3em 5px;}
	ul ul {display:none;position:absolute;width:100%;left:-1px;bottom:0;margin:0;margin-bottom: 1.55em;}
	.ui-layout-north ul ul {bottom:auto;margin:0;margin-top:1.45em;}
	ul ul li { padding: 3px 1em 3px 5px; }
	ul ul li:hover { background-color: #FF9; }
	ul li:hover ul { display:block; background-color: #EEE; }

	#feedback { font-size: 0.8em; }
	#tablas .ui-selecting { background: #FECA40; }
	#tablas .ui-selected { background: #F39814; color: white; }
	#tablas { list-style-type: none; margin: 0; padding: 0; width: 90%; }
	#tablas li { margin: 1px; padding: 0em; font-size: 0.8em; height: 14px; }

table.tc td.header {padding-right: 1px;padding-left: 1px;font-weight: bold;font-size: 8pt;color: navy;background-color: #f4edd5;text-align:center;}
table.tc td.title{padding-right: 1px;padding-left: 1px;font-weight: bold;font-size: 8pt;color:navy;text-align:center;background-color: #fdffdf;}
table.tc td.resalte{border-left:solid 1px #daac00;border-top:solid 1px #daac00;text-align:center;font-weight: bold;}
table.tc td{ border-left:solid 1px #DAAC00;border-TOP:solid  1px #DAAC00;}
table.tc {border-right: #daac00 1px solid;padding-right: 0px;border-top: medium none;padding-left: 0px;padding-bottom: 0px;border-left: medium none;border-bottom:  #daac00 1px solid;font-family: verdana;font-size:8pt;cellspacing: 0px}
table.tc td.sin_borde{border-left:solid 1px #DAAC00;border-TOP:solid 1px #DAAC00;text-align:center;border-right:solid 5px #f6f6f6;border-bottom:solid 5px #f6f6f6;}
</style>
';

		$title = "
<div id='encabe'>
<table width='98%'>
	<tr>
		<td>".heading('Generar Orden de Produccion')."</td>
		<td align='right' width='40'>".image('cerrar.png','Cerrar Ventana',array('onclick'=>'window.close()','height'=>'20'))."</td>
	</tr>
</table>
</div>
";
		$script  = "\n<!-- JQUERY -->\n";
		$script .= script('jquery-min.js');
		$script .= script('jquery-migrate-min.js');
		$script .= script('jquery-ui.custom.min.js');

		$script .= script("jquery.layout.js");
		$script .= script("i18n/grid.locale-sp.js");

		$script .= script("ui.multiselect.js");
		$script .= script("jquery.jqGrid.min.js");
		$script .= script("jquery.tablednd.js");
		$script .= script("jquery.contextmenu.js");

		$script .= script('plugins/jquery.numeric.pack.js');
		$script .= script('plugins/jquery.floatnumber.js');
		$script .= script('plugins/jquery.maskedinput.min.js');

		$script .= '
<script type="text/javascript">
	$(function(){
		$(".inputnum").numeric(".");
	});
	$(function() {
		$( "input:submit, a, button", ".botones",".otros" ).button();
	});

';

		$script .= '
	// set EVERY state here so will undo ALL layout changes
	// used by the Reset State button: myLayout.loadState( stateResetSettings )
	var stateResetSettings = {
		north__size:		"auto"
	,	north__initClosed:	false
	,	north__initHidden:	false
	,	south__size:		"auto"
	,	south__initClosed:	false
	,	south__initHidden:	false
	,	west__size:			200
	,	west__initClosed:	false
	,	west__initHidden:	false
	,	east__size:			300
	,	east__initClosed:	false
	,	east__initHidden:	false
	};

	var myLayout;

	$(document).ready(function () {

		// this layout could be created with NO OPTIONS - but showing some here just as a sample...
		// myLayout = $("body").layout(); -- syntax with No Options

		myLayout = $("body").layout({

		//	reference only - these options are NOT required because "true" is the default
			closable: true,	resizable:	true, slidable:	true, livePaneResizing:	true
		//	some resizing/toggling settings
		,	north__slidable: false, north__togglerLength_closed: "100%", north__spacing_closed:	20
		,	south__resizable:false,	south__spacing_open:0
		,	south__spacing_closed:20
		//	some pane-size settings
		,	west__minSize: 100, east__size: 300, east__minSize: 200, east__maxSize: .5, center__minWidth: 100
		//	some pane animation settings
		,	west__animatePaneSizing: false,	west__fxSpeed_size:	"fast",	west__fxSpeed_open: 1000
		,	west__fxSettings_open:{ easing: "easeOutBounce" },	west__fxName_close:"none"
		//	enable showOverflow on west-pane so CSS popups will overlap north pane
		//,	west__showOverflowOnHover:	true
		,	stateManagement__enabled:true, showDebugMessages: true
		});
 	});

	function sumar(j){
		var nn = \'[name="codigo_\'+j+\'"]\';
		var k = 0;
		var t;
		var totalc = 0;
		var maximo = 0;

		// Valida el maximo
		$("#resultados").html("Maximo "+maximo);
	
		$(nn).each( function() {
			k = $(this).val();
			t = Number($("#cana_"+k).val());
			maximo = Number($("#falta_"+k).val());
			if ( t > maximo ){
				t = maximo;
				$("#cana_"+k).val(maximo);
			}
			totalc += t;
		});
		$(\'#totalc_\'+j).val(totalc);
	}

	function guardar(){
		alert("Guardar");
		//$("#guardar").submit();

		$.post( "'.base_url().'inventario/prdo/guardaoe", $("form#guardar").serialize(), 
			function(data) {
				alert("Listo");
			},"json" 
		);

	}
		
</script>
';

// ENCABEZADO
$tabla = '
<div class="ui-layout-north" onmouseover="myLayout.allowOverflow(\'north\')" onmouseout="myLayout.resetOverflow(this)">
<table width="100%" bgcolor="#2067B5">
	<tr>
		<td align="left" width="80px"><img src="'.base_url().'assets/default/css/templete_01.jpg" width="120"></td><td align="center"><h1 style="font-size: 20px; color: rgb(255, 255, 255);" onclick="history.back()">ORDEN DE PRODUCCION</h1></td><td align="left" width="100px" nowrap="nowrap"><font style="color:#FFFFFF;font-size:12px">Usuario: '.$this->secu->usuario().'<br/>'.$this->secu->getnombre().'</font></td><td align="right" width="28px"></td>
	</tr>
</table>
</div>
';

// IZQUIERDO
$tabla .= '<div class="ui-layout-west">';

$tabla .= '</div>';

// INFERIOR
$tabla .= '
<div class="ui-layout-south">
';

$tabla .= $this->datasis->traevalor('TITULO1');

$tabla .= '
</div>
';

// DERECHA
$tabla .= '
<div class="ui-layout-east">
	<button type="button" onclick="guardar()">Guardar Orden</button>
	<div id="resultados"></div>
</div>
';

// CENTRO
$norden = $this->datasis->dameval('SELECT MAX(id) maxi FROM prdo');
if ($norden == '') $norden = 0;

$tabla .= '
<div class="ui-layout-center">
<form id="guardar" >';
$tabla .= "\nAlmacen: ";

$tabla .= $this->datasis->llenaopciones("SELECT ubica, ubides FROM caub WHERE gasto='N' ORDER BY ubica", false, $id='almacen' );

/*
	<table width="100%" bgcolor="#58ACFA">
		<tr>
			<td>Ultima Orden</td>
			<td>Nro. '.$norden.'</td>
		</tr>
	</table>

*/

$mSQL = '
SELECT a.id,
b.numero, b.fecha, b.cod_cli, b.nombre, a.codigoa, a.desca, a.cana, a.producido, a.cana-a.producido falta, d.ruta, d.descrip 
FROM itpfac a 
JOIN pfac b ON a.numa = b.numero 
LEFT JOIN sclitrut c ON b.cod_cli=c.cliente
LEFT JOIN sclirut  d ON c.ruta=d.ruta
WHERE b.producir="S" AND ( b.ordprod="" OR b.ordprod IS NULL )
ORDER BY a.codigoa, d.ruta, a.numa
';


$mSQL = '
SELECT a.id, b.numero, b.fecha, b.cod_cli, b.nombre, a.codigoa, a.desca, a.cana, COALESCE(sum(e.ordenado),0) producido, a.cana-COALESCE(sum(e.ordenado),0) falta, COALESCE(sum(e.ordenado),0) ordenado, d.ruta, d.descrip 
FROM itpfac a 
JOIN pfac b ON a.numa = b.numero 
LEFT JOIN sclitrut c ON b.cod_cli=c.cliente
LEFT JOIN sclirut  d ON c.ruta=d.ruta
LEFT JOIN itprdo   e ON a.id = e.idpfac
WHERE b.producir="S" AND ( b.ordprod="" OR b.ordprod IS NULL )
GROUP BY a.id
HAVING falta>0
ORDER BY a.codigoa, d.ruta, a.numa
';



$query  = $this->db->query($mSQL);
$ruta = 'XX0XX';
$codigo = 'XXZZWWXXWWXXZZZZ';
$i = 0;
$c = 0;
if ($query->num_rows() > 0){
	foreach ($query->result() as $row){
		if ( $ruta != $row->ruta ){
/*
			if ( $i > 0 ) $tabla .= "</tbody></table>\n";
			$tabla .= '<table class="tc">';
			//$tabla .= "<thead>";
			//$tabla .= "<tr style='background:#2067B5;color:#FFFFFF;'>\n";
			//$tabla .= "	<th colspan='7'>Ruta: ".$row->ruta." ".$row->descrip."</th>\n";
			$tabla .= "</tr></thead>";
			$tabla .= "<tbody>\n";
			$ruta = $row->ruta;
*/ 
		}

		if ( $codigo != $row->codigoa ){
			if ( $i > 0 ) $tabla .= "</tbody></table><br>\n";
			$tabla .= '<table class="tc" width="100%">';
			$tabla .= "<tbody>\n";

			if ( $i > 0 ) $c++;

			$tabla .= "<tr style='background:#2067B5;color:#FFFFFF;'>\n";
			$tabla .= "	<td colspan='5'>Cod: ".$row->codigoa." Desc: ".$row->desca."</td>\n";
			$tabla .= "	<td>&nbsp;</td>\n";
			$tabla .= "	<td><input class='inputnum' name='totalc_$c' id='totalc_$c' size='4' type='text' readonly></td>\n";
			$tabla .= "</tr>\n";

			$tabla .= "<tr bgcolor='#BEDCFD'>\n";
			$tabla .= "	<td >Ruta</td>\n";
			$tabla .= "	<td >Pedido</td>\n";
			$tabla .= "	<td >Fecha</td>\n";
			$tabla .= "	<td >Cliente</td>\n";
			$tabla .= "	<td >Cantidad</td>\n";
			$tabla .= "	<td >Producido</td>\n";
			$tabla .= "	<td >Ordenado</td>\n";
			$tabla .= "</tr>\n";

			$codigo = $row->codigoa;
		}

		$tabla .= "<tr>\n";
		$tabla .= "	<td>".$row->ruta."&nbsp;</td>\n";
		$tabla .= "	<td>".$row->numero."</td>\n";
		$tabla .= "	<td>".$row->fecha."</td>\n";
		$tabla .= "	<td>".$row->cod_cli."</td>\n";
		//$tabla .= "<td>".$row->desca."</td>\n";
		$tabla .= "	<td align='right'>".$row->cana."</td>\n";
		$tabla .= "	<td align='right'>".$row->producido."</td>\n";

		$tabla .= "	<td>\n";
		$tabla .= "		<input class='inputnum' name='cana_$i' id='cana_$i' size='4' onkeyUp='sumar($c)' value='0' >\n";
		$tabla .= "		<input name='codigo_$c' id='codigo_$c' type='hidden' value='$i' >\n";
		$tabla .= "		<input name='idpfac_$i' id='idpfac_$i' type='hidden' value='".$row->id.   "' >\n";
		$tabla .= "		<input name='falta_$i'  id='falta_$i'  type='hidden' value='".$row->falta."' >\n";
		$tabla .= "	</td>\n";

		$tabla .= "</tr>\n";
		$i++;
	}
	$tabla .= "</table>\n";
}

$tabla .= '
<input id="totalitem" name="totalitem" type="hidden" value="'.$i.'">
</form>
</div>
';
		$data['content'] = $tabla;
		$data['title']   = $title;
		$data['head']    = $styles;
		$data['head']   .= $script;
		$this->load->view('view_ventanas_lite',$data);
	}

	//******************************************************************
	// Crea la Orden de Produccion
	//
	function guardaoe(){
		$m = intval($_POST['totalitem']);
		$t = 0;
		// calcula el total de 
		for ( $i=0; $i < $m; $i++ ){
			$t += intval($_POST['cana_'.$i]);
		}
		if ( $t <= 0 ) {
			echo "No hay pedido";
			return;
		}

		// Crea encabezado
		$numero  = $this->datasis->fprox_numero('nprdo');
		$data['numero']  = $numero;
		$data['fecha']   = date('Y-m-d');
		$data['almacen'] = $_POST['almacen'];
		$data['status']  = 'A';
		$data['usuario'] = $this->secu->usuario();
		$data['estampa'] = date('Ymd');
		$data['hora']    = date('H:i:s');
		$this->db->insert('prdo',$data);
		
		// Crea Detalle
		$ids = '';
		for ( $i=0; $i < $m; $i++ ){
			$cana = intval($_POST['cana_'.$i]);
			if ( $cana > 0 ){
				// Guarda 
				$id = intval($_POST['idpfac_'.$i]);
				$mSQL = "
				INSERT INTO itprdo (numero, pedido, codigo, descrip, cana, ordenado, idpfac )
				SELECT '${numero}' numero, a.numa pedido, a.codigoa codigo, a.desca descrip, a.cana, ${cana} ordenado, ${id} idpfac 
				FROM itpfac a JOIN pfac b ON a.numa = b.numero
				WHERE a.id= ${id}";
				$this->db->query($mSQL);
			}
		}
		
	}
}

?>
