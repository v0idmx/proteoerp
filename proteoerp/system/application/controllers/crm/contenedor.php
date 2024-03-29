<?php require_once(BASEPATH.'application/controllers/validaciones.php');

class Contenedor extends validaciones {

	function Contenedor(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->prefijo='crm_';
		$this->crm_back='';
		//$this->datasis->modulo_id(136,1);
	}

	function index(){
		$this->rapyd->load('datafilter','datagrid');
		$this->rapyd->uri->keep_persistence();

		$filter = new DataFilter('Contenedor', $this->prefijo.'contenedor');

		$filter->descripcion = new inputField('Descripci&oacute;n', 'descripcion');

		$filter->titulo = new inputField('Titulo', 'titulo');

		$filter->buttons('reset','search');
		$filter->build();

		$uri  = anchor('crm/contenedor/dataedit/show/<#id#>','<str_pad><#id#>|8|0|0</str_pad>');
		$curl = anchor('crm/contenedor/comentario/<#id#>/create','Comentario');

		$grid = new DataGrid('Lista de Contenedores');
		$grid->use_function('str_pad');
		//$grid->order_by('caja','asc');
		$grid->per_page = 7;

		$grid->column('N&uacute;mero',$uri);
		$grid->column_orderby('Derivado'   ,'derivado'   ,'derivado');
		$grid->column_orderby('Tipo'       ,'tipo'       ,'tipo');
		$grid->column_orderby('Estatus'    ,'status'     ,'status');
		$grid->column_orderby('Fecha'      ,'<dbdate_to_human><#fecha#></dbdate_to_human>'      ,'fecha');
		$grid->column_orderby('Cierre'     ,'<dbdate_to_human><#cierre#></dbdate_to_human>'     ,'cierre');
		$grid->column_orderby('Titulo'     ,'titulo'     ,'titulo');
		$grid->column_orderby('Cliente'    ,'cliente'    ,'cliente');
		$grid->column_orderby('Proveed'    ,'proveed'    ,'proveed');
		$grid->column_orderby('Resumen'    ,'resumen'    ,'resumen');
		$grid->column_orderby('Condiciones','condiciones','condiciones');
		//$grid->column('Accion',$curl);

		$grid->add('crm/contenedor/dataedit/create');
		$grid->build();

		$data['content'] = $filter->output.$grid->output;
		$data['title']   = '<h1>Contenedor</h1>';
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}

