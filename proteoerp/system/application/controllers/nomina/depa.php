<?php
/**
* ProteoERP
*
* @autor    Andres Hocevar
* @license  GNU GPL v3
*/
class Depa extends Controller {
	var $mModulo='DEPA';
	var $titp='Departamento de Nomina';
	var $tits='Departamento de Nomina';
	var $url ='nomina/depa/';

	function Depa(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		//$this->datasis->modulo_nombre( 'DEPA', $ventana=0, $this->titp  );
	}

	function index(){
		if ( !$this->datasis->iscampo('depa','id') ) {
			$this->db->query('ALTER TABLE depa DROP PRIMARY KEY');
			$this->db->query('ALTER TABLE depa ADD COLUMN id INT(11) NULL AUTO_INCREMENT, ADD PRIMARY KEY (id) ');
			$this->db->query('ALTER TABLE depa ADD UNIQUE INDEX dividepa (division, departa)');
		};
		$this->datasis->modintramenu( 620, 505, substr($this->url,0,-1) );
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
		$param['listados']    = $this->datasis->listados('DEPA', 'JQ');
		$param['otros']       = $this->datasis->otros('DEPA', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('depa', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'depa', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'depa', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('depa', $ngrid, $this->url );

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


		$grid->addField('departa');
		$grid->label('Codigo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));


		$grid->addField('depadesc');
		$grid->label('Departamento');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));

		$grid->addField('division');
		$grid->label('Division');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:8, maxlength: 8 }',
		));


		$grid->addField('descrip');
		$grid->label('Nombre de la Division');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 30 }',
		));


		$grid->addField('enlace');
		$grid->label('Enlace');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:3, maxlength: 3 }',
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
		$grid->setAdd(    $this->datasis->sidapuede('DEPA','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('DEPA','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('DEPA','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('DEPA','BUSQUEDA%'));
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: depaadd, editfunc: depaedit, delfunc: depadel, viewfunc: depashow");

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
		$mWHERE = $grid->geneTopWhere('depa');

		$response   = $grid->getData('depa', array(array()), array(), false, $mWHERE );
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
				$check = $this->datasis->dameval("SELECT count(*) FROM depa WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('depa', $data);
					echo "Registro Agregado";

					logusu('DEPA',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM depa WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM depa WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE depa SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("depa", $data);
				logusu('DEPA',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('depa', $data);
				logusu('DEPA',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM depa WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM depa WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM depa WHERE id=$id ");
				logusu('DEPA',"Registro ????? ELIMINADO");
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

		$edit = new DataEdit('', 'depa');

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

		$edit->departa = new inputField('Departamento','departa');
		$edit->departa->rule='';
		$edit->departa->size =10;
		$edit->departa->maxlength =8;

		$edit->depadesc = new inputField('Descripcion','depadesc');
		$edit->depadesc->rule='';
		$edit->depadesc->size =32;
		$edit->depadesc->maxlength =30;

		$edit->division = new dropDownField('Division','division');
		$edit->division->rule='';
		$edit->division->options("SELECT division, CONCAT(division, ' ', descrip) descrip FROM divi ORDER BY division ");
		$edit->division->style='width:160px;';

		$edit->descrip = new hiddenField('','descrip');
		//$edit->descrip->rule='hidden';
		$edit->descrip->size =32;
		$edit->descrip->maxlength =30;

		$edit->enlace = new dropDownField('Enlace','enlace');
		$edit->enlace->rule='';
		$edit->enlace->options("SELECT depto, CONCAT( depto, ' ', descrip) nombre FROM dpto WHERE tipo='G' ORDER BY depto ");
		$edit->enlace->style='width:160px;';

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
		$dbdivi = $this->db->escape($do->get('division'));
		$desc   = $this->datasis->dameval('SELECT descrip FROM divi WHERE division='.$dbdivi);
		$do->set('descrip',$desc);
		$do->error_message_ar['pre_ins']='';
		return true;
	}

	function _pre_update($do){
		$dbdivi = $this->db->escape($do->get('division'));
		$desc   = $this->datasis->dameval('SELECT descrip FROM divi WHERE division='.$dbdivi);
		$do->set('descrip',$desc);
		$do->error_message_ar['pre_upd']='';
		return true;
	}

	function _pre_delete($do){
		$id      = $do->get('id');
		$departa = $this->datasis->dameval("SELECT departa FROM depa WHERE id=$id");
		$check   = $this->datasis->dameval("SELECT COUNT(*) FROM pers WHERE depto='$departa'");
		if ($check > 0){
			$msj = "El registro no puede ser eliminado; tiene movimiento ";
			$borra = false;
		} else {
			$this->db->simple_query("DELETE FROM depa WHERE id=$id ");
			logusu('DEPA',"Registro ".$departa." ELIMINADO");
			$msj = "Registro Eliminado";
			$borra = true;
		}
		$do->error_message_ar['pre_del']=$msj;
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
		if (!$this->db->table_exists('depa')) {
			$mSQL="CREATE TABLE `depa` (
			  `division` char(8) NOT NULL DEFAULT '',
			  `descrip` char(30) DEFAULT NULL,
			  `departa` char(8) NOT NULL DEFAULT '',
			  `depadesc` char(30) DEFAULT NULL,
			  `enlace` char(3) DEFAULT NULL,
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `dividepa` (`division`,`departa`)
			) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";
			$this->db->query($mSQL);
		}
		//$campos=$this->db->list_fields('depa');
		//if(!in_array('<#campo#>',$campos)){ }
	}
}

?>
