<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Afdeta extends Controller {
	var $mModulo = 'AFDETA';
	var $titp    = 'ACTIVOS FIJOS';
	var $tits    = 'ACTIVOS FIJOS';
	var $url     = 'afijos/afdeta/';

	function Afdeta(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'AFDETA', $ventana=0, $this->titp  );
	}

	function index(){
		$this->datasis->creaintramenu(array('modulo'=>'620','titulo'=>'Activos Fijos','mensaje'=>'Activos Fijos','panel'=>'Activos Fijos','ejecutar'=>'afijos/afdeta','target'=>'popu','visible'=>'S','pertenece'=>'6','ancho'=>900,'alto'=>600));
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
		$grid->wbotonadd(array("id"=>"fgrupo",   "img"=>"images/engrana.png",  "alt" => "Grupos de Activos",  "label"=>"Grupos",   'tema'=>'proteo'));
		$grid->wbotonadd(array("id"=>"ffami",    "img"=>"images/engrana.png",  "alt" => "Familia de Activos", "label"=>"Familias", 'tema'=>'proteo'));
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
		$param['listados']    = $this->datasis->listados('AFDETA', 'JQ');
		$param['otros']       = $this->datasis->otros('AFDETA', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('afdeta', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'afdeta', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'afdeta', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('afdeta', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '600', '400' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '300', '400' );

		$bodyscript .= '});';


		// Boton de Agregar
		$bodyscript .= '
		$("#fgrupo").click(function(){
			$.post("'.site_url('afijos/afdeta/fgrupoform').'",
			function(data){
				$("#fshow").html(data);
				$("#fshow").dialog({height: 450, width: 510, title: "fgrupo"});
				$("#fshow").dialog( "open" );
			});
		});';


		// Boton de Agregar
		$bodyscript .= '
		$("#ffami").click(function(){
			$.post("'.site_url('afijos/afdeta/ffamiform').'",
			function(data){
				$("#fshow").html(data);
				$("#fshow").dialog({height: 450, width: 510, title: "ffami"});
				$("#fshow").dialog( "open" );
			});
		});';


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

		$grid->addField('codigo');
		$grid->label('Codigo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:20, maxlength: 20 }',
		));


		$grid->addField('descrip');
		$grid->label('Descrip');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:100, maxlength: 100 }',
		));


		$grid->addField('fcompra');
		$grid->label('Fcompra');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));


		$grid->addField('ncompra');
		$grid->label('Ncompra');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:20, maxlength: 20 }',
		));


		$grid->addField('incorpora');
		$grid->label('Incorpora');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));


		$grid->addField('vidautil');
		$grid->label('Vidautil');
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


		$grid->addField('residual');
		$grid->label('Residual');
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


		$grid->addField('costo');
		$grid->label('Costo');
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


		$grid->addField('depacum');
		$grid->label('Depacum');
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


		$grid->addField('valorl');
		$grid->label('Valorl');
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


		$grid->addField('valora');
		$grid->label('Valora');
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


		$grid->addField('afectacont');
		$grid->label('Afectacont');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('ubica');
		$grid->label('Ubica');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('ficha');
		$grid->label('Ficha');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 250,
			'edittype'      => "'textarea'",
			'editoptions'   => "'{rows:2, cols:60}'",
		));


		$grid->addField('cuentaa');
		$grid->label('Cuentaa');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('cuentad');
		$grid->label('Cuentad');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('cuentac');
		$grid->label('Cuentac');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('serial1');
		$grid->label('Serial1');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('serial2');
		$grid->label('Serial2');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('serial3');
		$grid->label('Serial3');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('matricula');
		$grid->label('Matricula');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 150,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:15, maxlength: 15 }',
		));


		$grid->addField('responsable');
		$grid->label('Responsable');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->addField('status');
		$grid->label('Status');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));


		$grid->addField('origen');
		$grid->label('Origen');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));


		$grid->addField('retiro');
		$grid->label('Retiro');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));


		$grid->addField('motivo');
		$grid->label('Motivo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:200, maxlength: 200 }',
		));


		$grid->addField('idgr');
		$grid->label('Idgr');
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


		$grid->addField('idfami');
		$grid->label('Idfami');
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
		$grid->setAdd(    $this->datasis->sidapuede('AFDETA','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('AFDETA','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('AFDETA','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('AFDETA','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: afdetaadd, editfunc: afdetaedit, delfunc: afdetadel, viewfunc: afdetashow");

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
		$mWHERE = $grid->geneTopWhere('afdeta');

		$response   = $grid->getData('afdeta', array(array()), array(), false, $mWHERE );
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
				$check = $this->datasis->dameval("SELECT count(*) FROM afdeta WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('afdeta', $data);
					echo "Registro Agregado";

					logusu('AFDETA',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM afdeta WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM afdeta WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE afdeta SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("afdeta", $data);
				logusu('AFDETA',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('afdeta', $data);
				logusu('AFDETA',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM afdeta WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM afdeta WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM afdeta WHERE id=$id ");
				logusu('AFDETA',"Registro ????? ELIMINADO");
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

		$edit = new DataEdit('', 'afdeta');

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

		$edit->codigo = new inputField('Codigo','codigo');
		$edit->codigo->rule='';
		$edit->codigo->size =22;
		$edit->codigo->maxlength =20;

		$edit->descrip = new inputField('Descrip','descrip');
		$edit->descrip->rule='';
		$edit->descrip->size =102;
		$edit->descrip->maxlength =100;

		$edit->fcompra = new dateonlyField('Fcompra','fcompra');
		$edit->fcompra->rule='chfecha';
		$edit->fcompra->calendar=false;
		$edit->fcompra->size =10;
		$edit->fcompra->maxlength =8;

		$edit->ncompra = new inputField('Ncompra','ncompra');
		$edit->ncompra->rule='';
		$edit->ncompra->size =22;
		$edit->ncompra->maxlength =20;

		$edit->incorpora = new dateonlyField('Incorpora','incorpora');
		$edit->incorpora->rule='chfecha';
		$edit->incorpora->calendar=false;
		$edit->incorpora->size =10;
		$edit->incorpora->maxlength =8;

		$edit->vidautil = new inputField('Vidautil','vidautil');
		$edit->vidautil->rule='integer';
		$edit->vidautil->css_class='inputonlynum';
		$edit->vidautil->size =13;
		$edit->vidautil->maxlength =11;

		$edit->residual = new inputField('Residual','residual');
		$edit->residual->rule='numeric';
		$edit->residual->css_class='inputnum';
		$edit->residual->size =16;
		$edit->residual->maxlength =14;

		$edit->costo = new inputField('Costo','costo');
		$edit->costo->rule='numeric';
		$edit->costo->css_class='inputnum';
		$edit->costo->size =16;
		$edit->costo->maxlength =14;

		$edit->depacum = new inputField('Depacum','depacum');
		$edit->depacum->rule='numeric';
		$edit->depacum->css_class='inputnum';
		$edit->depacum->size =16;
		$edit->depacum->maxlength =14;

		$edit->valorl = new inputField('Valorl','valorl');
		$edit->valorl->rule='numeric';
		$edit->valorl->css_class='inputnum';
		$edit->valorl->size =16;
		$edit->valorl->maxlength =14;

		$edit->valora = new inputField('Valora','valora');
		$edit->valora->rule='numeric';
		$edit->valora->css_class='inputnum';
		$edit->valora->size =16;
		$edit->valora->maxlength =14;

		$edit->afectacont = new inputField('Afectacont','afectacont');
		$edit->afectacont->rule='';
		$edit->afectacont->size =32;
		$edit->afectacont->maxlength =30;

		$edit->ubica = new inputField('Ubica','ubica');
		$edit->ubica->rule='';
		$edit->ubica->size =32;
		$edit->ubica->maxlength =30;

		$edit->ficha = new textareaField('Ficha','ficha');
		$edit->ficha->rule='';
		$edit->ficha->cols = 70;
		$edit->ficha->rows = 4;

		$edit->cuentaa = new inputField('Cuentaa','cuentaa');
		$edit->cuentaa->rule='';
		$edit->cuentaa->size =32;
		$edit->cuentaa->maxlength =30;

		$edit->cuentad = new inputField('Cuentad','cuentad');
		$edit->cuentad->rule='';
		$edit->cuentad->size =32;
		$edit->cuentad->maxlength =30;

		$edit->cuentac = new inputField('Cuentac','cuentac');
		$edit->cuentac->rule='';
		$edit->cuentac->size =32;
		$edit->cuentac->maxlength =30;

		$edit->serial1 = new inputField('Serial1','serial1');
		$edit->serial1->rule='';
		$edit->serial1->size =32;
		$edit->serial1->maxlength =30;

		$edit->serial2 = new inputField('Serial2','serial2');
		$edit->serial2->rule='';
		$edit->serial2->size =32;
		$edit->serial2->maxlength =30;

		$edit->serial3 = new inputField('Serial3','serial3');
		$edit->serial3->rule='';
		$edit->serial3->size =32;
		$edit->serial3->maxlength =30;

		$edit->matricula = new inputField('Matricula','matricula');
		$edit->matricula->rule='';
		$edit->matricula->size =17;
		$edit->matricula->maxlength =15;

		$edit->responsable = new inputField('Responsable','responsable');
		$edit->responsable->rule='';
		$edit->responsable->size =52;
		$edit->responsable->maxlength =50;

		$edit->status = new inputField('Status','status');
		$edit->status->rule='';
		$edit->status->size =3;
		$edit->status->maxlength =1;

		$edit->origen = new inputField('Origen','origen');
		$edit->origen->rule='';
		$edit->origen->size =3;
		$edit->origen->maxlength =1;

		$edit->retiro = new dateonlyField('Retiro','retiro');
		$edit->retiro->rule='chfecha';
		$edit->retiro->calendar=false;
		$edit->retiro->size =10;
		$edit->retiro->maxlength =8;

		$edit->motivo = new inputField('Motivo','motivo');
		$edit->motivo->rule='';
		$edit->motivo->size =202;
		$edit->motivo->maxlength =200;

		$edit->idgr = new inputField('Idgr','idgr');
		$edit->idgr->rule='integer';
		$edit->idgr->css_class='inputonlynum';
		$edit->idgr->size =13;
		$edit->idgr->maxlength =11;

		$edit->idfami = new inputField('Idfami','idfami');
		$edit->idfami->rule='integer';
		$edit->idfami->css_class='inputonlynum';
		$edit->idfami->size =13;
		$edit->idfami->maxlength =11;

		$edit->build();

		if($edit->on_success()){
			$rt=array(
				'status' =>'A',
				'mensaje'=>'Registro guardado',
				'pk'     =>$edit->_dataobject->pk
			);
			echo json_encode($rt);
		}else{
			//echo $edit->output;
			$conten['form']  =&  $edit;
			$data['content']  =  $this->load->view('view_afdeta', $conten, false);
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


	//******************************************************************
	// Forma de Grupos
	//
	function fgrupoform(){
		$grid  = new $this->jqdatagrid;
		$editar = 'true';

		// ejemplos de dropdown
		//$mSQL  = "SELECT id, CONCAT(codigo,' ',nombre) nombre FROM tabla ORDER BY codigo";
		//$cargo = $this->datasis->llenajqselect($mSQL, true );

		$tipo = '{"T": "Tangible", "I": "Intangible", "A":"Amortizable"}';
		
		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'hidden'        => 'true',
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));
				$grid->addField('descrip');
		$grid->label('Nombre del Grupo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 150,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('tipo');
		$grid->label('Activo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 70,
			'edittype'      => "'select'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ value: '.$tipo.',  style:"width:70px"}',
			'stype'         => "'text'",
			'formatter'     => "fgtipo"
		));

		$grid->showpager(true);
		$grid->setViewRecords(false);
		$grid->setWidth('490');
		$grid->setHeight('280');

		$grid->setUrlget(site_url('afijos/afdeta/fgrupoget'));
		$grid->setUrlput(site_url('afijos/afdeta/fgruposet'));

		$mgrid = $grid->deploy();

		$msalida  = '<script type="text/javascript">'."\n";
		$msalida .= '
		$("#newapi'.$mgrid['gridname'].'").jqGrid({
			ajaxGridOptions : {type:"POST"}
			,jsonReader : { root:"data", repeatitems: false }
			'.$mgrid['table'].'
			,scroll: true
			,pgtext: null, pgbuttons: false, rowList:[]
		})
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'navGrid\',  "#pnewapi'.$mgrid['gridname'].'",{edit:false, add:false, del:true, search: false});
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'inlineNav\',"#pnewapi'.$mgrid['gridname'].'");
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'filterToolbar\');
		';

		$msalida  .= '
		function fgtipo(cv, op, ro ){
			var aftipos = '.$tipo.';
			return aftipos[cv];
		}';


		$msalida .= '</script>';
		$msalida .= '<div class="anexos"><table id="newapi'.$mgrid['gridname'].'"></table>';
		$msalida .= '<div id="pnewapi'.$mgrid['gridname'].'"></div></div>';

		echo $msalida;

	}
			
	//******************************************************************
	// Busca la data en el Servidor por json
	//
	function fgrupoget(){
		$grid       = $this->jqdatagrid;
		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('afgr');
		$response   = $grid->getData('afgr', array(array()), array(), false, $mWHERE );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

			
	//******************************************************************
	// Guarda los cambios
	//
	function fgruposet(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = intval($this->input->post('id'));
		$data   = $_POST;
		$mcodp  = 'descrip';
		$check  = 0;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$check = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM afgr WHERE descrip=".$this->db->escape('descrip')));
				if($check == 0){
					$this->db->insert('afgr', $data);
					echo 'Registro Agregado';

					logusu('AFDETA','Registro '.$data['descrip'].' INCLUIDO');
				}else{
					echo "Ya existe un cargo con ese codigo";
				}
			}else{
				echo 'Fallo Agregado!!!';
			}
		}elseif($oper == 'edit'){
			if($id<=0){
				return false;
			}
			$nuevo  = $data[$mcodp];
			unset($data[$mcodp]);
			$this->db->where('id', $id);
			$this->db->update('ahgr', $data);

			logusu('AFDETA','Grupo '.$nuevo.' MODIFICADO');
			echo $nuevo." Modificada";

		}elseif($oper == 'del'){
			if($id<=0){
				return false;
			}
			$this->db->delete('afgr', array('id'=>$id));

			logusu('AFDETA',"Grupo ELIMINADO");
			echo 'Registro Eliminado';
		}
	}


	//******************************************************************
	// Forma de Familia
	//
	function ffamiform(){
		$grid  = new $this->jqdatagrid;
		$editar = 'true';

		$mSQL  = "SELECT id, descrip FROM afgr ORDER BY descrip";
		$grupo = $this->datasis->llenajqselect($mSQL, true );

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'hidden'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));
				$grid->addField('nombre');
		$grid->label('Nombre');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 120,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));

		$grid->addField('grupo');
		$grid->label('Grupo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 70,
			'edittype'      => "'select'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ value: '.$grupo.',  style:"width:70px"}',
			'stype'         => "'text'",
			'formatter'     => "forgrupo"
		));


		$grid->showpager(true);
		$grid->setViewRecords(false);
		$grid->setWidth('490');
		$grid->setHeight('280');
	

		$grid->setUrlget(site_url('afijos/afdeta/ffamiget'));
		$grid->setUrlput(site_url('afijos/afdeta/ffamiset'));

		$mgrid = $grid->deploy();

		$msalida  = '<script type="text/javascript">'."\n";
		$msalida .= '
		$("#newapi'.$mgrid['gridname'].'").jqGrid({
			ajaxGridOptions : {type:"POST"}
			,jsonReader : { root:"data", repeatitems: false }
			'.$mgrid['table'].'
			,scroll: true
			,pgtext: null, pgbuttons: false, rowList:[]
		})
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'navGrid\',  "#pnewapi'.$mgrid['gridname'].'",{edit:false, add:false, del:true, search: false});
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'inlineNav\',"#pnewapi'.$mgrid['gridname'].'");
		$("#newapi'.$mgrid['gridname'].'").jqGrid(\'filterToolbar\');
		';

		$msalida  .= '
		function forgrupo(cv, op, ro ){
			var agrupos = '.$grupo.';
			return agrupos[cv];
		}';

		$msalida .= '</script>';
		$msalida .= '<div class="anexos"><table id="newapi'.$mgrid['gridname'].'"></table>';
		$msalida .= '<div id="pnewapi'.$mgrid['gridname'].'"></div></div>';



		echo $msalida;

	}
			
	//******************************************************************
	// Busca la data en el Servidor por json
	//
	function ffamiget(){
		$grid       = $this->jqdatagrid;
		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('afami');
		$response   = $grid->getData('afami', array(array()), array(), false, $mWHERE );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

			
	//******************************************************************
	// Guarda los cambios
	//
	function ffamiset(){
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = intval($this->input->post('id'));
		$data   = $_POST;
		$mcodp  = 'nombre';
		$check  = 0;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$check = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM afami WHERE nombre=".$this->db->escape($data['nombre'])));
				if($check == 0){
					$this->db->insert('afami', $data);
					echo 'Registro Agregado';

					logusu('AFDETA','Registro '.$data['nombre'].' INCLUIDO');
				}else{
					echo "Ya existe una Familia con ese nombre";
				}
			}else{
				echo 'Fallo Agregado!!!';
			}
		}elseif($oper == 'edit'){
			if($id<=0){
				return false;
			}
			$nuevo  = $data[$mcodp];
			unset($data[$mcodp]);
			$this->db->where('id', $id);
			$this->db->update('afami', $data);

			logusu('AFDETA','Familia '.$nuevo.' MODIFICADO');
			echo $nuevo." Modificada";

		}elseif($oper == 'del'){
			if($id<=0){
				return false;
			}
			//$this->db->where('id', $id);
			$this->db->delete('afami', array('id'=>$id));

			logusu('AFDETA',"Familia ELIMINADO");
			echo 'Registro Eliminado';
		}
	}
			
			


	function instalar(){
		if (!$this->db->table_exists('afdeta')) {
			$mSQL="CREATE TABLE `afdeta` (
			  `codigo`      VARCHAR(20)    DEFAULT NULL COMMENT 'Codigo',
			  `descrip`     VARCHAR(100)   DEFAULT NULL COMMENT 'Descripcion',
			  `fcompra`     DATE           DEFAULT NULL COMMENT 'Fecha de compra',
			  `ncompra`     VARCHAR(20)    DEFAULT NULL COMMENT 'Numero de Compra',
			  `incorpora`   DATE           DEFAULT NULL COMMENT 'fecha de incorportacion',
			  `vidautil`    INT(11)        DEFAULT NULL COMMENT 'vida en meses',
			  `residual`    DECIMAL(14,2)  DEFAULT NULL COMMENT 'valor residual',
			  `costo`       DECIMAL(14,2)  DEFAULT NULL COMMENT 'costo de adquisicion',
			  `depacum`     DECIMAL(14,2)  DEFAULT NULL COMMENT 'depresiacion acumulada',
			  `valorl`      DECIMAL(14,2)  DEFAULT NULL COMMENT 'valor segun libro',
			  `valora`      DECIMAL(14,2)  DEFAULT NULL COMMENT 'valor ajustado x inflacion',
			  `afectacont`  VARCHAR(30)    DEFAULT NULL COMMENT 'Afecta contabilidad: Si y No',
			  `ubica`       VARCHAR(30)    DEFAULT NULL COMMENT 'ubicacion',
			  `ficha`       TEXT                        COMMENT 'ficha tecnica',
			  `cuentaa`     VARCHAR(30)    DEFAULT NULL COMMENT 'cuenta contable del activo',
			  `cuentad`     VARCHAR(30)    DEFAULT NULL COMMENT 'cuenta contable deapresiacion acumulada',
			  `cuentac`     VARCHAR(30)    DEFAULT NULL COMMENT 'cuenta contable deapresiacion',
			  `serial1`     VARCHAR(30)    DEFAULT NULL COMMENT 'serial1',
			  `serial2`     VARCHAR(30)    DEFAULT NULL COMMENT 'serial2',
			  `serial3`     VARCHAR(30)    DEFAULT NULL COMMENT 'serial3',
			  `matricula`   VARCHAR(15)    DEFAULT NULL COMMENT 'placa del vehiculo',
			  `responsable` VARCHAR(50)    DEFAULT NULL COMMENT 'persona responsable',
			  `status`      CHAR(1)        DEFAULT NULL COMMENT 'status  Disponible, Ocupado, Inactivo',
			  `origen`      CHAR(1)        DEFAULT NULL COMMENT 'Origen: Gasto, Produccion, Inventario',
			  `retiro`      DATE           DEFAULT NULL COMMENT 'fecha de retiro',
			  `motivo`      VARCHAR(200)   DEFAULT NULL COMMENT 'Motivo del retiro',
			  `idgr`        INT(11)        DEFAULT NULL COMMENT 'Id Grupo',
			  `idfami`      INT(11)        DEFAULT NULL COMMENT 'Id Familia',
			  `id`          INT(11)        NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('afdeta');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}

?>
