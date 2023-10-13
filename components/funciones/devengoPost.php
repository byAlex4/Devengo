<?php
// Conectamos con la base de datos
include '../../config.php';

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
            $consulta = "SELECT *, SELECT id, fecha, monto, TIMESTAMPDIFF(MONTH, fecha, NOW()) AS meses_transcurridos, (monto*TIMESTAMPDIFF(MONTH, fecha, NOW())) AS total_gastos
            FROM devengos
            GROUP BY id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'clave' => $show['clave'],
                'desc' => $show['descripcion'],
                'max' => $show['mont_max'],
                'saldo' => $show['saldo'],
                'min' => $show['mont_min'],
                'ini' => $show['fecha_in'],
                'fin' => $show['fecha_fin'],
                'meses' => $show['meses']
            );

            $json = $respuesta;
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
?>