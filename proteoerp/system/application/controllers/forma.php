<?php
/**
 * ProteoERP
 *
 * @autor    Andres Hocevar
 * @license  GNU GPL v3
*/
class Forma extends Controller{

	var $_direccion;
	var $codigo=array();
	var $parametros=array();
	var $lencabp;
	var $lencabm;
	var $lencabu;
	var $lpiep;
	var $lpiem;
	var $lpieu;

	function Forma(){
		parent::Controller();
		$this->load->library('rapyd');
		//$this->load->library("numletra");
	}

	function index(){
		$this->ver();
	}

	function ver(){
		$this->parametros= func_get_args();
		if (count($this->parametros)>0){
			$_arch_nombre=implode('-',$this->parametros);
			$_fnombre=array_shift($this->parametros);
			$repo=$this->datasis->dameval("SELECT tcpdf FROM formatos WHERE nombre='${_fnombre}'");

			if(!$repo){
				echo 'Formato No Existe';
				return false;
			}

			$captura=false;
			$lineas=explode("\n",$repo);
			foreach($lineas as $linea){
				if(preg_match('/\/\/@(?P<funcion>\w+)/', $linea, $match)){
					$func=$match['funcion'];
					$captura=true;
				}elseif(preg_match('/\/\/@@(?P<funcion>\w+)/', $linea, $match)){
					$captura=false;
				}

				if($captura){
					if(isset($this->codigo[$func]))
					$this->codigo[$func] .= str_replace('<?php','',$linea)."\n";
					else
					$this->codigo[$func] = str_replace('<?php','',$linea)."\n";
				}
			}
		}

		$this->load->library('pdf');

		$o = new pdf;
		$t = new pdf;
		$this->config($t);
		$this->cuerpo($t,$o);
	}

	function config($obj){
		eval($this->codigo['config']);
	}

	function consultas(){
		eval($this->codigo['consultas']);
	}

	function encab($pdf){
		eval($this->codigo['encab']);
	}

	function encab2($pdf){
		eval($this->codigo['encab2']);
	}

	function encab3($pdf){
		eval($this->codigo['encab3']);
	}

	function pie($pdf){
		eval($this->codigo['pie']);
	}

	function pie2($pdf){
		eval($this->codigo['pie2']);
	}

	function pie3($pdf){
		eval($this->codigo['pie3']);
	}

	function cuerpo($objt,$pdf){
		eval($this->codigo['cuerpo']);
	}

	function enc_der($tipo,$numero,$fecha,$re,$pdf,$img=''){
		//$inicio = $this->infor_pdf->getY();
		if($img!=""){
			$pdf->Image(K_PATH_IMAGES.$img, 100, 4, 23);
		}
		eval($this->codigo['enc_der']);
		$this->infor_pdf->setY($this->lencabp);
		$this->infor_pdf->SetFont('times', '', 8);
	}

	function forma_header($pdf){
		eval($this->codigo['forma_header']);
	}

}
