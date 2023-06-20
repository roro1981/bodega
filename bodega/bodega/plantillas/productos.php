<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/ProductosDao.php');
$prods = new productosDao();
$prods2= new productosDao();
$prods3= new productosDao();
$prods4= new productosDao();
$prods5= new productosDao();
?>
<head>
<script type="text/javascript">
    function actualiza_list_prod(){
         $(".loader-background").show();
         $("#master").addClass("desactiva_pantalla");
        $.ajax({
        type : 'POST',
        url  : 'ajax/ajax_productos.php',
        data	: {
        opt		: 'trae_productos'
    		},
        dataType: 'json',
    
        success :  function(result){
            console.log(result);
            $('#tabla_productos').DataTable({
                responsive: true,
                destroy: true,
               "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"},
                "columnDefs": [
                    { "width": "450px", "targets": 2 },
                    { "width": "100px", "targets": 8 },
                    {"className": "dt-center", "targets": 0},
                    {"className": "dt-center", "targets": 1},
                    {"className": "dt-left", "targets": 2},
                    {"className": "dt-center", "targets": 3},
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 5},
                    {"className": "dt-center", "targets": 6},
                    {"className": "dt-left", "targets": 7}
                 ],
                data: result, //here we get the array data from the ajax call.
                columns: [
                  {"data" : "cod"},
                  {"data" : "cod_laudus"},
                  {"data" : "desc"},
                  {"data" : "pn"}, 
                  {"data" : "stock"}, 
                  {"data" : "imp"},
                  {"data" : "pb"},
                  {"data" : "cat"},
                  {"data" : "acciones"},
                  ],
                  dom: 'lBfrtip',
                  buttons: [
                  'excel', 'csv', 'pdf', 'print', 'copy',
                  ]
                   
            });
             $(".loader-background").hide();
             $("#master").removeClass("desactiva_pantalla");
            }
    });
    }

    $( document ).ready(function(e) {
       
     actualiza_list_prod();
    
     
    });
    /*$("#impu").change(function() {
        var dato=$(this).val().split("-");
        var valor_imp=dato[1];
        var neto=$("#pneto").val();
        console.log(valor_imp);
        if($("#pneto").val()=="" || $("#pneto").val()==0){
            $("#pbruto").val(0);
        }else{
            calculo=(parseInt(neto) * parseFloat(valor_imp))/100;
            bruto=Math.round(parseFloat(neto)+parseFloat(calculo));  
            $("#pbruto").val(bruto);
        }
    });*/
    $("#impu2").change(function() {
        var dato=$(this).val().split("-");
        var valor_imp=dato[1];
        var neto=$("#pneto2").val();
        console.log(valor_imp);
        if($("#pneto2").val()=="" || $("#pneto2").val()==0){
            $("#pbruto2").val(0);
        }else{
            calculo=(parseInt(neto) * parseFloat(valor_imp))/100;
            bruto=Math.round(parseFloat(neto)+parseFloat(calculo));  
            $("#pbruto2").val(bruto);
        }
    });
 $("#nuevo_prod").on('hidden.bs.modal', function () {
         $("#cod").val("");
         $('#desc').val("");
         $('#pneto').val("");
         $('#impu').val(0);
         $('#pbruto').val("");
         $('#categ').val(0);
         $('#ubicacion').val("");
 });
 $("#modifica_prod").on('hidden.bs.modal', function () {
        $("#cod2").val("");
         $('#desc2').val("");
         $('#pneto2').val("");
         $('#impu2').val(0);
         $('#pbruto2').val("");
         $('#categ2').val(0);
         $('#ubicacion2').val("");  
         $("id_producto").val("");
 });
 
