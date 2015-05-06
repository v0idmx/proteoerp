<?php
class Edinmue extends Controller {
	var $mModulo = 'EDINMUE';
	var $titp    = 'INMUEBLES';
	var $tits    = 'INMUEBLES';
	var $url     = 'construccion/edinmue/';

	function Edinmue(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		$this->instalar();
		$this->datasis->modulo_nombre( 'EDINMUE', $ventana=0 );
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
		//$grid->wbotonadd(array("id"=>"galicuota", "img"=>"images/pdf_logo.gif",  "alt" => "Alicuotas", "label"=>"Alicuotas"));

		$cabeza  = "<td style='vertical-align:top;'><div class='botones'><a style='width:94px;text-align:left;vertical-align:top;' href='#'";
		$cabeza1 = "<td style='vertical-align:top;'><div class='botones'><a style='text-align:left;vertical-align:top;' href='#'";

		$cola   = "</a></div></td>";
		$sgrupo = $this->datasis->llenaopciones('SELECT id, CONCAT(descrip," ",id) descrip FROM edgrupo ORDER BY descrip', true, 'grupoctual' );


		$WpAdic = "
		<tr><td><div class=\"anexos\">
			<table cellpadding='0' cellspacing='0'>
				<tr>
					 <td style='vertical-align:top;' colspan='2'><div class='botones'><a style='width:100%;text-align:left;vertical-align:top;' href='#' id='galicuota'>Alicuotas</a></div></td>
				</tr>

				";

		$WpAdic .= "
				<tr>
					<td colspan='2'>
						<table style='border-collapse:collapse;padding:0px;width:99%;border:1px solid #AFAFAF;'><tr>
							<td style='vertical-align:top;'><a id='vergrupo'>".img(array('src' =>"images/kardex.jpg", 'height'=>30, 'alt'=>'Ver de Grupos', 'title'=> 'Ver Grupos', 'border'=>'0'))."</a></td>
							${cabeza1} id='grupos'>Grupos</a></div></td>
							<td style='vertical-align:center;'><a id='sumagrupo' >".img(array('src' =>"images/agrega4.png",     'height'=> 25, 'alt'=>'Asignacion de Grupo',           'title'=>'Agregar inmueble al grupo',     'border'=>'0'))."</a></td>
							<td style='vertical-align:center;'><a id='restagrupo'>".img(array('src' =>"images/elimina4.png",    'height'=> 25, 'alt'=>'Elimina el inmueble del grupo', 'title'=>'Elimina el inmueble del grupo', 'border'=>'0'))."</a></td>
							<td style='vertical-align:center;'><a id='todogrupo' >".img(array('src' =>"images/agregatodo4.png", 'height'=> 25, 'alt'=>'Agrega todo lo seleccionado',   'title'=>'Agrega todo lo seleccionado',   'border'=>'0'))."</a></td>
						</tr>
							<td colspan='5'>Grupos: ${sgrupo} </td>
						</tr>
						</table>
					</td>
				</tr>
				";

		$WpAdic .= "
			</table>
			</div>
		</td></tr>\n
		";

		$grid->setWpAdicional($WpAdic);

		$WestPanel = $grid->deploywestp();

		$adic = array(
			array('id'=>'fedita',  'title'=>'Agregar/Editar Registro'),
			array('id'=>'fshow' ,  'title'=>'Mostrar Registro'),
			array('id'=>'falicu',  'title'=>'Agregar/Editar Alicuota'),
			array('id'=>'fborra',  'title'=>'Eliminar Registro'),
			array('id'=>'fciud'  , 'title'=>'Gestionar grupos'      )
		);
		$SouthPanel = $grid->SouthPanel($this->datasis->traevalor('TITULO1'), $adic);

		$param['WestPanel']   = $WestPanel;
		//$param['EastPanel'] = $EastPanel;
		$param['SouthPanel']  = $SouthPanel;
		$param['listados']    = $this->datasis->listados('EDINMUE', 'JQ');
		$param['otros']       = $this->datasis->otros('EDINMUE', 'JQ');
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

		$bodyscript .= $this->jqdatagrid->bsshow('edinmue', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsadd( 'edinmue', $this->url );
		$bodyscript .= $this->jqdatagrid->bsdel( 'edinmue', $ngrid, $this->url );
		$bodyscript .= $this->jqdatagrid->bsedit('edinmue', $ngrid, $this->url );

		//Wraper de javascript
		$bodyscript .= $this->jqdatagrid->bswrapper($ngrid);

		$bodyscript .= $this->jqdatagrid->bsfedita( $ngrid, '450', '570' );
		$bodyscript .= $this->jqdatagrid->bsfshow( '300', '400' );
		$bodyscript .= $this->jqdatagrid->bsfborra( $ngrid, '300', '400' );

		$bodyscript .= '});';

		$bodyscript .= '
		jQuery("#galicuota").click( function(){
			var id = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
			if (id)	{
				$.post("'.site_url('construccion/edalicuota/dataefla/create').'/"+id, function(data){
					$("#falicu").html(data);
					$("#falicu").dialog( "open" );
				})
			} else { $.prompt("<h1>Por favor Seleccione un Inmueble</h1>");}
		});';

////////////////////////////////////////////////////////////////////////
		// Rutas
		$bodyscript .= '
		$("#grupos").click(function(){
			$.post("'.site_url($this->url.'gruposform').'",
			function(data){
				$("#fciud").html(data);
				$("#fciud").dialog({height: 450, width: 510, title: "Grupos"});
				$("#fciud").dialog( "open" );
			});
		});';

		// Ver Grupos
		$bodyscript .= '
		$("#vergrupo").click(function(){
			var grupo = $("#grupoctual").val();
			if ( grupo == "-"){
				$.prompt("<h1>Por favor Seleccione un grupo para ver</h1>");
				return false;
			}
			$.post("'.site_url($this->url.'vergrupo').'/"+grupo,
			function(data){
				$("#fciud").html(data);
				$("#fciud").dialog({height: 470, width: 520, title: "Inmuebles en Grupos"});
				$("#fciud").dialog( "open" );
			});
		});';

		$dias = $this->datasis->llenadias();

		// Suma Grupos
		$bodyscript .= '
		$("#sumagrupo").click(function(){
			var id   = $("'.$ngrid.'").jqGrid(\'getGridParam\',\'selrow\');
			var grupo = $("#grupoctual").val();
			if ( grupo == "-"){
				$.prompt("<h1>Por favor Seleccione un grupo</h1>");
				return false;
			}
			if(id){
				$.prompt("<b>Agregar inmueble al grupo </b> ",{
					buttons: { Aceptar: 1, Salir: 0},
					submit: function(e,v,m,f){
						if ( v == 1 ){
							$.post("'.site_url($this->url.'gruposuma').'/"+id+"/"+grupo,
							function(data){

							});
						}
					}
				});
			} else {
				$.prompt("<h1>Por favor Seleccione un Inmueble</h1>");
			}
		});';

		// Suma todos a la Rutas
		$bodyscript .= '
		$("#todogrupo").click(function(){
			var id   = $("'.$ngrid.'").jqGrid(\'getGridParam\',\'selrow\');
			var grupo = $("#grupoctual").val();
			if ( grupo == "-"){
				$.prompt("<h1>Por favor Seleccione un Grupo</h1>");
			} else {
				$.post("'.site_url($this->url.'grupotodo').'/"+grupo,
				function(data){
					$("#fciud").html(data);
					$("#fciud").dialog({height: 450, width: 610, title: "Grupos"});
					$("#fciud").dialog( "open" );
				});
			}
		});';

		// Resta Rutas
		$bodyscript .= '
		$("#restagrupo").click(function(){
			var id   = $("'.$ngrid.'").jqGrid(\'getGridParam\',\'selrow\');
			var grupo = $("#grupoctual").val();
			if ( grupo == "-"){
				$.prompt("<h1>Por favor Seleccione un grupo</h1>");
				return false;
			}
			if(id){
				$.post("'.site_url($this->url.'gruporesta').'/"+id+"/"+grupo,
				function(data){
					//$("#fciud").html(data);
					//$("#fciud").dialog({height: 450, width: 610, title: "Rutas"});
					//$("#fciud").dialog( "open" );
				});
			} else {
				$.prompt("<h1>Por favor Seleccione un Registro</h1>");
			}
		});';

////////////////////////////**FIN**/////////////////////////////////////


		$bodyscript .= '
		$("#falicu").dialog({
			autoOpen: false, height: 200, width: 400, modal: true,
			buttons: {
				"Guardar": function() {
					var vurl = $("#df1").attr("action");
					$.ajax({
						type: "POST", dataType: "html", async: false,
						url: vurl,
						data: $("#df1").serialize(),
						success: function(r,s,x){
							try{
								var json = JSON.parse(r);
								if (json.status == "A"){
									//$.prompt("<h1>Registro Guardado</h1>");
									$( "#falicu" ).dialog( "close" );
									idvisita = json.pk.id;
									return true;
								} else {
									$.prompt(json.mensaje);
								}
							} catch(e) {
								$("#falicu").html(r);
							}
						}
					})
				},
				"Guardar y Seguir": function(){
					var id = jQuery("#newapi'.$grid0.'").jqGrid(\'getGridParam\',\'selrow\');
					var vurl = $("#df1").attr("action");
					$.ajax({
						type: "POST", dataType: "html", async: false,
						url: vurl,
						data: $("#df1").serialize(),
						success: function(r,s,x){
							try{
								var json = JSON.parse(r);
								if (json.status == "A"){
									$.prompt("<h1>Registro Guardado con exito</h1>");
									idalicu = json.pk.id;
									$.post("'.site_url('construccion/edialicuota/dataefla').'/create/"+id+"/"+idalicu,
									function(data){
										$("#falicu").html(data);
									});
									return true;
								} else {
									$.prompt(json.mensaje);
								}
							} catch(e) {
								$("#falicu").html(r);
							}
						}
					})				
				},
				"Cancelar": function() {
					$("#falicu").html("");
					$( this ).dialog( "close" );
				}
			},
			close: function() {
				$("#falicu").html("");
			}
		});
		';

		$bodyscript .= '
		function elialicu(id){
			$.prompt("<h1>Eliminar alicuota</h1>", {
				buttons: { Eliminar: true, Cancelar: false },
				submit: function(e,v,m,f){
					if (v) {
						$.ajax({ url: "'.site_url('construccion/edalicuota/elimina').'/"+id,
							complete: function(){ 
								alert("Alicuota Eliminada");
							}
						});
					}
				}
			});
		}
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

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'hidden'        => 'true',
			'frozen'        => 'true',
			'width'         => 30,
			'editable'      => 'false',
			'search'        => 'false'
		));


		$grid->addField('codigo');
		$grid->label('Codigo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 70,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:2, maxlength: 2 }',
		));

		$grid->addField('aplicacion');
		$grid->label('Aplic');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 50,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:15, maxlength: 15 }',
		));

