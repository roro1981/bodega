<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>PROACTIVE</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/trelyco-login-vertical-horizontal-1.css">
    <link rel="stylesheet" href="assets/css/trelyco-login-vertical-horizontal.css">
    <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="js/jQuery-2.1.4.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

<!-- 
    RTL version
-->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.rtl.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.rtl.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.rtl.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>
    <script type="text/javascript">
	$(document).on({
	click: function () {
		var user	= $("#inputUser").val();
		var clave	= $("#inputPassword").val();
		if(user=="" || clave==""){
			alertify.error("Ingrese usuario y password");
			return false;
		}
		$.ajax({
			type	: "POST",
			url		: "ajax/ajax_login.php",
			async	: true,
			data	: {
				opt		: 'login',
				usu	: user,
				psw	: clave
			},
			success: function(ret)
			{
				if(ret==0){
				    alertify.error("Usuario o password incorrecto");
					
				}else{
				    window.open('principal.php', "_self");
				}
				
			}
		});
		return false;
	}

},"#ingreso");
</script>
</head>

<body>
<div class="login-card"><img style="margin-left: 40px;" class="profile-img-card" src="assets/img/logo.png">
        <form class="form-signin">
            <span class="reauth-email"> </span><input class="form-control" type="text" id="inputUser" required="" placeholder="Nombre de Usuario" autofocus=""><input class="form-control" type="password" id="inputPassword" required="" placeholder="Contraseña">
            <div class="checkbox"></div><button id="ingreso" class="btn btn-primary btn-lg d-block btn-signin w-100" >Entrar </button>
        </form>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>