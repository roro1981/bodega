<?php
/*error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);*/
session_start();
require '../includes/Conexion.php';
require '../DAO/ProductosDao.php';
require '../includes/functions.php';
require '../resources/pdf/fpdf.php';
$opt = 0; if (isset($_POST['opt'])){ $opt=$_POST['opt']; }
        switch ($opt) {
          case 'trae_prods':
                $data = array();
                $prod=new productosDao();
                $list_produ = $prod->listarProductosActivos();  
                foreach($list_produ AS $pro){
                    $prod2=new productosDao();
                    $opc= "<input type='text' id='".$pro['id']."' data-codigo='".$pro['id']."' data-nombre='".$pro['descripcion']."' data-stock='".$prod2->traeStock($pro['id'])."' onchange='agregarDetalle(".$pro['id'].")' style='width:50px' class='incant form-control' >";
                    $datos=array();
                    $prod3=new productosDao();
                    $datos = ['opciones' => $opc,
                                'nombre' => $pro['descripcion'],
                                'cat'    => $pro['nombre_cat'],
                                'codigo' => $pro['id'],
                                'stock'  => $prod3->traeStock($pro['id'])
                            ];
                    
                    $data[]=$datos;
                     }
                echo json_encode($data);
            break; 
            case 'trae_prods_faena':
                $data = array();
                $id_faena=$_POST['faena'];
                $prod=new productosDao();
                $list_produ = $prod->listarProductosActivosFaena($id_faena);  
                foreach($list_produ AS $pro){
                    $opc= "<input type='text' id='".$pro['id']."' data-codigo='".$pro['codigo']."' data-nombre='".$pro['descripcion']."' data-stock='".$pro['stock']."' onchange='agregarDetalle(".$pro['id'].")' style='width:50px' class='incant form-control' >";
                    $datos=array();
                    $datos = ['opciones' => $opc,
                                'nombre' => $pro['descripcion'],
                                'cat'    => $pro['nombre_cat'],
                                'codigo' => $pro['codigo'],
                                'stock'  => $pro['stock']
                            ];
                    
                    $data[]=$datos;
                     }
                echo json_encode($data);
            break;
            case 'grabaPedido':
				$DATA=json_decode($_POST['arr']);
				$faena=$_SESSION['id_faena'];
				if (isset($DATA)){
			    $prod=new productosDao();
			    $id_pedido=$prod->grabaPedido($faena,$_SESSION['id_user']);
				for ($i=0; $i < count($DATA); $i++) {
				    $prod2=new productosDao();
				    $prod3=new productosDao();
					$prod2->grabaDetallePedido($id_pedido,$prod3->traeIdProducto($DATA[$i]->cod),$DATA[$i]->cantidad);
				}
				
				echo "OK_".$id_pedido;
				}else{
					echo "NO";
				}
			break;
			case 'ticket':
				$prod=new productosDao();
			    $datos_voucher=$prod->traeDatosVoucher($_POST['ticket']);
                    if ($datos_voucher != 0){
						$fecha=date("d-m-Y",strtotime($datos_voucher[0]['createdAt']));
						$hora=date("H:i:s",strtotime($datos_voucher[0]['createdAt']));
					}
			
        			    $ancho=80;
        				
        			if($ancho==80){
    					$dimensiones=array ($ancho,297); 
    					$pdf = new FPDF('P','mm',$dimensiones);
    					$pdf->SetMargins(0, 5 , 0);
    					$pdf->SetTopMargin(20);
    					$pdf->AddPage();
    					$pdf->Image('../assets/img/logo.png', 20 ,1, 40 , 20,'PNG', '');
    					$pdf->SetXY(1, 25);
    					$pdf->SetFont('Times','U',15);
    					$pdf->Cell($ancho,5,'Voucher '.$_POST['ticket'],0,1,'C');
    					$pdf->SetFont('Times','B',10);
    					$pdf->SetXY(0, 33);
    					$pdf->Cell($ancho,5,"Fecha: ".$fecha,0,1);
    					$pdf->SetXY(0, 33);
    					$pdf->Cell($ancho,5,"Hora: ".$hora,0,1,'R');
    					$pdf->SetFont('Times','',10);	
    					$prod2 = new productosDao();
                        $list_detalle_voucher = $prod2->detalleVoucher($_POST['ticket']); 
    					$fin = $pdf->GetY();
    					$pdf->Line(0,$fin,$ancho,$fin);
                        $tot_productos=0;
                        $items=0;
                        foreach($list_detalle_voucher AS $dat){
        					$fin = $pdf->GetY();
        					$pdf->SetXY(0, $fin+2);
        					$pdf->MultiCell($ancho,5,'Cantidad: '.$dat['cantidad']);
        					$fin = $pdf->GetY();
        					$pdf->SetXY(30, $fin+1);
        					$fin = $pdf->GetY();
        					$pdf->SetXY(0, $fin);
        					$pdf->MultiCell($ancho,5,'Codigo: '.$dat['id']);
        					$fin = $pdf->GetY();
        					$pdf->SetXY(0, $fin);
        					$pdf->MultiCell($ancho,5,utf8_decode('Descripción: '.$dat['descripcion']));
        					$fin = $pdf->GetY();
        					$pdf->Line(0,$fin+2,$ancho,$fin+2);
        					$items++;
        					$tot_productos=$tot_productos+intval($dat['cantidad']);
    					}
    					
    					$fin = $pdf->GetY();
    					$pdf->SetXY(0, $fin+2);
    					$pdf->SetFont('Times','B',12);
    					$pdf->Cell($ancho,5,'TOTAL ITEMS: '.$items,0,1,'C');
    					$fin = $pdf->GetY();
    					//$linea=$linea+5;
    					$pdf->SetXY(0, $fin+2);
    					$pdf->SetFont('Times','B',12);
    					$pdf->Cell($ancho,5,'TOTAL PRODUCTOS: '.number_format($tot_productos, 0, '', '.'),0,1,'C');
        			}
					$pdf->Output('../tickets/'.$_POST['ticket'].'.pdf','f');
					chmod($_SERVER["DOCUMENT_ROOT"]."/tickets/".$_POST['ticket'].".pdf",0777);
			break;
        }
?>


    

   
        
    


