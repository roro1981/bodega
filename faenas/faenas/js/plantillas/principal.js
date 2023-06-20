$(document).on('click','#crea_pedido',function(){
    $.ajax({
        url: 'plantillas/crea_pedidos.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo crear pedidos");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/crea_pedido.png");
        }
    })
   
});
$(document).on('click','#consumo',function(){
    $.ajax({
        url: 'plantillas/consumo_faena.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo para realizar salidas de productos");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/consumo.jpg");
        }
    })
   
});
/*$(document).on('click','#despachos',function(){
    $.ajax({
        url: 'plantillas/ver_despachos.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo visualizar despachos desde bodega central");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/despachos.jpg");
        }
    })
   
});*/
