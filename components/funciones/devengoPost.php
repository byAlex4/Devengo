<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscCuenta'])
    || isset($_POST['bscMonto'])
    || isset($_POST['bscContrato'])
    || isset($_POST['bscUnidad'])
    || isset($_POST['bscFecha'])
) {
    // Iniciar la sesión
    session_start();
    $unidad = $_SESSION['unidad'];

    // Preparamos la consulta SQL para obtener los datos de los usuarios, sus unidades y sus roles
    if ($_SESSION['rol'] == "Administrador") {
        $consultaSQL =
            "SELECT
            devengos.id,
            DATE_FORMAT(devengos.fecha, '%d-%M-%Y') AS fecha,
            devengos.descripcion,
            FORMAT(devengos.monto, 3, 'es-MX') AS monto_formato,
            DATE_FORMAT(devengos.created_at, '%d-%M-%Y') AS created_at,
            DATE_FORMAT(devengos.updated_at,'%d-%M-%Y') AS updated_at,
            cuentas.cuenta as cuenta,
            devengos.contratoID AS contratoID,
            contratos.clave AS contrato,
            FORMAT(contratos.mont_max, 3, 'es-MX') AS saldo, 
            FORMAT((contratos.mont_max - (SELECT SUM(monto)FROM devengos
            WHERE contratoID = contratos.id)),3, 'es-MX') AS saldoDis,
                usuarios.nombre AS usuario,
                unidades.nombre AS unidad,
                contratos.proveedor AS proveedor
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id ";
    } else {
        $consultaSQL =
            "SELECT
            devengos.id,
            DATE_FORMAT(devengos.fecha, '%d-%M-%Y') AS fecha,
            devengos.descripcion,
            FORMAT(devengos.monto, 3, 'es-MX') AS monto_formato,
            DATE_FORMAT(devengos.created_at, '%d-%M-%Y') AS created_at,
            DATE_FORMAT(devengos.updated_at,'%d-%M-%Y') AS updated_at,
            cuentas.cuenta as cuenta,
            devengos.contratoID AS contratoID,
            contratos.clave AS contrato,
            FORMAT(contratos.mont_max, 3, 'es-MX') AS saldo, 
            FORMAT((contratos.mont_max - (SELECT SUM(monto)FROM devengos
            WHERE contratoID = contratos.id)),3, 'es-MX') AS saldoDis,
                usuarios.nombre AS usuario,
                unidades.nombre AS unidad,
                contratos.proveedor AS proveedor
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id 
            WHERE unidades.nombre = '" . $_SESSION['unidad'] . "' ";
    }
    if (!empty($_POST['bscCuenta'])) {
        $consultaSQL .= "AND cuentas.cuenta LIKE '" . $_POST['bscCuenta'] . "%'";
    }
    if (!empty($_POST['bscMonto'])) {
        $consultaSQL .= "AND devengos.monto >=" . $_POST['bscMonto'];
    }
    if (!empty($_POST['bscContrato'])) {
        $consultaSQL .= "AND contratos.clave LIKE'%" . $_POST['bscContrato'] . "%'";
    }
    if (!empty($_POST['bscUnidad'])) {
        $consultaSQL .= "AND unidades.nombre LIKE'%" . $_POST['bscUnidad'] . "%'";
    }
    if (!empty($_POST['bscFecha'])) {
        $consultaSQL .= "AND DATE_FORMAT(devengos.fecha, '%Y-%m') = '" . $_POST['bscFecha'] . "'";
    }

    $sentecia = $conexion->prepare($consultaSQL);
    $sentecia->execute();

    $resultados = $sentecia->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($resultados);
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
                'fecha' => $show['fecha'],
                'clave' => $show['contratoID'],
                'monto' => $show['monto'],
                'desc' => $show['descripcion']
            );

            $json = $respuesta;
            // Devolver la respuesta como JSON
        } catch (Exception $error) {
            $json = $error;
        }

        echo json_encode($json);
    } else {
        if (isset($_POST['contr'])) {
            // Iniciar la sesión
            session_start();
            $unidad = $_SESSION['unidad'];
            try {
                // Obtener el valor de 'id' del cuerpo de la solicitud POST
                $id = $_POST['contr'];
                $consulta = "SELECT 
                contratos.clave, 
                contratos.descripcion, 
                contratos.mont_max, 
                contratos.mont_min,         
                DATE_FORMAT( contratos.fecha_in, '%d-%M-%Y') AS fecha_in,
                DATE_FORMAT( contratos.fecha_fin, '%d-%M-%Y') AS fecha_fin,
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
                if ($_SESSION['rol'] == "Administrador") {
                    $consultaSQL = "SELECT
                    SUM(d.monto) AS monto,
                    MONTH(d.fecha) AS mes,
                    d.descripcion,
                    (SELECT SUM(d2.monto)
                        FROM devengos d2
                        JOIN usuarios u2 ON d2.usuarioID = u2.id
                        WHERE u2.unidadID = u.unidadID AND MONTH(d2.fecha) <= MONTH(d.fecha)
                    ) AS acumulado,
                    un.nombre AS unidad
                    FROM
                        devengos d
                    JOIN usuarios u ON d.usuarioID = u.id
                    JOIN unidades un ON u.unidadID = un.id
                    WHERE `contratoID` = $id
                    GROUP BY
                        MONTH(d.fecha),
                        u.unidadID,
                        d.descripcion
                    ORDER BY
                        MONTH(d.fecha) ASC,
                        u.unidadID ASC;";
                } else {
                    $consultaSQL = "SELECT
                    SUM(d.monto) AS monto,
                    MONTH(d.fecha) AS mes,
                    d.descripcion,
                    (SELECT SUM(d2.monto)
                        FROM devengos d2
                        JOIN usuarios u2 ON d2.usuarioID = u2.id
                        WHERE u2.unidadID = u.unidadID AND MONTH(d2.fecha) <= MONTH(d.fecha)
                    ) AS acumulado,
                    un.nombre AS unidad
                    FROM
                        devengos d
                    JOIN usuarios u ON d.usuarioID = u.id
                    JOIN unidades un ON u.unidadID = un.id
                    WHERE `contratoID` = $id AND un.nombre = '" . $_SESSION['unidad'] . "'
                    GROUP BY
                        MONTH(d.fecha),
                        u.unidadID,
                        d.descripcion
                    ORDER BY
                        MONTH(d.fecha) ASC,
                        u.unidadID ASC;";
                }

                $sentencia = $conexion->prepare($consultaSQL);
                $sentencia->execute();
            } catch (PDOException $error) {
                $error = $error->getMessage();
            }

            $resultado = $sentencia;
            // Crear un array vacío para guardar los datos
            $output = array();

            $meses = array(
                1 => array("Enero", 0),
                2 => array("Febrero", 0),
                3 => array("Marzo", 0),
                4 => array("Abril", 0),
                5 => array("Mayo", 0),
                6 => array("Junio", 0),
                7 => array("Julio", 0),
                8 => array("Agosto", 0),
                9 => array("Septiembre", 0),
                10 => array("Octubre", 0),
                11 => array("Noviembre", 0),
                12 => array("Diciembre", 0)
            );

            // Recorrer el objeto mysqli_result y obtener los datos de cada fila
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                // Asignar los valores de monto, mes, acumulado y unidad a variables
                $monto = $fila["monto"];
                $mes = $fila["mes"];
                $acumulado = $fila["acumulado"];
                $unidad = $fila["unidad"];
                $des = $fila["descripcion"];

                // Agregar un array con los datos de cada fila al subarreglo correspondiente al mes
                $output[$mes][] = array($monto, $acumulado, $unidad, $des);
            }

            $html = "
            <div class='row'>
                        <div class='col-6'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-black mt-1'>Contrato:</li>
                                <li class='h4 text-black mt-1'>Fecha de inicio:</li>
                                <li class='h4 text-black mt-1'>Fecha de fin:</li>
                                <li class='h4 text-black mt-1'>Monto maximo:</li>
                                <li class='h4 text-black mt-1'>Monto minimo:</li>
                            </ul>
                        </div>
                        <div class='col-5' style='text-align: right;'>
                            <ul class='list-unstyled'>
                                <li class='h4 text-muted mt-1'>" . $contrato['clave'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['fecha_in'] . "</li>
                                <li class='h4 text-muted mt-1 '>" . $contrato['fecha_fin'] . "</li>
                                <li class='h4 text-muted mt-1 '>$ " . number_format($contrato['mont_max'], 3) . "</li>
                                <li class='h4 text-muted mt-1 '>$ " . number_format($contrato['mont_min'], 3) . "</li>
                    
                            </ul>
                        </div>
                    </div>
            <div style='border-radius: 5%; box-shadow: 10px 10px 10px 20px gray'>
        <div style='padding: 5%'>
        <div style='overflow-x: auto; display: grid'>
        <table class='table table-striped-columns' id='tablaReporte'>";
            // Crear una variable auxiliar para guardar la unidad actual
            $unidad_actual = '';
            $total_contrato = 0.000;
            // Recorrer el arreglo $output con un bucle foreach
            foreach ($output as $mes => $datos) {
                // Obtener el nombre del mes usando el índice del arreglo $meses
                $nombre_mes = $meses[$mes][0];
                $total_mes = 0.000;
                // Mostrar el nombre del mes en una nueva fila con un salto de línea
                $html .= "
                    <tr>
                        <th class='h4 text-md-center' colspan='4'>$nombre_mes</th>
                    </tr>
                    <tr>
                        <th>Unidad</th>
                        <th>Descripcion</th>
                        <th>Monto</th>
                        <th>Acumulado</th>
                    </tr>";
                // Recorrer el subarreglo de cada mes con otro bucle foreach
                foreach ($datos as $dato) {
                    // Obtener los valores de monto, acumulado y unidad de cada fila
                    $monto = $dato[0];
                    $acumulado = $dato[1];
                    $unidad = $dato[2];
                    $desc = $dato[3];


                    // Generar el código HTML de cada celda de la tabla con los datos encontrados
                    $html .= "<tr><td>$unidad</td><td>$desc</td><td>$ " . number_format($monto, 3) . " </td><td>$ " . number_format($acumulado, 3) . "</td></tr>";
                    $total_mes += $monto;
                    $total_mes = number_format($total_mes, 3, '.', '');
                }
                $html .= "<tr><td class='h4' colspan='4'>Total del mes: <p class='text-muted float-end'>$ " . number_format($total_mes, 3) . "</p></td></tr>";

                $total_contrato += $total_mes;
                $total_contrato = number_format($total_contrato, 3, '.', '');
            }
            $html .= "
                        </tbody>
                    </table>
                    </div>
                    <h3>Total del contrato<p class='text-muted float-end'>$ " . number_format($total_contrato, 3) . "</p></h3>
                </div>
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
                    $fecha = $_POST['fecha'];
                    $clave = $_POST['clave'];
                    $desc = $_POST['descripcion'];
                    $monto = $_POST['monto'];
                    $usuario = $_POST['usuario'];

                    $consulta = "UPDATE devengos SET fecha='" . $fecha . "', descripcion= '" . $desc . "', monto='" . $monto . "', usuarioID='" . $usuario . "', contratoID='" . $clave . "', updated_at=NOW() WHERE id='" . $id . "'";
                    $sentecia = $conexion->prepare($consulta);
                    $sentecia->execute();
                    $response = $sentecia;
                } catch (Exception $error) {
                    $response = $error;
                }
                echo json_encode(array($response));
            } else {
                if (
                    isset($_POST['fecha'])
                    && isset($_POST['monto'])
                    && isset($_POST['usuario'])
                    && isset($_POST['descripcion'])
                    && isset($_POST['clave'])
                ) {
                    try {
                        $devengo = array(
                            'fecha' => $_POST['fecha'],
                            'descripcion' => $_POST['descripcion'],
                            'monto' => $_POST['monto'],
                            'usuarioID' => $_POST['usuario'],
                            'contratoID' => $_POST['clave']
                        );

                        $consultaCrear = "INSERT INTO devengos (fecha, descripcion, monto , usuarioID, contratoID)";
                        $consultaCrear .= "VALUES (:" . implode(", :", array_keys($devengo)) . ")";
                        $sentenciaCrear = $conexion->prepare($consultaCrear);
                        $sentenciaCrear->execute($devengo); // Aquí pasamos el arreglo como parámetro

                        echo json_encode(array($devengo));
                    } catch (PDOException $error) {
                        echo json_encode(array('error' => true, 'mensaje' => $error->getMessage()));
                        echo json_encode(array($devengo));
                    }

                }
            }
        }
    }
}
?>