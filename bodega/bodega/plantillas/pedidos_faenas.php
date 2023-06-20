<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/ProductosDao.php');
$prods = new productosDao();
$valor_iva=$prods->traeImpuesto(1);
?>
<head>
<script type="text/javascript">
    function actualiza_list_pedidos(){
        $.ajax({
        type : 'POST',
        url  : 'ajax/ajax_productos.php',
        data	: {
        opt		: 'trae_pedidos'
    		},
        dataType: 'json',
    
        success :  function(result){
            console.log(result);
            $('#tabla_pedidos').DataTable({
                order: [[4, 'asc']],
                responsive: true,
                destroy: true,
               "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"},
                "columnDefs": [
                    {"className": "dt-center", "targets": 0},
                    {"className": "dt-center", "targets": 1},
                    {"className": "dt-center", "targets": 2},
                    {"className": "dt-center", "targets": 3},
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 5}
                 ],
                data: result, //here we get the array data from the ajax call.
                columns: [
                  {"data" : "id_ped"},
                  {"data" : "faena"},
                  {"data" : "fecha"}, 
                  {"data" : "usuario"}, 
                  {"data" : "est"},
                  {"data" : "acciones"},
                  ],
                  dom: 'lBfrtip',
                  buttons: [
                  'excel', 'csv', 'pdf', 'print', 'copy',
                  ]
                   
            });
        
                }
    });
    }

    $( document ).ready(function(e) {
       
     actualiza_list_pedidos();
     
    });
 
$("#envio_ped").unbind('click').bind('click', function () {    
    var DATA 	= [];
	var TABLA 	= $("#tabla_detalle_ped tbody > tr");
	var faena   = $("#id_faena").val();
	var pedido  = $("#id_pedido").val();
    TABLA.each(function(){
        var cod 		= $(this).find("td:eq(0)").html(),
    		cant    	= $(this).find("td:eq(2)").html(),
    		precio_neto	= $(this).find("td:eq(3)").html(),
    		
    	item = {};
    	item ["cod"] 	     = cod;
    	item ['cantidad'] 	 = cant;
    	item ['precio_neto'] = precio_neto;
        item ['id_faena']    = faena;
        item ['id_pedido']   = pedido;
        
        DATA.push(item);
    });	

        aInfo 	= JSON.stringify(DATA);
        $.ajax({
			type	: "POST",
			url		: "ajax/ajax_productos.php",
			async	: true,
			data	: {
			opt		: 'enviaPedido',
	        arr 	: aInfo
    	},
		success: function(r){
          if($.trim(r)=="OK"){
              alertify.success("Pedido "+pedido+" enviado a faena exitosamente");
              //$('#visualizar_pedido').hide();
              $('#contenido').load('plantillas/pedidos_faenas.php');
              $(".modal-backdrop").css("display","none");
          }else{
              alertify.alert("Error al enviar pedido: "+r);
          }
		}
		});
});