	function dataedit(){
		$this->rapyd->load('dataedit2','datagrid');

		$link1=site_url('crm/contenedor/get/1');
		$link2=site_url('crm/contenedor/get/2');

		$script='
		$(function(){
			$("#definicion").change(function(){
				$.post("'.$link1.'",{ defi:$("#definicion").val() },function(data){$("#status").html(data);});
				$.post("'.$link2.'",{ defi:$("#definicion").val() },function(data){$("#tipo").html(data);  });
			});
		});';

		$sprv=array(
			'tabla'   => $this->prefijo.'contenedor',
			'columnas'=> array('id' =>'C&oacute;digo','descripcion'=>'Descripci&oacute;n'),
			'filtro'  => array('descripcion'=>'Descripci&oacute;n'),
			'retornar'=> array('id'=>'derivado'),
			'titulo'  => 'Buscar Contrato');
		$boton3=$this->datasis->modbus($sprv);

		$sprv=array(
			'tabla'   => 'sprv',
			'columnas'=> array('proveed' =>'C&oacute;digo Proveedor','nombre'=>'Nombre','rif'=>'RIF'),
			'filtro'  => array('proveed'=>'C&oacute;digo Proveedor','nombre'=>'Nombre'),
			'retornar'=> array('proveed'=>'proveedor'),
			'titulo'  => 'Buscar Proveedor');
		$boton2=$this->datasis->modbus($sprv);

		$scli=array(
			'tabla'   =>'scli',
			'columnas'=>array(
			'cliente' =>'C&oacute;digo Cliente',
			'nombre'=>'Nombre',
			'contacto'=>'Contacto'),
			'filtro'  =>array('cliente'=>'C&oacute;digo Cliente','nombre'=>'Nombre'),
			'retornar'=>array('cliente'=>'cliente'),
			'titulo'  =>'Buscar Cliente');
		$boton=$this->datasis->modbus($scli);

		//$do = new DataObject($this->prefijo.'contenedor');
		//$do->pointer($this->prefijo.'definiciones' ,'.cliente=spre.cod_cli','scli.nombre as sclinombre','LEFT');

		$edit = new DataEdit2('Contenedor', $this->prefijo.'contenedor');
		$edit->script($script,'create');
		$edit->script($script,'modify');

		/*$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');*/

		$edit->back_url = site_url('crm/contenedor/index');

		$edit->usuario  = new autoUpdateField('usuario', $this->session->userdata('usuario'), $this->session->userdata('usuario'));

		$edit->titulo =  new inputField('Titulo', 'titulo');
		$edit->titulo->size = 50;
		$edit->titulo->maxlength=200;
		$edit->titulo->rule = 'trim|strtoupper|required||max_length[200]';

		$edit->derivado =  new inputField('Derivado', 'derivado');
		$edit->derivado->size = 15;
		$edit->derivado->maxlength=30;
		$edit->derivado->rule = 'trim|strtoupper';
		$edit->derivado->append($boton3. 'Si es sub-contrato');

		$edit->proveedor =  new inputField('Proveedor', 'proveedor');
		$edit->proveedor->size = 15;
		$edit->proveedor->maxlength=30;
		$edit->proveedor->rule = 'trim|strtoupper';
		$edit->proveedor->append($boton2);

		$edit->cliente =  new inputField('Cliente', 'cliente');
		$edit->cliente->size = 15;
		$edit->cliente->maxlength=30;
		$edit->cliente->rule = 'trim|strtoupper';
		$edit->cliente->append($boton);

		$edit->definicion = new dropdownField('Definici&oacute;n', 'definicion');
		$edit->definicion->rule = 'required';
		$edit->definicion->option('','Seleccionar');
		$edit->definicion->options('SELECT id,CONCAT_WS("-",nombre,estructura) AS val FROM '.$this->prefijo.'definiciones ORDER BY nombre');

		$edit->tipo = new dropdownField('Tipo', 'tipo');
		$edit->tipo->rule = 'required';
		$defi=$edit->getval('definicion');
		if($defi!==FALSE)
			$edit->tipo->options("SELECT definicion AS id, descrip as valor FROM ".$this->prefijo."tipos WHERE definicion=".$this->db->escape($defi)." ORDER BY definicion");
		else
			$edit->tipo->option('','Seleccione una defici&oacute;n primero');

		$edit->status = new dropdownField('Estatus', 'status');
		$edit->status->rule = 'required';
		$edit->status->option('','Seleccione una deficion primero');
		if($defi!==FALSE)
			$edit->status->options("SELECT definicion AS id, descrip as valor FROM ".$this->prefijo."status WHERE definicion=".$this->db->escape($defi)." ORDER BY definicion");
		else
			$edit->status->option('','Seleccione una defici&oacute;n primero');

		$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
		$edit->fecha->rule = 'required';
		$edit->fecha->size = 10;
		$edit->fecha->maxlength=8;
		$edit->fecha->insertValue = date('Y-m-d');

		$edit->cierre = new dateField('Cierre', 'cierre','d/m/Y');
		$edit->cierre->rule = 'required';
		$edit->cierre->size = 10;
		$edit->cierre->maxlength=8;
		$edit->cierre->insertValue = date('Y-m-d');

		$edit->resumen =  new textareaField('Resumen', 'resumen');
		$edit->resumen->cols = 87;
		$edit->resumen->rows = 2;
		$edit->resumen->rule = 'trim|required|max_length[200]';

		$edit->descripcion =  new editorField('Descripci&oacute;n', 'descripcion');
		$edit->descripcion->cols = 90;
		$edit->descripcion->rows = 4;
		$edit->descripcion->rule = 'trim|required';

		$edit->condiciones =  new textareaField('Condiciones', 'condiciones');
		$edit->condiciones->cols = 87;
		$edit->condiciones->rows = 4;
		$edit->condiciones->rule = 'trim|required';

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		if($edit->_status=='show'){
			$id=$edit->_dataobject->get('id');

			$coment = $this->_showComentarios($id);
			$evento = $this->_showEventos($id);
			$partid = $this->_showPartidas($id);
			$montos = $this->_showMontos($id);
			$adjunt = $this->_showAdjuntos($id);
		}else{
			$coment=$evento=$partid=$montos=$adjunt='';
		}

		$data['content'] = $edit->output.$coment.$evento.$partid.$montos.$adjunt;
		$data['title']   = '<h1>Contenedor</h1>';
		$data['head']    = $this->rapyd->get_head().script('jquery.js');
		$this->load->view('view_ventanas', $data);
	}

