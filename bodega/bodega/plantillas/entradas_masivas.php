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

	$("#carga_masiva_ent").unbind('click').bind('click', function () {     
        var info=JSON.stringify(DATA);
       if($("#xFile2").val()==""){
           alertify.error("Seleccione archivo para carga masiva");
           $("#confirmation").hide();
           return false;
       }
        alertify.confirm('¿Está seguro de cargar las entradas contenidas en este archivo?', function (e) {
            if (e) {
        		$.ajax({
                     type	: "POST",
                     url		: "ajax/ajax_productos.php",
                     data	: {
                     opt		: 'grabaEntradasMasivo',
                     arr           : info
                   },
                    success: function(data) {
                    console.log(data);
                    var respu=data.split("**");
                       if(respu[0]=="OK"){
                        $("#confirmation").show();   
                        $("#msg").text("Entradas grabadas: "+respu[1]+" - "+"Codigos no existentes: "+respu[2]+" - "+" - "+"Total procesados: "+respu[3]);
                        $("#xFile2").val("");
                       }else{
                        alertify.alert("Error en carga masivo:"+data);
                       }
                    }
                });
    						
    	    }else{ 
                alertify.error("Cancelado");
                $("#confirmation").hide();
    	    }
        })
    });    
	
    </script>
</head>

<div class='row'>
  
    <div class='col-xs-12'>      
          <button class="btn btn-success" style="float:left;margin-left:15px" id="carga_masiva_ent"><i class='fa fa fa-table'></i>&nbsp;&nbsp;Carga masiva de entradas</button>
          <input type="file" id="xFile2" name="" style="float:left;margin-left:15px;border: 2px solid;font-size:14pt" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" onchange="leerExcel(this)">
          <a style="padding-right: 10px;padding-left: 10px;margin-left:15px;color:green;font-size:14pt;float:left;border: 2px solid;" href="plantillas/archivos/base_carga_masiva_entradas.xlsx" >Descargar archivo excel base</a>
          <hr style="background-color: brown;width:100%;margin-top: 2pt;" />
    </div>
    <div  class='col-xs-10 alert alert-info' style="display:none;margin-left:30px" id="confirmation">
        <span id="msg" class="fa fa-check"></span> 
    </div>
</div>
          

        

