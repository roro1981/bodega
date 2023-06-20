<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);

require '../includes/Conexion.php';
require '../DAO/UsersDao.php';
require '../includes/functions.php';

?>
<script>
   
    $("#elim_user_faena").unbind('click').bind('click', function () { 
         var usuario= $('#elimina_usuario_faena option:selected').html();
         var usu=$("#elimina_usuario_faena").val();
        
         if(usu==0 ){
               alertify.error("Debe seleccionar usuario de faena que desea eliminar");
             return false;
         }
         alertify.confirm('¿Está seguro de eliminar al usuario de faena '+usuario+'?', function (e) {
            if (e) {
                 $.ajax({
                             type	: "POST",
                             url		: "ajax/ajax_usuarios.php",
                             data	: {
                             opt	      : 'elimina_user_faena',
                             user      : usu
                           },
                     success: function(data) {
                     
                       if($.trim(data)==1){
                           alertify.success("Usuario de faena eliminado exitosamente");
                           $("#elimina_usuario_faena").val(0);
                           $("#elim_usuario_faena").click();
                       }else{
                           alertify.alert("Error al eliminar usuario de faena: "+data);
                       }
                     }
                  });
            } else { 
                alertify.error("Cancelado");
            }
        })  
     });
</script>
<div class="d-flex justify-content-center align-items-center" style="height: inherit;min-height: initial;left: 0;width: 100%;">
    <div class="container bg-white" style="padding-bottom: 40px;margin-right: 12px;margin-left: 12px;padding-left: 6px;padding-right: 6px;">        
        <div class="row">
            <div class="col-10 col-sm-10 col-md-10 offset-1 offset-sm-1 offset-md-1 text-start align-content-md-center">
                    <div class="form-group mb-3"><label class="form-label" style="margin-bottom: 10px;margin-top: 10px;color: #505E6C;">
                        <strong>Seleccione usuario de faena que desea eliminar</strong></label><select class="form-control" id="elimina_usuario_faena" style="margin-bottom: 20px;width:60% !important">
                            <option value=0></option>
                            <?php
                                $usuario = new usersDao();
                                $list_usu = $usuario->listarUsuariosFaenas();  
                                foreach($list_usu AS $usu){
                                    echo "<option value=".$usu['id'].">".$usu['nombre_usuario']." (Faena ".$usu['nombre_faena'].")</option>";  
                                }
                                ?>
                             </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="margin-top: 40px;"><button class="btn btn-danger d-block d-lg-flex justify-content-lg-center btn-lg" id="elim_user_faena" type="button">Eliminar usuario faena</button></div>
                    </div>
            </div>
        </div>
    </div>
</div>               
  
    