	function comentario($contenedor){
		$this->rapyd->load('dataedit');
		$edit = new DataEdit('Comentario', $this->prefijo.'comentarios');

		$edit->back_save   = true;
		$edit->back_delete = true;
		$edit->back_cancel = true;
		$edit->back_cancel_save   = true;
		$edit->back_cancel_delete = true;

		if(empty($this->crm_back))
			$edit->back_url = site_url('crm/contenedor/dataedit/modify/'.$contenedor);
		else
			$edit->back_url = $this->crm_back;

		$edit->usuario    = new autoUpdateField('usuario'   , $this->session->userdata('usuario'), $this->session->userdata('usuario'));
		$edit->contenedor = new autoUpdateField('contenedor', $contenedor,$contenedor);

		$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
		$edit->fecha->rule = 'required';
		$edit->fecha->size = 10;
		$edit->fecha->maxlength=8;
		$edit->fecha->insertValue = date('Y-m-d');

		$edit->motivo =  new inputField('Motivo', 'motivo');
		$edit->motivo->rule = 'trim|required|max_length[200]';

		$edit->cuerpo =  new editorField('Cuerpo', 'cuerpo');
		$edit->cuerpo->cols = 90;
		$edit->cuerpo->rows = 4;
		$edit->cuerpo->rule = 'trim|required';

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		$data['content'] = $edit->output;
		$data['title']   = '<h1>Comentarios</h1>';
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}

	function _showPartidas($id,$uurl='crm/contenedor/partidas/',$urladicio=array()){
		if(count($urladicio)>0){
			$adicio='/'.implode('/',$urladicio);
		}else{
			$adicio='';
		}

		$parti= new DataGrid('Partidas asociadas',$this->prefijo.'partidas');
		$parti->db->where('contenedor',$id);
		$parti->per_page = 100;

		$url=anchor(reduce_double_slashes($uurl.'/'.$id.$adicio.'/modify/<#codigo#>'),'<#codigo#>');
		$parti->column('C&oacute;digo' ,$url    );
		$parti->column('Descripci&oacute;n','descripcion');
		$parti->column('Enlace' ,'enlace' );
		$parti->column('Medida' ,'medida' );
		$parti->column('Iva'    ,'<nformat><#iva#></nformat>','align="right"');

		$parti->add(reduce_double_slashes($uurl.$id.$adicio.'/create'),'A&ntilde;adir partidas');
		$parti->build();
		return ($parti->recordCount > 0) ? $parti->output : $parti->_button_container['TR'][0];
	}

	function _showAdjuntos($id,$uurl='crm/contenedor/adjuntos/',$urladicio=array()){
		if(count($urladicio)>0){
			$adicio='/'.implode('/',$urladicio);
		}else{
			$adicio='';
		}

		$adjun= new DataGrid('Archivos Ajuntos',$this->prefijo.'adjuntos');
		$adjun->db->where('contenedor',$id);
		$adjun->per_page = 100;

		$url=anchor(reduce_double_slashes($uurl.'/'.$id.$adicio.'/modify/<#id#>'),'Modificar');
		$adjun->column('Acci&oacute;n'  ,$url  );
		$adjun->column('Fecha'  ,'<dbdate_to_human><#fecha#></dbdate_to_human>');
		$adjun->column('Nombre' ,'nombre'    );
		$adjun->column('Descripci&oacute;n','descripcion');

		$adjun->add(reduce_double_slashes($uurl.'/'.$id.$adicio.'/create'),'A&ntilde;adir adjuntos');
		$adjun->build();
		return ($adjun->recordCount > 0) ? $adjun->output : $adjun->_button_container['TR'][0];
	}

