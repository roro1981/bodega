<?php
 class Conexion extends mysqli{


		private $result;
		private $ind=0;
		private $indBD=0;
		private $row;

		public function __construct(){
			
			$db_host	= 'www.p-active.cl';
			$db_user	= 'pactive_rpanes';
			$db_pass	= 'Qm{-u!1vqD0V';
			$db_name	= 'pactive_bodega';

			parent::__construct($db_host, $db_user, $db_pass, $db_name);	
			$this->set_charset('utf8');
			($this->connect_errno) ? die('Error en la conexión' . mysqli_connect_errno()) : $m = 'Conectado ;D';

		}
		
		public function consult($sql){
			
				$this->result = $this->query($sql);	
				if(!$this->result){
					throw new Exception("Error en la consulta : ".$sql, 1);
				}else{
					$this->row = $this->result->fetch_assoc();
					$this->ind=0;				
				}
			
		}

		function getResult($nom = ""){
			if($nom == "" || $nom == "*"){
				return $this->row;
			}else{
				return $this->row[$nom];			
			}
		}
		function nextInd(){
			if($this->row = $this->result->fetch_assoc()){
				return true;
			}else{
				return false;
			}
		}
		function setInd($ind){
			$this->nextInd();
			$this->ind++;
		}
		
		function getInd(){
			return $this->ind;
		}
		function getNumResult(){
			return $this->result->num_rows;
		}
		function getError(){
			return $this->errno;
		}
		function insert($sql){
			
			$this->query($sql);
			if($this->getError()){
				throw new Exception("Error en la consulta : ".$sql . "( ".$this->error." )", 1);	
			}else{
				return $this->insert_id;
			}						
			
		}
		function beginTransaccion(){
			$this->query("START TRANSACTION");
		}
		function setAutoCommit($modo){
			$this->autocommit($modo);
		}
		function setRollBack(){
			$this->rollback();
			$this->close();
		}
		function setCommit(){
			$this->commit();
			$this->close();
		}

		function onlyConsult($sql, $columna){
			$this->consult($sql);
			return $this->getResult($columna);
		}
		function consultFetch($sql){
			$this->result = $this->query($sql);	               
		}
		function fetch_assoc(){
			return $this->result->fetch_assoc();
		}
		function closeConection(){			
			$this->close();
		}
 }
?>