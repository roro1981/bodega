<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
session_start();
if(!isset($_SESSION['autentificado'])){
    session_destroy();
   echo "<SCRIPT>window.open('/../index.php','_self');</SCRIPT>";
}  
require 'includes/functions.php';
require_once 'includes/Conexion.php';
require 'DAO/UsersDao.php';
date_default_timezone_set('America/Santiago');
$fecha = date('d-m-Y');
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href='js/fullcalendar-4.4.2/packages/core/main.css' rel='stylesheet' />
    <link href='js/fullcalendar-4.4.2/packages/daygrid/main.css' rel='stylesheet' />
    <link href='js/fullcalendar-4.4.2/packages/timegrid/main.css' rel='stylesheet' />
    <link href='js/fullcalendar-4.4.2/packages/list/main.css' rel='stylesheet' />
    <script src='js/fullcalendar-4.4.2/packages/core/main.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/core/locales-all.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/interaction/main.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/daygrid/main.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/timegrid/main.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/list/main.js'></script>
    <script src='js/fullcalendar-4.4.2/packages/google-calendar/main.js'></script>
    <script src="js/jsPDF/examples/libs/jspdf.umd.js"></script>
    <script src="js/jsPDF/dist/jspdf.plugin.autotable.js"></script>
    <script type="text/javascript" src="js/excel_js/table_export/libs/FileSaver/FileSaver.min.js"></script>
    <script type="text/javascript" src="js/excel_js/table_export/tableExport.js"></script>
    <script type="text/javascript" src="js/excel_js/table_export/libs/js-xlsx/xlsx.core.min.js"></script>
    <script src="js/excel_js/table2excel/src/jquery.table2excel.js"></script>
    <script type="text/javascript" src="js/xlsx.mini.js" ></script> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PROACTIVE | Principal</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Css -->
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="css/_all-skins.min.css">
    
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="stylesheet" type="text/css" href="js/sb/shadowbox.css" />
	<!--<link rel="stylesheet" type="text/css" href="resources/pdf/fpdf.css" />-->
    <script type="text/javascript" src="resources/alertify/lib/alertify.js"></script>
  
    <link rel="stylesheet" href="resources/alertify/themes/alertify.core.css" />
    <link rel="stylesheet" href="resources/alertify/themes/alertify.default.css" />
	<!-- Javascript -->
	<!-- jQuery 2.1.4 -->
    <script src="js/jQuery-2.1.4.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="js/plantillas/principal.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/app.min.js"></script>
    <script type="text/javascript" src="js/sb/shadowbox.js"></script>
    <script type="text/javascript" src="js/js.js?<?php echo date("YmdHis")+1; ?>"></script>
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.css">
    <script type="text/javascript" charset="utf8" src="js/DataTables/datatables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/stacktable.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css'>
    <script type="text/javascript" charset="utf8" src="js/stacktable.js"></script>
    <script type="text/javascript" charset="utf8" src="js/jquery-ui/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="js/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/Ludens-Users---2-Simple-Registration-Section.css">
    <link rel="stylesheet" href="js/multiselect/dist/bootstrap-multiselect.css" type="text/css">
    <script type="text/javascript" src="js/multiselect/dist/bootstrap-multiselect.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
	<script type="text/javascript">
	
    Shadowbox.init({ viewportPadding	: 5 });
    
    </script>
    <style>
	.iframe-container {    
    padding-bottom: 60%;
    padding-top: 30px; height: 0; overflow: hidden;
		}
		 
		.iframe-container iframe,
		.iframe-container object,
		.iframe-container embed {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}
		
	</style>
<script type="text/javascript">
	(function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);
	function formatRut(rut)
	{
	  rut.value=rut.value.replace(/[.-]/g, '')
	  .replace( /^(\d{1,2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4')
	}
$(document).on({
	click: function () {
		
		$.ajax({
			type	: "POST",
			url		: "ajax/ajax_login.php",
			async	: true,
			data	: {
				opt		: 'cerrar_sesion'
				
			},
			success: function(ret)
			{
			  alertify.success('Cerrando sesión...');
			  window.open('index.php', "_self");
				
			}
		});
		return false;
	}

},"#cierre"); 

</script>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
  
    <div class="wrapper">
    <header class="main-header">

        <!-- Logo -->
        <a href="principal.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>P</b>A</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><img class="profile-img-card" style="border-color:blue;border:solid 1px" width="120" height="40" src="assets/img/logo.png"></b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <small style="margin-right:5px" class="bg-green usuario_activo">Usuario activo: <?php echo $_SESSION['nombre']; ?> - Faena: <?php echo $_SESSION['faena']; ?></small>
                   <i class="fa fa-power-off"></i>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
               
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-center">
                      <button type="button" id="cierre" class="btn btn-danger btn-lg btn-block" >Cerrar Sesión</button>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
    
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
                    
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            
           <!--inicio menu -->
             <li id="crea_pedido" class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>CREAR PEDIDO</span>
              </a>
            </li>
            <li id="consumo" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>CONSUMO</span>
              </a>
            </li>
            <li id="despachos" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>DESPACHOS</span>
              </a>
            </li>
            <!--fin menu -->
            
         </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div style="background-color:#33DAFF" class="box-header with-border">
                  <h4 id="titulo" style="color:white" class="box-title">Principal</h4>
                   <img id="imagen" style="display:none;float:right" src="" width="40px" height="30px"/>
                  <font style="float:right;margin-right:20px"><i style="padding-left:60px" class='fa fa fa-calendar'></i> <?php echo obtenerFechaEnLetra($fecha);?></font>
                 
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  	<div class="row">
	                  	<div id="contenido" class="col-md-12">
	                  	 

                           </div>
                        </div>
		                    
                  		</div>
                  		
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
      <<footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b></b>
        </div>
        <strong><a href="#"></a>.</strong>
      </footer>

      
    
  </body>
</html>