	function _showMontos($id,$uurl='crm/contenedor/montos/',$urladicio=array()){
		if(count($urladicio)>0){
			$adicio='/'.implode('/',$urladicio);
		}else{
			$adicio='';
		}

		$monto= new DataGrid('Montos asociados',$this->prefijo.'montos');
		$monto->db->where('contenedor',$id);
		$monto->per_page = 100;

		$url=anchor(reduce_double_slashes($uurl.'/'.$id.$adicio.'/modify/<#id#>'),'<#id#>');
		$monto->column('N&uacute;mero'  ,$url );
		$monto->column('Partida' ,'partida');
		$monto->column('Fecha'  ,'<dbdate_to_human><#fecha#></dbdate_to_human>');
		$monto->column('Debe'  ,'<nformat><#debe#></nformat>'  ,'align="right"');
		$monto->column('Haber'  ,'<nformat><#haber#></nformat>','align="right"');

		$monto->add(reduce_double_slashes($uurl.'/'.$id.$adicio.'/create'),'A&ntilde;adir montos');
		$monto->build();
		return ($monto->recordCount > 0) ? $monto->output : $monto->_button_container['TR'][0];
	}

	function _showEventos($id,$uurl='crm/contenedor/eventos/',$urladicio=array()){
		if(count($urladicio)>0){
			$adicio='/'.implode('/',$urladicio);
		}else{
			$adicio='';
		}

		$even= new DataGrid('Eventos asociados',$this->prefijo.'eventos');
		$even->db->where('contenedor',$id);
		$even->order_by('fecha','asc');
		$even->per_page = 100;

		$url=anchor(reduce_double_slashes($uurl.'/'.$id.$adicio.'/modify/<#id#>'),'Modificar');
		$even->column('N&uacute;mero'  ,$url);
		$even->column('Fecha'  ,'<dbdate_to_human><#fecha#></dbdate_to_human>');
		$even->column('Vence'  ,'<dbdate_to_human><#vence#></dbdate_to_human>');
		$even->column('Evento' ,'evento'    );

		$even->add(reduce_double_slashes($uurl.'/'.$id.$adicio.'/create'),'A&ntilde;adir eventos');
		$even->build();

		return ($even->recordCount > 0) ? $even->output : $even->_button_container['TR'][0];
	}

	function _showComentarios($id,$uurl='crm/comentario/partidas/',$urladicio=array()){
		if(count($urladicio)>0){
			$adicio='/'.implode('/',$urladicio);
		}else{
			$adicio='';
		}

		$grid = new DataGrid('Comentario',$this->prefijo.'comentarios');
		$grid->db->where('contenedor',$id);
		$grid->order_by('fecha','asc');
		$grid->per_page = 100;

		$url=anchor(reduce_double_slashes($uurl.'/'.$id.$adicio.'/modify/<#id#>'),'Modificar');
		$grid->column('N&uacute;mero'  ,$url );
		$grid->column('Fecha'  ,'<dbdate_to_human><#fecha#></dbdate_to_human>'      );
		$grid->column('Motivo' ,'motivo'    );
		$grid->column('Cuerpo' ,'<html_entity_decode><#cuerpo#></html_entity_decode>');

		$grid->add(reduce_double_slashes($uurl.'/'.$id.$adicio.'/create'),'A&ntilde;adir comentarios');
		$grid->build();

		//$coment=$grid->output;
		return ($grid->recordCount > 0) ? $grid->output : $grid->_button_container['TR'][0];
	}

	function eventos($contenedor){
		$this->rapyd->load('dataedit');
		$edit = new DataEdit('Eventos', $this->prefijo.'eventos');

		$edit->back_save   = true;
		$edit->back_delete = true;
		$edit->back_cancel = true;
		$edit->back_cancel_save   = true;
		$edit->back_cancel_delete = true;

		if(empty($this->crm_back))
			$edit->back_url = site_url('crm/contenedor/dataedit/show/'.$contenedor);
		else
			$edit->back_url = $this->crm_back;

		$edit->usuario    = new autoUpdateField('usuario'   , $this->session->userdata('usuario'), $this->session->userdata('usuario'));
		$edit->contenedor = new autoUpdateField('contenedor', $contenedor,$contenedor);

		$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
		$edit->fecha->rule = 'required';
		$edit->fecha->size = 10;
		$edit->fecha->maxlength=8;
		$edit->fecha->insertValue = date('Y-m-d');

		$edit->vence = new dateField('Vence', 'vence','d/m/Y');
		$edit->vence->rule = 'required';
		$edit->vence->size = 10;
		$edit->vence->maxlength=8;
		$edit->vence->insertValue = date('Y-m-d');

		$edit->evento =  new inputField('Evento', 'evento');
		$edit->evento->rule = 'trim|required|max_length[200]';

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		$data['content'] = $edit->output;
		$data['title']   = '<h1>Eventos de contratos</h1>';
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}


