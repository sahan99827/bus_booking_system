<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php'); // Include the TCPDF library
include 'db_connection.php';

// Fetch data from the database with a JOIN to get the role name
$sql = "SELECT users.id, users.username, users.email, users.tel_number, users.address, role.name AS role_name
        FROM users
        LEFT JOIN role ON users.role_id = role.id";
$result = $conn->query($sql);

// Create new TCPDF object
$pdf = new TCPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Customer Report', 0, 1, 'C');

// Column headers
$pdf->Ln(5); // Line break
$pdf->Cell(30, 8, 'User Name', 1);
$pdf->Cell(62, 8, 'Email', 1);
$pdf->Cell(32, 8, 'Phone Number', 1);
$pdf->Cell(45, 8, 'Address', 1);
$pdf->Cell(30, 8, 'Role', 1);
$pdf->Ln();

// Fetch and output data
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 8, $row['username'], 1);
    $pdf->Cell(62, 8, $row['email'], 1);
    $pdf->Cell(32, 8, $row['tel_number'], 1);
    $pdf->Cell(45, 8, $row['address'], 1);
    $pdf->Cell(30, 8, $row['role_name'], 1);
    $pdf->Ln();
}

// Output the PDF (send it to the browser as a download)
$pdf->Output('Customer_report.pdf', 'D');
?>
