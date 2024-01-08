<!doctype html>
<html lang="en" data-bs-theme="auto" style="width: 100%;">

<head>
  <script src="../bootstrap-5.3.2-dist/js/bootstrap.js"></script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.118.2">
  <title>Iniciar Sesión</title>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sign-in/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link href="../bootstrap-5.3.2-dist/css/bootstrap.css" rel="stylesheet">
  <link rel="shortcut icon" href="img/imsslogo.ico" />
  <link href="styles/style.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="styles/login.css" rel="stylesheet">
</head>

<body class="align-items-center">
  <?php
  $error = false;
  $config = include '../config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
  } catch (PDOException $error) {
    $error = $error->getMessage();
  }

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
  } ?>
  <main style="margin: 2%;">
    <div class="container2">
      <div class="mb-3 text-center" style="display: flex;">
        <div class="col-6 themed-grid-col1 align-items-center">
          <img class="doc" src="./img/docPhoto.png" alt="img_login"></img>
        </div>
        <div class="col-6 themed-grid-col2">
          <div class="form-signin m-2" style='min-width: 95%;'>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <img class="mb-3" src="img/logo-imss-green.png" alt="logo imss" width="120px" height="140px">
              <h2 class="mb-3 fw-normal">Iniciar sesión</h2>
              <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username">
                <label for="username" style="color: gray">Matricula</label>
              </div>
              <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password">
                <label for="password" style="color: gray">Contraseña</label>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  $username = $_POST["username"];
                  $pass = $_POST["password"];

                  if (empty($username) && empty($pass)) {
                    echo "Por favor llena ambos campos.";
                  } else if (empty($username)) {
                    echo "Por favor ingresa el usuario.";
                  } else if (empty($pass)) {
                    echo "Por favor ingresa la contraseña.";
                  } else {
                    // Preparar la consulta SQL
                    $stmt = $conexion->prepare(
                      "SELECT usuarios.id, usuarios.matricula, usuarios.nombre, unidades.nombre AS unidad, roles.nombre AS rol, usuarios.contra 
                      FROM usuarios  
                      JOIN unidades ON usuarios.unidadID = unidades.id 
                      JOIN roles ON usuarios.rolID = roles.id
                      WHERE matricula = ?"
                    );
                    $stmt->execute([$username]);
                    $user = $stmt->fetch();
                    if ($user) {
                      // Verificar si la contraseña es correcta
                      if ($user && password_verify($pass, $user['contra'])) {
                        session_start();
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['matricula'] = $user['matricula'];
                        $_SESSION['nombre'] = $user['nombre'];
                        $_SESSION['unidad'] = $user['unidad'];
                        $_SESSION['rol'] = $user['rol'];
                        if ($user['rol'] == "Administrador") {
                          echo "Usted es admin!";
                          header("Location: ./usuarios.php");
                        } else
                          header("Location: ./index.php");
                      } else
                        echo ("La contraseña es incorrecta.");
                    } else
                      echo "El nombre de usuario no existe. <br>";
                  }
                }
                ?>
              </div>
              <button class="btn btn-success w-100 py-2" type="submit" style="margin-top: 10%;">Iniciar
                sesión</button>
              <p class="mt-5 mb-3"> Instituto Mexicano de Seguro&copy <br>
                En caso de dudas comunicarse al 4491234567</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../bootstrap-5.3.2-dist/js/bootstrap.bundle.js"></script>
</body>

</html>