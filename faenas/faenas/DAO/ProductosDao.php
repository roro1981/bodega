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
  function listarProductosActivosFaena($id_faena){
      $cons = new Conexion();
      $sql = "SELECT f.*,c.nombre_cat from bodega_faenas f 
      inner join categorias c on f.categoria=c.id 
      where f.estado=1 and f.idFaena=".$id_faena." order by f.descripcion";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
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
  function grabaPedido($id_faena,$usuario_id){
      $cons = new Conexion();
      $sql = "INSERT INTO movimientos (faena,usuario) values(".$id_faena.",".$usuario_id.")";
      $id = $cons->insert($sql);
      return $id;
  } 
  function grabaDetallePedido($id_ped,$id_prod,$cantidad){
      $cons = new Conexion();
      $sql = "INSERT INTO movimientos_detalle (idMov,idProd,cantidad) values(".$id_ped.",".$id_prod.",".$cantidad.")";
      $id = $cons->insert($sql);
      return $id;
  }
  function traeDatosVoucher($id_voucher){
      $cons = new Conexion();
      $sql = "SELECT * from movimientos where id=".$id_voucher;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
          return $this->valores;  
      }else{
          return 0;
      }
  }
  function detalleVoucher($id_voucher){
      $cons = new Conexion();
      $sql = "SELECT md.*,bc.id,bc.descripcion from movimientos_detalle md inner join bodega_central bc on md.idProd=bc.id where md.idMov=".$id_voucher;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  } 
}


