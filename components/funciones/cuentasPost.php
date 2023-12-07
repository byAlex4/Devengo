<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscNumero'])
    || isset($_POST['bscDesc'])
) {
    $consultaSQL = "SELECT id, cuenta, descripcion, 
    DATE_FORMAT( created_at, '%d-%M-%Y') AS created_at, 
    DATE_FORMAT( updated_at, '%d-%M-%Y') AS updated_at
    FROM cuentas ";
    if (!empty($_POST['bscNumero'])) {
        $consultaSQL .= "WHERE cuenta LIKE '%" . $_POST['bscNumero'] . "%'";
    }
    if (!empty($_POST['bscDesc'])) {
        $consultaSQL .= "WHERE descripcion LIKE '%" . $_POST['bscDesc'] . "%'";
    }
    $sentecia = $conexion->prepare($consultaSQL);
    $sentecia->execute();

    $resultados = $sentecia->fetchAll(PDO::FETCH_ASSOC);
    // Devolvemos el resultado en formato JSON
    echo json_encode($resultados);
} else {
    if (isset($_POST['shw'])) {
        try {
            // Obtener el valor de 'id' del cuerpo de la solicitud POST
            $id = $_POST['shw'];
            $consulta = "SELECT * FROM cuentas WHERE id = $id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'cuenta' => $show['cuenta'],
                'descripcion' => $show['descripcion'],
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
                $cuenta = $_POST['cuenta'];
                $descripcion = $_POST['desc'];

                $consulta = "UPDATE cuentas SET cuenta='" . $cuenta . "', descripcion='" . $descripcion . "', updated_at = NOW() WHERE id='" . $id . "'";
                $sentecia = $conexion->prepare($consulta);
                $sentecia->execute();
                $response = $sentecia;
            } catch (Exception $error) {
                $response = $error;
            }
            echo json_encode(array($response));
        } else {
            if (
                isset($_POST['cuenta'])
                && isset($_POST['desc'])
            ) {
                try {
                    $cuenta = array(
                        'cuenta' => $_POST['cuenta'],
                        'descripcion' => $_POST['desc']
                    );

                    $consultaCrear = "INSERT INTO cuentas (cuenta, descripcion)";
                    $consultaCrear .= "VALUES (:" . implode(", :", array_keys($cuenta)) . ")";

                    $sentenciaCrear = $conexion->prepare($consultaCrear);
                    $sentenciaCrear->execute($cuenta); // Aquí pasamos el arreglo como parámetro

                    echo json_encode(array($cuenta));
                } catch (PDOException $error) {
                    echo json_encode(array('error' => true, 'mensaje' => $error->getMessage()));
                    echo json_encode(array($consultaCrear));
                    echo json_encode(array($cuenta));
                }

            }
        }
    }
}
?>