<?php

function obtenerFechaEnLetra($fecha){
    $dia= conocerDiaSemanaFecha($fecha);
    $num = date("j", strtotime($fecha));
    $anno = date("Y", strtotime($fecha));
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
    return $dia.', '.$num.' de '.$mes.' del '.$anno;
}
 
function conocerDiaSemanaFecha($fecha) {
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia = $dias[date('w', strtotime($fecha))];
    return $dia;
}
function formatoFecha($fecha) {
    $fec = date('d-m-Y', strtotime($fecha));
    return $fec;
}
function formatoHora($fecha) {
    $hor = date('H:i:s', strtotime($fecha));
    return $hor;
}
function actu_stock($pid,$cant){
	global $link;
         $sql = "UPDATE productos set stock=stock-$cant where idproductos = $pid";
         $q = mysqli_query($link, $sql) or die(mysqli_error($link));
}
function actu_cupo($cid,$monto){
	global $link;
         $sql = "UPDATE personas set cupo=cupo-$monto where idpersona = $cid and cupo_ilimitado=0";
         $q = mysqli_query($link, $sql) or die(mysqli_error($link));
}
function usuarios($id,$info){
	global $link;
			$s = "SELECT * FROM usuarios WHERE id=$id";
			$q = mysqli_query($link, $s) or die(mysqli_error($link));
         if (mysqli_num_rows($q)==1) {
			$r = mysqli_fetch_array($q);
			switch ($info) {
				case 0:																	break;
				case 1:		return strtoupper($r['nombre']);     						break;
				case 2:		return $r['nom_user'];				 						break;
				case 3:		return $r['activo'];				 						break;
			}
		 }		
}
function mes_palabras($m) {
	$r = '';
	switch($m) {
		case '1': 	$r =  "Enero"; 		break;
		case '2': 	$r =  "Febrero"; 	break;
		case '3': 	$r =  "Marzo"; 		break;
		case '4': 	$r =  "Abril"; 		break;
		case '5': 	$r =  "Mayo"; 		break;
		case '6': 	$r =  "Junio"; 		break;
		case '7': 	$r =  "Julio";		break;
		case '8': 	$r =  "Agosto";		break;
		case '9': 	$r =  "Septiembre";	break;
		case '10': 	$r =  "Octubre";	break;
		case '11': 	$r =  "Noviembre";	break;
		case '12': 	$r =  "Diciembre";	break;
	}
	return $r;
}
function pago_venta($ticket,$info){
	global $link;
	$pagos="";
			$s = "SELECT * FROM desglosepagos WHERE nro_ticket=$ticket";
			$q = mysqli_query($link, $s) or die(mysqli_error($link));
         if (mysqli_num_rows($q)>0) {
			
			switch ($info) {
				case 1:	
				while ($r = mysqli_fetch_array($q))
				{
					$pagos .= ' '.$r['fpago'];
					
				}
				return $pagos;     						
				
				break;
				
			}
		 }		
}
function clientes($id,$info){
	global $link;
			$s = "SELECT * FROM personas WHERE idpersona=$id and tipo_persona='Cliente'";
			$q = mysqli_query($link, $s) or die(mysqli_error($link));
         if (mysqli_num_rows($q)==1) {
			$r = mysqli_fetch_array($q);
			switch ($info) {
				case 1:		return strtoupper($r['nombres'].' '.$r['app'].' '.$r['apm']);     						break;
				
			}
		 }		
}
function calculaedad($fechanacimiento){
  list($ano,$mes,$dia) = explode("-",$fechanacimiento);
  $ano_diferencia  = date("Y") - $ano;
  $mes_diferencia = date("m") - $mes;
  $dia_diferencia   = date("d") - $dia;
  if ($dia_diferencia < 0 || $mes_diferencia < 0)
    $ano_diferencia--;
  return $ano_diferencia;
}

?>