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
		$param['grids'][] = $grid->deploy();

		//Funciones que ejecutan los botones
		$bodyscript = $this->bodyscript( $param['grids'][0]['gridname']);

		//Botones Panel Izq
		$grid->wbotonadd(array("id"=>"ordene",   "img"=>"images/engrana.png",  "alt" => "Orden Estimada", "label"=>"Orden Estimada"));
		$grid->wbotonadd(array("id"=>"ordenr",   "img"=>"images/engrana.png",  "alt" => "Orden Real",     "label"=>"Orden Real"));
		//$grid->wbotonadd(array("id"=>"ordenr",   "img"=>"images/engrana.png",  "alt" => "Orden Real",     "label"=>"Orden Real"));

		$WestPanel = $grid->deploywestp();

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
		$this->load->view('jqgrid/crud2',$param);
	}

	//******************************************************************
	// Funciones de los Botones
	//
	function bodyscript( $grid0 ){
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

		$bodyscript .= '
		$("#ordene").click(
			function(){
				window.open(\''.site_url('inventario/prdo/creaped/').'/\', \'_blank\', \'width=800, height=600, scrollbars=yes, status=yes, resizable=yes,screenx=((screen.availHeight/2)-300), screeny=((screen.availWidth/2)-400)\');
			}
		);
		';

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


		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
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

		$response   = $grid->getData('prdo', array(array()), array(), false, $mWHERE );
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

table.tabla_cualitativa_a td.header {padding-right: 1px;padding-left: 1px;font-weight: bold;font-size: 8pt;color: navy;background-color: #f4edd5;text-align:center;}
table.tabla_cualitativa_a td.title{padding-right: 1px;padding-left: 1px;font-weight: bold;font-size: 8pt;color:navy;text-align:center;background-color: #fdffdf;}
table.tabla_cualitativa_a td.resalte{border-left:solid 1px #daac00;border-top:solid 1px #daac00;text-align:center;font-weight: bold;}
table.tabla_cualitativa_a td{ border-left:solid 1px #DAAC00;border-TOP:solid  1px #DAAC00;}
table.tabla_cualitativa_a{border-right: #daac00 1px solid;padding-right: 0px;border-top: medium none;padding-left: 0px;padding-bottom: 0px;border-left: medium none;border-bottom:  #daac00 1px solid;font-family: verdana;font-size:8pt;cellspacing: 0px}
table.tabla_cualitativa_a td.sin_borde{border-left:solid 1px #DAAC00;border-TOP:solid 1px #DAAC00;text-align:center;border-right:solid 5px #f6f6f6;border-bottom:solid 5px #f6f6f6;}
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

		$script .= '
<script type="text/javascript">
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

	$(function() {
		$("#tablas").selectable({
			selected: function( event, ui ) {
				if ( $("#tabla1").val() == "" ) 
					$("#tabla1").val(ui.selected.id);
				else 
					$("#tabla2").val(ui.selected.id);
			}
		});
	});

	function camposdb() { 
		$.post("'.site_url('desarrollo/camposdb')."/".'"+$("#tabla1").val(),
		function(data){
			$("#resultado").html("");
			$("#resultado").html(data);
		});
	};
	
	function lcamposdb () { 
		$.post("'.site_url('desarrollo/lcamposdb')."/".'"+$("#tabla1").val(),
		function(data){
			$("#resultado").html("");
			$("#resultado").html(data);
		});
	};

	function ccamposdb () { 
		$.post("'.site_url('desarrollo/ccamposdb')."/".'"+$("#tabla1").val(),
		function(data){
			
			$("#resultado").html(data);
		});
	};

	function jqgrid () { 
		window.open(\''.site_url('desarrollo/jqgrid').'/\'+$("#tabla1").val()+"/"+$("#modulo").val(), \'_blank\', \'width=900, height=700, scrollbars=yes, status=yes, resizable=yes,screenx=((screen.availHeight/2)-350), screeny=((screen.availWidth/2)-450)\');
	};

	function jqgridmd () { 
		window.open(\''.site_url('desarrollo/jqgridmd').'/\'+$("#tabla1").val()+"/"+$("#tabla2").val()+"/"+$("#modulo").val(), \'_blank\', \'width=900, height=700, scrollbars=yes, status=yes, resizable=yes,screenx=((screen.availHeight/2)-350), screeny=((screen.availWidth/2)-450)\');
	};


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
$tabla .= '
<div class="ui-layout-west">
	Tablas Disponibles:
	<ol id="tablas">
';
	//Trae las tablas
/*
	$query = $this->db->query("SHOW TABLE STATUS");
	foreach ($query->result_array() as $field){
		if ( substr($field['Name'],0,4) != 'b2b_'
			&& $field['Name'] != 'ModBusqueda' 
			&& $field['Name'] != 'accdirecto' 
			&& $field['Name'] != 'usuario' 
			&& $field['Name'] != 'sida' 
			&& ctype_lower($field['Name'])
		)
		$tabla .= "\t<li class='ui-widget-content' id='".$field['Name']."' > ".$field['Name'].'</li>';
	}
*/
$tabla .= '
	</ol>
</div>
';

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
</div>
';

// CENTRO
/*
$tabla .= '
<div class="ui-layout-center">
	<table width="100%" bgcolor="#58ACFA">
		<tr>
			<td>Tabla Maestra</td>
			<td><input id="tabla1" type="text" value="" ></td>
			<td><button id="camposdb"  onclick="camposdb()" >Arreglo de Campos</button></td>
			<td><button id="lcamposdb" onclick="lcamposdb()">Lista de Campos</button></td>
		</tr><tr>
			<td>Tabla Detalle </td>
			<td><input id="tabla2" type="text" value="" ></td>
			<td><button id="ccamposdb" onclick="ccamposdb()">Lista con comillas</button></td>
		</tr><tr>
			<td>Modulo</td>
			<td><button id="jqgrid"   onclick="jqgrid()"  >Generar Maestro</button></td>
			<td><button id="jqgridmd" onclick="jqgridmd()">Maestro Detalle</button></td>
		</tr>
	</table>
	<br>
	<div style="background:#EAEAEA" id="resultado"></div>
</div>
';
*/

$norden = $this->datasis->dameval('SELECT MAX(id) maxi FROM prdo');
if ($norden == '') $norden = 0;

$tabla .= '
<div class="ui-layout-center">
	<table width="100%" bgcolor="#58ACFA">
		<tr>
			<td>Ultima Orden</td>
			<td>Nro. '.$norden.'</td>
		</tr>
	</table>
	<br>
	<div style="background:#EAEAEA" id="resultado"></div>
';

$mSQL = '
SELECT a.id,
b.numero, b.fecha, b.cod_cli, b.nombre, a.codigoa, a.desca, a.cana, a.producido, d.ruta, d.descrip 
FROM itpfac a 
JOIN pfac b ON a.numa = b.numero 
LEFT JOIN sclitrut c ON b.cod_cli=c.cliente
LEFT JOIN sclirut  d ON c.ruta=d.ruta
WHERE b.producir="S" AND ( b.ordprod="" OR b.ordprod IS NULL )
ORDER BY d.ruta, a.codigoa, a.numa
';

$query  = $this->db->query($mSQL);
$ruta = 'XX0XX';
$codigo = 'XXZZWWXXWWXXZZZZ';
$i = 0;
$c = 0;
if ($query->num_rows() > 0){
	foreach ($query->result() as $row){
		if ( $ruta != $row->ruta ){
			if ( $i > 0 ) $tabla .= '</tbody></table>';
			$tabla .= '<table class="tabla_cualitativa_a" width="100%">';

			$tabla .= "<thead><tr style='background:#2067B5;color:#FFFFFF;'>\n";
			$tabla .= "<th colspan='8'>Ruta: ".$row->ruta." ".$row->descrip."</th>\n";
			$tabla .= "</tr></thead><tbody>\n";

			$tabla .= "<tr bgcolor='#BEDCFD'>\n";
			$tabla .= "<td >Numero</td>\n";
			$tabla .= "<td >Fecha</td>\n";
			$tabla .= "<td >Cliente</td>\n";
			$tabla .= "<td >Codigo</td>\n";
			$tabla .= "<td >Descripcion</td>\n";
			$tabla .= "<td >Cantidad</td>\n";
			$tabla .= "<td >Producido</td>\n";
			$tabla .= "<td >Ordenado</td>\n";
			$tabla .= "</tr>\n";

			$ruta = $row->ruta;
		}
		if ( $codigo != $row->codigoa ){
			if ( $i > 0 ){
				$tabla .= "<tr style='background:#2067B5;color:#FFFFFF;'>\n";
				$tabla .= "	<td colspan='8'>Codigo: ".$row->codigoa." ".$row->desca."</td>\n";
				$tabla .= "</tr>\n";
				
				$codigo = $row->codigoa;
			}
		}

		$tabla .= "<tr>\n";
		$tabla .= "<td>".$row->numero."</td>\n";
		$tabla .= "<td>".$row->fecha."</td>\n";
		$tabla .= "<td>".$row->cod_cli."</td>\n";
		$tabla .= "<td>".$row->codigoa."</td>\n";
		$tabla .= "<td>".$row->desca."</td>\n";
		$tabla .= "<td>".$row->cana."</td>\n";
		$tabla .= "<td>".$row->producido."</td>\n";

		$tabla .= "<td>\n";
		$tabla .= "<input name='can[$i]'    id='can[$i]'    size='6'>\n";
		//$tabla .= "<input name='numero[$i]' id='numero[$i]' type='hidden' size='2'>\n";
		$tabla .= "<input name='codigo[$c]' id='codigo[$c]' type='hidden' size='2'>\n";
		$tabla .= "</td>\n";

		$tabla .= "</tr>\n";
		$i++;
	}
	$tabla .= "</table>\n";

}

$tabla .= '
</div>
';
		$data['content'] = $tabla;
		$data['title']   = $title;
		$data['head']    = $styles;
		$data['head']   .= $script;

		$this->load->view('view_ventanas_lite',$data);

	}


}


?>
