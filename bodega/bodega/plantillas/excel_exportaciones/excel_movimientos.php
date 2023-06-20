<?php 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
require '../../includes/Conexion.php';
require '../../DAO/ProductosDao.php';
require '../../includes/functions.php';

$tip_mov=$_GET["tipo_mov"];
$id=$_GET["idprod"];
$desde=$_GET["desde"]." 00:00:00";
$hasta=$_GET["hasta"]." 23:59:59";

        $movi="";
        $filas="";
        if($tip_mov==2){
           $movi="ENVIO";
        }elseif($tip_mov==3){
           $movi="DEVOLUCIONES";
        }elseif($tip_mov==4){
           $movi="ENTRADAS";
        }elseif($tip_mov==5){
           $movi="SALIDAS";
        }elseif($tip_mov==6){
           $movi="MERMAS";
        }else{
            $movi="TODOS";
        }
        
        $mov=new productosDao();
        if($tip_mov==1){
            $list_mov=$mov->listarMovimientos($movi,$id,$desde,$hasta,1);
        }else{
           $list_mov=$mov->listarMovimientos($movi,$id,$desde,$hasta,2);
        }
         foreach($list_mov AS $pro){ 
                   switch($pro['tipo_mov']){
                      case 'ENVIO':  
                            $signo=" (-)";
                            $obs="MOV-".$pro["num_doc"];
                       break; 
                       case 'DEVOLUCION':
                           $signo=" (+)";
                           $obs="DEV-".$pro["obs"];
                       break;
                       case 'ENTRADA':
                           $signo=" (+)";
                           $obs=$pro["obs"];
                       break; 
                       case 'SALIDA':
                           $signo=" (-)";
                           $obs=$pro["obs"];
                       break; 
                       case 'MERMA':
                           $signo=" (-)";
                           $obs=$pro["obs"];
                       break; 
                       default:
                           $signo="";
                       break;   
                   }
                   $producto=utf8_decode($pro['descripcion']);
                    $filas .= '<tr><td style="text-align:center">'.date("d-m-Y H:i:s", strtotime($pro['createdAt'])).'</td><td>'.utf8_decode($pro['descripcion']).'</td><td style="text-align:center">'.utf8_decode($pro['tipo_mov']).$signo.'</td><td style="text-align:center">'.$pro['cantidad'].'</td><td style="text-align:center">'.$pro['stock'].'</td><td>'.utf8_decode($pro['obs']).'</td></tr>';
            }

header("Pragma: public");
header("Expires: 0");
$filename ="Movimientos (".$movi.") ".$producto." ".date("d-m-Y", strtotime($desde))." al ".date("d-m-Y", strtotime($hasta)).".xls";
header('Content-Encoding: UTF-8');
header('Content-type: text/csv; charset=UTF-8');
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
?>
<table class="table table-responsive table-hover table-fixed">
          <thead style="background-color:#A9D0F5">
                <tr>
                <th style="text-align:center">Fecha movimiento</th>
                <th>Producto</th>
                <th style="text-align:center">Tipo movimiento</th>
                <th style="text-align:center">Cantidad</th>
                <th style="text-align:center">Stock</th>
                <th style="text-align:center">Observaci&oacute;n</th>
            </tr></thead>
            <tbody>
            <?php
                echo $filas;
            ?>
            </tbody>
        </table>