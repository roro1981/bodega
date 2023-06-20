<?php

class usersDao{
    
    private $valores = array();
   
 
  function traeUsuario($usu,$pass){
      $cons = new Conexion();
      $sql = "SELECT uf.*,f.nombre_faena,f.id as idfa from usuarios_faenas uf inner join faenas f on uf.idFaena=f.id where uf.nombre_usuario='".$usu."' and uf.pass_usuario='".md5($pass)."' and uf.estado=1 and f.estado=1";
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

}


