<?php
	class Plantilla{
		
		var $ls_code;
		var $par;
		var $DIR_PLANTILLAS = ''; 
		var $error;
		var $ls_error;
		function acentoToHtml($texto){
			$texto = str_replace("á","&aacute;",$texto);
			$texto = str_replace("é","&eacute;",$texto);
			$texto = str_replace("í","&iacute;",$texto);
			$texto = str_replace("ó","&oacute;",$texto);
			$texto = str_replace("ú","&uacute;",$texto);
			$texto = str_replace("Á","&Aacute;",$texto);
			$texto = str_replace("É","&Eacute;",$texto);
			$texto = str_replace("Í","&Iacute;",$texto);
			$texto = str_replace("Ó","&Oacute;",$texto);
			$texto = str_replace("Ú","&Uacute;",$texto);
			$texto = str_replace("ñ","&ntilde;",$texto);
			$texto = str_replace("Ñ","&Ntilde;",$texto);
			$texto = str_replace("¿","&iquest;",$texto);
			return $texto;
		}
		function acentoToDF($texto){
			$texto = str_replace("&aacute;","á",$texto);
			$texto = str_replace("&eacute;","é",$texto);
			$texto = str_replace("&iacute;","í",$texto);
			$texto = str_replace("&oacute;","ó",$texto);
			$texto = str_replace("&uacute;","ú",$texto);
			$texto = str_replace("&Aacute;","Á",$texto);
			$texto = str_replace("&Eacute;","É",$texto);
			$texto = str_replace("&Iacute;","Í",$texto);
			$texto = str_replace("&Oacute;","Ó",$texto);
			$texto = str_replace("&Uacute;","Ú",$texto);
			$texto = str_replace("&ntilde;","ñ",$texto);
			$texto = str_replace("&Ntilde;","Ñ",$texto);
			$texto = str_replace("&iquest;","¿",$texto);
			return $texto;
		}
		function remp_html(){
			if(count($this->par)>0){
				foreach($this->par AS $np => $vp){
					$this->ls_code = str_replace($np,$vp,$this->ls_code);
				}
			}
		}
		function cargaVariables($arr){
			foreach($arr AS $name => $item){
				$this->par["#".strtoupper($name)."#"] = $item; 
			}
		}
		function getCode(){
			$this->remp_html();
			if(trim($this->error) == ""){
				return $this->acentoToHtml($this->ls_code);
			}else{
				return $this->error;
			}
		}
		function getXML(){
			$this->remp_html();
			if(trim($this->error) == ""){
				return utf8_decode($this->acentoToDF($this->ls_code));
			}else{
				return $this->error;
			}
		}
		
		function addParametros($nombre, $valor){
			$this->par[$nombre]=$valor;
		}
		
		function __construct($file, $path=""){
			$name_file = $file;
			$file = $path . $this->DIR_PLANTILLAS . $file;
			if(!$file){
				$this->ls_error="Error no existe el archivo ".$name_file."(".$file.")";
			}else{
				if(!is_file($file)){
					$this->ls_error="Error no existe el archivo ".$name_file."(".$file.")";
				}else{
					if(!is_readable($file)){
						$this->ls_error="Error el archivo no es leible ".$name_file."(".$file.")";
					}else{
						$buffer = fopen($file,'r');
						if(!$buffer){
							$this->ls_error="Error de archivo ".$name_file."(".$file.")";
						}else{
							$contenido = '';
							while (!feof($buffer)) {
							  $contenido .= fread($buffer, 124);
							}
							fclose($buffer);
							unset($file);
							unset($buffer);
							$this->ls_code=$contenido;
						}
					}
				}
				
			}
			if($this->ls_error){
				exit($this->ls_error);
			}
		}
	}
?>