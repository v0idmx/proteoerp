<?php
	class xlsauto extends Controller{
		function xlsauto(){
			parent::Controller();
			}
		function index(){
			redirect("/xlsauto/repoauto");				
		}
		function repoauto($mSQL){//ORDeR BY envia,recibe      
			$this->load->library("XLSReporte"); 
			$xls= new xlsreporte($mSQL);					
			$xls->tcols();			
			$xls->Table();
			$xls->Output();					
	  }
	  function repoauto2(){//ORDeR BY envia,recibe 
			$this->load->library('encrypt');
			$this->load->library("XLSReporte");
			
			$mSQL=$this->input->post("mSQL");
			$consulta = $this->encrypt->decode($mSQL); 
			$xls= new xlsreporte($consulta);					
			$xls->tcols();			
			$xls->Table();
			$xls->Output();					
	  }
	}
?>