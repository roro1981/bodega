<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
session_start();
require '../includes/Conexion.php';
require '../DAO/FaenasDao.php';
require '../DAO/ProductosDao.php';
require '../includes/functions.php';

$opt = 0; if (isset($_POST['opt'])){ $opt=$_POST['opt']; }
    switch ($opt) {
        case 'listarFaenas':
             $fae = new faenasDao();
             $faenas = $fae->listarFaenas(); 
             $filas="";
                foreach($faenas AS $fa){
                    $elimina='<button class="elim_fae" id="'.$fa['id'].'_'.$fa['nombre_faena'].'"><i title="Eliminar faena" style="cursor:pointer;font-size:18pt;color:red" class="fa fa fa-minus-square"></i></button>';
                    $filas .= '<tr><td>'.$fa['id'].'</td><td>'.$fa['nombre_faena'].'</td><td>'.$elimina.'</td></tr>';
                }
            echo $filas;    
        break;
        case 'grabaFaena':
            $nombre_faena=$_POST['faena'];
            $fae = new faenasDao();
            $verifica = $fae->faeExiste($nombre_faena);
            if($verifica==1){
                $creado="existe";
            }else{
    	        $creado="no";		
                 $fae2 = new faenasDao();  
                 $fae3 = new faenasDao();  
                 $crea_fae = $fae2->nuevaFaena(strtoupper($nombre_faena));
                 $crea_prods_fae = $fae3->creaProductosNuevaFaena($crea_fae);
                if($crea_fae){
                   $creado= $crea_fae;
                }
            }
            echo $creado;    
        break;
        case 'borraFaena':
            $id=$_POST['id'];
            $borrado="no";
    	     if (isset($id)){
    	        $fae2= new faenasDao();
    	        $conStock=$fae2->verificaFaena($id);
    	        if($conStock==1){
    	            $borrado="noborra";
    	        }else{
                    $fae = new faenasDao();
                    $fae2 = new faenasDao();
                    $borra_fae = $fae->elimFaena($id);
                    $borra_prods_fae = $fae2->elimProductosFaena($id);
                    if($borra_fae==0){
                        $borrado= $borra_fae;
                    }
    	        }
            }   
            echo $borrado;
        break;
        case 'trae_productos':
            $data = array();
            $id_fae=$_POST['faena'];
            $fae = new faenasDao();
            $list_prod = $fae->listarProductosActivosFaenas($id_fae);  
            foreach($list_prod AS $pro){
                $prods3 = new productosDao();
                $precio_neto=$prods3->traePrecio($pro['codigo']);
                $acum_precio=0;
                $acum_cantidad=0;
                $precio_ponderado=0;
                foreach($precio_neto AS $pn){
                    $acum_precio=$acum_precio+($pn['cantidad'] * $pn['precio_neto']); 
                    $acum_cantidad=(int)($acum_cantidad)+(int)($pn['cantidad']); 
                }
                if($acum_precio==0 && $acum_cantidad==0){
                    $prod_precio=new productosDao();
                    $precio_ponderado=$prod_precio->obtenerPrecio($pro['codigo']);
                }else{
                    $precio_ponderado=round($acum_precio/$acum_cantidad);
                }
                $datos=array();
                $datos =   ['cod' => $pro['codigo'],
                            'cod_laudus' => $pro['cod_laudus'],
                            'desc' => $pro['descripcion'],
                            'pn' => $precio_ponderado,
                            'stock' => $pro['stock'],
                            'imp' => $pro['impuesto'],
                            'pb' => (($precio_ponderado*$pro['impuesto'])/100)+$precio_ponderado,
                            'cat' => $pro['nombre_cat']
                            ];
            
                $data[]=$datos;
                 }
            echo json_encode($data);
        break;
    }     
    
?>


    

   
        
    


