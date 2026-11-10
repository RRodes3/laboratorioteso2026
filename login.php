<?php
if (!isset($_SESSION)) {
  session_start();
}


if (isset($_SESSION['U_sdni']) and $_SESSION['U_sdni'] != '' and $_SESSION['U_sptipo'] != '') {

  echo '<script language=javascript>
	self.location="diagrama.php"
</script>';
}
?>

<?php
if (isset($_GET['falloo'])) {
  $mensaje = 'Contraseña no válida.';
} elseif (isset($_GET['fallor'])) {
  $msgerror = 'El usuario que ingresaste es incorrecto.';
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Lab Teso</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  <script language="javascript">
    function validacionrec() {
      var emailrec = document.getElementById('emailrec').value;
      var usuariorec = document.getElementById('usuariorec').value;
      if (emailrec.length == 0 && usuariorec.length == 0) {
        $("#recargadoerror").empty().append('* Debe ingresar por lo menos uno de ellos (Email o usuario)');
        return false;
      }
    }
  </script>
</head>

<body class="hold-transition login-page">

  <div class="login-box">
    <div class="login-logo">
      <h1>Bienvenid@</h1>
      <a>Laboratorio <b>Teso 2026</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Ingresa tus datos para iniciar su sesión</p>

      <form id="loginform" action="proce_login.php" method="post">

        <div class="form-group has-feedback">
          <input id="login-username" autofocus type="text" class="form-control" required name="miusuario" value="" placeholder="Usuario">

          <span class="fa fa-user form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input id="login-password" type="password" class="form-control" required name="mipass" placeholder="password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">

          <!-- /.col -->
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Iniciar sesión</button>
          </div>
          <!-- /.col -->
        </div>
        <?php if (isset($msgerror)) {
          echo '<div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> ' . $msgerror . '
        </div>';
        } ?>
        <?php if (isset($mensaje)) {
          echo '<div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>¡Aviso!</strong> ' . $mensaje . '
        </div>';
        } ?>
      </form>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- jQuery 2.2.3 -->
  <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="../../bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="../../plugins/iCheck/icheck.min.js"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>

  <script>
    Pace.on('hide', function() {
      $("#container").fadeIn('1000');
      $.backstretch([
        "images/taxi-1.jpg",
        "images/taxi-3.jpg"
      ], {
        duration: 5000,
        fade: 1000
      });
    });
  </script>

</body>

</html>