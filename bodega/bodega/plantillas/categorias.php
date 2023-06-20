<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/ProductosDao.php');
?>
<head>
    
<script type="text/javascript">

    function actualiza_list_cat(){
       $.ajax({
                type	: "POST",
                url		: "ajax/ajax_productos.php",
                async	: false,
                data	: {
                opt		: 'trae_categorias',
                },
                success: function(data) {
                   $(".contenido_cat").html(data);  
                 }
        });
    }
    
    $( document ).ready(function() {
         actualiza_list_cat();
     (function ($) {
 
        $('#filtrar').keyup(function () {

            var rex = new RegExp($(this).val(), 'i');
            $('.contenido_cat tr').hide();
            $('.contenido_cat tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })
 
     }(jQuery));
 
    });
 $("#nue_cat").on('hidden.bs.modal', function () {
         $("#nom_cat").val("");
 });
 $("#edit_cat").on('hidden.bs.modal', function () {
         $("#categoria").val("");
         $("#new_cat").val("");
 });
$(document).on('click','#btn_nuevacat',function(){
    if($('#nom_cat').val()==""){
      alertify.error("Debe ingresar nombre de categoria");
      $('#nom_Cat').focus();
      return false;  
    }
   var categoria= $('#nom_cat').val();
   
       $.ajax({
				
				type	: "POST",
			url		: "ajax/ajax_productos.php",
			async	: true,
			data	: {
			opt		: 'grabaCategoria',
	    	 cat 	: categoria
                        
			},
		
            success: function(r){
                console.log(r);
                if($.trim(r)=="existe"){ 
                    alertify.alert("Categoria ingresada ya existe");
                    $("#nom_cat").focus();
                 }else{   
    				if($.trim(r)=="no"){
                        alertify.alert("Error al grabar categoria: "+r);
                    }else{
                        alertify.success("Categoria grabada exitosamente");
                        $('#nue_cat').modal('hide');
                        $("#nue_cat").on('hidden.bs.modal', function () {
                        actualiza_list_cat();
                        $(this).off('hidden.bs.modal');
                        });
                    }	
                }	
			}
			});
			return false;
});
$(document).on('click','.edita_cat',function(){
   var id_cat=$(this).attr('id');
   $.ajax({
		type	: "POST",
		url		: "ajax/ajax_productos.php",
		data	: {
			opt		: 'trae_categoria',
            id           : id_cat
		},
		success: function(data) {
           recibo=JSON.parse(data);
           $("#categoria").val(recibo[0]);
           $("#new_cat").val(recibo[0]);
		}
	});
        return false;
});    
$(document).on('click','#btn_actucat',function(){
   
    if($('#new_cat').val()==""){
      alertify.error("Debe ingresar nombre de categoria");
      $('#new_cat').focus();
      return false;  
    }
    

 var DATA 	= [];
 item = {};
					
	item ["cat"] 	= $('#categoria').val();
	item ["nueva_cat"]	= $('#new_cat').val();

        
  //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
        DATA.push(item);
       info = JSON.stringify(DATA);
       
       $.ajax({
				
				type	: "POST",
			url		: "ajax/ajax_productos.php",
			async	: true,
			data	: {
			opt		: 'actuCat',
	    	  arr 	: info
			},
		
			success: function(res){
            console.log(res);
    			if($.trim(res)=="no"){
                    alertify.error("Error al actualizar categoria");
                }else{
                    $('#edit_cat').modal('hide');
                    $("#edit_cat").on('hidden.bs.modal', function (e) {
                    actualiza_list_cat();
                    $(this).off('hidden.bs.modal');
                    });
                }	
			}
			});
			return false;
}); 
  co=0;
    $(document).on('click','.elim_cat', function(e){
       e.preventDefault();
        e.stopImmediatePropagation();
        var trozos=$(this).attr("id").split("_");
        var id_cat=trozos[0];
        var nombre_cat=trozos[1];
        alertify.confirm('¿Está seguro de eliminar la categoria '+nombre_cat+'?', function (e) {
            if (e) {
                 co++;
                 console.log(co);
                $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_productos.php",
                     async	: false,
                     data	: {
                     opt		: 'borraCat',
                     id           : id_cat
                   },
                    success: function(data) {
                       if($.trim(data)==-1){
                            alertify.error("Categoria tiene productos asociados, no puede ser eliminada");
                        }else{       
                           if($.trim(data)=="no"){
                            alertify.alert("Error al borrar categoria:"+data);
                           }else{
                           actualiza_list_cat();
                           }
                        }
                    }
                });
        				
            } else { 
                  alertify.error("Cancelado");
            }
        });
    });      
    </script>
</head>

<div class='row'>
    <div class='col-xs-12'>
          <div class="input-group">
            Buscar
            <input id="filtrar" type="text" style="width:250px" placeholder="Ingresa categoria que desea buscar...">
            <button style="margin-left: 40px" class="btn btn-dropbox" data-toggle="modal" id="nueva_cat" data-target="#nue_cat"><i class='fa fa fa-save'></i>Nueva Categoría</button>
          </div>  
        <!--modal ingresa -->
 <div class="modal fade" id="nue_cat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Ingreso de categorias</h4>
      </div>
      <div id="contenido" class="modal-body">
                <label style="font-size:10pt;width:100px;text-align: left">Nombre: </label>
                <input type='text' autocomplete="off" id="nom_cat" style="width:150px;font-size:14px; font-weight: bold;">
                <br><br>
                <center><button class='btn btn-instagram btn-lg' id='btn_nuevacat'><i class='fa fa fa-save'></i>&nbsp;&nbsp;Registrar nueva categoria</button></center>
      </div>
    </div>
  </div>
</div> 
        <!--modal modifica-->
 <div class="modal fade" id="edit_cat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Editar categoria</h4>
      </div>
      <div id="contenido" class="modal-body">
          <center><input type='text' autocomplete="off" id="categoria" style="text-align:center;width:150px;font-size:14px; font-weight: bold;" disabled></center>
          <br><label style="font-size:10pt;width:100px;text-align: left">Nuevo nombre: </label>
                <input type='text' autocomplete="off" id="new_cat" style="width:150px;font-size:14px; font-weight: bold;">
                <br><br>
                <center><button class='btn btn-instagram btn-lg' id='btn_actucat'><i class='fa fa fa-edit'></i>&nbsp;&nbsp;Actualizar categoria</button></center>
      </div>
    </div>
  </div>
</div>    
        <br>
         <form class='form-inline' role='form'>
            <div class='table-responsive'>
	    <table class='table table-condensed table' align='center'>
	    <tr style='background-color:#01338d;color:white'>
            <th style="width:400px">NOMBRE CATEGORIA</th>
            <th style="width:150px;text-align: center">PRODUCTOS ASOCIADOS</th>
            </tr>
	    <tbody class='contenido_cat'>
	    </tbody>
            </table>
            </div>
         </form>
    </div>              
</div>
        

