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
    $consultaSQL = "SELECT devengos.id, devengos.fecha, devengos.descripcion, devengos.monto, 
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
                $clave = $_POST['contr'];
                $consulta = "SELECT * FROM contratos
            WHERE clave = '$clave'";

                $sentecia = $conexion->prepare($consulta);
                $sentecia->execute();

                $show = $sentecia->fetch(PDO::FETCH_ASSOC);

                $json = $show;
                // Devolver la respuesta como JSON
            } catch (Exception $error) {
                $json = $error;
            }

            echo json_encode($json);
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
                            'monto' => $_POST['monto'],
                            'descripcion' => $_POST['descripcion'],
                            'usuarioID' => $_POST['usuario'],
                            'contratoID' => $_POST['clave']
                        );

                        $consultaCrear = "INSERT INTO devengos (fecha, monto, descripcion, usuarioID, contratoID)";
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