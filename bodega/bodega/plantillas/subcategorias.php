<?php 
session_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1); 
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/ProductosDao.php');
?>
<head>

<script type="text/javascript">
$( document ).ready(function(e) {
   $('.selectpicker').selectpicker({
         style: 'btn-default',
     });
     $('.selectpicker').attr("title", "");
});

$(document).on('change','#categorias_sel',function(e){
$('.selectpicker').attr("title", "");
var cat=$(this).val();   
if(cat==0){
   $(".datos").html("");
   return false;
}
    $.ajax({
        type	: "POST",
        url		: "ajax/ajax_productos.php",
        async	: true,
        data	: {
        opt		: 'trae_subcategorias2',
        id_cat 	: cat,
        desc_cat: $('#categorias_sel option:selected').html()
        },
        success: function(r){
            console.log(r);
            if($.trim(r)!="NO"){
               $(".datos").html(r);
               $(".titulo_subs").html("Subcategorias asociadas a "+$('#categorias_sel option:selected').html()); 
               $('.selectpicker').attr("title", "");
            }else{
                $(".datos").html("");
            }
        }
    });
    			
});
$(document).on('click','#guarda_subcat',function(e){
var subcat=$("#new_subcateg").val();
var catego=$('#categorias_sel').val();
if(subcat==""){
    alertify.error("Ingrese nombre de subcategroria");
    return false;
}
if(catego==0){
    alertify.error("Seleccione categoria a la cual va a asignar nueva subcategoria");
    return false;
}
    $.ajax({
				
			type	: "POST",
			url		: "ajax/ajax_productos.php",
			async	: true,
			data	: {
			opt		: 'grabaSubcategoria',
	    	cat 	: catego,
	    	scat    : subcat
			},
		
			success: function(r){
				var respu = $.trim(r);
				if(respu=="OK"){
				    alertify.success("Nueva subcategoria grabada exitosamente");
				    $(".datos").html("");
				    $("#new_subcateg").val("");
				    $("#categorias_sel").change();
                }else if(respu=='existe'){
                    alertify.alert("Subcategoria ya existe para la categoria "+$('#categorias_sel option:selected').html());
				}else{
				    alertify.alert("Error al actualizar: "+respu);
				}
				
			}
    });		
});  
co=0;
    $(document).on('click','.elim_scat', function(e){
       e.preventDefault();
        e.stopImmediatePropagation();
        var trozos=$(this).attr("id").split("_");
        var id_scat=trozos[0];
        var nombre_scat=trozos[1];
        alertify.confirm('¿Está seguro de eliminar la subcategoria '+nombre_scat+'?', function (e) {
            if (e) {
                 co++;
                 console.log(co);
                $.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_productos.php",
                     async	: false,
                     data	: {
                     opt		: 'borraScat',
                     id         : id_scat
                   },
                    success: function(data) {
                       if($.trim(data)==-1){
                            alertify.error("Subcategoria tiene productos asociados, no puede ser eliminada");
                        }else{       
                           if($.trim(data)=="no"){
                            alertify.alert("Error al borrar subcategoria:"+data);
                           }else{
                            $("#categorias_sel").change();
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
       <select id="categorias_sel" class="selectpicker" data-live-search="true">
       <option value="0">-- Seleccione categoria --</option>  
       	<?php
            $prods = new productosDao();
            $list_cats = $prods->listarCategorias();  
            foreach($list_cats AS $cat){
                echo "<option value=".$cat['id'].">".$cat['nombre_cat']."</option>"; 
            }
        ?>      
       </select>  
       </div>
       <div class='col-xs-4'>
          <input class="form-control" id="new_subcateg" type="text" placeholder="Nueva subcategoria" spellcheck="false" data-ms-editor="true">
       </div>
       <div class='col-xs-5'>
           <button type="button" id="guarda_subcat" style="margin-left:15px" class="btn btn-primary">Guardar nueva subcategoria</button>
       </div>        
</div>
<div class='row'>
    <div class='col-xs-8'>
        <div class="table-wrapper-scroll-y my-custom-scrollbar">
               <table style="margin-top:20px" id="tabla_permisos_actuales" class="table table-bordered table-striped table-hover">
               <thead>
                   <tr><th class="titulo_subs" colspan="3" style="text-align:center"></th></tr>
                   <tr><th>Categoria</th><th style="text-align:center">Subcategoría</th><th style="text-align:center">Productos asociados</th><th style="text-align:center"></th></tr>
                </thead>       
               <tbody class="datos">
               </tbody>  
               </table>
        </div>
    </div>
    
</div>          
        