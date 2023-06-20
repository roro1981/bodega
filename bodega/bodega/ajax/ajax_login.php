<?php
/*error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);*/
require '../includes/Conexion.php';
require '../DAO/UsersDao.php';
require '../includes/functions.php';
$usuario = new usersDao();
$opt = 0; if (isset($_POST['opt'])){ $opt=$_POST['opt']; }
        switch ($opt) {
            case 'login':
                $usr = $_POST['usu'];
                $pass=$_POST['psw'];
                $existe_usuario = $usuario->traeUsuario($usr,$pass);
                if($existe_usuario != 0){
    				session_start();
    				$_SESSION['nombre']=$_POST['usu'];
    				$_SESSION['autentificado']="SI";
    				$_SESSION['id_user']=$existe_usuario;
    				echo $_SESSION['id_user']."--";
                }else{
                    $existe_usuario=0;
                }
                echo $existe_usuario;
            break;
            case 'cerrar_sesion':
				session_start();
				session_destroy();
				header("location: index.php");
			break;
            
        }
?>


    

   
        
    


