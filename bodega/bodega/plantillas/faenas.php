<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/FaenasDao.php');
?>
<head>
    
<script type="text/javascript">

    function actualiza_list_faenas(){
       $.ajax({
                type	: "POST",
                url		: "ajax/ajax_faenas.php",
                async	: false,
                data	: {
                opt		: 'listarFaenas',
                },
                success: function(data) {
                   $(".contenido_fae").html(data);                      
                 }
        });
    }
    
    $( document ).ready(function() {
         actualiza_list_faenas();
     (function ($) {
 
        $('#filtrar').keyup(function () {

            var rex = new RegExp($(this).val(), 'i');
            $('.contenido_fae tr').hide();
            $('.contenido_fae tr').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));
 
    });
 $("#nue_fae").on('hidden.bs.modal', function () {
         $("#nom_fae").val("");
 });
 $("#edit_cat").on('hidden.bs.modal', function () {
         $("#categoria").val("");
         $("#new_cat").val("");
 });
$("#btn_nuevafae").unbind('click').bind('click', function () {     
    if($('#nom_fae').val()==""){
      alertify.error("Debe ingresar nombre de faena");
      $('#nom_fae').focus();
      return false;  
    }
   var faena= $('#nom_fae').val();
   
       $.ajax({
				
			type	: "POST",
			url		: "ajax/ajax_faenas.php",
			async	: true,
			data	: {
			opt		: 'grabaFaena',
	    	 faena 	: faena
                        
			},
		
            success: function(r){
                console.log(r);
                if($.trim(r)=="existe"){ 
                    alertify.alert("Faena ingresada ya existe");
                    $("#nom_fae").focus();
                 }else{   
    				if($.trim(r)=="no"){
                        alertify.alert("Error al grabar faena: "+r);
                    }else{
                        alertify.success("Faena grabada exitosamente");
                        $('#nue_fae').modal('hide');
                        $("#nue_fae").on('hidden.bs.modal', function () {
                        actualiza_list_faenas();
                        $(this).off('hidden.bs.modal');
                        });
                    }	
                }	
			}
			});
			return false;
});
  co=0;
    $(document).on('click','.elim_fae', function(e){
       e.preventDefault();
        e.stopImmediatePropagation();
        var trozos=$(this).attr("id").split("_");
        var id_fae=trozos[0];
        var nombre_fae=trozos[1];
        alertify.confirm('¿Está seguro de eliminar la faena '+nombre_fae+'?', function (e) {
            if (e) {
                 co++;
                 console.log(co);
                $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_faenas.php",
                     async	: false,
                     data	: {
                     opt		: 'borraFaena',
                     id           : id_fae
                   },
                    success: function(data) {
                       if($.trim(data)=="no"){
                           alertify.alert("Error al borrar faena:"+data);
                       }else if($.trim(data)=="noborra"){
                           alertify.error("Faena contiene productos con stock, por lo tanto no puede eliminarse");
                       }else{
                        actualiza_list_faenas();
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
            <input id="filtrar" type="text" style="width:250px" placeholder="Ingresa faena que desea buscar...">
            <button style="margin-left: 40px" class="btn btn-dropbox" data-toggle="modal" id="nueva_cat" data-target="#nue_fae"><i class='fa fa fa-save'></i>Nueva faena</button>
          </div>  
        <!--modal ingresa -->
 <div class="modal fade" id="nue_fae" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Ingreso de faenas</h4>
      </div>
      <div id="contenido" class="modal-body">
                <label style="font-size:10pt;width:100px;text-align: left">Nombre: </label>
                <input type='text' autocomplete="off" id="nom_fae" style="width:150px;font-size:14px; font-weight: bold;">
                <br><br>
                <center><button class='btn btn-instagram btn-lg' id='btn_nuevafae'><i class='fa fa fa-save'></i>&nbsp;&nbsp;Registrar nueva faena</button></center>
      </div>
    </div>
  </div>
</div> 
        <br>
         <form class='form-inline' role='form'>
            <div class='table-responsive'>
	    <table class='table table-condensed table' style="width:60% !important" align='left'>
	    <tr style='background-color:#01338d;color:white'>
            <th style="width:30px">ID FAENA</th>
            <th style="width:100px;">NOMBRE FAENA</th>
            <th style="width:30px;"></th>
            </tr>
	    <tbody class='contenido_fae'>
	    </tbody>
            </table>
            </div>
         </form>
    </div>              
</div>
        

