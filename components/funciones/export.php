<?php
// Incluir la librería
require '../../vendor/autoload.php';
include '../../config.php';

// Iniciar la sesión
session_start();

if ($_SESSION['rol'] == "Administrador") {
    $sql = "SELECT devengos.id,
            cuentas.cuenta as cuenta,
             contratos.proveedor AS proveedor,
            DATE_FORMAT(devengos.fecha, '%d-%M-%Y') AS fecha,
            devengos.descripcion,
            devengos.monto,
            contratos.clave AS contrato,
            contratos.mont_max, 
            (contratos.mont_max - (SELECT SUM(monto)FROM devengos
            WHERE contratoID = contratos.id)) AS saldoDis,
            unidades.nombre AS unidad,
            usuarios.nombre AS usuario,
            DATE_FORMAT(devengos.created_at, '%d-%M-%Y') AS created_at,
            DATE_FORMAT(devengos.updated_at,'%d-%M-%Y') AS updated_at
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id";
} else {
    $sql = "SELECT devengos.id,
            cuentas.cuenta as cuenta,
             contratos.proveedor AS proveedor,
            DATE_FORMAT(devengos.fecha, '%d-%M-%Y') AS fecha,
            devengos.descripcion,
            devengos.monto,
            contratos.clave AS contrato,
            contratos.mont_max, 
            (contratos.mont_max - (SELECT SUM(monto)FROM devengos
            WHERE contratoID = contratos.id)) AS saldoDis,
            unidades.nombre AS unidad,
            usuarios.nombre AS usuario,
            DATE_FORMAT(devengos.created_at, '%d-%M-%Y') AS created_at,
            DATE_FORMAT(devengos.updated_at,'%d-%M-%Y') AS updated_at
            FROM devengos
                JOIN contratos ON devengos.contratoID = contratos.id
                JOIN usuarios ON devengos.usuarioID = usuarios.id
                JOIN unidades ON usuarios.unidadID = unidades.id
                JOIN cuentas ON contratos.cuentaID = cuentas.id
            WHERE unidades.nombre = '" . $_SESSION['unidad'] . "';";
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
$sheet->setCellValue('C1', 'Proveedor');
$sheet->setCellValue('D1', 'Fecha de cargo');
$sheet->setCellValue('E1', 'Descripcion');
$sheet->setCellValue('F1', 'Monto');
$sheet->setCellValue('G1', 'Contrato');
$sheet->setCellValue('H1', 'Saldo de contrato');
$sheet->setCellValue('I1', 'Saldo disponible');
$sheet->setCellValue('J1', 'Unidad');
$sheet->setCellValue('K1', 'Usuario');
$sheet->setCellValue('L1', 'Created at');
$sheet->setCellValue('M1', 'Updated at');

$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(25);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(30);
$sheet->getColumnDimension('L')->setWidth(20);
$sheet->getColumnDimension('M')->setWidth(20);


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
$sheet->getStyle('A1:M1')->applyFromArray($styleArray);
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
$sheet->getStyle('A2:M60')->applyFromArray($styleArray1);



// Escribir los datos de la tabla
$sheet->fromArray($datos, null, 'A2');

// Crear el objeto Writer
$writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

// Enviar las cabeceras al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Devengos.xlsx"');

// Guardar el archivo en el flujo de salida
$writer->save('php://output');
?>