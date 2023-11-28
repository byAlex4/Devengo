<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscNombre'])
    || isset($_POST['bscClave'])
) {
    $consultaSQL = "SELECT id, nombre, descripcion, 
    DATE_FORMAT( created_at, '%d-%M-%Y') AS created_at,
    DATE_FORMAT( updated_at, '%d-%M-%Y') AS updated_at 
    FROM unidades ";
    if (!empty($_POST['bscClave'])) {
        $consultaSQL .= "WHERE nombre LIKE '%" . $_POST['bscClave'] . "%'";
    }
    if (!empty($_POST['bscNombre'])) {
        $consultaSQL .= "WHERE descripcion LIKE '%" . $_POST['bscNombre'] . "%'";
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
            $consulta = "SELECT * FROM unidades WHERE id = $id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'nombre' => $show['nombre'],
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
                $nombre = $_POST['nombre'];
                $descripcion = $_POST['descripcion'];

                $consulta = "UPDATE unidades SET nombre='" . $nombre . "', descripcion='" . $descripcion . "', updated_at = NOW() WHERE id='" . $id . "'";
                $sentecia = $conexion->prepare($consulta);
                $sentecia->execute();
                $response = $sentecia;
            } catch (Exception $error) {
                $response = $error;
            }
            echo json_encode(array($response));
        }
    }
}
?>