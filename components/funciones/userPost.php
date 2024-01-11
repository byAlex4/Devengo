<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscNombre'])
    || isset($_POST['bscUnidad'])
    || isset($_POST['bscMatricula'])
    || isset($_POST['bscRol'])
) {
    $consultaSQL = "SELECT usuarios.id, usuarios.matricula, usuarios.nombre, 
        CONCAT(DAY(usuarios.created_at), '-', ELT(MONTH(usuarios.created_at), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(usuarios.created_at)) AS created_at, 
        CONCAT(DAY(usuarios.updated_at), '-', ELT(MONTH(usuarios.updated_at), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(usuarios.updated_at)) AS updated_at,
        unidades.nombre AS unidad, 
        roles.nombre AS rol, usuarios.contra FROM usuarios 
        JOIN unidades ON usuarios.unidadID = unidades.id 
        JOIN roles ON usuarios.rolID = roles.id ";
    $conditions = [];
    if (!empty($_POST['bscMatricula'])) {
        $conditions[] = "matricula LIKE '%" . $_POST['bscMatricula'] . "%'";
    }
    if (!empty($_POST['bscNombre'])) {
        $conditions[] = "usuarios.nombre LIKE '%" . $_POST['bscNombre'] . "%'";
    }
    if (!empty($_POST['bscRol'])) {
        $conditions[] = "roles.nombre LIKE '%" . $_POST['bscRol'] . "%'";
    }
    if (!empty($_POST['bscUnidad'])) {
        $conditions[] = "unidades.nombre LIKE '%" . $_POST['bscUnidad'] . "%'";
    }
    if (!empty($conditions)) {
        $consultaSQL .= " WHERE " . implode(' AND ', $conditions);
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
            $consulta = "SELECT * FROM usuarios WHERE id = $id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'matricula' => $show['matricula'],
                'nombre' => $show['nombre'],
                'unidadID' => $show['unidadID'],
                'rolID' => $show['rolID'],
                'contra' => $show['contra']
            );
            $json = $respuesta;
            // Devolver la respuesta como JSON
        } catch (Exception $error) {
            $json = $error;
        }

        echo json_encode($json);
    } else {
        if (
            isset($_POST['editar'])
            && isset($_POST['nombre'])
            && isset($_POST['matricula'])
            && isset($_POST['unidad'])
            && isset($_POST['contra'])
            && isset($_POST['rol'])
        ) {
            $error = false;
            try {
                // Obtener el valor de 'id' del cuerpo de la solicitud POST
                $id = $_POST['editar'];
                $nombre = $_POST['nombre'];
                $matricula = $_POST['matricula'];
                $unidad = $_POST['unidad'];
                $contra = $_POST['contra'];
                $rol = $_POST['rol'];

                $prueba = "SELECT contra FROM usuarios WHERE id = $id";
                $sentenciaPrueba = $conexion->prepare($prueba);
                $sentenciaPrueba->execute();

                $passcript = $sentenciaPrueba->fetch(PDO::FETCH_NUM);
                if ($contra != $passcript[0]) {
                    $contra = password_hash($contra, PASSWORD_BCRYPT);
                }

                $consulta = "UPDATE usuarios SET matricula='" . $matricula . "', nombre='" . $nombre . "', unidadID='" . $unidad . "', rolID='" . $rol . "', contra='" . $contra . "', updated_at = NOW() WHERE id='" . $id . "'";
                $sentecia = $conexion->prepare($consulta);
                $sentecia->execute();
                $response = $sentecia;
            } catch (Exception $error) {
                $response = $error;
            }
            echo json_encode(array($response));
        } else {
            if (
                isset($_POST['nombre'])
                && isset($_POST['matricula'])
                && isset($_POST['unidad'])
                && isset($_POST['contraseña'])
                && isset($_POST['rol'])
            ) {
                try {
                    $user = array(
                        'matricula' => $_POST['matricula'],
                        'nombre' => $_POST['nombre'],
                        'unidadID' => $_POST['unidad'],
                        'rolID' => $_POST['rol'],
                        'contra' => $_POST['contraseña']
                    );

                    $user['contra'] = password_hash($user['contra'], PASSWORD_BCRYPT); // Aquí cambiamos 'contraseña' por 'contra'
                    $consultaCrear = "INSERT INTO usuarios (matricula, nombre, unidadID, rolID, contra)";
                    $consultaCrear .= "VALUES (:" . implode(", :", array_keys($user)) . ")";

                    $sentenciaCrear = $conexion->prepare($consultaCrear);
                    $sentenciaCrear->execute($user); // Aquí pasamos el arreglo como parámetro

                    echo json_encode(array($user));
                } catch (PDOException $error) {
                    echo json_encode(array('error' => true, 'mensaje' => $error->getMessage()));
                    echo json_encode(array($consultaCrear));
                    echo json_encode(array($user));
                }

            }
        }
    }
}
?>