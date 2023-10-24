<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Devengo</title>
  <link href="../bootstrap-5.3.2-dist/css/bootstrap.css" rel="stylesheet">
  <link rel="shortcut icon" href="./img/imsslogo.ico" />
  <link rel="stylesheet" href="../sweetalert2-11.7.31/dist/sweetalert2.min.css">

  <!-- Custom styles for this template -->
  <link href="./styles/style.css" rel="stylesheet">
  <link href="./styles/navbars.css" rel="stylesheet">

</head>

<body class="col-md-12" style="background-color: #ffff important;">
  <?php
  $error = false;
  $config = include '../config.php';

  if ($error) {
    ?>
    <div class="container mt-2">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-danger" role="alert">
            <?= $error ?>
          </div>
        </div>
      </div>
    </div>
    <?php
  }
  // Iniciar la sesión
  session_start();
  // Establecer el tiempo de vida de la sesión en segundos
  $expireAfter = 60;
  //Comprobar si nuestra "última acción" está configurada
  if (isset($_SESSION['last_action'])) {
    // Calcular el tiempo transcurrido desde la última acción
    $secondsInactive = time() - $_SESSION['last_action'];
    // Convertir nuestra duración en segundos a minutos.
    $expireAfterSeconds = $expireAfter * 60;
    // Comprobar si es mayor al maximo tiempo
    if ($secondsInactive >= $expireAfterSeconds) {
      // Matar su sesión.
      session_unset();
      session_destroy();
    }
  }

  // Asignar la marca de tiempo de la última actividad del usuario a la variable de sesión
  $_SESSION['last_action'] = time();

  if (!isset($_SESSION['matricula'])) {
    echo '<script>alert("Debe iniciar sesión para acceder a esta página.");</script>';
    header('Location: login.php');
    exit;
  }


  function closeSession()
  {
    session_unset();
    session_destroy();
    header("Location: login.php");
  }

  if (isset($_POST['cerrar_sesion'])) {
    closeSession();
  }
  ?>
  <header style="position: initial">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success bg-gradient" style="padding: 5px;">
      <div class="container-fluid">
        <a class="navbar-brand " href="./main.php" aria-label="Bootstrap">
          <img class="img-fluid" src="img/imsslogo.png" width="40" height="40" alt="logoNav" class="d-block my-1"
            fill="currentColor" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
          aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup" style="width: 50%;">
          <div class="navbar-nav">
            <?php if ($_SESSION['rol'] == "Administrador") { ?>
              <a class="nav-link" href="./main.php" style="color: #ffffff; !important">Inicio</a>
              <a class="nav-link" href="./contratos.php" style="color: #ffffff; !important">Contratos</a>
              <a class="nav-link" href="./devengos.php" style="color: #ffffff; !important">Devengos</a>
              <a class="nav-link" href="./usuarios.php" style="color: #ffffff; !important">Usuarios</a>
              <a class="nav-link" href="./unidades.php" style="color: #ffffff; !important">Unidades</a>
            <?php } else { ?>
              <a class="nav-link" href="./main.php" style="color: #ffffff; !important">Inicio</a>
              <a class="nav-link" href="./devengos.php" style="color: #ffffff; !important">Devengo</a>
            <?php } ?>
          </div>

          <div class="navbar-nav ms-md-auto p-0 me-4" style="max-height: 57px; z-index: 1">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown"
                aria-expanded="false" style="color: #ffffff; !important">
                <?php echo $_SESSION['rol']; ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="">
                <li><a class="dropdown-item" href="#">
                    <?php echo $_SESSION['nombre']; ?>
                  </a></li>
                <li><a class="dropdown-item" href="#">Cambiar a usuario</a></li>
                <li>
                  <form method="post">
                    <button class="dropdown-item" type="submit" name="cerrar_sesion">
                      Cerrar sesion
                    </button>
                  </form>
                </li>
              </ul>
            </li>
          </div>
        </div>
      </div>
    </nav>
  </header>