$(document).on('click','.ver_pedido',function(){
    var id_ped=$(this).attr('id');
    var faena=$(this).data('faena');
    var pedido=$(this).data('pedido');
    var estado=$(this).data('estado');
    $("#id_faena").val(faena);
    $("#id_pedido").val(pedido);
    $("#titulo_ped").html("Detalle pedido "+id_ped);

    $.ajax({
        type	: "POST",
        url		: "ajax/ajax_productos.php",
        data	: {
        opt		: 'trae_detalle_pedido',
        id      : id_ped,
        est     : estado
    },
    success: function(data) {
       var separa=data.split("**");    
       $(".deta_ped").html(separa[0]);
       $('#envio_ped').prop('disabled', true);
       var acum_neto=0;
       $("#tabla_detalle_ped tbody tr").find('td:eq(4)').each(function () {
           acum_neto = acum_neto+parseInt($(this).html());
       });  
       $('#tot_neto_ped').html(acum_neto);
       var val_iva='<?php echo $valor_iva; ?>';
       var iva=Math.round((acum_neto * parseInt(val_iva))/100);
       var bruto=Math.round((acum_neto * parseInt(val_iva))/100)+acum_neto;
       $('#iva').html(iva);
       $('#bruto').html(bruto);
       if(estado=='FINALIZADO'){
           $('#envio_ped').hide();
           $('#verifica_stock').hide();
           $("#fec_finalizado").text("Fecha envío a faena: "+separa[1]);
           $("#fec_finalizado").addClass("boton_verde");
       }else{
           $('#envio_ped').show();
           $('#verifica_stock').show();
           $("#fec_finalizado").text("");
            $("#fec_finalizado").removeClass("boton_verde");
       }
    }
    });
});
$(document).on('click','.stock',function(){
  var codigo=$(this).data("codigo");
  var stock=$(this).data("stock");
  var cantidad=$(this).html();
  
  $("#cabecera_cant").html("Editar cantidad (Stock actual: "+stock+")");
  $("#cantidad_produ").val(cantidad);
  $("#codigo_cambio_cant").val(codigo);
});
$("#verifica_stock").unbind('click').bind('click', function () {
    var DATA 	= [];
	var TABLA 	= $("#tabla_detalle_ped tbody > tr");
	var cont=0;
	TABLA.each(function(){
        var cod 		= $(this).find("td:eq(0)").html(),
    		cant    	= $(this).find("td:eq(2)").html(),
    		
    	item = {};
    	item ["cod"] 	    = cod;
    	item ['cantidad'] 	= cant;

        DATA.push(item);
        cont++;
    });	
   if(cont==0){
       $('#envio_ped').prop('disabled', true);
       return false;
    }else{
       $('#envio_ped').prop('disabled', false);
    }
    aInfo 	= JSON.stringify(DATA);
	$.ajax({
    	type	: "POST",
    	url		: "ajax/ajax_productos.php",
    	async	: true,
    	data	: {
    	opt		: 'verifica_stock',
    	arr 	: aInfo
    	},
		success: function(r){
		var data=JSON.parse(r);	
		//console.log(r);
		cont=0;
    		$.each(data, function(i, item) {
    		    
                console.log(item.cod+" "+item.respuesta);
                $("#tabla_detalle_ped tbody tr").find('td:eq(0)').each(function () {
                 //obtenemos el codigo de la celda
                  cod_tab = $(this).html();
                   //comparamos para ver si el código es igual a la busqueda
                   if(cod_tab==item.cod){
                        if(item.respuesta==1){
                            mostrar='<img src="img/checa.gif" style="display: inline; margin-top: -10px;" width="30" height="30">';
                        }else{
                            mostrar='<button type="button" data="'+$(this).html()+'" class="btn btn-danger btn-sm elimina" title="Eliminar producto">X</button>';
                            cont++;
                        }
                        trDelResultado=$(this).parent();
    					trDelResultado.find("td:eq(5)").html(mostrar);
                   }
                });
             });
             console.log(cont);
            if(cont>0){
               $('#envio_ped').prop('disabled', true);
            }else{
               $('#envio_ped').prop('disabled', false);
            }
		}
	});    
});
$(document).on('click', '.elimina', function (event) {
    $(this).closest('tr').remove();
    var acum_neto=0;
    var items=0;
    $("#tabla_detalle_ped tbody tr").find('td:eq(4)').each(function () {
        acum_neto = acum_neto+parseInt($(this).html());
        items++;
    });  
    $('#tot_neto_ped').html(acum_neto);
    var val_iva='<?php echo $valor_iva; ?>';
    var iva=Math.round((acum_neto * parseInt(val_iva))/100);
    var bruto=Math.round((acum_neto * parseInt(val_iva))/100)+acum_neto;
    $('#iva').html(iva);
    $('#bruto').html(bruto);
    if(items==0){
        $('#envio_ped').prop('disabled', true);
    }else{
       $('#envio_ped').prop('disabled', false);
    }
    
});
$("#actu_cantidad").unbind('click').bind('click', function () { 
  var cantidad=$("#cantidad_produ").val();  
  var codigo=$("#codigo_cambio_cant").val();
  if(cantidad !="" && cantidad != 0){
    $("#"+codigo).html(cantidad);
    var pn=$("#precio_"+codigo).html();
    $("#tot_"+codigo).html(cantidad*pn);
    var acum_neto=0;
    $("#tabla_detalle_ped tbody tr").find('td:eq(4)').each(function () {
        acum_neto = acum_neto+parseInt($(this).html());
    });  
    $('#tot_neto_ped').html(acum_neto);
    var val_iva='<?php echo $valor_iva; ?>';
    var iva=Math.round((acum_neto * parseInt(val_iva))/100);
    var bruto=Math.round((acum_neto * parseInt(val_iva))/100)+acum_neto;
    $('#iva').html(iva);
    $('#bruto').html(bruto);
    console.log(acum_neto);
    $('#edit_cant').modal('hide')
  }else{
      alertify.error("Ingrese una cantidad mayor a 0");
  }
});
</script>
<style>
    .boton_verde{
        background-color:#59BA41;
        color:white;
        padding:8px;
        font-weight:bold;
    }