		$grid->addField('descripcion');
		$grid->label('Descripcion');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:100, maxlength: 100 }',
		));


		$grid->addField('edificacion');
		$grid->label('Edificacion');
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


		$grid->addField('uso');
		$grid->label('Uso');
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


		$grid->addField('usoalter');
		$grid->label('Usoalter');
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


		$grid->addField('ubicacion');
		$grid->label('Ubicacion');
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


		$grid->addField('caracteristicas');
		$grid->label('Caracteristicas');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 250,
			'edittype'      => "'textarea'",
			'editoptions'   => "'{rows:2, cols:60}'",
		));


		$grid->addField('area');
		$grid->label('Area');
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


		$grid->addField('estaciona');
		$grid->label('Estaciona');
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


		$grid->addField('deposito');
		$grid->label('Deposito');
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


		$grid->addField('preciomt2e');
		$grid->label('Preciomt2e');
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


		$grid->addField('preciomt2c');
		$grid->label('Preciomt2c');
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


		$grid->addField('preciomt2a');
		$grid->label('Preciomt2a');
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


		$grid->addField('objeto');
		$grid->label('Objeto');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:1, maxlength: 1 }',
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

		$grid->showpager(true);
		$grid->setWidth('');
		$grid->setHeight('290');
		$grid->setTitle($this->titp);
		$grid->setfilterToolbar(true);
		$grid->setToolbar('false', '"top"');

		$grid->setOnSelectRow('
			function(id){
			if (id){
				$.ajax({
					url: "'.site_url('construccion/edalicuota').'/tabla/"+id,
					success: function(msg){
						$("#ladicional").html(msg);
					}
				});
			}}
		');

		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:300, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];},afterShowForm: function(frm){$("select").selectmenu({style:"popup"});} ');
		$grid->setAfterSubmit("$('#respuesta').html('<span style=\'font-weight:bold; color:red;\'>'+a.responseText+'</span>'); return [true, a ];");

		$grid->setOndblClickRow('');		#show/hide navigations buttons
		$grid->setAdd(    $this->datasis->sidapuede('EDINMUE','INCLUIR%' ));
		$grid->setEdit(   $this->datasis->sidapuede('EDINMUE','MODIFICA%'));
		$grid->setDelete( $this->datasis->sidapuede('EDINMUE','BORR_REG%'));
		$grid->setSearch( $this->datasis->sidapuede('EDINMUE','BUSQUEDA%'));

		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		$grid->setBarOptions("addfunc: edinmueadd, editfunc: edinmueedit, delfunc: edinmuedel, viewfunc: edinmueshow");

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
		$mWHERE = $grid->geneTopWhere('edinmue');

		$response   = $grid->getData('edinmue', array(array()), array(), false, $mWHERE, 'codigo' );
		$rs = $grid->jsonresult( $response);

		//Guarda en la BD el Where para usarlo luego
		$querydata = array('data1' => $this->session->userdata('dtgQuery'));
		$emp = strpos($querydata['data1'],'WHERE ');
		if($emp > 0){
			$querydata['data1'] = substr( $querydata['data1'], $emp );
			$emp = strpos($querydata['data1'],'ORDER BY ');
			if($emp > 0){
				$querydata['data1'] = substr( $querydata['data1'], 0, $emp );
			}
		}else{
			$querydata['data1'] = '';
		}
		$ids = $this->datasis->guardasesion($querydata);

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
				$check = $this->datasis->dameval("SELECT count(*) FROM edinmue WHERE $mcodp=".$this->db->escape($data[$mcodp]));
				if ( $check == 0 ){
					$this->db->insert('edinmue', $data);
					echo "Registro Agregado";

					logusu('EDINMUE',"Registro ????? INCLUIDO");
				} else
					echo "Ya existe un registro con ese $mcodp";
			} else
				echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			$nuevo  = $data[$mcodp];
			$anterior = $this->datasis->dameval("SELECT $mcodp FROM edinmue WHERE id=$id");
			if ( $nuevo <> $anterior ){
				//si no son iguales borra el que existe y cambia
				$this->db->query("DELETE FROM edinmue WHERE $mcodp=?", array($mcodp));
				$this->db->query("UPDATE edinmue SET $mcodp=? WHERE $mcodp=?", array( $nuevo, $anterior ));
				$this->db->where("id", $id);
				$this->db->update("edinmue", $data);
				logusu('EDINMUE',"$mcodp Cambiado/Fusionado Nuevo:".$nuevo." Anterior: ".$anterior." MODIFICADO");
				echo "Grupo Cambiado/Fusionado en clientes";
			} else {
				unset($data[$mcodp]);
				$this->db->where("id", $id);
				$this->db->update('edinmue', $data);
				logusu('EDINMUE',"Grupo de Cliente  ".$nuevo." MODIFICADO");
				echo "$mcodp Modificado";
			}

		} elseif($oper == 'del') {
			$meco = $this->datasis->dameval("SELECT $mcodp FROM edinmue WHERE id=$id");
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM edinmue WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->query("DELETE FROM edinmue WHERE id=$id ");
				logusu('EDINMUE',"Registro ????? ELIMINADO");
				echo "Registro Eliminado";
			}
		};
	}

	//******************************************************************
	// Edicion 

	function dataedit(){
		$this->rapyd->load('dataedit');

		$link1=site_url('construccion/common/get_ubic');
		$script ='
		$(function() {
			$("#edificacion").change(function(){ edif_change(); });
			$(".inputnum").numeric(".");
			$(".inputonlynum").numeric();
		});

		function edif_change(){
			$.post("'.$link1.'",{ edif:$("#edificacion").val() }, function(data){ $("#ubicacion").html(data);})
		}
		';


		$edit = new DataEdit('', 'edinmue');
		$edit->script($script,'modify');
		$edit->script($script,'create');
		$edit->on_save_redirect=false;

		//$edit->back_url = site_url($this->url.'filteredgrid');

		$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');
		$edit->pre_process( 'insert','_pre_insert');
		$edit->pre_process( 'update','_pre_update');
		$edit->pre_process( 'delete','_pre_delete');

		$edit->codigo = new inputField('C&oacute;digo','codigo');
		$edit->codigo->rule='max_length[15]|unique';
		$edit->codigo->size =17;
		$edit->codigo->maxlength =15;

		$edit->aplicacion = new dropdownField('Aplicacion','aplicacion');
		$edit->aplicacion->option('','Seleccionar');
		$edit->aplicacion->options('SELECT depto, CONCAT(depto," ",descrip) descrip FROM dpto WHERE tipo="G" AND depto NOT IN ("CO","GP") ORDER BY depto');
		$edit->aplicacion->rule='max_length[11]';
		$edit->aplicacion->style='width:150px;';

		$edit->descripcion = new inputField('Descripci&oacute;n','descripcion');
		$edit->descripcion->rule='max_length[100]';
		$edit->descripcion->maxlength =100;

		$edit->objeto = new dropdownField('Objeto','objeto');
		$edit->objeto->option('','Seleccionar');
		$edit->objeto->option('A','Alquiler');
		$edit->objeto->option('V','Venta');
		$edit->objeto->rule='max_length[1]|required';
		$edit->objeto->style='width:150px;';

		$edit->status = new dropdownField('Estatus','status');
		$edit->status->option('D','Disponible');
		$edit->status->option('A','Alquilado');
		$edit->status->option('V','Vendido');
		$edit->status->option('R','Reservado');
		$edit->status->option('O','Otro');
		$edit->status->rule='max_length[11]';
		$edit->status->style='width:150px;';

		$edit->edificacion = new dropdownField('Edificaci&oacute;n','edificacion');
		$edit->edificacion->option('','Seleccionar');
		$edit->edificacion->options('SELECT id,TRIM(nombre) AS nombre FROM edif ORDER BY nombre');
		$edit->edificacion->rule='max_length[11]';
		$edit->edificacion->style='width:150px;';

		$edit->ubicacion = new dropdownField('Ubicaci&oacute;n','ubicacion');
		$edit->ubicacion->rule='max_length[11]|integer';
		$edit->ubicacion->style='width:150px;';
		$edif=$edit->getval('edificacion');
		if($edif!==false){
			$dbedif=$this->db->escape($edif);
			$edit->ubicacion->option('','Seleccionar');
			$edit->ubicacion->options("SELECT id,descripcion FROM `edifubica` WHERE id_edif=$dbedif ORDER BY descripcion");
		}else{
			$edit->ubicacion->option('','Seleccione una edificacion');
		}

		$edit->uso = new dropdownField('Uso','uso');
		$edit->uso->option('','Seleccionar');
		$edit->uso->options('SELECT id,uso FROM `eduso` ORDER BY uso');
		$edit->uso->rule='max_length[11]|required';
		$edit->uso->style='width:150px;';

		$edit->usoalter = new dropdownField('Uso Alternativo','usoalter');
		$edit->usoalter->option('','Seleccionar');
		$edit->usoalter->options('SELECT id,uso FROM `eduso` ORDER BY uso');
		$edit->usoalter->rule='max_length[11]';
		$edit->usoalter->style='width:150px;';

		$edit->caracteristicas = new textareaField('Caracter&iacute;sticas','caracteristicas');
		//$edit->caracteristicas->rule='max_length[8]';
		$edit->caracteristicas->cols = 65;
		$edit->caracteristicas->rows = 2;

		$edit->area = new inputField('&Aacute;rea Mt2','area');
		$edit->area->rule='max_length[15]|numeric';
		$edit->area->css_class='inputnum';
		$edit->area->size =10;
		//$edit->area->maxlength =15;

		$edit->estaciona = new inputField('Estacionamiento','estaciona');
		$edit->estaciona->rule='max_length[10]|integer';
		$edit->estaciona->size =15;
		$edit->estaciona->css_class='inputonlynum';
		$edit->estaciona->maxlength =10;

		$edit->deposito = new inputField('Dep&oacute;sito','deposito');
		$edit->deposito->rule='max_length[11]|integer';
		$edit->deposito->size =15;
		$edit->deposito->maxlength =11;
		$edit->deposito->css_class='inputonlynum';

		$edit->preciomt2e = new inputField('Precio x mt2 (Contado)','preciomt2e');
		$edit->preciomt2e->rule='max_length[15]|numeric';
		$edit->preciomt2e->css_class='inputnum';
		$edit->preciomt2e->size =15;
		$edit->preciomt2e->maxlength =15;

		$edit->preciomt2c = new inputField('Precio x mt2 (Cr&eacute;dito)','preciomt2c');
		$edit->preciomt2c->rule='max_length[15]|numeric';
		$edit->preciomt2c->css_class='inputnum';
		$edit->preciomt2c->size =15;
		$edit->preciomt2c->maxlength =15;

		$edit->preciomt2a = new inputField('Precio x mt2 (Alquiler)','preciomt2');
		$edit->preciomt2a->rule='max_length[15]|numeric';
		$edit->preciomt2a->css_class='inputnum';
		$edit->preciomt2a->size =15;
		$edit->preciomt2a->maxlength =15;

		$edit->alicuota = new inputField('Alicuota %','alicuota');
		$edit->alicuota->rule='max_length[15]|numeric';
		$edit->alicuota->css_class='inputnum';
		$edit->alicuota->size =15;

		$edit->propietario = new inputField('Propietario','propietario');
		$edit->propietario->rule='';
		$edit->propietario->size =7;
		$edit->propietario->maxlength =5;

		$edit->ocupante = new inputField('Ocupante','ocupante');
		$edit->ocupante->rule='';
		$edit->ocupante->size =7;
		$edit->ocupante->maxlength =5;

		$edit->buttons('modify', 'save', 'undo', 'delete', 'back','add');
		$edit->build();

		if($edit->on_success()){
			$rt=array(
				'status' =>'A',
				'mensaje'=>'Registro guardado',
				'pk'     =>$edit->_dataobject->pk
			);
			echo json_encode($rt);
		}else{
			$conten['form']  =&  $edit;
			$data['content']  =  $this->load->view('view_edinmue', $conten, false);
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
		if (!$this->db->table_exists('edinmue')) {
			$mSQL="
			CREATE TABLE edinmue (
				id              INT(11)   NOT NULL AUTO_INCREMENT,
				codigo          CHAR(15)  DEFAULT NULL,
				aplicacion      CHAR(2)   NULL DEFAULT NULL,
				descripcion     CHAR(100) DEFAULT NULL,
				edificacion     INT(11)   DEFAULT NULL,
				uso             INT(11)   DEFAULT NULL,
				usoalter        INT(11)   DEFAULT NULL,
				ubicacion       INT(11)   DEFAULT NULL,
				caracteristicas TEXT,
				area            DECIMAL(15,2) DEFAULT NULL,
				estaciona       INT(10)   DEFAULT NULL,
				deposito        INT(11)   DEFAULT NULL,
				preciomt2e      DECIMAL(15,2) DEFAULT NULL,
				preciomt2c      DECIMAL(15,2) DEFAULT NULL,
				preciomt2a      DECIMAL(15,2) DEFAULT NULL,
				objeto          CHAR(1) NOT NULL,
				status          CHAR(1) NOT NULL COMMENT 'Alquilado, Vendido, Reservado, Otro',
			  PRIMARY KEY (id),
			  UNIQUE KEY codigo (codigo)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Facilidades'";
			$this->db->query($mSQL);
		}
		$campos = $this->db->list_fields('edinmue');
		if(!in_array('aplicacion',$campos)) $this->db->query('ALTER TABLE edinmue ADD COLUMN aplicacion CHAR(2) NULL DEFAULT NULL AFTER codigo');

		if (!$this->db->table_exists('edgrupo')) {
			$mSQL="
			CREATE TABLE edgrupo (
				id      INT(11) NOT NULL AUTO_INCREMENT,
				descrip VARCHAR(50) NULL DEFAULT NULL,
				cargo   INT(11)     NULL DEFAULT NULL,
				activo  VARCHAR(20) NULL DEFAULT NULL,
				fecha   DATE        NULL DEFAULT NULL,
			PRIMARY KEY (`id`)
			)
			COMMENT='Grupos de inmuebles'
			CHARSET=latin1
			ENGINE=MyISAM
			;";
			$this->db->query($mSQL);
		}

		if (!$this->db->table_exists('editgrupo')) {
			$mSQL="
			CREATE TABLE editgrupo (
				id       INT(11)    NOT NULL AUTO_INCREMENT,
				grupo    INT(11)    NOT NULL COMMENT 'Grupo de edgrupo',
				inmueble INT(11)        NULL DEFAULT NULL COMMENT 'Inmueble de edinmue',
				alicuota DECIMAL(15,10) NULL DEFAULT '0.0000000000',
				lectura  VARCHAR(20)    NULL DEFAULT NULL,
				monto    DECIMAL(15,2)  NULL DEFAULT '0.00',
			PRIMARY KEY (id),
			UNIQUE INDEX ingrupo (inmueble, grupo)
			)
			COMMENT='Detalle de grupos de inmuebles'
			CHARSET=latin1
			ENGINE=MyISAM
			;";
			$this->db->query($mSQL);
		}


	}

	//******************************************************************
	// Forma de Grupos
	//
	function gruposform(){
		$grid  = new $this->jqdatagrid;
		$editar = 'true';

		$mSQL  = "SELECT id, cargo FROM gcargo ORDER BY cargo";
		$cargo = $this->datasis->llenajqselect($mSQL, true );

		$activo = '{"S": "Activo", "N": "Inactivo"}';

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
		$grid->label('Nombre');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:50, maxlength: 50 }',
		));


		$grid->addField('cargo');
		$grid->label('Cargo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'width'         => 100,
			'edittype'      => "'select'",
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ value: '.$cargo.', style:"width:120px"}',
			'stype'         => "'text'"

		));


		$grid->addField('activo');
		$grid->label('Activo');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 70,
			'edittype'      => "'select'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ value: '.$activo.',  style:"width:70px"}',
			'stype'         => "'text'",
		));

