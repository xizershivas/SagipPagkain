<?php
require '../config/db_connection.php';
require '../../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$month = $_POST['month'] ?? '';
$category = $_POST['category'] ?? '';
$foodbank = $_POST['foodbank'] ?? '';

$where = [];
$params = [];
$types = '';

if ($month) {
    $where[] = 'MONTH(dm.dtmDate) = ? AND YEAR(dm.dtmDate) = ?';
    $monthParts = explode('-', $month);
    $params[] = $monthParts[1]; // month
    $params[] = $monthParts[0]; // year
    $types .= 'ss';
}
if ($category) {
    $where[] = 'i.intCategoryId = ?';
    $params[] = $category;
    $types .= 'i';
}
if ($foodbank) {
    $where[] = 'i.intFoodBankId = ?';
    $params[] = $foodbank;
    $types .= 'i';
}

$whereSql = $where ? 'WHERE ' . implode(' OR ', $where) : '';

$sql = "SELECT
    dm.dtmDate AS Date,
    fb.strFoodBank AS Location,
    it.strItem AS Item,
    c.strCategory AS FoodType,
    CONCAT(i.intQuantity, ' ', u.strUnit) AS Quantity,
    i.dtmExpirationDate AS ExpiryDate,
    b.strName AS Beneficiaries,
    r.strRemarks AS Issues,
    dm.strRemarks AS Notes
FROM tblinventory i
JOIN tbldonationmanagement dm ON i.intDonationId = dm.intDonationId
JOIN tbluser u1 ON dm.intUserId = u1.intUserId
JOIN tblfoodbank fb ON i.intFoodBankId = fb.intFoodBankId
JOIN tblitem it ON i.intItemId = it.intItemId
JOIN tblcategory c ON i.intCategoryId = c.intCategoryId
JOIN tblunit u ON i.intUnitId = u.intUnitId
LEFT JOIN tbltrackdonation td ON td.intUserId = dm.intUserId
LEFT JOIN tblremarks r ON td.intRemarks = r.intRemarks
LEFT JOIN tblbeneficiary b ON b.intUserId = dm.intUserId
$whereSql
ORDER BY dm.dtmDate DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Admin Report');

$headers = ['Date', 'Location', 'Item', 'Type of Food', 'Quantity (kg/units)', 'Expiry Date', 'Beneficiaries (Individuals/Families)', 'Issues Encountered', 'Notes'];

$sheet->mergeCells('A1:I1');
$sheet->setCellValue('A1', 'Sagip Pagkain Program - Admin Report');
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '31869b']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

// Date/Month
$range = '';
if (!empty($_POST['month'])) {
    $monthNum = date('n', strtotime($_POST['month'] . '-01'));
    $yearNum = date('Y', strtotime($_POST['month'] . '-01'));
    $range = 'For ' . date('F Y', mktime(0, 0, 0, $monthNum, 1, $yearNum));
} else {
    // This block runs if $_POST['month'] is empty
    $range = 'All Data to Current Date';
}

$sheet->mergeCells('A2:I2');
$sheet->setCellValue('A2', $range);
$sheet->getStyle('A2')->applyFromArray([
    'font' => ['italic' => true, 'color' => ['rgb' => '000000']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '31869b']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

// Table Header Row
$sheet->fromArray($headers, null, 'A4');
$sheet->getStyle('A4:I4')->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '92d050']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);

$rowIndex = 5;
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray(array_values($row), null, 'A' . $rowIndex);
    $rowIndex++;
}

// --- Auto-fit column widths ---
// Get the highest column letter (e.g., 'A', 'Z', 'AA', etc.)
$highestColumn = $sheet->getHighestColumn();

// Iterate through all columns from 'A' to the highest column
foreach (range('A', $highestColumn) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
// --- End Auto-fit ---

// Get the current datetime in a desired format (e.g., YYYY-MM-DD_HH-MM-SS)
$currentDateTime = date('Y-m-d_H-i-s');
// Construct the filename with the datetime
$filename = 'Sagip_Pagkain_Admin_Report_' . $currentDateTime . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="Sagip_Pagkain_Admin_Report.xlsx"');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
