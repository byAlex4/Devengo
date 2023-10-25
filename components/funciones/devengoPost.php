<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscDesc'])
    || isset($_POST['bscMonto'])
    || isset($_POST['bscContrato'])
    || isset($_POST['bscUnidad'])
    || isset($_POST['bscFecha'])
) {
    $consultaSQL = "SELECT devengos.id, devengos.proveedros, devengos.fecha, devengos.descripcion, devengos.monto, 
    devengos.created_at, devengos.updated_at, 
    contratos.clave AS contrato, 
    contratos.mont_max AS saldo,
    (contratos.mont_max - (SELECT SUM(monto) FROM devengos WHERE contratoID = contratos.id)) AS saldoDis, 
    usuarios.nombre AS usuario, 
    unidades.nombre AS unidad 
    FROM devengos 
    JOIN contratos ON devengos.contratoID = contratos.id 
    JOIN usuarios ON devengos.usuarioID = usuarios.id 
    JOIN unidades ON usuarios.unidadID = unidades.id ";
    if (!empty($_POST['bscDesc'])) {
        $consultaSQL .= "WHERE devengos.descripcion LIKE '%" . $_POST['bscDesc'] . "%'";
    }
    if (!empty($_POST['bscMonto'])) {
        $consultaSQL .= "WHERE devengos.monto >=" . $_POST['bscMonto'];
    }
    if (!empty($_POST['bscContrato'])) {
        $consultaSQL .= "WHERE contratos.clave LIKE'%" . $_POST['bscContrato'] . "%'";
    }
    if (!empty($_POST['bscUnidad'])) {
        $consultaSQL .= "WHERE unidades.nombre LIKE'%" . $_POST['bscUnidad'] . "%'";
    }
    if (!empty($_POST['bscFecha'])) {
        $consultaSQL .= "WHERE DATE_FORMAT(devengos.fecha, '%Y-%m') = '" . $_POST['bscFecha'] . "'";
    }

    $sentecia = $conexion->prepare($consultaSQL);
    $sentecia->execute();

    $resultados = $sentecia->fetchAll(PDO::FETCH_ASSOC);

    // Comprobamos si hay resultados
    if (count($resultados) > 0) {
        // Devolvemos el resultado en formato JSON
        echo json_encode($resultados);
    } else {
        // Mostramos un mensaje indicando que no hay resultados
        echo json_encode(array("message" => "No se la unidad"));
    }

} else {
    if (isset($_POST['shw'])) {
        try {
            // Obtener el valor de 'id' del cuerpo de la solicitud POST
            $id = $_POST['shw'];
            $consulta = "SELECT * FROM devengos
                WHERE devengos.id = $id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'provedor' => $show['proveedros'],
                'fecha' => $show['fecha'],
                'clave' => $show['contratoID'],
                'monto' => $show['monto'],
                'desc' => $show['descripcion'],
                'usuario' => $show['usuarioID']
            );

            $json = $respuesta;
            // Devolver la respuesta como JSON
        } catch (Exception $error) {
            $json = $error;
        }

        echo json_encode($json);
    } else {
        if (isset($_POST['contr'])) {
            try {
                // Obtener el valor de 'id' del cuerpo de la solicitud POST
                $id = $_POST['contr'];
                $consulta = "SELECT *, 
                (contratos.mont_max - (SELECT SUM(monto) FROM devengos WHERE contratoID = contratos.id)) AS saldoDis 
                FROM contratos WHERE contratos.id = $id";

                $sentecia = $conexion->prepare($consulta);
                $sentecia->execute();

                $contrato = $sentecia->fetch(PDO::FETCH_ASSOC);
                // Devolver la respuesta como JSON
            } catch (Exception $error) {
                $contrato = $error;
            }
            try {
                $consultaSQL = "SELECT SUM(monto) AS monto, DATE_FORMAT(fecha, '%M %Y') AS mes, 
                SUM(monto) OVER (ORDER BY month(fecha)) AS acumulado
                FROM `devengos`
                WHERE `contratoID` = $id
                GROUP BY month(fecha)
                ORDER BY month(fecha) ASC;";

                $sentencia = $conexion->prepare($consultaSQL);
                $sentencia->execute();
            } catch (PDOException $error) {
                $error = $error->getMessage();
            }

            $html = "<div class='row'>
                        <div class='col-9'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-black mt-1'>
                                    <h3>Contrato:</h3>
                                </li>
                                <li class='h4 text-black mt-1'>Fecha de inicio:</li>
                                <li class='h4 text-black mt-1'>Fecha de fin:</li>
                                <li class='h4 text-black mt-1'>Monto maximo:</li>
                                <li class='h4 text-black mt-1'>Monto minimo:</li>
                    
                            </ul>
                        </div>
                        <div class='col-3' style='text-align: right;'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-muted mt-1'>" . $contrato['clave'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['fecha_in'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['fecha_fin'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['mont_max'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['mont_min'] . "</li>
                    
                            </ul>
                        </div>
                    </div>
                    <div class='row m-3'>
                    ";
            $total = 0;
            // Verificar si hay resultados
            if ($sentencia->rowCount() > 0) {
                // Crear una tabla para mostrar los datos
                $html .= "<div class='col-4'><p>Fecha</p></div><div class='col-6'><p class='float-end'>Total del mes</p></div> <hr>";
                // Recorrer los resultados y mostrarlos en la tabla
                while ($fila = $sentencia->fetch(PDO::FETCH_ASSOC)) {
                    $html .= "<div class='col-4'><p>" . $fila['mes'] . "</p></div><div class='col-6'><p class='float-end'>$" . $fila['monto'] . "</p></div><hr>";
                    $total = $fila['acumulado'];
                }
            } else {
                // No hay resultados
                $html .= "No se encontraron datos";
            }
            $html .= "</div>
                    <div class='row text-black'>
                        <div class='col-xl-12'>
                            <p class='float-end fw-bold'>
                                Total: $" . $total . "
                            </p>
                        </div>
                        <hr style='border: 2px solid black;'>
                        <div class='col-9'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-black mt-1'>Saldo disponible:</li>
                            </ul>
                        </div>
                        <div class='col-3'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-black mt-1'>$" . $contrato['saldoDis'] . "</span>
                                </li>
                            </ul>

                        </div>
                        <hr style='border: 2px solid black;'>
                    </div>";
            if ($contrato['saldoDis'] <= ($contrato['mont_max'] * 0.3)) {
                $html .= "<script>Swal.fire(
                            'Advertencia',
                            'El saldo del contrato esta cerca de agotarse',
                            'warning')
                        </script>";
            }
            if ($contrato['saldoDis'] <= 0) {
                $html .= "<script>Swal.fire(
                            'Advertencia',
                            'El saldo del contrato es nullo',
                            'error')
                        </script>";
            }
            echo ($html);
        } else {
            if (isset($_POST['editar'])) {
                $error = false;
                try {
                    // Obtener el valor de 'id' del cuerpo de la solicitud POST
                    $id = $_POST['editar'];
                    $provedor = $_POST['provedor'];
                    $fecha = $_POST['fecha'];
                    $clave = $_POST['clave'];
                    $desc = $_POST['descripcion'];
                    $monto = $_POST['monto'];
                    $usuario = $_POST['usuario'];

                    $consulta = "UPDATE devengos SET proveedros='" . $provedor . "', fecha='" . $fecha . "', descripcion= '" . $desc . "', monto='" . $monto . "', usuarioID='" . $usuario . "', contratoID='" . $clave . "', updated_at=NOW() WHERE id='" . $id . "'";
                    $sentecia = $conexion->prepare($consulta);
                    $sentecia->execute();
                    $response = $sentecia;
                } catch (Exception $error) {
                    $response = $error;
                }
                echo json_encode(array($response));
            } else {
                if (
                    isset($_POST['provedor'])
                    && isset($_POST['fecha'])
                    && isset($_POST['monto'])
                    && isset($_POST['usuario'])
                    && isset($_POST['descripcion'])
                    && isset($_POST['clave'])
                ) {
                    try {
                        $devengo = array(
                            'proveedros' => $_POST['provedor'],
                            'fecha' => $_POST['fecha'],
                            'monto' => $_POST['monto'],
                            'descripcion' => $_POST['descripcion'],
                            'usuarioID' => $_POST['usuario'],
                            'contratoID' => $_POST['clave']
                        );

                        $consultaCrear = "INSERT INTO devengos (proveedros, fecha, monto, descripcion, usuarioID, contratoID)";
                        $consultaCrear .= "VALUES (:" . implode(", :", array_keys($devengo)) . ")";
                        $sentenciaCrear = $conexion->prepare($consultaCrear);
                        $sentenciaCrear->execute($devengo); // Aquí pasamos el arreglo como parámetro

                        echo json_encode(array($devengo));
                    } catch (PDOException $error) {
                        echo json_encode(array('error' => true, 'mensaje' => $error->getMessage()));
                        echo json_encode(array($consultaCrear));
                        echo json_encode(array($devengo));
                    }

                }
            }
        }
    }
}
?>