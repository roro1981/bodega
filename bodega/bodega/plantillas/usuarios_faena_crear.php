<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/FaenasDao.php');
?>
<script>
    // Submit button made with javascript
    function changePassword() {
        var password = document.querySelector("#password_faena").value;
        var confirmPassword = document.querySelector("#confirmPassword_faena").value;
        console.log(password+"--"+confirmPassword);
        if(password.length >= 8) {
            if(password === confirmPassword)
            {
                var btn = $('#ing_user_faena').removeAttr("disabled");
                document.querySelector('#passwordsError_faena').style.display = 'none';
                console.log("enabling")
            }
            else {
                var btn = $('#ing_user_faena').attr("disabled", "true");
                document.querySelector('#passwordsError_faena').style.display = 'block';
                document.querySelector('#errorMessage_faena').innerHTML = 'Las contraseñas no coinciden';
                console.log("disabling")
            }
        }
        else {
            var btn = $('#ing_user_faena').attr("disabled", "true");
            document.querySelector('#passwordsError_faena').style.display = 'block';
            document.querySelector('#errorMessage_faena').innerHTML = 'La contraseña debe tener al menos 8 caracteres';
            console.log("disabling")
        }
    }
    $("#ing_user_faena").unbind('click').bind('click', function () { 
         var usuario=$("#usuario_faena").val();
         var faena=$("#faena").val();
         var pass=$("#password_faena").val();
         if(usuario=="" || faena==0 || pass==""){
               alertify.error("Debe ingresar usuario, faena y password");
             return false;
         }
         if(usuario.length < 8){
            document.querySelector('#passwordsError_faena').style.display = 'block';
            document.querySelector('#errorMessage_faena').innerHTML = 'El nombre de usuario debe tener al menos 8 caracteres';
            return false;
         }else{
             document.querySelector('#passwordsError_faena').style.display = 'none';
         }
         $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_usuarios.php",
                     data	: {
                     opt	      : 'crea_user_faena',
                     user      : usuario,
                     pass      : pass,
                     faena     : faena
                   },
             success: function(data) {
             
               if($.trim(data)==1){
                   alertify.success("Usuario de faena grabado exitosamente");
                   $("#usuario_faena").val("");
                   $("#password_faena").val("");
                   $("#faena").val(0);
                   $("#confirmPassword_faena").val("");
               }else if($.trim(data)=="existe"){
                   alertify.alert("Usuario de faena ya existe en sistema");
               }else{
                   alertify.alert("Error al grabar usuario de faena: "+data);
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
                        <strong>Usuario</strong></label><input class="form-control" id="usuario_faena" type="text" style="margin-bottom: 20px;width:60% !important" placeholder="Nombre de usuario">
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Faena usuario</strong></label><select class="form-control" id="faena" style="margin-bottom: 20px;width:60% !important">
                            <option value=0></option>
                            <?php 
                    			$fae=new faenasDao();
                    			$list_fae = $fae->listarFaenas();  
                                foreach($list_fae AS $fa){
                                 echo "<option value=".$fa['id'].">".$fa['nombre_faena']."</option>";  
                                }
                    			?>
                            </select>
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="password_faena" placeholder="Contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Confirmar Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="confirmPassword_faena" placeholder="Confirmación de contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div id="passwordsError_faena" class="form-group mb-3" style="display: none;margin-bottom: 18px;"><span id="errorMessage_faena" class="text-danger" style="font-size:15px;">Text</span></div>
                    <div class="row">
                        <div class="col-md-6" style="margin-top: 20px;"><button class="btn btn-success d-block d-lg-flex justify-content-lg-center btn-lg" id="ing_user_faena" type="button">Crear nuevo usuario faena</button></div>
                    </div>
            </div>
        </div>
    </div>
</div>               
  
    