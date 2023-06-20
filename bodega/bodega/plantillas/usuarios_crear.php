<script>
    // Submit button made with javascript
    function changePassword() {
        var password = document.querySelector("#password").value;
        var confirmPassword = document.querySelector("#confirmPassword").value;
        
        if(password.length >= 8) {
            if(password === confirmPassword)
            {
                var btn = $('#ing_user').removeAttr("disabled");
                document.querySelector('#passwordsError').style.display = 'none';
                console.log("enabling")
            }
            else {
                var btn = $('#ing_user').attr("disabled", "true");
                document.querySelector('#passwordsError').style.display = 'block';
                document.querySelector('#errorMessage').innerHTML = 'Las contrase�as no coinciden';
                console.log("disabling")
            }
        }
        else {
            var btn = $('#ing_user').attr("disabled", "true");
            document.querySelector('#passwordsError').style.display = 'block';
            document.querySelector('#errorMessage').innerHTML = 'La contrase�a debe tener al menos 8 caracteres';
            console.log("disabling")
        }
    }
    $("#ing_user").unbind('click').bind('click', function () { 
         var usuario=$("#usuario").val();
         var tipo=$("#tipo").val();
         var pass=$("#password").val();
         if(usuario=="" || tipo==0 || pass==""){
               alertify.error("Debe ingresar usuario, tipo y password");
             return false;
         }
         if(usuario.length < 8){
            document.querySelector('#passwordsError').style.display = 'block';
            document.querySelector('#errorMessage').innerHTML = 'El nombre de usuario debe tener al menos 8 caracteres';
            return false;
         }else{
             document.querySelector('#passwordsError').style.display = 'none';
         }
         $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_usuarios.php",
                     data	: {
                     opt	      : 'crea_user',
                     user      : usuario,
                     pass      : pass,
                     tipo      : tipo
                   },
             success: function(data) {
             
               if($.trim(data)==1){
                   alertify.success("Usuario grabado exitosamente");
                   $("#usuario").val("");
                   $("#password").val("");
                   $("#tipo").val(0);
                   $("#confirmPassword").val("");
               }else if($.trim(data)=="existe"){
                   alertify.alert("Usuario ya existe en sistema");
               }else{
                   alertify.alert("Error al grabar usuario: "+data);
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
                        <strong>Usuario</strong></label><input class="form-control" id="usuario" type="text" style="margin-bottom: 20px;width:60% !important" placeholder="Nombre de usuario">
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Tipo usuario</strong></label><select class="form-control" id="tipo" style="margin-bottom: 20px;width:60% !important">
                            <option value=0></option>
                            <option value=1>Administrador</option>
                            <option value=2>Usuario</option>
                            </select>
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="password" placeholder="Contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Confirmar Contraseña</strong></label><input class="form-control" autocomplete="new-password" type="password" id="confirmPassword" placeholder="Confirmación de contraseña" style="margin-bottom: 20px;width:60% !important" onchange="changePassword();">
                    </div>
                    <div id="passwordsError" class="form-group mb-3" style="display: none;margin-bottom: 18px;"><span id="errorMessage" class="text-danger" style="font-size:15px;">Text</span></div>
                    <div class="row">
                        <div class="col-md-6" style="margin-top: 20px;"><button class="btn btn-primary d-block d-lg-flex justify-content-lg-center btn-lg" id="ing_user" type="button">Crear nuevo usuario</button></div>
                    </div>
            </div>
        </div>
    </div>
</div>               
  
    