$("#grabaProducto").unbind('click').bind('click', function () {    
    if($('#cod').val()==""){
      alertify.error("Debe ingresar codigo del producto");
      $('#cod').focus();
      return false;  
    }
    
    if($('#desc').val()==""){
      alertify.error("Debe ingresar descripcion de producto");
      $('#desc').focus();
      return false;  
    }
    /*if($('#pneto').val()==0 || $('#pneto').val()==""){
      alertify.error("Debe ingresar precio neto");
      $('#pneto').focus();
      return false;  
    }*/
    if($('#impu').val()==0){
      alertify.error("Seleccione impuesto");
      $('#impu').focus();
      return false;  
    }
    /*if($('#pbruto').val()=="" || $('#pbruto').val()==0){
      alertify.error("Sin precio bruto");
      $('#categoria').focus();
      return false;  
    }*/
    if($('#categ').val()==0){
      alertify.error("Seleccione categoria");
      $('#categ').focus();
      return false;  
    }
    if($('#ubicacion').val()==""){
      $("#ubicacion").val("-");
    }
 var DATA 	= [];
        item = {};		
	    item ["cod"] 	        = $('#cod').val();
	    item ["descripcion"]	= $('#desc').val();
        item ["pn"] 	        = 0;
        item ["impu"] 	        = $('#impu').val().split("-")[1];
        item ["impuId"] 	    = $('#impu').val().split("-")[0];
        item ["categ"] 	        = $('#categ').val();
        item ["ubic"]           = $('#ubicacion').val();
  
        DATA.push(item);
       info = JSON.stringify(DATA);
       
       $.ajax({
				
    			type	: "POST",
    			url		: "ajax/ajax_productos.php",
    			async	: true,
    			data	: {
    			opt		: 'grabaProducto',
    	    arr 	: info
    		},
		
				success: function(r){
          if($.trim(r)=="existe"){ 
              alertify.alert("Producto ingresado ya existe en nuestros registros");
              $("#desc").focus();
          }else if($.trim(r)=="existelaudus"){
              alertify.alert("Codigo laudus ya existe, favor rectificar");
              $("#cod").focus();
          }else{   
              if($.trim(r)=="no"){
                  alertify.alert("Error al grabar producto: "+r);
              }else{
                  alertify.success("Producto grabado exitosamente");
                  $('#nuevo_prod').modal('hide');
                  $("#nuevo_prod").on('hidden.bs.modal', function () {
                    actualiza_list_prod();
                     $("#cod").val("");
                     $('#desc').val("");
                     $('#pneto').val("");
                     $('#impu').val(0);
                     $('#pbruto').val("");
                     $('#categ').val(0);
                     $('#ubicacion').val("");
                  });
              }	
          }	
				}
			});
});

$("#actuProd").unbind('click').bind('click', function () {     
   
  if($('#cod2').val()==""){
      alertify.error("Debe ingresar codigo laudus");
      $('#cod2').focus();
      return false;  
    }
    
    if($('#desc2').val()==""){
      alertify.error("Debe ingresar descripcion de producto");
      $('#desc2').focus();
      return false;  
    }
    /*if($('#pneto2').val()==0 || $('#pneto2').val()==""){
      alertify.error("Debe ingresar precio neto");
      $('#pneto2').focus();
      return false;  
    }*/
    if($('#impu2').val()==0){
      alertify.error("Seleccione impuesto");
      $('#impu2').focus();
      return false;  
    }
    if($('#pbruto2').val()=="" || $('#pbruto2').val()==0){
      alertify.error("Sin precio bruto");
      $('#impu2').focus();
      return false;  
    }
    if($('#categ2').val()==0){
      alertify.error("Seleccione categoria");
      $('#categ2').focus();
      return false;  
    }
    if($('#ubicacion2').val()==""){
      $("#ubicacion2").val("-");
    }

 var DATA 	= [];
 item = {};
	item = {};	
	    item ["id_prod"]	    = $('#id_producto').val();
	    item ["codigo"]	        = $('#cod2').val();
	    item ["descripcion"]	= $('#desc2').val();
        item ["pn"] 	        = $('#pneto2').val();
        item ["impu"] 	        = $('#impu2').val().split("-")[1];
        item ["impuId"] 	    = $('#impu2').val().split("-")[0];
        item ["categ"] 	        = $('#categ2').val();
        item ["ubic"]           = $('#ubicacion2').val();
  
        DATA.push(item);
       info = JSON.stringify(DATA);
       
       $.ajax({
				
			type	: "POST",
			url		: "ajax/ajax_productos.php",
			async	: true,
			data	: {
			opt		: 'actuProducto',
	    	  arr 	: info
			},
		
			success: function(res){
                console.log(res);
                if($.trim(res)==0){
                    $('#modifica_prod').modal('hide');
                    $("#modifica_prod").on('hidden.bs.modal', function (e) {
                    actualiza_list_prod();
                    $(this).off('hidden.bs.modal');
                    alertify.success("Producto actualizado exitosamente")
                    });
                }else{
                    alertify.alert("Error al actualizar producto:"+res);
                    
                }	
                                	
			}
			});
			return false;
});


