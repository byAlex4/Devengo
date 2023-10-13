<?php
include_once("./template/header.php");

function cerrarSesion()
{
  $_SESSION["Sesion"] = false;
  header("Location: ./login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  cerrarSesion();
}

$sentecia1 = $conexion->prepare("SELECT COUNT(*) FROM contratos");
$sentecia1->execute();
$resultado1 = $sentecia1->fetchColumn();

$sentecia2 = $conexion->prepare("SELECT SUM(mont_max) FROM contratos");
$sentecia2->execute();
$resultado2 = $sentecia2->fetchColumn();

$sentecia3 = $conexion->prepare("SELECT COUNT(*) FROM devengos");
$sentecia3->execute();
$resultado3 = $sentecia3->fetchColumn();

$sentecia4 = $conexion->prepare("SELECT COUNT(*) FROM unidades");
$sentecia4->execute();
$resultado4 = $sentecia4->fetchColumn();
?>

<main class="bodymain mt-3">
    <h1 class="text-gray-800">Página principal</h1>
    <div class="row mb-4">
      <div class="col-xl-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Número de contratos </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  <?php echo $resultado1; ?>
                </div>
              </div>
              <div class="col-auto">
                <img src="./img/paper.png" alt="contratos" width="65px" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Presupuesto</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  <?php echo $resultado2; ?>
                </div>
              </div>
              <div class="col-auto">
                <img src="./img/money.png" alt="contratos" width="65px" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                  Disponibilidad
                </div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  <?php echo $resultado3; ?>
                </div>
              </div>
              <div class="col-auto">
                <img src="./img/check.png" alt="contratos" width="65px" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                  Porcentaje ocupado </div>
              </div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                    <p><?php echo $resultado1*10; ?>%</p>
                  </div>
                </div>
                <div class="col">
                  <div class="progress progress-sm mr-2">
                    <div class="progress-bar bg-info" role="progressbar" style="width:<?php echo $resultado1; ?>0%"
                      aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Uso del presupuesto</h6>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <img src="https://i.postimg.cc/s2vrSHWc/image.png" style="width: 96%;" />
            </div>
          </div>
        </div>
      </div>

      <div class="col-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Division de contratos</h6>
          </div>
          <div class="card-body">
            <div class="chart-pie pt-4 pb-2">
              <img src="https://i.postimg.cc/hjXZpH4C/image.png" style="width: 89%;" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4" style="width: 100%;">
      <div class="col-5">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Unidades</h6>
          </div>
          <div class="card-body">
            <h4 class="small font-weight-bold">Unidad 1 <span class="float-right">20%</span></h4>
            <div class="progress mb-4">
              <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small font-weight-bold">Unidad 2 <span class="float-right">40%</span></h4>
            <div class="progress mb-4">
              <div class="progress-bar bg-warning" role="progressbar" style="width: 40%" aria-valuenow="40"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small font-weight-bold">Unidad 3 <span class="float-right">60%</span></h4>
            <div class="progress mb-4">
              <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                aria-valuemax="100"></div>
            </div>
            <h4 class="small font-weight-bold">Unidad 4 <span class="float-right">80%</span></h4>
            <div class="progress mb-4">
              <div class="progress-bar bg-info" role="progressbar" style="width: 80%" aria-valuenow="80"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <h4 class="small font-weight-bold">OOAD <span class="float-right">Complete!</span></h4>
            <div class="progress">
              <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Mapeado</h6>
          </div>
          <div class="card-body">
            <div class="text-center">
              <img src="https://i.postimg.cc/63LsdkMD/69088539-2957175734353778-4169984679694303232-n.jpg" alt="..."
                width="95%">
            </div>
          </div>
        </div>
      </div>
    </div>
</main>
<?php include_once("./template/footer.php"); ?>