/*
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
*/

		$grid->showpager(true);
		$grid->setViewRecords(false);
		$grid->setWidth('490');
		$grid->setHeight('280');

		$grid->setUrlget(site_url($this->url.'getruta/'));
		$grid->setUrlput(site_url($this->url.'setruta/'));

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

		$msalida .= '</script>';
		$msalida .= '<id class="anexos"><table id="newapi'.$mgrid['gridname'].'"></table>';
		$msalida .= '<div   id="pnewapi'.$mgrid['gridname'].'"></div></div>';

		echo $msalida;

	}

	//******************************************************************
	// Busca la data en el Servidor por json
	//
	function getruta(){
		$grid       = $this->jqdatagrid;
		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneTopWhere('edgrupo');
		$response   = $grid->getData('edgrupo', array(array()), array(), false, $mWHERE );
		$rs = $grid->jsonresult( $response);
		echo $rs;
	}

	//******************************************************************
	// Guarda la Informacion del Grid o Tabla
	//
	function setruta(){
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
				$check = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM edgrupo WHERE descrip=".$this->db->escape($data['descrip'])));
				if($check == 0){
					$this->db->insert('edgrupo', $data);
					echo 'Registro Agregado';

					logusu('edgrupo','Registro '.$data['descrip'].' INCLUIDO');
				}else{
					echo "Ya existe un grupo con ese nombre";
				}
			}else{
				echo 'Fallo Agregado!!!';
			}
		}elseif($oper == 'edit'){
			if($id<=0){ 
				return false; 
			}

			$nuevo  = $data[$mcodp];
			//unset($data[$mcodp]);
			$this->db->where('id', $id);
			$this->db->update('edgrupo', $data);

/*
			$dbnuevo=$this->db->escape($nuevo);
			$mSQL="SELECT  d.id
			FROM sclitrut AS a
			JOIN sclirut  AS b ON a.ruta=b.ruta AND b.ruta=${dbnuevo}
			JOIN sclirut  AS c ON c.vende=b.vende
			JOIN sclitrut AS d ON c.ruta=d.ruta AND d.cliente=a.cliente  AND c.ruta!=${dbnuevo}";
			$query = $this->db->query($mSQL);
			if ($query->num_rows() > 0){
				foreach ($query->result() as $row){
					$sql='DELETE FROM sclitrut WHERE id='.$row->id;
					$this->db->simple_query($sql);
				}
			}
*/
			logusu('edgrupo','Grupos de Inmueble '.$nuevo.' MODIFICADO');
			echo $nuevo." Modificada";

		}elseif($oper == 'del'){
			if($id<=0){ 
				return false; 
			}
			//$ruta  = $this->datasis->dameval("SELECT $ FROM sclirut WHERE id=${id}");
			//$dbruta= $this->db->escape($ruta);
			$check = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM sclitrut a JOIN scli b ON a.cliente=b.cliente WHERE a.ruta=${dbruta}"));
			if($check > 0){
				echo 'El registro no puede ser eliminado; elimine primero los clientes asociados';
			}else{
				$this->db->query("DELETE FROM sclirut WHERE id=${id}");
				logusu('sclirut',"Ruta ${ruta} ELIMINADO");
				echo 'Registro Eliminado';
			}
		}
	}


	//******************************************************************
	// Ver Rutas
	//
	function vergrupo(){
		$grupo   = $this->uri->segment($this->uri->total_segments());
		$dbgrupo = $this->db->escape($grupo);

		$mSQL = '
		SELECT COUNT(*) AS cana 
		FROM edinmue a 
		JOIN editgrupo b ON a.id=b.inmueble 
		WHERE b.grupo='.$dbgrupo;

		if( intval($this->datasis->dameval($mSQL)) == 0 ) {
			echo '<h1>No hay Inmuebles asignados a este grupo...</h1>';
			return;
		}

		$nombre = 'verutatab';
		$mSQL = '
		SELECT a.codigo, a.descripcion, b.inmueble eli
		FROM edinmue a 
		JOIN editgrupo b ON a.id=b.inmueble
		JOIN edgrupo   c ON b.grupo = c.id 
		WHERE b.grupo='.$dbgrupo.' 
		ORDER BY a.codigo';

		$columnas = $this->datasis->jqdata($mSQL,'verutatabdat');
		$colModel = "
			{name:'id',          index:'id',          label:'id',          width: 50, hidden:true},
			{name:'codigo',      index:'codigo',      label:'Codigo',      width: 50},
			{name:'descripcion', index:'descripcion', label:'Descripcion', width:300},
			{name:'eli',         index:'eli',         label:' ',           width: 25, formatter: fsele },
		 ";

		$Salida  = '<script>';
		$Salida .= '
		$("#'.$nombre.'").jqGrid({
			datatype: "local",
			height: 350,
			colModel:['.$colModel.'],
			multiselect: false,
			shrinkToFit: false,
			hiddengrid:  false,
			width: 480,
			rowNum:'.$columnas['i'].',
			loadonce: true,
			viewrecords: true,
			editurl: ""
		});
		'.$columnas['data'].'
		for(var i=0;i<='.$nombre."dat".'.length;i++) $("#'.$nombre.'").jqGrid(\'addRowData\',i+1,'.$nombre.'dat[i]);
		';

		$Salida .= '
		function fsele( el, val, opts ){
			var meco=\'<div><a onclick="quitagrupo(\\\''.$grupo.'\\\',\'+el+\')">'.img(array('src'=>"images/elimina4.png", 'height'=> 20, 'alt'=>'Elimina el cliente de la ruta', 'title'=>'Elimina el cliente de la ruta', 'border'=>'0')).'</a></div>\';
			return meco;
		}
		function quitagrupo(grupo, id){
			$.post("'.site_url($this->url.'gruporesta').'/"+id+"/"+grupo);
			//$("#verutatab").delRowData(rowid)
			//$("#verutatab").trigger("reloadGrid");
		}';
		$Salida .= '</script><table id="verutatab"></table><div id="pnewapi_21293249"></div>';
		echo $Salida;
	}

	//******************************************************************
	//  Suma a las rutas
	//
	function gruposuma(){
		$salida = 'Guardado';
		$grupo    = $this->uri->segment($this->uri->total_segments());
		$inmueble = $this->uri->segment($this->uri->total_segments()-1);

		$dbinmueble = $this->db->escape($inmueble);
		$dbgrupo    = $this->db->escape($grupo);

		// Comprueba si existe el inmueble
		$mSQL = "SELECT COUNT(*) AS cana FROM edinmue WHERE id=${dbinmueble}";
		$rcli = intval($this->datasis->dameval($mSQL));
		
		// Comprueba si existe el grupo
		$mSQL = "SELECT COUNT(*) FROM edgrupo WHERE id=${dbgrupo}";
		$rgru = intval($this->datasis->dameval($mSQL));

		if($rgru == 1 && $rcli == 1){
			$mSQL="SELECT count(*) FROM editgrupo WHERE grupo=${dbgrupo} AND inmueble=${dbinmueble}";
			$hay = intval($this->datasis->dameval($mSQL));
			if($hay <> 0){
				$salida = 'El inmueble ya pertenece al grupo ';
			} else {
				$mSQL = "INSERT IGNORE INTO editgrupo (grupo, inmueble) VALUES ( ${dbgrupo}, ${dbinmueble} ) ";
				$this->db->query($mSQL);
			}
		}else{
			$salida = $mSQL.' Error en los datos ';
		}
		echo $salida;
	}

	//******************************************************************
	//  Resta a las rutas
	//
	function gruporesta() {
		$salida = 'Guardado';
		$grupo    = intval($this->uri->segment($this->uri->total_segments()));
		$inmueble = intval($this->uri->segment($this->uri->total_segments()-1));

		$dbinmueble = $this->db->escape($inmueble);
		$dbgrupo    = $this->db->escape($grupo);

		$mSQL = "DELETE FROM editgrupo WHERE inmueble=${dbinmueble} AND grupo=${dbgrupo} ";
		$this->db->query($mSQL);
		echo $salida;
	}


	//******************************************************************
	//  Suma a todas las rutas
	//
	function grupotodo(){
		$data   = $this->datasis->damesesion();
		if(isset($data['data1'])){
			$where   = $data['data1'];
			$grupo    = $this->uri->segment($this->uri->total_segments());
			$dbgrupo = $this->db->escape($grupo);
			$salida  = 'Guardado';

			// Comprueba si existe la Ruta y son menos de 100
			$mSQL  = "SELECT COUNT(*) AS cana FROM edgrupo WHERE id=${dbgrupo}";
			$resta = intval($this->datasis->dameval($mSQL));
			$cana  = intval($this->datasis->dameval("SELECT COUNT(*) AS cana FROM edinmue ${where}"));

			if($cana <= 500){
				if($resta == 1){
					$mSQL = "INSERT IGNORE INTO editgrupo (grupo, inmueble) SELECT ${dbgrupo}, id  FROM edinmue ${where} ";
					$this->db->query($mSQL);
				}else{
					$salida = 'Error en los datos '.$mSQL;
				}
			}else{
				$salida = 'Demasiados resultados para agregar en un grupo, max 500. ('.$cana.')';
			}
		}else{
			$salida = 'No hay clientes seleccionados';
		}
		echo $salida;
	}

}
?>
