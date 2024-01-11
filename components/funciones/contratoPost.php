<?php
// Conectamos con la base de datos
include '../../config.php';

if (
    isset($_POST['bscClave'])
    || isset($_POST['bscCuenta'])
    || isset($_POST['bscMonto'])
    || isset($_POST['bscFecha'])
    || isset($_POST['bscProv'])
) {
    $consultaSQL = "SELECT 
    contratos.id, 
    contratos.proveedor, 
    contratos.clave, 
    cuentas.cuenta AS cuenta,
    cuentas.descripcion AS cuentaDes,
    contratos.descripcion, 
    FORMAT(contratos.mont_max, 3, 'es-MX') AS mont_max, 
    FORMAT(contratos.mont_min, 3, 'es-MX') AS mont_min, 
    CONCAT(DAY(contratos.fecha_in), '-', ELT(MONTH(contratos.fecha_in), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(contratos.fecha_in)) AS fecha_in,
    CONCAT(DAY(contratos.fecha_fin), '-', ELT(MONTH(contratos.fecha_fin), 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'), '-', YEAR(contratos.fecha_fin)) AS fecha_fin
    FROM contratos
    JOIN cuentas ON contratos.cuentaID = cuentas.id ";
    $conditions = [];
    if (!empty($_POST['bscClave'])) {
        $conditions[] = "clave LIKE '%" . $_POST['bscClave'] . "%'";
    }
    if (!empty($_POST['bscCuenta'])) {
        $conditions[] = "cuentas.cuenta LIKE '" . $_POST['bscCuenta'] . "%'";
    }
    if (!empty($_POST['bscMonto'])) {
        $conditions[] = "contratos.mont_max >=" . $_POST['bscMonto'];
    }
    if (!empty($_POST['bscFecha'])) {
        $conditions[] = "DATE_FORMAT(contratos.fecha_in, '%Y-%m') = '" . $_POST['bscFecha'] . "'";
    }
    if (!empty($_POST['bscProveedor'])) {
        $conditions[] = "contratos.proveedor LIKE '%" . $_POST['bscProveedor'] . "%'";
    }
    if (!empty($conditions)) {
        $consultaSQL .= " WHERE " . implode(' AND ', $conditions);
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
            $consulta = "SELECT * FROM contratos WHERE id = $id";

            $sentecia = $conexion->prepare($consulta);
            $sentecia->execute();

            $show = $sentecia->fetch(PDO::FETCH_ASSOC);
            //Crear una respuesta
            $respuesta = array(
                'id' => $show['id'],
                'cuenta' => $show['cuentaID'],
                'clave' => $show['clave'],
                'descripcion' => $show['descripcion'],
                'mont_max' => $show['mont_max'],
                'mont_min' => $show['mont_min'],
                'fecha_in' => $show['fecha_in'],
                'fecha_fin' => $show['fecha_fin'],
                'proveedor' => $show['proveedor']
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
                $clave = $_POST['clave'];
                $descripcion = $_POST['descripcion'];
                $mont_max = $_POST['mont_max'];
                $mont_min = $_POST['mont_min'];
                $fecha_in = $_POST['fecha_in'];
                $fecha_fin = $_POST['fecha_fin'];
                $proveedor = $_POST['proveedor'];

                $consulta = "UPDATE contratos SET cuentaID = '" . $cuenta . "', proveedor='" . $proveedor . "', clave='" . $clave . "', descripcion='" . $descripcion . "', 
                mont_max=" . $mont_max . ", mont_min=" . $mont_min . ", fecha_in='" . $fecha_in . "', fecha_fin='" . $fecha_fin . "', 
                updated_at = NOW() WHERE id='" . $id . "'";
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
                && isset($_POST['clave'])
                && isset($_POST['descripcion'])
                && isset($_POST['mont_max'])
                && isset($_POST['mont_min'])
                && isset($_POST['fecha_in'])
                && isset($_POST['fecha_fin'])
                && isset($_POST['proveedor'])
            ) {
                try {
                    $contrato = array(
                        'cuenta' => $_POST['cuenta'],
                        'clave' => $_POST['clave'],
                        'descripcion' => $_POST['descripcion'],
                        'mont_max' => $_POST['mont_max'],
                        'mont_min' => $_POST['mont_min'],
                        'fecha_in' => $_POST['fecha_in'],
                        'fecha_fin' => $_POST['fecha_fin'],
                        'proveedor' => $_POST['proveedor']
                    );

                    $consultaCrear = "INSERT INTO contratos (cuentaID, clave, descripcion, mont_max, mont_min, fecha_in, fecha_fin, proveedor)";
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
}
?>