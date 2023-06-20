<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/ProductosDao.php');
?>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
<script type="text/javascript">
$(document).ready(function() {
    $('.selectpicker').selectpicker({
         style: 'btn-default'
     });
  
});
function getRandomIntInclusive(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1) + min);
}
$("#btn_cargar").unbind('click').bind('click', function () {  
    var tm=$("#tip_movi").val();
    var idprod=$("#produ_movi").val();
    var dprod=$("#produ_movi option:selected").text();
    var cant=$("#canti_mov").val();
    var aleatorio= getRandomIntInclusive(10, 100000);
 
    if(tm==0 || idprod==0 || cant==""){
        alertify.error("Debe ingresar tipo de movimiento, producto y cantidad");
        return false;
    }
    if(cant==0 || cant<0){
        alertify.error("Cantidad debe ser mayor a 0");
        return false;
    }
    var cont=0;
    $("#tbl_movis tbody tr").find('td:eq(1)').each(function () {
          cod_prod = $(this).attr("id").split("_")[1];
		
           //comparamos para ver si el código es igual a la busqueda
           if(cod_prod==idprod){
               cont++;
           }
            
    });
   
    if(cont>0 && tm !="E"){
        alertify.error("Producto ya fue ingresado en este listado");
        return false;
    }
    var elimina='<button type="button" id="elimina_'+aleatorio+'" class="borrar btn btn-danger">X</button>';
    $(".movis").append('<tr id="'+aleatorio+'"><td style="text-align:center">'+elimina+'</td><td data-ale="'+aleatorio+'" id="idp_'+idprod+'">'+idprod+'</td><td>'+dprod+' </td><td style="text-align:center">'+cant+'</td><td style="text-align:center"><input type="number" class="precio_prod" id="precio_'+aleatorio+'" style="text-align:center" value="0" /></td><td style="text-align:center">ENTRADA</td><td><textarea class="form-control" title="Máximo 500 caratcteres" id="obs_'+idprod+'" rows="1"></textarea></tr>');
 
    //$("#tip_movi").val(0);
    //$("#produ_movi").val("").selectpicker('refresh');
    $("#canti_mov").val("");
		    
});
$(document).on('click', '.borrar', function (event) {
    var id=$(this).attr("id").split("_")[1];
    $(this).closest('tr').remove();

});
$("#grabar_movs").unbind('click').bind('click', function () {      
	var DATA 	= [];
	var TABLA 	= $("#tbl_movis tbody > tr");
	var con=0;
	alertify.confirm('Se realizarán movimientos que modificarán stock, ¿Está seguro de continuar?', function (e) {
    if (e) {
    
    	TABLA.each(function(){
            var cod 		= $(this).find("td:eq(1)").html(),
                aleatorio   = $(this).find("td:eq(1)").data("ale"),
    			cant    	= $(this).find("td:eq(3)").html(),
    			tipo    	= $(this).find("td:eq(5)").html(),
    			obs     	= $(this).find("textarea[id*='obs_']").val();
    			
    			if($(this).find("td:eq(5)").html()=="ENTRADA"){
    			    precio = $("#precio_"+aleatorio).val();   
    			}else{
    			    precio = $("#precio_"+aleatorio).html();
    			}
    			console.log(precio);
    		item = {};
    		item ["idp"]    = cod;
    		item ["cant"] 	= cant;
    		item ["precio"] = precio;
    		item ['tipo'] 	= tipo.charAt(0);
    		item ['obs'] 	= obs;
    		
            DATA.push(item);
            con++;           
        });	
        if(con==0){
            alertify.error("No hay productos para procesar");
            return false;
        }
        var cont2=0;
        $(".precio_prod").each(function(){
            if($(this).val()==0 || $(this).val()=="") 
                cont2++;
        });
        if(cont2>0){
            alertify.error("Existen precios en 0 o vacíos, favor corregir");
            return false;
        }
    		prods 	= JSON.stringify(DATA);
    		console.log(prods);
    	$.ajax({
    		type	: "POST",
    		url		: "ajax/ajax_productos.php",
    		async	: true,
    		data	: {
    		opt		: 'grabaMovs',
        	arr 	: prods
    		},
    	
    		success: function(r){
    		    resp=$.trim(r);
    		    if(resp=="OK"){
    		        alertify.success("Movimientos grabados exitosamente");
    		        $(".movis").html("");
    		    }else{
    		        alertify.alert("Error al grabar: "+resp);
    		    }
    		}
    	});
    }else{ 
        alertify.error("Cancelado");
    }
    })			
});	

</script>
</head>
        <div id="cabecera2"> 
             <div style="margin-top:5px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>TIPO MOVIMIENTO(*):</label>
                <select class="form-control" id="tip_movi">
                    <option value="0">-- Seleccione --</option> 
                    <option value="E" selected>ENTRADA (+)</option> 
                </select>     
            </div>
            <div style="margin-top:5px" class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>PRODUCTO(*):</label>
                            <select id="produ_movi" class="selectpicker form-control" data-live-search="true" required="campo obligatorio">
                            <option value="0">-- Seleccione producto --</option>  
                            <div id="select_prods">
                            	<?php
                            	    $prod=new productosDao();
                                    $list_prod = $prod->listarProductosActivos();  
                                    foreach($list_prod AS $prod){
                                     $prod2=new productosDao();    
                                     $stock = $prod2->traeStock($prod['id']);
                                     echo "<option value=".$prod['id'].">".$prod['descripcion']."(STOCK ".$stock.")</option>"; 
                                    }
                                ?>
                             </div>    
                            </select>  
            </div>
             <div style="margin-top:5px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>CANTIDAD(*):</label>
                <input type="number" class="form-control" id="canti_mov" required>
             </div>
              <div style="margin-top:30px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <button class="btn btn-primary" id="btn_cargar"><i class="fa fa-tasks"></i> Cargar</button>
             </div>
        </div>
         
      <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <table id="tbl_movis" class="table table-responsive table-hover table-fixed">
          <thead style="background-color:#A9D0F5">
                <tr><th style="width:10px"><button id="grabar_movs" type="button" class="btn btn-danger"> Grabar movimientos</button></th>
                <th>Código Producto</th>
                <th>Producto</th>
                <th style="text-align:center">Cantidad</th>
                <th style="text-align:center">Precio Neto</th>
                <th style="text-align:center">Tipo mov.</th>
                <th style="text-align:center">Observación</th>
            </tr></thead>
            <tbody class='movis'>
              
            </tbody>
        </table>
      </div>

        

