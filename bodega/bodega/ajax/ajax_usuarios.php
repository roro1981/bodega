<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
require '../includes/Conexion.php';
require '../DAO/UsersDao.php';
require '../includes/functions.php';
$opt = 0; if (isset($_POST['opt'])){ $opt=$_POST['opt']; }
    switch ($opt) {
        case 'crea_user':
            $usr = $_POST['user'];
            $pass=$_POST['pass'];
            $tipo=$_POST['tipo'];
            $usuario = new usersDao();
            $usuario2 = new usersDao();
            $verifica = $usuario->usuarioExiste($usr);
            if($verifica==1){
                $creado="existe";
            }else{
	          $creado="no";		
   
             $crea_usu = $usuario2->nuevoUsuario(strtolower($usr),$tipo,$pass);
            
            if($crea_usu){
               $creado= 1;
            }
            }
            echo $creado;    
        break;
        case 'crea_user_faena':
            $usr = $_POST['user'];
            $pass=$_POST['pass'];
            $faena=$_POST['faena'];
            $usuario = new usersDao();
            $usuario2 = new usersDao();
            $verifica = $usuario->usuarioFaenaExiste($usr);
            if($verifica==1){
                $creado="existe";
            }else{
	          $creado="no";		
   
             $crea_usu = $usuario2->nuevoUsuarioFaena(strtolower($usr),$faena,$pass);
            
            if($crea_usu){
               $creado= 1;
            }
            }
            echo $creado;    
        break;
         case 'edita_user':
            $usr = $_POST['user'];
            $pass=$_POST['pass'];
            $tipo=$_POST['tipo'];
            $usuario = new usersDao();
            $actualizado="no";
            
            $actu_usu = $usuario->actuUsuario($tipo,$pass,$usr,1);
            
            if($actu_usu==0){
               $actualizado= 1;
            }
            
            echo $actualizado; 
        break;
        case 'edita_user_faena':
            $usr = $_POST['user'];
            $pass=$_POST['pass'];
            $faena=$_POST['faena'];
            $usuario = new usersDao();
            $actualizado="no";
            
            $actu_usu = $usuario->actuUsuarioFaena($faena,$pass,$usr,1);
            
            if($actu_usu==0){
               $actualizado= 1;
            }
            
            echo $actualizado; 
        break;
        case 'elimina_user':
            $usr = $_POST['user'];
            $usuario = new usersDao();
            $eliminado="no";
            
            $elim_usu = $usuario->actuUsuario(0,0,$usr,2);
            
            if($elim_usu==0){
               $eliminado= 1;
            }
            
            echo $eliminado; 
        break;
        case 'elimina_user_faena':
            $usr = $_POST['user'];
            $usuario = new usersDao();
            $eliminado="no";
            
            $elim_usu = $usuario->actuUsuarioFaena(0,0,$usr,2);
            
            if($elim_usu==0){
               $eliminado= 1;
            }
            
            echo $eliminado; 
        break;
        case 'trae_permisos':
           $id_usu=$_POST['usr'];   
           $tabla="";
           $usuario = new usersDao();
           $lista = $usuario->listar_modulos_submodulos(); 
           $totalp=0;
            foreach($lista AS $ms){
                $usuario2 = new usersDao();
                $modulo = $usuario2->verificaModulo($id_usu,$ms['modulo_id']);
                $usuario3 = new usersDao();
                $submodulo = $usuario3->verificaSubmodulo($id_usu,$ms['submodulo_id']);
                if($modulo==1 && $submodulo==1){
                    $check="checked";
                }else{
                    $check="";
                }
                $seleccion='<label class="switch"><input type="checkbox" id="'.$id_usu.'" data-idm="'.$ms['modulo_id'].'" data-idsm="'.$ms['submodulo_id'].'" class="ch" '.$check.'><span class="slider round"></span></label>';
                $tabla .= '<tr><td>'.$ms['descripcion_mod'].'</td><td style="text-align:center">'.$ms['descripcion_smod'].'</td><td style="text-align:center">'.$seleccion.'</td></tr></tr>';
                $totalp++;
            }
            if($totalp > 0){
                echo $tabla;
            }else{
                echo "NO";
            }    
        break;
        case 'actu_permisos':
            $modulos=json_decode($_POST['arr']);
			$submodulos=json_decode($_POST['arr2']); 
			$user_id=$_POST['usuario'];
			
			$usuario = new usersDao();
			$elimina_modulos=$usuario->borraModulosUsuario($user_id);
			for ($i=0; $i < count($modulos); $i++) {
		       $usuario2 = new usersDao();
		       $usuario2->grabaModulosUsuario($modulos[$i]->id_usu,$modulos[$i]->idMod);
		    }
		    $usuario3 = new usersDao();
		    $elimina_submodulos=$usuario3->borraSubmodulosUsuario($user_id);
			for ($k=0; $k < count($submodulos); $k++) {
		       $usuario2 = new usersDao();
		       $usuario2->grabaSubmodulosUsuario($submodulos[$k]->id_usu,$submodulos[$k]->idSmod);
		    }
			echo "OK";
    	break;
    }
?>


    

   
        
    


