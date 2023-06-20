<?php 
session_start();
require '../includes/functions.php';
require '../includes/Conexion.php';
require('../DAO/FaenasDao.php');
?>
<head>
<script type="text/javascript">
$(document).on('change','#fae',function(e){
var idFaena=$(this).val();   
if(idFaena==0){
    $('#tabla_productos_faenas').DataTable().clear().draw();
    return false;
}
$(".loader-background2").show();
$("#master2").addClass("desactiva_pantalla"); 
        $.ajax({
        type : 'POST',
        url  : 'ajax/ajax_faenas.php',
        data	: {
        opt		: 'trae_productos',
        faena   : idFaena
    		},
        dataType: 'json',
    
        success :  function(result){
            console.log(result);
            $('#tabla_productos_faenas').DataTable({
                responsive: true,
                destroy: true,
               "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"},
                "columnDefs": [
                    { "width": "250px", "targets": 2 },
                    {"className": "dt-center", "targets": 0},
                    {"className": "dt-center", "targets": 1},
                    {"className": "dt-left", "targets": 2},
                    {"className": "dt-center", "targets": 3},
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 5},
                    {"className": "dt-center", "targets": 6},
                    {"className": "dt-center", "targets": 7}
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
                  {"data" : "cat"}
                  ],
                  dom: 'lBfrtip',
                  buttons: [
                  'excel', 'csv', 'pdf', 'print', 'copy',
                  ]
                   
            });
             $(".loader-background2").hide();
             $("#master2").removeClass("desactiva_pantalla");
        
                }
    });
});

    </script>
    <style>
        .loader-background2 {
            /*width: 100%;
            height: 100%;*/
            position: relative; /* Cambiamos de absolute a relative */
            background-color: rgba(0,0,0,0.7);
            z-index:50;
        }
        
        .loader2{
          border: 16px solid #d4d4d4;
          border-top: 16px solid #3498db;
          border-bottom: 16px solid #3498db;
          border-radius: 50%;
          width: 60px;
          height: 60px;
          animation: spin2 1.5s linear infinite;
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

@keyframes spin2 {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    </style>
</head>

<div id="master2" class='row'>
  
    <div class='col-xs-12'>
          <div style="width:100%">
              <div class='box-header' style="width:100%">
                <h3 class='box-title'>Control de bodegas de faenas</h3>
              </div> 
          </div>      
    </div>
    <div class='col-xs-12'>      
            <select class="form-control" style="width:30% !important" id="fae">
			<option value=0>Seleccione bodega</option>
			<?php 
			$fae=new faenasDao();
			$list_fae = $fae->listarFaenas();  
            foreach($list_fae AS $fa){
             echo "<option value=".$fa['id'].">".$fa['nombre_faena']."</option>";  
            }
			?>
		  </select>
          <hr style="background-color: brown;width:100%;margin-top: 2pt;" />
    </div>
    
    <div class='col-xs-12'>
        <div style="display:none" class="loader-background2">
      <div class="loader2"></div>
    </div>
          <table id='tabla_productos_faenas' class="display" style="width:100%">
           <thead>
           <tr style="background-color: #01338d;color:white">
               <th>CODIGO</th><th>CODIGO LAUDUS</th><th>DESCRIPCION</th><th>PRECIO NETO</th><th>STOCK</th><th>IMPUESTO</th><th>PRECIO BRUTO</th><th>CATEGORIA</th>
           </tr>
           </thead>
           <tbody class="listado">
           </tbody>
          </table>
    </div>

</div>
          

        

