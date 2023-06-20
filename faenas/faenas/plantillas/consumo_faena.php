<?php 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
session_start();
require '../includes/functions.php';
require_once '../includes/Conexion.php';
require '../DAO/UsersDao.php';
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
$(document).ready(function() {
//trae_productos_compra();
});
    
$("#btnAgregarArtFaena").unbind('click').bind('click', function () {
  $.ajax({
    type : 'POST',
    url  : 'ajax/ajax_productos.php',
    data	: {
    opt		: 'trae_prods_faena',
    faena   : $("#idFaena").val()
		},
    dataType: 'json',
    cache: false,
    success :  function(result){
        $('#tblconsumo').DataTable({
            destroy: true,
           "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"},
            data: result,
            //here we get the array data from the ajax call.
            columns: [
              {"data" : "opciones"},
              {"data" : "nombre"},
              {"data" : "cat"}, 
              {"data" : "codigo"},
              {"data" : "stock"}
              ],
        });
    }
               
     });
});
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}
function totales(){
	var items=0;
	var prods=0;
	$("#tab_consumo tbody tr").find('td:eq(0)').each(function () {
        //aqui ya que tenemos el td que contiene el codigo utilizaremos parent para obtener el tr.
        trDelResultado=$(this).parent();
		items+=1;	
		prods+=parseFloat(trDelResultado.find("td:eq(2)").html());	
    });
	$("#recuento").html("Total Items: "+items+" | Total Productos: "+prods);
}
$(document).on('click', '.borrar', function (event) {
    var id=$(this).attr("id").split("_")[1];
    $(this).closest('tr').remove();
    totales();
});
function agregarDetalle(id){
    var num_al=getRandomInt(100,10000);
  	var cantidad=$("#"+id).val();
  	var codigo=$("#"+id).data('codigo');
    var desc_prod=$("#"+id).data('nombre');
    var stock=$("#"+id).data('stock');
    ida=num_al;
           
    if (cantidad !="" && cantidad !=0){
        var excede=0;
        var ciclo=0;
		$("#tab_consumo tbody tr").find('td:eq(0)').each(function () {
			nueva_cant=0;
             //obtenemos el codigo de la celda
              cod_tab = $(this).html();
               //comparamos para ver si el código es igual a la busqueda
               console.log(cod_tab+" "+codigo);
               if(cod_tab==codigo){
                    //aqui ya que tenemos el td que contiene el codigo utilizaremos parent para obtener el tr.
                    trDelResultado=$(this).parent();
 
                    //ya que tenemos el tr seleccionado ahora podemos navegar a las otras celdas con find
                    cant_actual=parseFloat(trDelResultado.find("td:eq(2)").html());
					nueva_cant=cant_actual+parseFloat(cantidad);
					console.log(nueva_cant);
					if(nueva_cant>parseInt(stock)){ 
                        alertify.error("Cantidad excede el stock actual ("+stock+")"); 
                        excede=1; 
                        $("#"+id).val("");
                        return false;
                    }
					trDelResultado.find("td:eq(2)").html(nueva_cant);
					ciclo+=1;
               }
               
        });
        if (excede==1){
			return false;
		}
		if (ciclo>0){
			totales();
			$("#"+id).val("");
			return false;
		}
		console.log(parseInt(cantidad)+" "+parseInt(stock))
        if(parseInt(cantidad)<=parseInt(stock)){
        	var fila='<tr class="filas" id="produ_'+ida+'">'+
        	'<td id="cod_'+ida+'">'+codigo+'</td>'+
        	'<td id="nom_'+ida+'">'+desc_prod+'</td>'+
        	'<td style="text-align:center">'+cantidad+'</td>'+
        	'<td style="text-align:center"><button type="button" id="elimina_'+id+'" class="borrar btn btn-danger">X</button></td>';
        	$('.cons').append(fila);
        	totales();
        }else{
            alertify.error("Cantidad excede el stock actuall ("+stock+")");
        }
    }
    $("#"+id).val("");
}
$("#term_pedido").unbind('click').bind('click', function () {
    var DATA 	= [];
	var TABLA 	= $("#tab_pedidos tbody > tr");
    var items=0;
    $("#tab_pedidos tbody tr").find('td:eq(0)').each(function () {
		items+=1;	
    });
    if(items==0){
        alertify.error("No se han ingresado productos al pedido");
        return false;
    }
	TABLA.each(function(){
        var cod 		= $(this).find("td:eq(0)").html(),
    		desc    	= $(this).find("td:eq(1)").html(),
    		cant    	= $(this).find("td:eq(2)").html(),
    		
    	item = {};
    	item ["cod"] 	    = cod;
    	item ['descrip'] 	= desc;
    	item ['cantidad'] 	= cant;

        DATA.push(item);
    });	
    aInfo 	= JSON.stringify(DATA);
	$.ajax({
    	type	: "POST",
    	url		: "ajax/ajax_productos.php",
    	async	: true,
    	data	: {
    	opt		: 'grabaPedido',
    	arr 	: aInfo
    	},
    
    		success: function(r){
    			
    			var respu = $.trim(r).split("_");
    			if(respu[0]=="OK"){
    				
    			//window.parent.Shadowbox.close();	
    				
    			var vale=respu[1];
    			$.ajax({
    				type	: "POST",
    				url		: "ajax/ajax_productos.php",
    				data	: {
    					opt		: 'ticket',
    					ticket   : vale
    				},
    				success: function(data) {
    					var iframe = '<div class="iframe-container"><iframe src="../tickets/'+vale+'.pdf"></iframe></div>'
    						$.createModal({
    						title:'Imprimir voucher de pedido',
    						message: iframe,
    						closeButton:true,
    						scrollable:false
    						});
    						$('#contenido').load('plantillas/crea_pedidos.php');
    				}
    			});
    			
    			}else{
    				alertify.alert("error al grabar pedido: "+r);
    			}
    		}
	});    
});
</script>  
</head>
<input type="hidden" id="idFaena" value="<?php echo $_SESSION['id_faena']; ?>" />
      <div class='row form-group' >
    	<div class="col-sm-4">
			<button id="btnAgregarArtFaena" type="button" data-toggle="modal" data-target="#modalProd" class="btn btn-warning" title="Agregar productos"> Agregar productos <span class="fa fa-plus"></span></button>
    	</div>
    
      </div>

	<div class='row'>	
		<div class='col-md-12'>
          <div>
          <div class='box-header' style="margin-bottom:-10px">
          </div>
          <div class='box-body table-responsive table-bordered table-dark'>
          <table id="tab_consumo" class="table table-bordered table-dark">
			<thead>
				<tr>
					<th style="width:20%">Código</th>
					<th style="width:50%">Descripción</th>
					<th style="width:20%;text-align:center">Cantidad</th>
					<th style="width:10%;text-align:center">Quitar</th>
				</tr>
			</thead>
			<tbody class="cons">
				
			</tbody>
		</table>
		<h3 id="recuento">Total Items: 0 | Total Productos: 0</h3>
		<button id="term_consumo" class="btn btn-info">Sacar productos de bodega</button>
          </div>
          </div>
        </div>
        <!--modal productos-->
         <div class="modal fade" id="modalProd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title">Seleccione un Artículo</h4>
                </div>
                <div class="modal-body">
                  <table id="tblconsumo" data-page-length='3' class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <th>Cantidad</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Código</th>
                        <th>Stock</th>
                    </thead>
                    <tbody class="listado_pc">
                      
                    </tbody>
                   
                  </table>
                </div>
                
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>        
              </div>
            </div>
        </div>  
  </div>

           

