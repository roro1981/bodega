<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
session_start();    
require '../includes/Conexion.php';
require '../DAO/UsersDao.php';
require '../includes/functions.php';

?>
<script>
    // Submit button made with javascript
    function changePassword() {
        var password = document.querySelector("#actu_password").value;
        var confirmPassword = document.querySelector("#actu_confirmPassword").value;
        
        if(password.length >= 8) {
            if(password === confirmPassword)
            {
                var btn = $('#actu_user').removeAttr("disabled");
                document.querySelector('#passwordsError').style.display = 'none';
                console.log("enabling")
            }
            else {
                var btn = $('#actu_user').attr("disabled", "true");
                document.querySelector('#passwordsError').style.display = 'block';
                document.querySelector('#errorMessage').innerHTML = 'Las contraseñas no coinciden';
                console.log("disabling")
            }
        }
        else {
            var btn = $('#actu_user').attr("disabled", "true");
            document.querySelector('#passwordsError').style.display = 'block';
            document.querySelector('#errorMessage').innerHTML = 'La contraseña debe tener al menos 8 caracteres';
            console.log("disabling")
        }
    }
    $("#actu_user").unbind('click').bind('click', function () { 
         var usuario= $('#actu_usuario option:selected').html();
         var tipo=$("#actu_tipo").val();
         var pass=$("#actu_password").val();
         var usuario_activo=<?php echo $_SESSION['id_user']; ?>;
         console.log(usuario_activo);
         if(usuario==0 || tipo==0 || pass==""){
               alertify.error("Debe seleccionar usuario, tipo e ingresar nuevo password");
             return false;
         }
         if(usuario_activo != 1){
             alertify.alert("Ud. no esta autorizado para modificar datos del SUPER-ADMINISTRADOR");
             return false;
         }
         $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_usuarios.php",
                     data	: {
                     opt	      : 'edita_user',
                     user      : usuario,
                     pass      : pass,
                     tipo      : tipo
                   },
             success: function(data) {
             
               if($.trim(data)==1){
                   alertify.success("Usuario actualizado exitosamente");
                   $("#actu_usuario").val(0);
                   $("#actu_password").val("");
                   $("#actu_tipo").val(0);
                   $("#actu_confirmPassword").val("");
               }else{
                   alertify.alert("Error al actualizar usuario: "+data);
               }
             }
          });
         
     });
</script>
<div class="d-flex justify-content-center align-items-center" style="height: inherit;min-height: initial;left: 0;width: 100%;">
    <div class="container bg-white" style="padding-bottom: 40px;margin-right: 12px;margin-left: 12px;padding-left: 6px;padding-right: 6px;">        
        <div class="row">
            <div class="col-10 col-sm-10 col-md-10 offset-1 offset-sm-1 offset-md-1 text-start align-content-md-center">
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Seleccione usuario</strong></label><select class="form-control" id="actu_usuario" style="margin-bottom: 20px;width:60% !important">
                            <option value=0></option>
                            <?php
                                $usuario = new usersDao();
                                $list_usu = $usuario->listarUsuarios();  
                                foreach($list_usu AS $usu){
                                    echo "<option value=".$usu['id'].">".$usu['nombre_usuario']."</option>"; 
                                }
                                ?>
                             </select>
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Tipo usuario</strong></label><select class="form-control" id="actu_tipo" style="margin-bottom: 20px;width:60% !important">
                            <option value=0></option>
                            <option value=1>Administrador</option>
                            <option value=2>Usuario</option>
                            </select>
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Nueva Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="actu_password" placeholder="Nueva Contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Confirmar Nueva Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="actu_confirmPassword" placeholder="Confirmación de nueva contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div id="passwordsError" class="form-group mb-3" style="display: none;margin-bottom: 18px;"><span id="errorMessage" class="text-danger" style="font-size:15px;">Text</span></div>
                    <div class="row">
                        <div class="col-md-6" style="margin-top: 20px;"><button class="btn btn-primary d-block d-lg-flex justify-content-lg-center btn-lg" id="actu_user" type="button">Actualizar usuario</button></div>
                    </div>
            </div>
        </div>
    </div>
</div>               
  
    