<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
session_start();
require '../includes/Conexion.php';
require '../DAO/ProductosDao.php';
require '../DAO/FaenasDao.php';
require '../includes/functions.php';

$opt = 0; if (isset($_POST['opt'])){ $opt=$_POST['opt']; }
        switch ($opt) {
            case 'trae_productos':
                $data = array();
                $prods = new productosDao();
                $prods2 = new productosDao();
                $list_prod = $prods->listarProductosActivos();  
                foreach($list_prod AS $pro){
                    $stock = $prods2->traeStock($pro['id']);
                    $prods3 = new productosDao();
                    $precio_neto=$prods3->traePrecio($pro['id']);
                    $acum_precio=0;
                    $acum_cantidad=0;
                    $precio_ponderado=0;
                    foreach($precio_neto AS $pn){
                        $acum_precio=$acum_precio+($pn['cantidad'] * $pn['precio_neto']); 
                        $acum_cantidad=(int)($acum_cantidad)+(int)($pn['cantidad']); 
                    }
                    if($acum_precio==0 && $acum_cantidad==0){
                        $prod_precio=new productosDao();
                        $precio_ponderado=$prod_precio->obtenerPrecio($pro['id']);
                    }else{
                        $precio_ponderado=round($acum_precio/$acum_cantidad);
                    }
                    $edita= '<button class="edita_prod" id='.$pro['id'].' data-pneto="'.$precio_ponderado.'" data-toggle="modal" data-target="#modifica_prod"><i title="Edita este producto" style="cursor:pointer;font-size:18pt;color:green" class="fa fa fa-edit"></i></button>';
                    $elimina='<button class="elim_prod" id="'.$pro['id'].'*'.$pro['descripcion'].'"><i title="Eliminar este producto" style="cursor:pointer;font-size:18pt;color:red" class="fa fa fa-minus-square"></i></button>';  
                    $datos=array();
                $datos =   ['cod' => $pro['id'],
                            'cod_laudus' => $pro['cod_laudus'],
                            'desc' => $pro['descripcion'],
                            'pn' => $precio_ponderado,
                            'stock' => $stock,
                            'imp' => $pro['impuesto'],
                            'pb' => round((($precio_ponderado*$pro['impuesto'])/100)+$precio_ponderado),
                            'cat' => $pro['nombre_cat'],
                            'acciones' => $edita.$elimina,
                            ];
                
                    $data[]=$datos;
                     }
                echo json_encode($data);
            break;
            case 'grabaProducto':
                $DATA=json_decode($_POST['arr']);
                $creado="no";
                $prods = new productosDao();
                $prods3 = new productosDao();
                    $verifica = $prods->prodExiste($DATA[0]->descripcion);
                    $verifica_laudus = $prods3->laudusExiste($DATA[0]->cod);
                    if($verifica==1){
                        $creado="existe";
                    }elseif($verifica_laudus==1){
                        $creado="existelaudus";
                    }else{
                        $creado="si";       
                        if (isset($DATA)){
                         $prods2 = new productosDao();
                         $crea_pro = $prods2->nuevoProducto(strtoupper(str_replace("'", "",$DATA[0]->cod)), strtoupper(str_replace("'", "",$DATA[0]->descripcion)), $DATA[0]->pn,
                         $DATA[0]->impu, $DATA[0]->impuId, $DATA[0]->categ, $DATA[0]->ubic);
                         $fae = new faenasDao();   
                         $cant_faenas=$fae->listarFaenas();  
                         foreach($cant_faenas AS $fa){
                             $prods3 = new productosDao();
                             $crea_pro_faena = $prods3->nuevoProductoFaena($crea_pro,str_replace("'", "",strtoupper($DATA[0]->cod)), strtoupper(str_replace("'", "",$DATA[0]->descripcion)),$DATA[0]->pn, 
                             $DATA[0]->impu, $DATA[0]->impuId, $DATA[0]->categ, $fa['id']);
                             $mov2=new productosDao();
                             $mov2->registraMovimientoEnFaena($crea_pro,0,0,"CREACIÓN","-","-",$fa['id']);
                         }
                         $mov=new productosDao();
                         $movim="CREACIÓN";
                         $mov->registraMovimiento($crea_pro,0,0,$movim,"-","-");
                         
                        }
                    }
                echo $creado;    
            break;
            case 'grabaProductoMasivo':
            $DATA=json_decode($_POST['arr']);
            $existe=0;
            $creado=0;
            
            $nocreado=0;
            for ($i=0; $i < count($DATA); $i++) {
            $prods = new productosDao();
            $prods3 = new productosDao();
                $verifica = $prods->prodExiste(strtoupper($DATA[$i]->Descripcion));
                $verifica_laudus = $prods3->laudusExiste($DATA[0]->cod);
                if($verifica==1){
                    $existe++;
                }elseif($verifica_laudus==1){    
                    $existe++;
                }else{
                     $prods2 = new productosDao();
                     $crea_pro = $prods2->nuevoProducto(strtoupper(str_replace("'", "",$DATA[$i]->Codigo_laudus)), strtoupper(str_replace("'", "",$DATA[$i]->Descripcion)), 0,
                        $DATA[$i]->valor_imp, $DATA[$i]->imp, $DATA[$i]->categoria, $DATA[$i]->ubicacion);
                        $fae = new faenasDao();   
                         $cant_faenas=$fae->listarFaenas();  
                         foreach($cant_faenas AS $fa){
                             $prods3 = new productosDao();
                             $crea_pro_faena = $prods3->nuevoProductoFaena($crea_pro,strtoupper(str_replace("'", "",$DATA[$i]->Codigo_laudus)), strtoupper(str_replace("'", "",$DATA[$i]->Descripcion)), 0,
                             $DATA[$i]->valor_imp, $DATA[$i]->imp, $DATA[$i]->categoria, $fa['id']);
                             $mov2=new productosDao();
                             $mov2->registraMovimientoEnFaena($crea_pro,0,0,"CREACIÓN","-","-",$fa['id']);
                         }
                     if($crea_pro){
                         $creado++;
                         $mov=new productosDao();
                         $qty=new productosDao();
                         $movim="CREACIÓN";
                           $mov->registraMovimiento($crea_pro,0,0,$movim,"-","-");
                     }else{
                         $nocreado++;
                     }    
                }
            }    
            echo "OK"."**".$creado."**".$nocreado."**".$existe."**".$i;    
            break;
            case 'trae_datos_producto':
                $id=$_POST['id'];
				$datos=array();  
				$prods = new productosDao();
                $list_datos = $prods->listarProductoId($id);  
                foreach($list_datos AS $dat){
                    $datos[]=$dat['cod_laudus'];
                    $datos[]=$dat['descripcion'];
                    $prods2 = new productosDao();
                    $datos[]=$prods2->traePrecio($id);
                    $datos[]=$dat['impuestoId']."-".$dat['impuesto'];
                    $datos[]=$dat['categoria'];
                    $datos[]=$dat['ubicacion'];
                }
                echo json_encode($datos);
            break; 
            case 'actuProducto':
                $DATA=json_decode($_POST['arr']);
                $actualizado="no";
                $prods = new productosDao();
	            if (isset($DATA)){
                 $actu_pro = $prods->actuProducto($DATA[0]->id_prod, strtoupper(str_replace("'", "",$DATA[0]->codigo)), strtoupper(str_replace("'", "",$DATA[0]->descripcion)), $DATA[0]->pn, $DATA[0]->impu, $DATA[0]->impuId, $DATA[0]->categ, $DATA[0]->ubic);
                 $fae = new faenasDao();   
                         $cant_faenas=$fae->listarFaenas();  
                         foreach($cant_faenas AS $fa){
                             $prods2 = new productosDao();
                             $actu_pro_faena = $prods2->actuProductoFaena($DATA[0]->id_prod,strtoupper(str_replace("'", "",$DATA[0]->codigo)),strtoupper(str_replace("'", "",$DATA[0]->descripcion)), $DATA[0]->pn, $DATA[0]->impu, $DATA[0]->impuId, $DATA[0]->categ, $fa['id']);
                         }
                }
                if($actu_pro==0){
                   $actualizado= $actu_pro;
                }
                echo $actualizado;    
            break;   
            case 'borraProducto':
            $id=$_POST['id'];
            $borrado="no";
            $prods = new productosDao();
    	     if (isset($id)){
                $borra_prod = $prods->elimProd($id);
                $fae = new faenasDao();   
                     $cant_faenas=$fae->listarFaenas(); 
                     $prods3 = new productosDao();
                     $codigo_prod=$prods3->traeCodigoProducto($id);
                     foreach($cant_faenas AS $fa){
                         $prods2 = new productosDao();
                         $borra_pro_faena = $prods2->elimProdFaena($codigo_prod, $fa['id']);
                     }
                 if($borra_prod==0){
                    $borrado= $borra_prod;
                 }
            }   
            echo $borrado;    
            break; 
            case 'trae_categorias':
               $filas="";
               $prods = new productosDao();
                $list_cat = $prods->listarCategorias();  
                foreach($list_cat AS $cat){
                    $prods2 = new productosDao();
                    $prods_asoc=$prods2->productosConCategoria($cat['id']);
                    $edita= '<button class="edita_cat" id='.$cat['id'].' data-toggle="modal" data-target="#edit_cat"><i title="Edita esta categoria" style="cursor:pointer;font-size:18pt;color:green" class="fa fa fa-edit"></i></button>';
                    $elimina='<button class="elim_cat" id="'.$cat['id'].'_'.$cat['nombre_cat'].'"><i title="Eliminar esta categoria (solo si no tiene productos asociados)" style="cursor:pointer;font-size:18pt;color:red" class="fa fa fa-minus-square"></i></button>';
                    $filas .= '<tr><td>'.$cat['nombre_cat'].' (ID '.$cat['id'].')</td><td style="text-align:center">'.$prods_asoc.'</td><td>'.$edita.$elimina.'</td></tr>';
                }
                echo $filas;
            break;
            case 'grabaCategoria':
                $nombre_cat=$_POST['cat'];
                $prods = new productosDao();
                $verifica = $prods->catExiste($nombre_cat);
                if($verifica==1){
                    $creado="existe";
                }else{
		        $creado="no";		
	             $prods2 = new productosDao();     
                 $crea_cat = $prods2->nuevaCategoria(strtoupper($nombre_cat));
                if($crea_cat){
                   $creado= $crea_cat;
                }
                }
                echo $creado;    
            break;
             case 'trae_categoria':
               $id=$_POST['id'];
			   $datos=array();  
			   $prods = new productosDao();
               $list_datos = $prods->listarCategoriaId($id);  
                foreach($list_datos AS $dat){
                    $datos[]=$dat['nombre_cat'];
                }
                echo json_encode($datos);
            break; 
            case 'actuCat':
            $DATA=json_decode($_POST['arr']);
            $actualizado="no";
            $prods = new productosDao();
	        if (isset($DATA)){
                $actu_cat = $prods->actuCat(strtoupper($DATA[0]->nueva_cat), $DATA[0]->cat);
            }
            if($actu_cat==0){
               $actualizado= $actu_cat;
            }
            echo $actualizado;    
            break;  
            case 'borraCat':
            $id=$_POST['id'];
            $borrado="no";
            $prods = new productosDao();
    	     if (isset($id)){
                 $prod_con_cat=$prods->productosConCategoria($id);
                 if($prod_con_cat==0){
                    $prods2 = new productosDao();
                    $borra_cat = $prods2->elimCat($id);
                    if($borra_cat==0){
                    $borrado= $borra_cat;
                    }
                }else{
                    $borrado=-1;
                }
            }   
            echo $borrado;    
            break; 
            
            case 'carga_movis':
               $id=$_POST['idp'];
               $tip_mov=$_POST['tipo_mov'];
               $cant=$_POST['canti'];
               $filas="";
               if($tip_mov=="E"){
                   $movi="ENTRADA";
               }elseif($tip_mov=="S"){
                   $movi="SALIDA";
               }else{
                   $movi="MERMA";
               }
                $prods2=new productosDao();
                $producto = $prods2->listarProductoId($id);
                $aleatorio=rand(100,5000);
                $elimina='<button type="button" id="elimina_'.$aleatorio.'" class="borrar btn btn-danger">X</button>';
             
                if($tip_mov=="S" || $tip_mov=="M"){
                  $prods3=new productosDao();    
                  $stock = $prods3->traeStock($id);
                  $operacion=$stock-$cant;
                  if($operacion<0){
                      $filas="NO";
                  }else{
                      $filas = '<tr id="'.$aleatorio.'"><td style="text-align:center">'.$elimina.'</td><td data-ale="'.$aleatorio.'" id="idp_'.$id.'">'.$producto[0]['id'].'</td><td>'.$producto[0]['descripcion'].' <font style="font-weight: bold;">(STOCK '.$stock.')</font></td><td style="text-align:center">'.$cant.'</td><td id="precio_'.$aleatorio.'" style="text-align:center">'.$producto[0]['precio_neto'].'</td><td style="text-align:center">'.$movi.'</td><td><textarea class="form-control" title="Máximo 500 caratcteres" id="obs_'.$id.'" rows="1"></textarea></tr>';
                  }
                }else{
                      $prods3=new productosDao();    
                      $stock = $prods3->traeStock($id);
                      $filas = '<tr id="'.$aleatorio.'"><td style="text-align:center">'.$elimina.'</td><td data-ale="'.$aleatorio.'" id="idp_'.$id.'">'.$producto[0]['id'].'</td><td>'.$producto[0]['descripcion'].' <font style="font-weight: bold;">(STOCK '.$stock.')</font></td><td style="text-align:center">'.$cant.'</td><td style="text-align:center"><input type="number" id="precio_'.$aleatorio.'" style="text-align:center" value="'.$producto[0]['precio_neto'].'" /></td><td style="text-align:center">'.$movi.'</td><td><textarea class="form-control" title="Máximo 500 caratcteres" id="obs_'.$id.'" rows="1"></textarea></tr>';    
                }
                
                echo trim($filas);
            break;
            
            case 'grabaMovs':
    			$items=json_decode($_POST['arr']); 
    			date_default_timezone_set('America/Santiago');
    			$num_guia="GMB-".date("Ymd-His");
    			$usuario=$_SESSION['id_user'];
    				for ($i=0; $i < count($items); $i++) {
    			       $prodm = new productosDao(); 
    			       $actu  = new productosDao();
    			       $prods = new productosDao();
    			       if($items[$i]->tipo=="E"){
                           $movi="ENTRADA";
                       }elseif($items[$i]->tipo=="S"){
                           $movi="SALIDA";
                       }else{
                           $movi="MERMA";
                       }
    			       $prodm->nuevaGuia($num_guia,$items[$i]->idp,$items[$i]->cant,$movi,$items[$i]->obs,$usuario);
    			       if($items[$i]->tipo=="E"){
    			            //$actu->actu_stock_id($items[$i]->idp,$items[$i]->cant,1);
    			            $prods->grabaStockProducto($items[$i]->idp,$items[$i]->cant,$items[$i]->precio);
    			       }else{
    			           $actu->actu_stock_id($items[$i]->idp,$items[$i]->cant,0);
    			       } 
    			       $mov=new productosDao();
                       $qty=new productosDao();
                       if($items[$i]->tipo=="E"){
                           $movim="ENTRADA";
                       }elseif($items[$i]->tipo=="S"){
                           $movim="SALIDA";
                       }else{
                           $movim="MERMA";
                       }
                       $mov->registraMovimiento($items[$i]->idp,$items[$i]->cant,$qty->traeStock($items[$i]->idp),$movim,$num_guia,$items[$i]->obs);
    			    }
    			echo "OK";
            break;  
            
            case 'trae_movis':
               $id=$_POST['idp'];
               $tip_mov=$_POST['tipo_mov'];
               $desde=$_POST['fec_desde']." 00:00:00";
               $hasta=$_POST['fec_hasta']." 23:59:59";
               $filas="";
               $movi="";
               if($tip_mov==2){
                   $movi="ENVIO A FAENA";
               }elseif($tip_mov==3){
                   $movi="DEVOLUCIONES";
               }elseif($tip_mov==4){
                   $movi="ENTRADAS";
               }
               
               $mov=new productosDao();
               if($tip_mov==1){
                    $list_mov=$mov->listarMovimientos($movi,$id,$desde,$hasta,1);
               }else{
                   $list_mov=$mov->listarMovimientos($movi,$id,$desde,$hasta,2);
               }
               foreach($list_mov AS $pro){ 
                   switch($pro['tipo_mov']){
                       case 'ENVIO A FAENA':  
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
                       default:
                           $signo="";
                           $obs=$pro["obs"];
                       break;   
                   }
                    $filas .= '<tr><td style="text-align:center">'.date("d-m-Y H:i:s", strtotime($pro['createdAt'])).'</td><td>'.$pro['descripcion'].'</td><td style="text-align:center">'.$pro['tipo_mov'].$signo.'</td><td style="text-align:center">'.$pro['cantidad'].'</td><td style="text-align:center">'.$pro['stock'].'</td><td style="text-align:center">'.$obs.'</td></tr>';
               }
                echo trim($filas);
            break;
            
            case 'grabaEntradasMasivo':
                $items=json_decode($_POST['arr']);
                $codnoexiste=0;
                $creado=0;
                date_default_timezone_set('America/Santiago');
    			$num_guia="GMB-".date("Ymd-His");
    			$usuario=$_SESSION['id_user'];
                for ($i=0; $i < count($items); $i++) {
                   $prodm = new productosDao(); 
    		       $actu= new productosDao();
    		       $prod= new productosDao();
    		       $prods= new productosDao();
    		       $prods_ub= new productosDao();
    		       $id_producto=$prod->traeIdProducto($items[$i]->id);
    		       if($id_producto != 0){
        		       $prodm->nuevaGuia($num_guia,$id_producto,$items[$i]->cantidad,$items[$i]->tipo_movimiento,'Carga masiva '.date("d-m-Y"),$usuario);
        		       $prods->grabaStockProducto($id_producto,$items[$i]->cantidad,$items[$i]->precio_neto);
        		       $mov=new productosDao();
                       $qty=new productosDao();
                       $mov->registraMovimiento($id_producto,$items[$i]->cantidad,$qty->traeStock($id_producto),"ENTRADA",$num_guia,'Carga masiva '.date("d-m-Y"));
                       $prods_ub->grabaUbicacion($id_producto,$items[$i]->ubicacion);
                       $creado++;
    		       }else{
    		           $codnoexiste++;
    		       }
                }    
                echo "OK"."**".$creado."**".$codnoexiste."**".$i;    
            break;
            case 'trae_pedidos':
                $data = array();
                $prods = new productosDao();
                $prods2 = new productosDao();
                $list_prod = $prods->listarPedidos();  
                foreach($list_prod AS $pro){
                    $ver= '<button class="ver_pedido" id='.$pro['id'].' data-estado="'.$pro['estado'].'" data-faena="'.$pro['idFaena'].'" data-pedido="'.$pro['id'].'" data-toggle="modal" data-target="#visualizar_pedido"><i title="Ver detalle de pedido" style="cursor:pointer;font-size:18pt;color:green" class="fa fa fa-search"></i></button>';
                    $datos=array();
                $datos =   ['id_ped' => $pro['id'],
                            'faena' => $pro['nombre_faena'],
                            'fecha' => date('d/m/Y', strtotime($pro['createdAt'])),
                            'usuario' => $pro['nombre_usuario'],
                            'est' => $pro['estado'],
                            'acciones' => $ver
                            ];
                
                    $data[]=$datos;
                     }
                echo json_encode($data);
            break;
            case 'trae_detalle_pedido':
                $filas="";
                $fec_fin="";
                $id_pedido=$_POST['id'];
                $estado=$_POST['est'];
                $prods = new productosDao();
                if($estado=="CREADO"){
                    $detalle_pedido = $prods->traePedido($id_pedido);  
                    foreach($detalle_pedido AS $ped){
                        $prods2 = new productosDao();
                        $precio_neto=$prods2->traePrecio($ped['id']);
                        $acum_precio=0;
                        $acum_cantidad=0;
                        $precio_ponderado=0;
                        $total=0;
                        foreach($precio_neto AS $pn){
                            $acum_precio=$acum_precio+($pn['cantidad'] * $pn['precio_neto']); 
                            $acum_cantidad=(int)($acum_cantidad)+(int)($pn['cantidad']);    
                        }
                        if($acum_precio==0 && $acum_cantidad==0){
                            $prod_precio=new productosDao();
                            $precio_ponderado=$prod_precio->obtenerPrecio($ped['id']);
                        }else{
                            $precio_ponderado=round($acum_precio/$acum_cantidad);
                        }
                        $total=$precio_ponderado*$ped['cantidad'];
                        $prods2 = new productosDao();
                        $stock_prod = $prods2->verificaStock($ped['id']);
                        $filas .= '<tr><td>'.$ped['id'].'</td><td>'.$ped['descripcion'].'</td><td style="text-align:center;cursor:pointer" data-toggle="modal" data-target="#edit_cant" id="'.$ped['id'].'" class="stock" data-codigo="'.$ped['id'].'" data-stock="'.$stock_prod.'">'.$ped['cantidad'].'</td><td style="text-align:center" id="precio_'.$ped['id'].'">'.$precio_ponderado.'</td><td style="text-align:center" id="tot_'.$ped['id'].'">'.$total.'</td><td></td></tr>';
                    }
                }else{
                    $detalle_pedido = $prods->traePedidoReal($id_pedido);  
                    foreach($detalle_pedido AS $ped){
                        $total=$ped['precio_neto']*$ped['cantidad'];
                        $prods2 = new productosDao();
                        $stock_prod = $prods2->verificaStock($ped['id']);
                        $filas .= '<tr><td>'.$ped['id'].'</td><td>'.$ped['descripcion'].'</td><td style="text-align:center;">'.$ped['cantidad'].'</td><td style="text-align:center">'.$ped['precio_neto'].'</td><td style="text-align:center">'.$total.'</td><td></td></tr>';
                    }
                    $prods3 = new productosDao();
                    $fec_fin=date("d-m-Y H:i", strtotime($prods3->traeFecFinalizado($id_pedido)));
                }
                echo $filas."**".$fec_fin;
            break;
            case 'verifica_stock':
            $items=json_decode($_POST['arr']);
            $data = array();
            for ($i=0; $i < count($items); $i++) {
               $prod = new productosDao();
               $resp="";
		       $stock_prod=$prod->verificaStock($items[$i]->cod);
		       if($stock_prod >= $items[$i]->cantidad){
    		       $resp=1;
		       }else{
		           $resp=0;
		       }
		        $datos=array();
                $datos =   ['cod' => $items[$i]->cod,
                            'respuesta' => $resp
                            ];
                
                $data[]=$datos;
            }    
            echo json_encode($data); 
            break;
            
            case 'enviaPedido':
                $items=json_decode($_POST['arr']);
                for ($i=0; $i < count($items); $i++) {
                   $prod = new productosDao(); 
                   $prod2 = new productosDao(); 
    		       $prod->actuBodegaFaena($items[$i]->id_faena,$items[$i]->cod,$items[$i]->cantidad,$items[$i]->precio_neto);
    		       $cantidades=$prod2->traeStockPorOrden($items[$i]->cod);
    		       $falta=(int)$items[$i]->cantidad;
    		       foreach($cantidades AS $cant){ 
    		          if($cant['cantidad']>$falta && $falta>0){ 
    		              $prod3 = new productosDao();
    		              $prod3->actu_stock_sp($cant['id_sp'],$falta); 
    		              $falta=0; 
    		          }elseif($cant['cantidad']<$falta && $falta>0){ 
    		              $prod3 = new productosDao();
    		              $prod3->actu_stock_sp($cant['id_sp'],$cant['cantidad']);
    		              $falta=$falta-(int)$cant['cantidad'];
    		          }elseif($cant['cantidad']==$falta && $falta>0){ 
    		              $prod3 = new productosDao();
    		              $prod3->actu_stock_sp($cant['id_sp'],$cant['cantidad']);
    		              $falta=0;
    		          } 
    		       }
    		       $mov=new productosDao();
    		       $mov2=new productosDao();
                   $qty=new productosDao();
                   $qty2=new productosDao();
                   $prod4=new productosDao();
                   $prod5=new productosDao();
                   $prod5_2=new productosDao();
                   $prod6=new productosDao();
                   $idProd=$prod4->traeIdProducto($items[$i]->cod);
                   $mov->registraMovimiento($idProd,$items[$i]->cantidad,$qty->traeStock($idProd),"ENVIO A FAENA","PEDIDO ".$items[$i]->id_pedido,$prod5->traeNombreFaena($items[$i]->id_faena));
                   $mov2->registraMovimientoEnFaena($idProd,$items[$i]->cantidad,$qty2->traeStockFaena($items[$i]->cod,$items[$i]->id_faena),"ENVIO DESDE BODEGA","PEDIDO ".$items[$i]->id_pedido,$prod5_2->traeNombreFaena($items[$i]->id_faena),$items[$i]->id_faena);
                   $prod6->registraEnvioReal($items[$i]->id_pedido,$idProd,$items[$i]->cantidad,$items[$i]->precio_neto);
                   $pedido=$items[$i]->id_pedido;
                }  
                $prod6=new productosDao();
                $prod6->actuEstadoPedido($pedido);
                echo "OK";    
            break;
        }     
    
?>


    

   
        
    


