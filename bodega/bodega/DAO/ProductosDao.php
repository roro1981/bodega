<?php

class productosDao{
    
    private $valores = array();
    
  function listarProductosActivos(){
      $cons = new Conexion();
      
      $sql = "SELECT p.*,c.nombre_cat from bodega_central p 
      inner join categorias c on p.categoria=c.id 
      where p.estado=1 order by p.descripcion";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function listarImpuestos(){
      $cons = new Conexion();
      
      $sql = "SELECT * from impuestos where estado=1 order by id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
 function listarCategorias(){
      $cons = new Conexion();
      
      $sql = "SELECT * from categorias where estado=1 order by nombre_cat";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function productosConCategoria($id_cat){
      $cons = new Conexion();
      $sql = "SELECT * from bodega_central where categoria=".$id_cat;
      $cons->consult($sql);
      $productos=$cons->getNumResult();
      return $productos;        
  }
  function prodExiste($nombre_prod){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from bodega_central where descripcion='".$nombre_prod."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
        $id = $cons->getResult("id");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      } 
  }
  function laudusExiste($cod_laudus){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from bodega_central where cod_laudus='".$cod_laudus."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
        $id = $cons->getResult("id");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      } 
  }
  function nuevoProducto($cod,$desc,$pn,$imp,$impId,$cat,$ubic){
      $cons = new Conexion();
      $sql = "INSERT INTO bodega_central (cod_laudus,descripcion,precio_neto,impuesto,impuestoId,categoria,ubicacion,estado,creadoPor) values('".$cod."','".$desc."',".$pn.",".$imp.",".$impId.",".$cat.",'".$ubic."',1,'".$_SESSION['nombre']."')";
      $id = $cons->insert($sql);
      return $id;
  } 
  function nuevoProductoFaena($cod,$cod_laudus,$desc,$pn,$imp,$impId,$cat,$id_faena){
      $cons = new Conexion();
      $sql = "INSERT INTO bodega_faenas (codigo,cod_laudus,descripcion,precio_neto,impuesto,impuestoId,categoria,idFaena) values(".$cod.",'".$cod_laudus."','".$desc."',".$pn.",".$imp.",".$impId.",".$cat.",".$id_faena.")";
      $id = $cons->insert($sql);
      return $id;
  } 
  function listarProductoId($id){
      $cons = new Conexion();
      
      $sql = "SELECT * from bodega_central WHERE id=$id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function actuProducto($id,$cod,$desc,$pn,$imp,$impId,$cat,$ubic){
      $cons = new Conexion();
      $sql = "update bodega_central set cod_laudus='".$cod."', descripcion='".$desc."',precio_neto=".$pn.",impuesto=".$imp.",impuestoId=".$impId.",categoria=".$cat.",ubicacion='".$ubic."' where id=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function actuProductoFaena($id_prod,$cod,$desc,$pn,$imp,$impId,$cat,$idFaena){
      $cons = new Conexion();
      $sql = "update bodega_faenas set cod_laudus='".$cod."',descripcion='".$desc."',precio_neto=".$pn.",impuesto=".$imp.",impuestoId=".$impId.",categoria=".$cat." where codigo=".$id_prod." and idFaena=".$idFaena;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimProd($idp){
      $cons = new Conexion();
      $sql = "update bodega_central set estado=0 where id=".$idp;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimProdFaena($cod,$idFaena){
      $cons = new Conexion();
      $sql = "update bodega_faenas set estado=0 where codigo='".$cod."' and idFaena=".$idFaena;
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarCategoriasInactivas(){
      $cons = new Conexion();
      
      $sql = "SELECT * from categorias where estado='Inactivo' order by nom_cat";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function catExiste($nombre_cat){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from categorias where nombre_cat='".$nombre_cat."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("id");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function nuevaCategoria($nom){
      $cons = new Conexion();
      $sql = "INSERT INTO categorias (nombre_cat,estado) "
              . "values('".$nom."',1)";
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarCategoriaId($id){
      $cons = new Conexion();
      
      $sql = "SELECT * from categorias where id=$id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function actuCat($nueva_cat,$antigua_cat){
      $cons = new Conexion();
      $sql = "update categorias set nombre_cat='".$nueva_cat."' where nombre_cat='".$antigua_cat."'";
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimCat($idc){
      $cons = new Conexion();
      $sql = "update categorias set estado=0 where id=".$idc;
      $id = $cons->insert($sql);
     return $id;
  } 
  
  function traeCodigoProducto($id_prod){
      $cons = new Conexion();
      $sql = "SELECT codigo from bodega_central where id=".$id_prod;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          $codigo = $cons->getResult("codigo");
      }else{
          $codigo=0;
      }
      return $codigo;        
  }
  function traeIdProducto($cod_prod){
      $cons = new Conexion();
      $sql = "SELECT id from bodega_central where id=".$cod_prod;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          $id = $cons->getResult("id");
      }else{
          $id=0;
      }
      return $id;        
  }
  function traeStock($id_prod){
      $cons = new Conexion();
      $sql = "SELECT sum(cantidad) as stock from stock_productos where id_prod=".$id_prod;
      $cons->consult($sql);
      if($cons->getResult("stock") != null){
          $valor = $cons->getResult("stock");
      }else{
          $valor=0;
      }
      return $valor;        
  }
  function traeStockFaena($cod_prod,$id_faena){
      $cons = new Conexion();
      $sql = "SELECT stock from bodega_faenas where codigo='".$cod_prod."' and idFaena=".$id_faena;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          $valor = $cons->getResult("stock");
      }else{
          $valor=0;
      }
      return $valor;        
  }
  function nuevaGuia($numg,$idp,$cant,$tipo,$obs,$usuariom){
      $cons = new Conexion();
      $sql = "INSERT INTO guia_movimientos_bodega (num_guia,id_prod,cantidad,tipo_movi,obs,usuario_mov) "
              . "values('".$numg."',".$idp.",".$cant.",'".$tipo."','".$obs."',".$usuariom.")";
      $id = $cons->insert($sql);
     return $id;
  } 
  function registraMovimiento($idp,$cant,$stock,$tipo,$numdoc,$obs){
      $cons = new Conexion();
      $sql = "INSERT INTO historial_movs (id_prod,cantidad,stock,tipo_mov,num_doc,obs) "
              . "values(".$idp.",".$cant.",".$stock.",'".$tipo."','".$numdoc."','".$obs."')";
      $id = $cons->insert($sql);
     return $id;
  } 
  function registraMovimientoEnFaena($idp,$cant,$stock,$tipo,$numdoc,$obs,$id_faena){
      $cons = new Conexion();
      $sql = "INSERT INTO historial_movs_faenas (id_prod,cantidad,stock,tipo_mov,num_doc,obs,idFaena) "
              . "values(".$idp.",".$cant.",".$stock.",'".$tipo."','".$numdoc."','".$obs."',".$id_faena.")";
      $id = $cons->insert($sql);
     return $id;
  } 
  function actu_stock_id($id,$cant,$tipo){
      $cons = new Conexion();
      if($tipo==1){
        $sql = "update bodega_central set stock=stock+".$cant." where id=".$id;
      }else{
        $sql = "update bodega_central set stock=stock-".$cant." where id=".$id;
      }
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarMovimientos($tipo,$id_prod,$fdesde,$fhasta,$consulta){
      $cons = new Conexion();
      if($consulta==1){ //todo
        $sql = "SELECT h.id_prod,h.cantidad,h.stock,h.tipo_mov,h.createdAt,h.num_doc,h.obs,p.descripcion from historial_movs h inner join bodega_central p on h.id_prod=p.id where h.id_prod=".$id_prod." and h.createdAt between '".$fdesde."' and '".$fhasta."' order by h.createdAt desc";
      }else{   
        $sql = "SELECT h.id_prod,h.cantidad,h.stock,h.tipo_mov,h.createdAt,h.num_doc,h.obs,p.descripcion from historial_movs h inner join bodega_central p on h.id_prod=p.id where h.id_prod=".$id_prod." and h.createdAt between '".$fdesde."' and '".$fhasta."' and h.tipo_mov='".$tipo."' order by h.createdAt desc";
      }
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function obtenerPrecio($idprod){
      $cons = new Conexion();
      
      $sql = "SELECT precio_neto FROM bodega_central WHERE id=".$idprod;
      $cons->consult($sql);
      $valorp=0;
      if($cons->getNumResult()>0){
          do{ 
              $valorp = $cons->getResult("precio_neto");
            }while($cons->nextInd());
      }
      
      return $valorp;
  } 
  function grabaStockProducto($idp,$cant,$neto){
      $cons = new Conexion();
      $sql = "INSERT INTO stock_productos (id_prod,cantidad,precio_neto) values(".$idp.",".$cant.",".$neto.")";
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarPedidos(){
      $cons = new Conexion();
      
      $sql = "SELECT m.id,f.nombre_faena,f.id as idFaena,m.createdAt,uf.nombre_usuario,m.estado FROM movimientos m 
             inner join usuarios_faenas uf on m.usuario=uf.id
             inner join faenas f on f.id=m.faena order by m.estado desc,m.createdAt desc";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function traePedido($id_ped){
      $cons = new Conexion();
      
      $sql = "SELECT b.id,b.descripcion,m.cantidad FROM movimientos_detalle m inner join bodega_central b on m.idProd=b.id where m.idMov=".$id_ped;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function traePedidoReal($id_ped){
      $cons = new Conexion();
      
      $sql = "SELECT b.id,b.descripcion,er.cantidad,er.precio_neto FROM envio_real_a_faena er inner join bodega_central b on er.id_prod=b.id where er.id_pedido=".$id_ped;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function verificaStock($cod_prod){
      $cons = new Conexion();
      $stock=0;
      $sql = "SELECT sum(sp.cantidad) as stock from stock_productos sp inner join bodega_central bc on sp.id_prod=bc.id where bc.id=".$cod_prod;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
         $stock = $cons->getResult("stock");
      }
      return $stock;  
  }
  function traePrecio($idProd){
      $cons = new Conexion();
      $stock=0;
      $sql = "SELECT cantidad,precio_neto from stock_productos where id_prod=".$idProd;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores; 
  }
  function traeImpuesto($idImp){
      $cons = new Conexion();
      $impu=0;
      $sql = "SELECT valor_imp from impuestos where id=".$idImp;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
         $impu = $cons->getResult("valor_imp");
      }
      return $impu;  
  }
  function actuBodegaFaena($id_faena,$cod_prod,$cant,$pneto){
      $cons = new Conexion();
      $sql = "update bodega_faenas set stock=stock+".$cant.",precio_neto=".$pneto." where codigo='".$cod_prod."' and idFaena=".$id_faena;
      $id = $cons->insert($sql);
     return $id;
  }
  function traeStockPorOrden($cod_prod){
      $cons = new Conexion();
      $sql = "SELECT sp.id as id_sp,sp.cantidad FROM stock_productos sp inner join bodega_central b on sp.id_prod=b.id where b.id=".$cod_prod." order by fec_ingreso";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
  function actu_stock_sp($id,$cant){
      $cons = new Conexion();
        $sql = "update stock_productos set cantidad=cantidad-".$cant." where id=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function traeNombreFaena($id_faena){
      $cons = new Conexion();
      $nomFaena="";
      $sql = "SELECT nombre_faena from faenas where id=".$id_faena;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
         $nomFaena = $cons->getResult("nombre_faena");
      }
      return $nomFaena;  
  }
  function actuEstadoPedido($id_pedido){
      $cons = new Conexion();
      $sql = "update movimientos set estado='FINALIZADO' where id=".$id_pedido;
      $id = $cons->insert($sql);
     return $id;
  }
  function registraEnvioReal($idp,$idProd,$cantidad,$pneto){
      $cons = new Conexion();
      $sql = "INSERT INTO envio_real_a_faena (id_pedido,id_prod,cantidad,precio_neto) "
              . "values(".$idp.",".$idProd.",".$cantidad.",".$pneto.")";
      $id = $cons->insert($sql);
     return $id;
  } 
  function traeFecFinalizado($id_ped){
      $cons = new Conexion();
      $nomFaena="";
      $sql = "SELECT modifiedAt from movimientos where id=".$id_ped;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
         $nomFaena = $cons->getResult("modifiedAt");
      }
      return $nomFaena;  
  }
  function grabaUbicacion($idp,$ubicacion){
      $cons = new Conexion();
      $sql = "update bodega_central set ubicacion='".$ubicacion."' where id=".$idp;
      $id = $cons->insert($sql);
     return $id;
  }
}


