<?php 
function Conectar() 
{ 
global $link;
   $cn_oper =   "www.p-active.cl"; # Ip del Servidor  
   $user_oper = "pactive_rpanes";        # Usuario de la Base de Datos
   $pwd_oper =  "Qm{-u!1vqD0V";        # Password del usuario de la Base de datos
   $bd="pactive_bodega"; 
   if (!($link= mysqli_connect($cn_oper, $user_oper, $pwd_oper,$bd))) 
   { 
      echo "Error conectando a la base de datos."; 
      exit(); 
   } 
    //mysqli_set_charset('utf8', $link);
   return $link; 
} 
?>