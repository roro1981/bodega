<?php

class faenasDao{
    
    private $valores = array();
    
 function listarFaenas(){
      $cons = new Conexion();
      
      $sql = "SELECT * from faenas where estado=1 order by nombre_faena";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function faeExiste($nombre_fae){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from faenas where nombre_faena='".$nombre_fae."'";
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
  function nuevaFaena($nom){
      $cons = new Conexion();
      $sql = "INSERT INTO faenas (nombre_faena,estado) "
              . "values('".$nom."',1)";
      $id = $cons->insert($sql);
     return $id;
  } 
  function creaProductosNuevaFaena($idFaena){
      $cons = new Conexion();
      $sql = "INSERT INTO bodega_faenas(codigo, cod_laudus, descripcion, stock, precio_neto, impuesto, impuestoId, categoria, estado, idFaena) 
              select id,cod_laudus,descripcion,stock,precio_neto,impuesto,impuestoId,categoria,estado,".$idFaena." from bodega_central";
      $id = $cons->insert($sql);
      return $id;
  }
  function verificaFaena($idf){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT * from bodega_faenas where stock > 0 and idFaena=".$idf;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
        return 1; 
      }else{
         return 0; 
      }
  }
  function elimFaena($idf){
      $cons = new Conexion();
      $sql = "update faenas set estado=0 where id=".$idf;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimProductosFaena($idf){
      $cons = new Conexion();
      $sql = "delete from bodega_faenas where idFaena=".$idf;
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarProductosActivosFaenas($id){
      $cons = new Conexion();
      
      $sql = "SELECT p.*,c.nombre_cat from bodega_faenas p 
      inner join categorias c on p.categoria=c.id 
      where p.estado=1 and idFaena=".$id." order by p.descripcion";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }     
}


