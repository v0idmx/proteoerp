<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Botr extends Controller {
	var $mModulo = 'BOTR';
	var $titp    = 'Otros Concepto de Contabilidad';
	var $tits    = 'Otros Concepto de Contabilidad';
	var $url     = 'finanzas/botr/';

	function Botr(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->datasis->modulo_nombre( 'BOTR', $ventana=0, $this->titp  );
	}

	function index(){
		$this->instalar();
		//$this->datasis->creaintramenu(array('modulo'=>'000','titulo'=>'<#titulo#>','mensaje'=>'<#mensaje#>','panel'=>'<#panal#>','ejecutar'=>'<#ejecuta#>','target'=>'popu','visible'=>'S','pertenece'=>'<#pertenece#>','ancho'=>900,'alto'=>600));
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
		//$grid->wbotonadd(array("id"=>"funcion",   "img"=>"images/engrana.png",  "alt" => "Formato PDF", "label"=>"Ejemplo"));
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
		$param['listados']    = $this->datasis->listados('BOTR', 'JQ');
		$param['otros']       = $this->datasis->otros('BOTR', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('botr', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'botr', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'botr', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('botr', $ngrid, $this->url );

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

		$grid->addField('codigo');
		$grid->label('C&oacute;digo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 50,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:5, maxlength: 5 }',
		));


		$grid->addField('nombre');
		$grid->label('Nombre');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('cuenta');
		$grid->label('Cuenta');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 150,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:15, maxlength: 15 }',
		));


		/*$grid->addField('precio');
		$grid->label('Precio');
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


		$grid->addField('iva');
		$grid->label('Iva');
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


		$grid->addField('tipo');
		$grid->label('Tipo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));


		/*$grid->addField('intocable');
		$grid->label('Intocable');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));*/


		$grid->addField('clase');
		$grid->label('Clase');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));


		/*$grid->addField('usacant');
		$grid->label('Usacant');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
		));*/


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
		$grid->setAdd(    $this->datasis->sidapuede('BOTR','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('BOTR','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('BOTR','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('BOTR','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions('addfunc: botradd, editfunc: botredit, delfunc: botrdel, viewfunc: botrshow');

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
		$mWHERE = $grid->geneTopWhere('botr');

		$response   = $grid->getData('botr', array(array()), array(), false, $mWHERE );
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
		$check  = 0;

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			// Busca si esta Repetido
			if($this->datasis->dameval("SELECT COUNT(*) FROM botr WHERE codigo=".$this->db->escape($data['codigo'])) > 0){
				echo " Codigo Repetido!!!";
			}else{
				if(false == empty($data)){
					$this->db->insert('botr', $data);
					echo " Registro Agregado";
				}
			}

		}elseif($oper == 'edit'){
			$codigo = $this->datasis->dameval("SELECT codigo FROM botr WHERE id=${id}");
			unset($data['codigo']);   // No cambia el Codigo
			$this->db->where('id', $id);
			$this->db->update('botr', $data);
			echo " Registro Modificado";

		}elseif($oper == 'del'){
			$codigo   = $this->datasis->dameval("SELECT codigo FROM botr WHERE id=${id}");
			$dbcodigo = $this->db->escape($codigo);

			$check  = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM bmov   WHERE clipro='O' AND codcp=${dbcodigo}"));
			$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM itotin WHERE codigo=${dbcodigo}"));
			$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM smov   WHERE codigo=${dbcodigo}"));
			$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM sprm   WHERE codigo=${dbcodigo}"));
			if($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento $codigo";
			}else{
				$this->db->simple_query("DELETE FROM botr WHERE id=${id}");
				logusu('botr',"Registro ${codigo} ELIMINADO");
				echo " Registro Eliminado";
			}
		}
	}

	//******************************************************************
	// Edicion

	function dataedit(){
		$this->rapyd->load('dataedit');
		$script= '
		$(function() {
			$("#fecha").datepicker({dateFormat:"dd/mm/yy"});
			$(".inputnum").numeric(".");
		});';

		$qformato=$this->qformato=$this->datasis->formato_cpla();

		$mCPLA=array(
			'tabla'   =>'cpla',
			'columnas'=>array(
				'codigo' =>'C&oacute;digo',
				'descrip'=>'Descripci&oacute;n'),
			'filtro'  =>array('codigo'=>'C&oacute;digo','descrip'=>'Descripci&oacute;n'),
			'retornar'=>array('codigo'=>'cuenta'),
			'titulo'  =>'Buscar Cuenta',
			'where'=>"codigo LIKE \"${qformato}\"",
			);

		$bcpla = $this->datasis->modbus($mCPLA);

		$edit = new DataEdit('', 'botr');

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

		$edit->codigo = new inputField('C&oacute;digo','codigo');
		$edit->codigo->rule='';
		$edit->codigo->size =7;
		$edit->codigo->maxlength =5;
		$edit->codigo->mode = 'autohide';

		$edit->nombre = new inputField('Nombre','nombre');
		$edit->nombre->rule='';
		$edit->nombre->size =32;
		$edit->nombre->maxlength =30;

		$edit->tipo = new dropdownField('Tipo','tipo');
		$edit->tipo->options(array('C'=>'Cliente', 'P'=>'Proveedor', 'O'=>'Otro' ));

		$edit->clase = new dropdownField('Clase','clase');
		$edit->clase->options(array('E'=>'Entrada', 'S'=>'Salida', 'N'=>'Ninguno' ));

		$edit->cuenta = new inputField('Cuenta','cuenta');
		$edit->cuenta->rule='trim|existecpla';
		$edit->cuenta->size =15;
		$edit->cuenta->maxlength =15;
		$edit->cuenta->append($bcpla);

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
		$do->error_message_ar['pre_ins']='';
		return true;
	}

	function _pre_update($do){
		$do->error_message_ar['pre_upd']='';
		return true;
	}

	function _pre_delete($do){
		$codigo   = $do->get('codigo');
		$dbcodigo = $this->db->escape($codigo);

		$check  = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM bmov   WHERE clipro='O' AND codcp=${dbcodigo}"));
		$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM itotin WHERE codigo=${dbcodigo}"));
		$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM smov   WHERE codigo=${dbcodigo}"));
		$check += intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM sprm   WHERE codigo=${dbcodigo}"));
		if($check>0){
			$do->error_message_ar['pre_del']='No se puede eliminar el registro porque esta relacionado con movimientos';
			return false;
		}

		$ldbcodigo = $this->db->escape('%'.$codigo.'%');
		$check  = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM reglascont WHERE origen LIKE ${ldbcodigo}"));
		if($check>0){
			$do->error_message_ar['pre_del']='El concepto esta asociado a una regla contable, no se puede eliminar';
			return false;
		}

		return true;
	}

	function _post_insert($do){
		$codigo   = $do->get('codigo');
		$primary =implode(',',$do->pk);
		logusu($do->table,"Creo $this->tits ${primary} ${codigo}");
	}

	function _post_update($do){
		$codigo   = $do->get('codigo');
		$primary =implode(',',$do->pk);
		logusu($do->table,"Modifico $this->tits  ${primary} ${codigo}");
	}

	function _post_delete($do){
		$primary =implode(',',$do->pk);
		logusu($do->table,"Elimino $this->tits  ${primary} ${codigo}");
	}

	function instalar(){
		if(!$this->db->table_exists('botr')){
			$mSQL="CREATE TABLE `botr` (
			  `codigo` varchar(5) NOT NULL DEFAULT '',
			  `nombre` varchar(30) DEFAULT NULL,
			  `cuenta` varchar(15) DEFAULT NULL,
			  `precio` decimal(17,2) DEFAULT NULL,
			  `iva` decimal(6,2) DEFAULT NULL,
			  `tipo` char(1) DEFAULT NULL,
			  `intocable` char(1) NOT NULL DEFAULT 'N',
			  `clase` char(1) DEFAULT NULL,
			  `usacant` char(1) DEFAULT NULL,
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `codigo` (`codigo`)
			) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1";
			$this->db->query($mSQL);
		}

		$campos=$this->db->list_fields('botr');
		if(!in_array('id',$campos)){
			$this->db->simple_query('ALTER TABLE botr DROP PRIMARY KEY');
			$this->db->simple_query('ALTER TABLE botr ADD UNIQUE INDEX codigo (codigo)');
			$this->db->simple_query('ALTER TABLE botr ADD COLUMN id INT(11) NULL AUTO_INCREMENT, ADD PRIMARY KEY (id)');
		};
		$this->datasis->modintramenu( 750, 470, 'finanzas/botr' );
		//$this->datasis->creaintramenu(array('modulo'=>'000','titulo'=>'<#titulo#>','mensaje'=>'<#mensaje#>','panel'=>'<#panal#>','ejecutar'=>'<#ejecuta#>','target'=>'popu','visible'=>'S','pertenece'=>'<#pertenece#>','ancho'=>900,'alto'=>600));
	}
}
