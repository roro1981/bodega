<?php

class usersDao{
    
    private $valores = array();
   
 
  function traeUsuario($usu,$pass){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT * from usuarios where nombre_usuario='".$usu."' and pass_usuario='".md5($pass)."' and estado_usuario=1";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
        $id = $cons->getResult("id");
      }
      if($id){
        return $id;  
      }else{
         return 0; 
      }
      
  }
  
  function verifica_acceso_modulo($id_user,$modulo){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT * from permisos_usuarios_modulos where idUser=".$id_user." and idMod=".$modulo;
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
  function verifica_acceso_submodulo($id_user,$submodulo){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT * from permisos_usuarios_submodulos where idUser=".$id_user." and idSmod=".$submodulo;
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
   function usuarioExiste($nombre_usu){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from usuarios where nombre_usuario='".$nombre_usu."'";
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
  function usuarioFaenaExiste($nombre_usu){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id from usuarios_faenas where nombre_usuario='".$nombre_usu."'";
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
  function nuevoUsuario($usu,$tipo,$passw){
      $cons = new Conexion();
      $sql = "INSERT INTO usuarios (nombre_usuario,pass_usuario,tipo_usuario,estado_usuario) "
              . "values('".$usu."','".md5($passw)."',".$tipo.",1)";
      $id = $cons->insert($sql);
     return $id;
  } 
  function nuevoUsuarioFaena($usu,$faena,$passw){
      $cons = new Conexion();
      $sql = "INSERT INTO usuarios_faenas (nombre_usuario,pass_usuario,idFaena,estado) "
              . "values('".$usu."','".md5($passw)."',".$faena.",1)";
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarUsuarios(){
      $cons = new Conexion();
      $sql = "SELECT * from usuarios where estado_usuario=1 order by nombre_usuario";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;       
  }
  function listarUsuariosFaenas(){
      $cons = new Conexion();
      $sql = "SELECT uf.*,f.nombre_faena from usuarios_faenas uf inner join faenas f on uf.idFaena=f.id where uf.estado=1 order by uf.nombre_usuario  ";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;       
  }
  function actuUsuario($tipo,$passw,$usu,$accion){
      $cons = new Conexion();
      date_default_timezone_set('America/Santiago');
      $hoy=date('Y-m-d H:i:s');
      if($accion==1){
        if($usu=='rpanes'){
            $tipo=0;
        }  
        $sql = "update usuarios set tipo_usuario=".$tipo.",pass_usuario='".md5($passw)."',modified_usuario='".$hoy."' where nombre_usuario='".$usu."'";
      }else{
        $sql = "update usuarios set estado_usuario=0,modified_usuario='".$hoy."' where id='".$usu."'";  
      }    
      $id = $cons->insert($sql);
      return $id;
  }
  function actuUsuarioFaena($faena,$passw,$usu,$accion){
      $cons = new Conexion();
      date_default_timezone_set('America/Santiago');
      $hoy=date('Y-m-d H:i:s');
      if($accion==1){
        $sql = "update usuarios_faenas set idFaena=".$faena.",pass_usuario='".md5($passw)."',modified_usuario='".$hoy."' where nombre_usuario='".$usu."'";
      }else{
        $sql = "update usuarios_faenas set estado=0,modified_usuario='".$hoy."' where id='".$usu."'";  
      }    
      $id = $cons->insert($sql);
      return $id;
  }
  function listar_modulos_submodulos(){
      $cons = new Conexion();
      $sql = "SELECT m.id as modulo_id,m.descripcion_mod, s.id as submodulo_id,s.descripcion_smod from modulos m inner join submodulos s on m.id=s.idMod where m.estado=1";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;       
  }
  function verificaModulo($id_user,$idModulo){
      $id="";
      $cons = new Conexion();
      $sql = "SELECT * FROM permisos_usuarios_modulos where idUser = ".$id_user." and idMod=".$idModulo;
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
  function verificaSubmodulo($id_user,$idSubmodulo){
      $id="";
      $cons = new Conexion();
      $sql = "SELECT * FROM permisos_usuarios_submodulos where idUser = ".$id_user." and idSmod=".$idSubmodulo;
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
  function borraModulosUsuario($id_usu){
      $cons = new Conexion();
      $sql = "delete from permisos_usuarios_modulos where idUser=".$id_usu;
      $id = $cons->insert($sql);
     return $id;
  } 
  function borraSubmodulosUsuario($id_usu){
      $cons = new Conexion();
      $sql = "delete from permisos_usuarios_submodulos where idUser=".$id_usu;
      $id = $cons->insert($sql);
     return $id;
  } 
 function grabaModulosUsuario($id_usu,$idMod){
      $cons = new Conexion();
      $sql = "INSERT INTO permisos_usuarios_modulos (idUser,idMod) values(".$id_usu.",".$idMod.")";
      $id = $cons->insert($sql);
     return $id;
  } 
  function grabaSubmodulosUsuario($id_usu,$idSmod){
      $cons = new Conexion();
      $sql = "INSERT INTO permisos_usuarios_submodulos (idUser,idSmod) values(".$id_usu.",".$idSmod.")";
      $id = $cons->insert($sql);
     return $id;
  } 
}


