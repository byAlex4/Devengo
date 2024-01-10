<?php
// Incluir la librería
require '../../vendor/autoload.php';
include '../../config.php';

// Iniciar la sesión
session_start();

if ($_SESSION['rol'] == "Administrador") {
    $sql = "SELECT devengos.id,
            cuentas.cuenta as cuenta,
            contratos.clave AS contrato,
            contratos.proveedor AS proveedor,
            DATE_FORMAT(devengos.fecha, '%d-%m-%Y'),
            devengos.descripcion,
            devengos.monto,
            unidades.nombre AS unidad,
            usuarios.nombre AS usuario,
            DATE_FORMAT(devengos.created_at, '%d-%m-%Y'),
            DATE_FORMAT(devengos.updated_at, '%d-%m-%Y') 
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id ";

    if (!empty($_POST['expCuenta'])) {
        $sql .= "AND cuentas.cuenta LIKE '" . $_POST['expCuenta'] . "%'";
    }
    if (!empty($_POST['expMonto'])) {
        $sql .= "AND devengos.monto >=" . $_POST['expMonto'];
    }
    if (!empty($_POST['expContrato'])) {
        $sql .= "AND contratos.clave LIKE '%" . $_POST['expContrato'] . "%'";
    }
    if (!empty($_POST['expUnidad'])) {
        $sql .= "AND unidades.nombre LIKE '%" . $_POST['expUnidad'] . "%'";
    }
    if (!empty($_POST['expFecha'])) {
        $sql .= "AND DATE_FORMAT(devengos.fecha, '%Y-%m') = '" . $_POST['expFecha'] . "'";
    }
} else {
    $sql = "SELECT devengos.id,
            cuentas.cuenta as cuenta,
            contratos.clave AS contrato,
            contratos.proveedor AS proveedor,
            DATE_FORMAT(devengos.fecha, '%d-%m-%Y'),
            devengos.descripcion,
            devengos.monto,
            unidades.nombre AS unidad,
            usuarios.nombre AS usuario,
            DATE_FORMAT(devengos.created_at, '%d-%m-%Y'),
            DATE_FORMAT(devengos.updated_at, '%d-%m-%Y') 
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id 
            WHERE unidades.nombre = '" . $_SESSION['unidad'] . "' ";

    if (!empty($_POST['expCuenta'])) {
        $sql .= "AND cuentas.cuenta LIKE '" . $_POST['expCuenta'] . "%'";
    }
    if (!empty($_POST['expMonto'])) {
        $sql .= "AND devengos.monto >=" . $_POST['expMonto'];
    }
    if (!empty($_POST['expContrato'])) {
        $sql .= "AND contratos.clave LIKE '%" . $_POST['expContrato'] . "%'";
    }
    if (!empty($_POST['expUnidad'])) {
        $sql .= "AND unidades.nombre LIKE '%" . $_POST['expUnidad'] . "%'";
    }
    if (!empty($_POST['expFecha'])) {
        $sql .= "AND DATE_FORMAT(devengos.fecha, '%Y-%m') = '" . $_POST['expFecha'] . "'";
    }
}


$stmt = $conexion->prepare($sql);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Crear el objeto Spreadsheet
$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Escribir el encabezado de la tabla
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Cuenta');
$sheet->setCellValue('C1', 'Contrato');
$sheet->setCellValue('D1', 'Proveedor');
$sheet->setCellValue('E1', 'Fecha de cargo');
$sheet->setCellValue('F1', 'Descripcion');
$sheet->setCellValue('G1', 'Monto');
$sheet->setCellValue('H1', 'Unidad');
$sheet->setCellValue('I1', 'Usuario');
$sheet->setCellValue('J1', 'Creado en');
$sheet->setCellValue('K1', 'Actualizado en');

$sheet->getColumnDimension('D')->setWidth(30);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(25);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(20);
$sheet->getColumnDimension('K')->setWidth(20);


$styleArray = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'ffffff'], // Cambiar el color del texto a 288f5f
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Cambiar la alineación a centro
    ],
    'borders' => [
        'allBorders' => [ // Aplicar bordes a todos los lados
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => '288f5f',
        ],
    ],
];
$sheet->getStyle('A1:K1')->applyFromArray($styleArray);
$styleArray1 = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => '000000'], // Cambiar el color del texto a 288f5f
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Cambiar la alineación a centro
    ],
    'borders' => [
        'allBorders' => [ // Aplicar bordes a todos los lados
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'e4f7e8',
        ],
    ],
];
$sheet->getStyle('A2:K60')->applyFromArray($styleArray1);

// Escribir los datos de la tabla
$sheet->fromArray($datos, null, 'A2');

// Crear el objeto Writer
$writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

// Enviar las cabeceras al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// Obtener la fecha actual en el formato día-mes-año
$fecha = date("d-m-Y-H-i-s");
// Asignar el nombre del archivo con la fecha
$nombre = "reporte devengo $fecha.xlsx";
// Enviar la cabecera para descargar el archivo
header("Content-Disposition: attachment; filename=\"$nombre\"");


// Guardar el archivo en el flujo de salida
$writer->save('php://output');
?>