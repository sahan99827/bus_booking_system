<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php'); // Include the TCPDF library
include 'db_connection.php';

// Fetch data from the database with a JOIN to get the role name
$sql = "SELECT buses.bus_id, buses.name, buses.type, buses.capacity, buses.route, agents.name AS agents_name
        FROM buses
        LEFT JOIN agents ON buses.agents_agent_id = agents.agent_id";
$result = $conn->query($sql);

// Create new TCPDF object
$pdf = new TCPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Bus Report', 0, 1, 'C');

// Column headers
$pdf->Ln(5); // Line break
$pdf->Cell(30, 8, 'Bus Name', 1);
$pdf->Cell(25, 8, 'Buss Type', 1);
$pdf->Cell(32, 8, 'Capacity', 1);
$pdf->Cell(20, 8, 'Route', 1);
$pdf->Cell(30, 8, 'Agents', 1);
$pdf->Ln();

// Fetch and output data
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 8, $row['name'], 1);
    $pdf->Cell(25, 8, $row['type'], 1);
    $pdf->Cell(32, 8, $row['capacity'], 1);
    $pdf->Cell(20, 8, $row['route'], 1);
    $pdf->Cell(30, 8, $row['agents_name'], 1);
    $pdf->Ln();
}

// Output the PDF (send it to the browser as a download)
$pdf->Output('Bus_report.pdf', 'D');
?>