$(document).on('click','.edita_prod',function(){
    var id_prod=$(this).attr('id');
    var precio_neto=$(this).data('pneto');
    $.ajax({
    type	: "POST",
    url		: "ajax/ajax_productos.php",
    data	: {
    	opt		: 'trae_datos_producto',
        id      : id_prod
    },
    success: function(data) {
       recibo=JSON.parse(data);
       $("#id_producto").val(id_prod);
       $("#cod2").val(recibo[0]);
       $("#desc2").val(recibo[1]); 
       $("#pneto2").val(precio_neto);
       $("#impu2").val(recibo[3]); 
       $("#impu2").change();
       $("#categ2").val(recibo[4]); 
       $("#ubicacion2").val(recibo[5]);
    }
            
    });
});
  
    $(document).on('click','.elim_prod', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var trozos=$(this).attr("id").split("*");
        var id_prod=trozos[0];
        var descr=trozos[1];
        alertify.confirm('¿Está seguro de eliminar el producto '+descr+'?', function (e) {
            if (e) {
        		$.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_productos.php",
                     data	: {
                     opt		: 'borraProducto',
                     id           : id_prod
                   },
                    success: function(data) {
                    console.log(data);
                       if($.trim(data)=="no"){
                        alertify.alert("Error al borrar producto:"+data);
                       }else{
                        actualiza_list_prod();
                        alertify.success("Se eliminó producto "+descr)
                       }
                    }
                });
    						
    	    }else{ 
                alertify.error("Cancelado");
    	    }
        })
    });    
       
    var excelFile;
    var DATA 	= [];
		// lee el contenido del archivo
	function leerExcel(obj) {
		if(!obj.files) {
			return;
		}
		var f = obj.files[0];
		var reader = new FileReader();
		reader.readAsBinaryString(f);
		reader.onload = function(e) {
			var data = e.target.result;
			excelFile = XLSX.read(data, {
				type: 'binary'
			});
			var str = XLSX.utils.sheet_to_json(excelFile.Sheets[excelFile.SheetNames[0]])
			var registros=str.length;
            	    
			for(var i=0;i<registros;i++){
				var json = str[i]
				 DATA.push(json);
			}
		}
	} 

	$("#carga_masiva").unbind('click').bind('click', function () {     
        var info=JSON.stringify(DATA);
       if($("#xFile").val()==""){
           alertify.error("Seleccione archivo para carga masiva");
           return false;
       }
        alertify.confirm('¿Está seguro de cargar los productos contenidos en este archivo?', function (e) {
            if (e) {
                $(".loader-background").show();
        		$.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_productos.php",
                     data	: {
                     opt		: 'grabaProductoMasivo',
                     arr           : info
                   },
                    success: function(data) {
                    console.log(data);
                    var respu=data.split("**");
                    $(".loader-background").hide();
                       if(respu[0]=="OK"){
                        alertify.alert("Productos grabados: "+respu[1]+" - "+"Productos repetidos: "+respu[3]+" - "+"Productos no grabados: "+respu[2]+" - "+"Total productos procesados: "+respu[4]);
                        actualiza_list_prod();
                        $("#xFile").val("");
                       }else{
                        alertify.alert("Error en grabado masivo:"+data);
                       }
                    }
                });
    						
    	    }else{ 
                alertify.error("Cancelado");
    	    }
        })
    });    
	
    </script>
    <style>
        .loader-background {
            /*width: 100%;
            height: 100%;*/
            position: relative; /* Cambiamos de absolute a relative */
            background-color: rgba(0,0,0,0.7);
            z-index:50;
        }
        
        .loader{
          border: 16px solid #d4d4d4;
          border-top: 16px solid #3498db;
          border-bottom: 16px solid #3498db;
          border-radius: 50%;
          width: 60px;
          height: 60px;
          animation: spin 1.5s linear infinite;
          position: absolute; /* cambiamos de fixed a absolute */
          /* Ponemos el valor de left, bottom, right y top en 0 */
          left: 0;
          bottom: 0; 
          right: 0; 
          top: 0; 
          margin: auto; /* Centramos vertical y horizontalmente */
        }
        .desactiva_pantalla {
            pointer-events: none;
            opacity: 0.4;
        }

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    </style>
</head>