	function partidas($contenedor){
		$this->rapyd->load('dataedit');
		$script='$(function() { $(".inputnum").numeric("."); });';

		$mgas=array(
			'tabla'   => 'mgas',
			'columnas'=> array('codigo' =>'C&oacute;digo','descrip'=>'Descripci&oacute;n','tipo'=>'Tipo'),
			'filtro'  => array('descrip'=>'Descripci&oacute;n'),
			'retornar'=> array('codigo'=>'enlace'),
			'titulo'  => 'Buscar enlace administrativo');
		$boton=$this->datasis->modbus($mgas);

		$edit = new DataEdit('Partidas', $this->prefijo.'partidas');

		$edit->back_save   = true;
		$edit->back_delete = true;
		$edit->back_cancel = true;
		$edit->back_cancel_save   = true;
		$edit->back_cancel_delete = true;

		if(empty($this->crm_back))
			$edit->back_url = site_url('crm/contenedor/dataedit/show/'.$contenedor);
		else
			$edit->back_url = $this->crm_back;

		$edit->codigo =  new inputField('C&oacute;digo', 'codigo');
		$edit->codigo->rule = 'trim|required|max_length[15]';
		$edit->codigo->size =16;

		$edit->descripcion =  new inputField('Descripci&oacute;n', 'descripcion');
		$edit->descripcion->rule = 'trim|required|max_length[100]';

		$edit->enlace =  new inputField('Enlace Administrativo', 'enlace');
		$edit->enlace->rule = 'trim|required|max_length[6]';
		$edit->enlace->size = 7;
		$edit->enlace->append($boton);

		$edit->medida =  new inputField('Unidad de medida', 'medida');
		$edit->medida->rule = 'trim|required|max_length[5]';
		$edit->medida->size = 6;
		$edit->medida->max_size = 5;

		/*$edit->iva =  new inputField('Iva', 'iva');
		$edit->iva->css_class='inputnum';
		$edit->iva->rule = 'required|numeric';
		$edit->iva->size = 6;*/

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		$data['content'] = $edit->output;
		$data['title']   = '<h1>Partidas de contratos</h1>';
		$data['head']    = $this->rapyd->get_head().script('jquery.js').script('plugins/jquery.numeric.pack.js');
		$this->load->view('view_ventanas', $data);
	}


	function montos($contenedor){
		$this->rapyd->load('dataedit');
		$mSQL='SELECT COUNT(*) FROM '.$this->prefijo.'partidas ';
		$parti=$this->datasis->dameval($mSQL);

		if($parti>0){

			$script='$(function() { $(".inputnum").numeric("."); });';

			$edit = new DataEdit('Montos', $this->prefijo.'montos');

			if(empty($this->crm_back))
				$edit->back_url = site_url('crm/contenedor/dataedit/show/'.$contenedor);
			else
				$edit->back_url = $this->crm_back;

			$edit->script($script, 'create');
			$edit->script($script, 'modify');

			$edit->usuario    = new autoUpdateField('usuario'   , $this->session->userdata('usuario'), $this->session->userdata('usuario'));
			//$edit->contenedor = new autoUpdateField('contenedor', $contenedor,$contenedor);

			$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
			$edit->fecha->rule = 'required';
			$edit->fecha->size = 10;
			$edit->fecha->maxlength=8;
			$edit->fecha->insertValue = date('Y-m-d');

			$edit->partida = new dropdownField('Partida', 'partida');
			$edit->partida->rule = 'required';
			$edit->partida->option('','Seleccionar');
			$edit->partida->options('SELECT codigo,descripcion FROM '.$this->prefijo.'partidas ');

			$edit->descripcion =  new inputField('Descripci&oacute;n', 'descripcion');
			$edit->descripcion->rule = 'trim|required|max_length[200]';

			$edit->debe =  new inputField('Debe', 'debe');
			$edit->debe->css_class='inputnum';
			$edit->debe->rule = 'required|numeric';
			$edit->debe->size=10;

			$edit->haber =  new inputField('Haber', 'haber');
			$edit->haber->css_class='inputnum';
			$edit->haber->rule = 'required|numeric';
			$edit->haber->size=10;

			$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
			$edit->build();

			$sal=$edit->output;
		}else{
			$sal='Debe ingresar primero algunas partidas antes de asignar los montos '.anchor('crm/contenedor/dataedit/show/'.$contenedor,'Regresar');
		}

		$data['content'] = $sal;
		$data['title']   = '<h1>Montos de contratos</h1>';
		$data['head']    = $this->rapyd->get_head().script('jquery.js').script('plugins/jquery.numeric.pack.js');
		$this->load->view('view_ventanas', $data);
	}