</style>
</head>

<div class='row'>
  
    <div class='col-xs-12'>
          <div style="width:100%">
              <div class='box-header' style="width:100%">
                <h3 class='box-title'>Listado de Pedidos</h3>
              </div> 
          </div>      
    </div>

    <div class='col-xs-12'>
          <table id='tabla_pedidos' class="display" style="width:100%">
           <thead>
           <tr style="background-color: #01338d;color:white">
               <th>ID-PEDIDO</th><th>FAENA</th><th>FECHA-PEDIDO</th><th>SOLICITADO POR</th><th>ESTADO-PEDIDO</th><th></th>
           </tr>
           </thead>
           <tbody class="listado">
           </tbody>
          </table>
    </div>
    
  <!-- modal visualiza_pedido -->  
<div class="modal fade" id="visualizar_pedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <input type="hidden" id="id_faena" />
    <input type="hidden" id="id_pedido" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h3 class="modal-title" id="titulo_ped"></h3></center>
                    </div>
                    <div class="modal-body">
        			<div class="container-fluid">
        		
        				<div class="row form-group">
        					<div class="col-sm-12">
        					<table id="tabla_detalle_ped" class="table table-hover">
                              <thead style="background-color:#0EB7DD">
                                <tr>
                                  <th>Codigo</th>
                                  <th>Descripcion</th>
                                  <th>Cantidad</th>
                                  <th>Precio</th>
                                  <th>Total</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody class="deta_ped">
                                
                              </tbody>
                              <tfoot style="background-color:#0EB7DD">
                                  <tr style="font-weight:bold">
                                      <td colspan="5" align="right">
                                          <div style="float:left;width:35%">Neto: <span id="tot_neto_ped"></span></div> <div style="float:left;width:35%">Iva(<?php echo $valor_iva; ?>%): <span id="iva"></span></div><div style="float:right;width:30%">Total: <span id="bruto"></span></div>
                                      </td>
                                      <td></td>
                                  </tr>
                              </tfoot>
                            </table>
        					</div>
        				</div>
        				<div style="text-align:center" class="row form-group">
        				    <button id="verifica_stock" type="button" class="btn btn-primary">Verificar stock</button>
        				    <span style="background-color:#59BA41" id="fec_finalizado"></span>
        				</div>    
                    </div> 
        			</div>
                    <div class="modal-footer" style="text-align: center;">
                        <button id="envio_ped" class="btn btn-primary btn-lg" disabled><i class='fa fa fa-save'></i>&nbsp;&nbsp; Enviar pedido a faena</button>
                    </div>
        
                </div>
            </div>
        </div> 
          <!-- modal cambia cantidad --> 
        <div class="modal fade" id="edit_cant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="cabecera_cant"></h4>
              </div>
              <div id="contenido" class="modal-body">
                  <input type="hidden" id="codigo_cambio_cant" />
                  <div class="container-fluid">
                      <div class="row form-group" style="text-align:center">
                          <input type="number" style="width:70px" id="cantidad_produ" />
                      </div>       
                  </div>          
              </div>
              <div class="modal-footer" style="text-align: center;">
                 <button id="actu_cantidad" class="btn btn-primary btn-sm"><i class='fa fa fa-save'></i>Actualizar cantidad</button>
              </div>
            </div>
          </div>
        </div>
          
          </div>
          
		</form>
 
          </div>

        

