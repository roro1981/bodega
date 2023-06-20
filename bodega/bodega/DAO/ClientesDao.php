<?php

class clientesDao{
    
    private $valores = array();
    
  function listarClientes($app){
      $cons = new Conexion();
      
      $sql = "SELECT * from personas WHERE app like '%$app%' and tipo_persona='Cliente' and estado='Activo'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarPersonasId($id){
      $cons = new Conexion();
      
      $sql = "SELECT * from personas WHERE idpersona=$id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarClientesDlvId($id){
      $cons = new Conexion();
      
      $sql = "SELECT * from clientes_ped WHERE id_cliente=$id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarClientesActivos(){
      $cons = new Conexion();
      
      $sql = "SELECT p.*,c.nom_comuna from personas p inner join comunas c on p.comuna=c.idcomuna WHERE (p.tipo_persona='Cliente' or p.tipo_persona='dual') and estado='Activo' order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarClientesInactivos(){
      $cons = new Conexion();
      
      $sql = "SELECT p.*,c.nom_comuna from personas p inner join comunas c on p.comuna=c.idcomuna WHERE p.tipo_persona='Cliente' and estado='Inactivo' order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarClientesInactivosDlv(){
      $cons = new Conexion();
      
      $sql = "SELECT * from clientes_ped WHERE estado=0 order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarComunas(){
      $cons = new Conexion();
      
      $sql = "SELECT * from comunas order by nom_comuna";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function idComuna($nombre){
      $cons = new Conexion();
      
      $sql = "SELECT id from comunas where nom_comuna='".$nombre."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $idcomu = $cons->getResult("id");
      }
      return $idcomu;
  }
  function nomComuna($id){
      $cons = new Conexion();
      
      $sql = "SELECT nom_comuna from comunas where idcomuna=".$id;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $comu = $cons->getResult("nom_comuna");
      }
      return $comu;
  }
 function nuevoCliente($rut,$nom,$app,$apm,$dir,$com,$fon,$mail,$cupo,$ilimitado,$usuario){
      $cons = new Conexion();
      $sql = "INSERT INTO personas (rut_persona,nombres,app,apm,direccion,comuna,fono,email,tipo_persona,cupo,cupo_ilimitado,estado,fecha_ing,usuario_crea) "
              . "values('".$rut."','".$nom."','".$app."','".$apm."','".$dir."',".$com.",'".$fon."','".$mail."','Cliente',".$cupo.",".$ilimitado.",'Activo',now(),'".$usuario."')";
      $id = $cons->insert($sql);
     return $id;
  } 
  function nuevoClientedlv($rut,$nom,$app,$apm,$fnac,$dir,$num,$com,$fono,$mail,$obs,$usuario){
      $cons = new Conexion();
      $sql = "INSERT INTO clientes_ped (rut,nombres,ap_pat,ap_mat,fec_nac,direccion,numero,comuna,fono,correo,obs,estado,fec_creacion,usuario_crea) "
              . "values('".$rut."','".$nom."','".$app."','".$apm."','".$fnac."','".$dir."',".$num.",".$com.",'".$fono."','".$mail."','".$obs."',1,now(),'".$usuario."')";
      $id = $cons->insert($sql);
     return $id;
  } 
  function actuCliente($rut,$nom,$app,$apm,$dir,$com,$fon,$mail,$cupo,$ilimitado,$usuario){
      $cons = new Conexion();
      $sql = "update personas set nombres='".$nom."',app='".$app."',apm='".$apm."',direccion='".$dir."',comuna=".$com.",fono='".$fon."',email='".$mail."',cupo=".$cupo.",cupo_ilimitado=".$ilimitado.",ult_actu=now(),usuario_actu='".$usuario."' where rut_persona='".$rut."' and tipo_persona='Cliente'";
      $id = $cons->insert($sql);
     return $id;
  } 
  function actuClientedlv($id,$rut,$nom,$app,$apm,$fnac,$dir,$num,$com,$fono,$mail,$obs,$usuario){
      $cons = new Conexion();
      $sql = "update clientes_ped set rut='".$rut."',nombres='".$nom."',ap_pat='".$app."',ap_mat='".$apm."',fec_nac='".$fnac."',direccion='".$dir."',numero=".$num.",comuna=".$com.",fono='".$fono."',correo='".$mail."',obs='".$obs."',fec_actu=now(),usuario_actu='".$usuario."' where id_cliente=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimCliente($idp,$usuario){
      $cons = new Conexion();
      $sql = "update personas set estado='Inactivo',fec_elim=now(),usuario_elim='".$usuario."' where idpersona=".$idp." and tipo_persona='Cliente'";
      $id = $cons->insert($sql);
     return $id;
  }
  function elimClientedlv($id,$usuario){
      $cons = new Conexion();
      $sql = "update clientes_ped set estado=0,fec_elim=now(),usuario_elim='".$usuario."' where id_cliente=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimClienteDual($idc,$usuario){
      $cons = new Conexion();
      $sql = "update personas set tipo_persona='Prov',ult_actu=now(),usuario_actu='".$usuario."' where idpersona=".$idc;
      $id = $cons->insert($sql);
     return $id;
  }
  function activaCliente($idp,$usuario){
      $cons = new Conexion();
      $sql = "update personas set estado='Activo',fec_elim=null,usuario_elim=null,ult_actu=now(),usuario_actu='".$usuario."' where idpersona=".$idp." and tipo_persona='Cliente'";
      $id = $cons->insert($sql);
     return $id;
  } 
  function activaClienteDlv($id,$usuario){
      $cons = new Conexion();
      $sql = "update clientes_ped set estado=1,fec_elim=null,usuario_elim=null,fec_actu=now(),usuario_actu='".$usuario."' where id_cliente=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
 /* function rutExistecli($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT idpersona from personas where rut_persona='".$rut."' and tipo_persona='Cliente'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("idpersona");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }*/
  function buscarClientes($texto){
      $cons = new Conexion();
      
      $sql = "SELECT * from personas where ((rut_persona like '%".$texto."%' or nombres like '%".$texto."%' or app like '%".$texto."%' or apm like '%".$texto."%' or direccion like '%".$texto."%' or fono like '%".$texto."%') and (estado='Activo')) order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function totalClientes(){
      $cons = new Conexion();
      $tot=0;
      $sql = "SELECT count(idpersona) as total from personas where tipo_persona='Cliente' and estado='Activo'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $tot = $cons->getResult("total");
      }
      
        return $tot;  
  }
  function totalClientesElim(){
      $cons = new Conexion();
      $tot=0;
      $sql = "SELECT count(idpersona) as total from personas where tipo_persona='Cliente' and estado='Inactivo'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $tot = $cons->getResult("total");
      }
      
        return $tot;  
  }
  function rutExistecli($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT idpersona from personas where rut_persona='".$rut."' and (tipo_persona='Cliente' or tipo_persona='dual')";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("idpersona");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function fonoExistecli($fono){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id_cliente from clientes_ped where fono='".$fono."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("id_cliente");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function rutExistecliDlv($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id_cliente from clientes_ped where rut='".$rut."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("id_cliente");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function rutExistecli2($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT idpersona from personas where rut_persona='".$rut."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("idpersona");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function rutExistecli3($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT idpersona from personas where rut_persona='".$rut."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("idpersona");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function creaDual($rut,$usuario){
      $cons = new Conexion();
      $sql = "update personas set nombres=SUBSTRING_INDEX(razon_social,' ',1),app=SUBSTRING(SUBSTRING_INDEX(razon_social,' ',2),instr(SUBSTRING_INDEX(razon_social,' ',2),' ')+1, CHAR_LENGTH(SUBSTRING_INDEX(razon_social,' ',2))),apm=SUBSTRING_INDEX(razon_social,' ',-1),tipo_persona='dual',cupo=0,cupo_ilimitado=1,ult_actu=now(),usuario_actu='".$usuario."' where rut_persona='".$rut."'";
      $id = $cons->insert($sql);
     return $id;
  }
   function esDual($idc){
      $id="";
      $cons = new Conexion();
      $sql = "SELECT idpersona from personas where idpersona=".$idc." and tipo_persona='dual'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("idpersona");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
      
  }
  function nom_cliente($idc){
      $cons = new Conexion();
      $cliente="";
      $sql = "SELECT * from personas where idpersona=".$idc;
      $cons->consult($sql);
      if($cons->getNumResult()>0){
        $tipo = $cons->getResult("tipo_persona");
        if($tipo=="Cliente"){
            $cliente=$cons->getResult("nombres")." ".$cons->getResult("app")." ".$cons->getResult("apm");
        }else{
            $cliente=$cons->getResult("razon_social");
        }      
      }
      return $cliente;
  }
  function listarClientesActivosDeli(){
      $cons = new Conexion();
      
      $sql = "SELECT cp.*,c.nom_comuna from clientes_ped cp inner join comunas c on c.idcomuna=cp.comuna WHERE estado=1 order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function listarRepartidores(){
      $cons = new Conexion();
      
      $sql = "SELECT * from repartidores WHERE estado=1 order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function rutExisterep($rut){
      $cons = new Conexion();
      $id="";
      $sql = "SELECT id_repartidor from repartidores where rut_rep='".$rut."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $id = $cons->getResult("id_repartidor");
      }
      if($id){
        return 1;  
      }else{
         return 0; 
      }
  }
  function nuevoRepartidor($rut,$nom,$ap,$dir,$com,$fono,$t1,$t2,$pat,$usuario){
      $cons = new Conexion();
      $sql = "INSERT INTO repartidores (rut_rep,nombres,apellidos,direccion,comuna,fono,tarifa1,tarifa2,patente,estado,fec_crea,usuario_crea) "
              . "values('".$rut."','".$nom."','".$ap."','".$dir."',".$com.",'".$fono."',".$t1.",".$t2.",'".$pat."',1,now(),'".$usuario."')";
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarRepartidorId($id){
      $cons = new Conexion();
      
      $sql = "SELECT * from repartidores WHERE id_repartidor=$id";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function actuRepartidor($id,$rut,$nom,$ap,$dir,$com,$fono,$t1,$t2,$pat,$usuario){
      $cons = new Conexion();
      $sql = "update repartidores set rut_rep='".$rut."',nombres='".$nom."',apellidos='".$ap."',direccion='".$dir."',comuna=".$com.",comuna=".$com.",fono='".$fono."',tarifa1=".$t1.",tarifa2=".$t2.",patente='".$pat."',fec_mod=now(),usuario_mod='".$usuario."' where id_repartidor=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function elimRepartidor($id,$usuario){
      $cons = new Conexion();
      $sql = "update repartidores set estado=0,fec_elim=now(),usuario_elim='".$usuario."' where id_repartidor=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarRepartidoresInactivos(){
      $cons = new Conexion();
      
      $sql = "SELECT * from repartidores WHERE estado=0 order by nombres";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
  function activaRepartidor($id,$usuario){
      $cons = new Conexion();
      $sql = "update repartidores set estado=1,fec_elim=null,usuario_elim=null,fec_mod=now(),usuario_mod='".$usuario."' where id_repartidor=".$id;
      $id = $cons->insert($sql);
     return $id;
  } 
  function listarDatosCorp($item){
      $cons = new Conexion();
      $sql = "SELECT * from corporativo where item='".$item."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
              $it = $cons->getResult("descrip_item");
      }
      return $it;
  }
  function actuCorporativo($item,$desc){
      $cons = new Conexion();
      $sql = "update corporativo set descrip_item='".$desc."' where item='".$item."'";
      $id = $cons->insert($sql);
     return $id;
  } 
  function clienteDeliFono($fono){
      $cons = new Conexion();
      
      $sql = "SELECT c.*,co.nom_comuna from clientes_ped c inner join comunas co on c.comuna=co.idcomuna WHERE fono='".$fono."'";
      $cons->consult($sql);
      if($cons->getNumResult()>0){
          do{ 
              $this->valores[] = $cons->getResult("*");
            }while($cons->nextInd());
      }
      return $this->valores;
  }
}