<div id="master" class='row'>
    <div class='col-xs-12'>
          <div style="width:100%">
              <div class='box-header' style="width:100%">
                <h3 class='box-title'>Listado de Productos</h3>
              </div> 
          </div>      
    </div>
    <div class='col-xs-12'>      
          <button class="btn btn-dropbox" data-toggle="modal" style="float:left" id="nuevo_pro" data-target="#nuevo_prod"><i class='fa fa fa-save'></i>&nbsp;&nbsp;Nuevo Producto</button>
          <button class="btn btn-success" style="float:left;margin-left:15px" id="carga_masiva"><i class='fa fa fa-table'></i>&nbsp;&nbsp;Carga masiva de productos</button>
          <input type="file" id="xFile" name="" style="float:left;margin-left:15px;border: 2px solid;font-size:14pt" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" onchange="leerExcel(this)">
          <a style="padding-right: 10px;padding-left: 10px;margin-left:15px;color:green;font-size:14pt;float:left;border: 2px solid;" href="plantillas/archivos/base_carga_masiva_productos.xlsx" >Descargar archivo excel base</a>
          <hr style="background-color: brown;width:100%;margin-top: 2pt;" />
    </div>
    <div style="display:none" class="loader-background">
      <div class="loader"></div>
    </div>
    <div class='col-xs-12'>
          <table id='tabla_productos' class="display" style="width:100%">
           <thead>
           <tr style="background-color: #01338d;color:white">
               <th>CODIGO</th><th>CODIGO LAUDUS</th><th>DESCRIPCION</th><th>PRECIO NETO</th><th>STOCK</th><th>IMPUESTO</th><th>PRECIO BRUTO</th><th>CATEGORIA</th><th></th>
           </tr>
           </thead>
           <tbody class="listado">
           </tbody>
          </table>
    </div>
    
           <!-- modal nuevo producto -->  
          <div class="modal fade" id="nuevo_prod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h4 class="modal-title" id="myModalLabel">Agregar Nuevo Producto</h4></center>
                    </div>
                    <div class="modal-body">
        			<div class="container-fluid">
        		
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Codigo Laudus:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="cod">
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Descripcion:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="desc">
        					</div>
        				</div>
        				<!--<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Precio neto:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="number" class="form-control" id="pneto" disabled>
        					</div>
        				</div>-->
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Impuesto:</label>
        					</div>
        					<div class="col-sm-9">
        						<select class="form-control" id="impu">
        						<option value=0></option>
        						<?php 
        						$list_imp = $prods->listarImpuestos();  
                                foreach($list_imp AS $imp){
                                 echo "<option value=".$imp['id']."-".$imp['valor_imp'].">".$imp['descripcion_imp']."</option>";  
                                }
        						?>
        						</select>
        					</div>
        				</div>
        				<!--<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Precio Bruto:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="pbruto" disabled>
        					</div>
        				</div>-->
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Categoria:</label>
        					</div>
        					<div class="col-sm-9">
        						<select class="form-control" id="categ">
        						<option value=0></option>
        						<?php 
        						$list_cat = $prods2->listarCategorias();  
                                foreach($list_cat AS $cat){
                                 echo "<option value=".$cat['id'].">".$cat['nombre_cat']."</option>";  
                                }
        						?>
        						</select>
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Ubicacion:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="ubicacion">
        					</div>
        				</div>
                    </div> 
        			</div>
                    <div class="modal-footer" style="text-align: center;">
                        <button id="grabaProducto" class="btn btn-primary btn-lg"><i class='fa fa fa-save'></i>&nbsp;&nbsp; Grabar producto</button>
                    </div>
        
                </div>
            </div>
        </div>
            
            
     
  <!-- modal edita cliente -->  
<div class="modal fade" id="modifica_prod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <input type="hidden" id="id_producto" />
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <center><h4 class="modal-title" id="myModalLabel">Editar Producto</h4></center>
                    </div>
                    <div class="modal-body">
        			<div class="container-fluid">
        		
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Codigo Laudus:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="cod2">
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Descripcion:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="desc2">
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Precio neto:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="number" class="form-control" id="pneto2" disabled>
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Impuesto:</label>
        					</div>
        					<div class="col-sm-9">
        						<select class="form-control" id="impu2">
        						<option value=0></option>
        						<?php 
        						$list_imp2 = $prods4->listarImpuestos();  
                                foreach($list_imp2 AS $imp){
                                 echo "<option value=".$imp['id']."-".$imp['valor_imp'].">".$imp['descripcion_imp']."</option>";  
                                }
        						?>
        						</select>
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Precio Bruto:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="pbruto2" disabled>
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Categoria:</label>
        					</div>
        					<div class="col-sm-9">
        						<select class="form-control" id="categ2">
        						<option value=0></option>
        						<?php 
        						$list_cat2 = $prods5->listarCategorias();  
                                foreach($list_cat2 AS $cat){
                                 echo "<option value=".$cat['id'].">".$cat['nombre_cat']."</option>";  
                                }
        						?>
        						</select>
        					</div>
        				</div>
        				<div class="row form-group">
        					<div class="col-sm-3">
        						<label class="control-label" style="position:relative; top:7px;">Ubicacion:</label>
        					</div>
        					<div class="col-sm-9">
        						<input type="text" class="form-control" id="ubicacion2">
        					</div>
        				</div>
                    </div> 
        			</div>
                    <div class="modal-footer" style="text-align: center;">
                        <button id="actuProd" class="btn btn-primary btn-lg"><i class='fa fa fa-save'></i>&nbsp;&nbsp; Actualizar producto</button>
                    </div>
        
                </div>
            </div>
        </div>            
    </div>

        

