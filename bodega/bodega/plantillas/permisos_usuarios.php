<?php 
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1); 
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/UsersDao.php');
?>
<head>

<script type="text/javascript">
$( document ).ready(function(e) {
   $('.selectpicker').selectpicker({
         style: 'btn-default',
     });
     $('.selectpicker').attr("title", "");
});

$(document).on('change','#usuarios_asignar',function(e){
$('.selectpicker').attr("title", "");
var usu=$(this).val();   
if(usu==0){
   $(".datos").html("");
   return false;
}
    $.ajax({
        type	: "POST",
        url		: "ajax/ajax_usuarios.php",
        async	: true,
        data	: {
        opt		: 'trae_permisos',
        usr 	: usu
        },
        success: function(r){
            console.log(r);
            if($.trim(r)!="NO"){
               $(".datos").html(r);
               $(".titulo_permisos").html("Permisos actuales para usuario "+$('#usuarios_asignar option:selected').html()); 
               $('.selectpicker').attr("title", "");
            }else{
                $(".datos").html("");
            }
        }
    });
    			
});
$("#todo").on("click", function() {  
  $(".ch").prop("checked", this.checked);  
  
});
$(document).on('click','#guarda_per',function(e){
    var DATA  = [];
    var DATA2 = [];
    cuenta=0;
       $('input[type=checkbox]').each(function() {
           if($(this).is(':checked')){
            var idUser  = $(this).prop("id");
            var idMod   = $(this).data("idm");
            var idSmod  = $(this).data("idsm");
         
                item = {};
    		    item ["id_usu"] = idUser;
    		    item ["idMod"]  = idMod;
    		    
    		    item2 = {};
    		    item2 ["id_usu"] = idUser;
    		    item2 ["idSmod"]  =idSmod;
              
                DATA.push(item); //array de modulos
                DATA2.push(item2);// array de submodulos
                cuenta++;   
           }
           
        });
        
        if(cuenta==0){
           alertify.error("Seleccione al menos un permiso");
           return false;
        }
    
        var hash = {};
        DATA = DATA.filter(function(current) {
          var exists = !hash[current.idMod];
          hash[current.idMod] = true;
          return exists;
        });
        aInfo 	= JSON.stringify(DATA);
        aInfo2 	= JSON.stringify(DATA2);
        
    $.ajax({
				
			type	: "POST",
			url		: "ajax/ajax_usuarios.php",
			async	: true,
			data	: {
			opt		: 'actu_permisos',
	    	arr 	: aInfo,
	    	arr2    : aInfo2,
	    	usuario : $("#usuarios_asignar").val()
			},
		
			success: function(r){
				var respu = $.trim(r);
				if(respu=="OK"){
				    alertify.success("Permisos actualizados exitosamente");
				    $("#usuarios_asignar").val("0").selectpicker('refresh');
				    $(".datos").html("");
				    $(".titulo_permisos").html("");
                    $('.selectpicker').attr("title", "");
                    
				}else{
				    alertify.alert("Error al actualizar: "+respu);
				}
				
			}
    });		
});    
</script>
<style>
    .my-custom-scrollbar {
    position: relative;
    height: 400px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
    .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
</head>

<div class='row'>
    <div class='col-xs-3'>
       <select id="usuarios_asignar" class="selectpicker" data-live-search="true">
       <option value="0">-- Seleccione usuario --</option>  
       	<?php
            $usuario = new usersDao();
            $list_usu = $usuario->listarUsuarios();  
            foreach($list_usu AS $usu){
                echo "<option value=".$usu['id'].">".$usu['nombre_usuario']."</option>"; 
            }
        ?>      
       </select>  
       </div>
       <div class='col-xs-9'>
           <button type="button" id="guarda_per" style="margin-left:15px" class="btn btn-primary">Guardar cambios</button>
           <input id="todo" style="margin-left:15px" type="checkbox"><label for="todo">Seleccionar todos</label>
       </div>
    
</div>
<div class='row'>
    <div class='col-xs-12'>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
               <table style="margin-top:20px" id="tabla_permisos_actuales" class="table table-bordered table-striped table-hover">
               <thead>
                   <tr><th class="titulo_permisos" colspan="3" style="text-align:center"></th></tr>
                   <tr><th>Modulo</th><th style="text-align:center">Submodulo</th><th style="text-align:center">Activo/Inactivo</th></tr>
                </thead>       
               <tbody class="datos">
               </tbody>  
               </table>
        </div>
    </div>
    
</div>          
        

