$(document).on('click','#new_usuario',function(){
    $.ajax({
        url: 'plantillas/usuarios_crear.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios - Crear usuario");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#mod_usuario',function(){
    $.ajax({
        url: 'plantillas/usuarios_editar.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios - Editar usuario");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#elim_usuario',function(){
    $.ajax({
        url: 'plantillas/usuarios_eliminar.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios - Eliminar usuario");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#permisos',function(){
    $.ajax({
        url: 'plantillas/permisos_usuarios.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios - Permisos usuario");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#new_usuario_faena',function(){
    $.ajax({
        url: 'plantillas/usuarios_faena_crear.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios faenas - Crear usuario de faena");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#mod_usuario_faena',function(){
    $.ajax({
        url: 'plantillas/usuarios_faena_editar.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios faenas - Editar usuario de faena");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#elim_usuario_faena',function(){
    $.ajax({
        url: 'plantillas/usuarios_faena_eliminar.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo usuarios faenas- Eliminar usuario de faena");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/users.png");
        }
    })
   
});
$(document).on('click','#gestion_productos',function(){
    $.ajax({
        url: 'plantillas/productos.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo productos - Gestión de productos");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/productos.png");
        }
    })
   
});
$(document).on('click','#categorias',function(){
    $.ajax({
        url: 'plantillas/categorias.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo productos - Categorías");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/productos.png");
        }
    })
});
$(document).on('click','#subcategorias',function(){
    $.ajax({
        url: 'plantillas/subcategorias.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo productos - Subcategorías");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/productos.png");
        }
    })
});
$(document).on('click','#faenas',function(){
    $.ajax({
        url: 'plantillas/faenas.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo faenas - Gestion de faenas");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/faenas.jpg");
        }
    })
});
$(document).on('click','#faenas_bod',function(){
    $.ajax({
        url: 'plantillas/faenas_bodegas.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo faenas - Bodegas de faenas");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/faenas.jpg");
        }
    })
});
$(document).on('click','#ent_sal',function(){
    $.ajax({
        url: 'plantillas/ent_sal.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo movimientos - Entradas de productos");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/movimientos.png");
        }
    })
});
$(document).on('click','#histo_mov',function(){
    $.ajax({
        url: 'plantillas/historial_mov.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo movimientos - Historial de movimientos");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/movimientos.png");
        }
    })
});
$(document).on('click','#ent_masiva',function(){
    $.ajax({
        url: 'plantillas/entradas_masivas.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo movimientos - Entradas de productos masiva");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/movimientos.png");
        }
    })
});
$(document).on('click','#ped_faenas',function(){
    $.ajax({
        url: 'plantillas/pedidos_faenas.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo envíos-devoluciones - Pedidos desde faenas");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/faenas.jpg");
        }
    })
});
$(document).on('click','#dev_faenas',function(){
    $.ajax({
        url: 'plantillas/devoluciones_faenas.php',
        dataType: 'html',
        success: function(html) {
        $("#contenido").html(html);
        $('#titulo').html("Módulo envíos-devoluciones - Devoluciones desde faenas");
        $("#imagen").css("display","block");
        $("#imagen").attr("src","img/faenas.jpg");
        }
    })
});