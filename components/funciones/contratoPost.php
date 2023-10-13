<?php
// Conectamos con la base de datos
include '../../config.php';

if (isset($_POST['shw'])) {
    try {
        // Obtener el valor de 'id' del cuerpo de la solicitud POST
        $id = $_POST['shw'];
        $consulta = "SELECT * FROM contratos WHERE id = $id";

        $sentecia = $conexion->prepare($consulta);
        $sentecia->execute();

        $show = $sentecia->fetch(PDO::FETCH_ASSOC);
        //Crear una respuesta
        $respuesta = array(
            'id' => $show['id'],
            'clave' => $show['clave'],
            'descripcion' => $show['descripcion'],
            'mont_max' => $show['mont_max'],
            'mont_min' => $show['mont_min'],
            'fecha_in' => $show['fecha_in'],
            'fecha_fin' => $show['fecha_fin']
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
            $clave = $_POST['clave'];
            $descripcion = $_POST['descripcion'];
            $mont_max = $_POST['mont_max'];
            $mont_min = $_POST['mont_min'];
            $fecha_in = $_POST['fecha_in'];
            $fecha_fin = $_POST['fecha_fin'];

            $consulta = "UPDATE contratos SET clave='" . $clave . "', descripcion='" . $descripcion . "', mont_max=" . $mont_max . ", mont_min=" . $mont_min . ", fecha_in='" . $fecha_in . "', fecha_fin='" . $fecha_fin . "', updated_at = NOW() WHERE id='" . $id . "'";
            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();
            $response = $sentecia;
        } catch (Exception $error) {
            $response = $error;
        }
        echo json_encode(array($response));
    } else {
        if (
            isset($_POST['clave'])
            && isset($_POST['descripcion'])
            && isset($_POST['mont_max'])
            && isset($_POST['mont_min'])
            && isset($_POST['fecha_in'])
            && isset($_POST['fecha_fin'])
        ) {
            try {
                $contrato = array(
                    'clave' => $_POST['clave'],
                    'descripcion' => $_POST['descripcion'],
                    'mont_max' => $_POST['mont_max'],
                    'mont_min' => $_POST['mont_min'],
                    'fecha_in' => $_POST['fecha_in'],
                    'fecha_fin' => $_POST['fecha_fin']
                );

                $consultaCrear = "INSERT INTO contratos (clave, descripcion, mont_max, mont_min, fecha_in, fecha_fin)";
                $consultaCrear .= "VALUES (:" . implode(", :", array_keys($contrato)) . ")";

                $sentenciaCrear = $conexion->prepare($consultaCrear);
                $sentenciaCrear->execute($contrato); // Aquí pasamos el arreglo como parámetro

                echo json_encode(array($contrato));
            } catch (PDOException $error) {
                echo json_encode(array('error' => true, 'mensaje' => $error->getMessage()));
                echo json_encode(array($consultaCrear));
                echo json_encode(array($contrato));
            }

        }
    }
}
?>