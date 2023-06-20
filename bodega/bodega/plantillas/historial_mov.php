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
  const quitaAcentos = (str) => {
  return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
} 
});
$('#fecha_desde, #fecha_hasta').datepicker({
        clearText: 'Borra',
		clearStatus: 'Borra fecha actual',
		closeText: 'Cerrar',
		closeStatus: 'Cerrar sin guardar',
		prevText: '<Ant',
		prevBigText: '<<',
		prevStatus: 'Mostrar mes anterior',
		prevBigStatus: 'Mostrar año anterior',
		nextText: 'Sig>',
		nextBigText: '>>',
		nextStatus: 'Mostrar mes siguiente',
		nextBigStatus: 'Mostrar año siguiente',
		currentText: 'Hoy',
		currentStatus: 'Mostrar mes actual',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		monthStatus: 'Seleccionar otro mes',
		yearStatus: 'Seleccionar otro año',
		weekHeader: 'Sm',
		weekStatus: 'Semana del año',
		dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		dayStatus: 'Set DD as first week day',
		dateStatus: 'Select D, M d',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		initStatus: 'Seleccionar fecha',
		isRTL: false
    });
    
$("#btn_ver_movs").unbind('click').bind('click', function () {  
    var tipo=$("#tip_movi").val();
    var desde=$("#fecha_desde").val();
    var hasta=$("#fecha_hasta").val();
    var idprod=$("#produ_movi").val();
    if(tipo==0 || idprod==0 || desde=="" || hasta==""){
        alertify.error("Debe ingresar tipo de movimiento, producto y rango de fechas de consulta");
        return false;
    }
    
    var f1=desde.split("/");
    var f2=hasta.split("/");
    desde=f1[2]+"/"+f1[1]+"/"+f1[0];
    hasta=f2[2]+"/"+f2[1]+"/"+f2[0];
    desde2=f1[0]+"-"+f1[1]+"-"+f1[2];
    hasta2=f2[0]+"-"+f2[1]+"-"+f2[2];
    if(Date.parse(hasta) < Date.parse(desde)){
      alertify.error("Fecha inicial (desde) no puede ser mayor a la final (hasta)");
      return false;
    }
  $.ajax({
		type	: "POST",
		url		: "ajax/ajax_productos.php",
		async	: true,
		data	: {
		opt		: 'trae_movis',
    	tipo_mov 	: tipo,
    	idp         : idprod,
    	fec_desde   : desde,
    	fec_hasta   : hasta
		},
	
		success: function(r){
		    var respu=$.trim(r);
		    if(respu !="NO"){
		       $(".movis_det").html(respu);
		       $("#datos_repo").val("Movimientos "+$('#produ_movi option:selected').text()+" "+desde2+" al "+hasta2);
		       $("#datos_repo2").val($('#tip_movi option:selected').text());
		       $("#tmov").val($('#tip_movi').val());
		       $("#idpr").val(idprod);
		       $("#fde").val(desde);
		       $("#fha").val(hasta);
		    }
		}
    });	
});
$("#excel").unbind('click').bind('click', function () {
    var filas=0;
    var tipo=$("#tmov").val();
    var id=$("#idpr").val();
    var fec1=$("#fde").val();
    var fec2=$("#fha").val();
    $("#tbl_movis tbody tr").find('td:eq(0)').each(function () {
			filas+=1;	
    });
    if(filas>0){
     window.location.replace("plantillas/excel_exportaciones/excel_movimientos.php?tipo_mov="+tipo+"&idprod="+id+"&desde="+fec1+"&hasta="+fec2);
    }else{
        alertify.error("No hay registros para exportar a Excel");
    }
})
$("#pdf").unbind('click').bind('click', function () {
    var filas=0;
    $("#tbl_movis tbody tr").find('td:eq(0)').each(function () {
			filas+=1;	
    });
    if(filas>0){
        var nom_archi=$("#datos_repo").val(); 
        var prod=$('#produ_movi option:selected').html();
        var doc = new jspdf.jsPDF()
            // Simple html example
            doc.text("Tipo movimiento: "+$("#datos_repo2").val(), 15, 15)
            doc.text("Producto: "+prod, 15, 21)
            doc.autoTable({ html: '#tbl_movis', startY: 22})
            doc.save(nom_archi+'.pdf')
    }else{
        alertify.error("No hay registros para exportar a PDF");
    }    
})

</script> 
</head>
<input type="hidden" id="datos_repo" />
<input type="hidden" id="datos_repo2" />
<input type="hidden" id="tmov" />
<input type="hidden" id="idpr" />
<input type="hidden" id="fde" />
<input type="hidden" id="fha" />
        <div id="cabecera2"> 
             <div style="margin-top:5px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>TIPO MOVIMIENTO(*):</label>
                <select class="form-control" id="tip_movi">
                    <option value="0" selected>-- Seleccione --</option> 
                    <option value="1">TODOS</option>
                    <option value="2">ENVIO A FAENA</option>
                    <option value="3">DEVOLUCIONES</option>
                    <option value="4">ENTRADAS</option> 
                </select>     
            </div>
            <div style="margin-top:5px" class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>PRODUCTO(*):</label>
                            
                            <select id="produ_movi" class="selectpicker form-control" data-live-search="true" required="campo obligatorio">
                            <option value="0">-- Seleccione producto --</option>  
                            <div id="select_prods">
                            	<?php
                            	    $prod=new productosDao();
                            	    $prod2=new productosDao();
                                    $list_prod = $prod->listarProductosActivos();  
                                    foreach($list_prod AS $prod){
                                     echo "<option value=".$prod['id'].">".$prod['descripcion']."(STOCK ".$prod2->traeStock($prod['id']).")</option>"; 
                                    }
                                ?>
                             </div>    
                            </select>  
                           
            </div>
             <div style="margin-top:5px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>DESDE(*):</label>
                <input id="fecha_desde" class="form-control" readonly required />
             </div>
             <div style="margin-top:5px" class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label>HASTA(*):</label>
                <input id="fecha_hasta" class="form-control" readonly required />
             </div>
              <div style="margin-top:30px" class="form-group col-lg-1 col-md-1 col-sm-1 col-xs-12">
                <button class="btn btn-success" id="btn_ver_movs"><i class="fa fa-archive"></i> Ver movimientos</button>
             </div>
             <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-success" id="excel"><i class="fa fa-file-excel-o"></i> Exportar a excel</button>
                <button class="btn btn-danger" id="pdf"><i class="fa fa-file-pdf-o"></i> Exportar a pdf</button>
             </div>
        </div>
         
      <div id="tabla" class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
        <table id="tbl_movis" class="table table-responsive table-hover table-fixed">
          <thead style="background-color:#A9D0F5">
                <tr>
                <th style="text-align:center">Fecha movimiento</th>
                <th>Producto</th>
                <th style="text-align:center">Tipo movimiento</th>
                <th style="text-align:center">Cantidad</th>
                <th style="text-align:center">Stock</th>
                <th style="text-align:center">Observación</th>
            </tr></thead>
            <tbody class='movis_det'>
              
            </tbody>
        </table>
        
      </div>