	function adjuntos($contenedor){
		$this->load->library('path');
		$path=new Path();
		$path->setPath($this->config->item('uploads_dir'));
		$path->append('/crm');
		$upload_path =$path->getPath().'/';

		$this->rapyd->load('dataedit');
		$edit = new DataEdit('Adjuntos', $this->prefijo.'adjuntos');

		$edit->back_save   = true;
		$edit->back_delete = true;
		$edit->back_cancel = true;
		$edit->back_cancel_save   = true;
		$edit->back_cancel_delete = true;

		if(empty($this->crm_back))
			$edit->back_url = site_url('crm/contenedor/dataedit/show/'.$contenedor);
		else
			$edit->back_url = $this->crm_back;

		$edit->usuario    = new autoUpdateField('usuario'   , $this->session->userdata('usuario'), $this->session->userdata('usuario'));
		$edit->contenedor = new autoUpdateField('contenedor', $contenedor,$contenedor);

		$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
		$edit->fecha->rule = 'required';
		$edit->fecha->size = 10;
		$edit->fecha->maxlength=8;
		$edit->fecha->insertValue = date('Y-m-d');

		$edit->descripcion =  new inputField('Descripci&oacute;n', 'descripcion');
		$edit->descripcion->rule = 'trim|required|max_length[200]';

		$edit->nombre  = new uploadField("Archivo", "nombre");
		$edit->nombre->upload_path = $upload_path;
		$edit->nombre->rule = 'required';
		$edit->nombre->allowed_types = "pdf|doc|xls|txt|xlsx";
		$edit->nombre->append('Formatos permitidos: pdf,doc,xls y txt');
		//$edit->img->thumb = array (63,91);

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		$data['content'] = $edit->output;
		$data['title']   = '<h1>Archivos adjuntos</h1>';
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}


	function imagenes($contenedor){
		$this->load->library('path');
		$path=new Path();
		$path->setPath($this->config->item('uploads_dir'));
		$path->append('/crm');
		$upload_path =$path->getPath().'/';

		$this->rapyd->load('dataedit');
		$edit = new DataEdit('Imagenes', $this->prefijo.'adjuntos');

		$edit->back_save   = true;
		$edit->back_delete = true;
		$edit->back_cancel = true;
		$edit->back_cancel_save   = true;
		$edit->back_cancel_delete = true;

		if(empty($this->crm_back))
			$edit->back_url = site_url('crm/contenedor/dataedit/show/'.$contenedor);
		else
			$edit->back_url = $this->crm_back;

		$edit->usuario    = new autoUpdateField('usuario'   , $this->session->userdata('usuario'), $this->session->userdata('usuario'));
		$edit->contenedor = new autoUpdateField('contenedor', $contenedor,$contenedor);

		$edit->fecha = new dateField('Fecha', 'fecha','d/m/Y');
		$edit->fecha->rule = 'required';
		$edit->fecha->size = 10;
		$edit->fecha->maxlength=8;
		$edit->fecha->insertValue = date('Y-m-d');

		$edit->descripcion =  new inputField('Descripci&oacute;n', 'descripcion');
		$edit->descripcion->rule = 'trim|required|max_length[200]';

		$edit->nombre  = new uploadField('Archivo', 'nombre');
		$edit->nombre->upload_path = $upload_path;
		$edit->nombre->rule = 'required';
		$edit->nombre->allowed_types = 'jpg';
		//$edit->img->thumb = array (63,91);

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back');
		$edit->build();

		$data['content'] = $edit->output;
		$data['title']   = '<h1>Imagenes</h1>';
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}


	function get($que=null){
		if(!empty($que)){
			$tabla=($que=='1') ? $this->prefijo.'status': $this->prefijo.'tipos';
			$defi=$this->db->escape($this->input->post('defi'));
			$mSQL=$this->db->query("SELECT definicion AS id, descrip as valor FROM ${tabla} WHERE definicion=${defi} ORDER BY definicion");
			echo "<option value=''>Seleccionar</option>";
			if($mSQL){
				foreach($mSQL->result() AS $fila )
					echo "<option value='".$fila->id."'>".$fila->valor."</option>";
			}
		}
	}

	function instalar(){
		$prefijo=$this->prefijo;

		if (!$this->db->table_exists("${prefijo}comentarios")) {
			$mSQL="CREATE TABLE `${prefijo}comentarios` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`contenedor` int(11) NOT NULL DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`motivo` varchar(200) DEFAULT NULL,
			`cuerpo` text,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}definiciones")) {
			$mSQL="CREATE TABLE `${prefijo}definiciones` (
			`id` int(7) NOT NULL AUTO_INCREMENT,
			`nombre` varchar(50) DEFAULT '0',
			`estructura` text,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}


		if (!$this->db->table_exists("${prefijo}contenedor")) {
			$mSQL="CREATE TABLE `${prefijo}contenedor` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`derivado` int(11) DEFAULT '0',
			`definicion` int(7) DEFAULT '0',
			`tipo` int(7) DEFAULT '0',
			`status` int(7) DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`cierre` date DEFAULT NULL,
			`resumen` varchar(200) DEFAULT NULL,
			`titulo` varchar(200) DEFAULT NULL,
			`cliente` varchar(5) DEFAULT NULL,
			`proveed` varchar(5) DEFAULT NULL,
			`descripcion` text,
			`condiciones` text,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set." COMMENT='contenedor'";
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}eventos")) {
			$mSQL="CREATE TABLE `${prefijo}eventos` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`contenedor` int(11) NOT NULL DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`evento` varchar(200) DEFAULT NULL,
			`vence` date DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}imagenes")) {
			$mSQL="CREATE TABLE `${prefijo}imagenes` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`contenedor` int(11) NOT NULL DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`nombre` varchar(200) DEFAULT NULL,
			`descripcion` text,
			`url` varchar(200) DEFAULT NULL,
			`imagen` blob,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}status")) {
			$mSQL="CREATE TABLE `${prefijo}status` (
			`id` int(7) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`definicion` int(7) DEFAULT '0',
			`descrip` varchar(50) DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}tipos")) {
			$mSQL="CREATE TABLE `${prefijo}tipos` (
			`id` int(7) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`definicion` int(7) DEFAULT '0',
			`descrip` varchar(50) DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}adjuntos")) {
			$mSQL="CREATE TABLE `${prefijo}adjuntos` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`contenedor` int(11) NOT NULL DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`nombre` varchar(200) DEFAULT NULL,
			`descripcion` text,
			`url` varchar(200) DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}montos")) {
			$mSQL="CREATE TABLE `${prefijo}montos` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`usuario` varchar(50) DEFAULT NULL,
			`contenedor` int(11) NOT NULL DEFAULT '0',
			`fecha` date DEFAULT NULL,
			`partida` varchar(15) DEFAULT NULL,
			`descripcion` varchar(200) DEFAULT NULL,
			`debe` decimal(19,0) DEFAULT '0',
			`haber` decimal(19,0) DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

		if (!$this->db->table_exists("${prefijo}partidas")) {
			$mSQL="CREATE TABLE `${prefijo}partidas` (
			`codigo` varchar(15) NOT NULL DEFAULT '',
			`contenedor` int(7) NOT NULL,
			`descripcion` varchar(100) DEFAULT NULL,
			`enlace` varchar(6) DEFAULT NULL,
			`iva` decimal(5,2) DEFAULT NULL,
			`medida` varchar(5) DEFAULT NULL,
			`dacumu` varchar(5) DEFAULT NULL,
			PRIMARY KEY (`codigo`)
			) ENGINE=MyISAM DEFAULT CHARSET=".$this->db->char_set;
			$this->db->simple_query($mSQL);
		}

	}
}
