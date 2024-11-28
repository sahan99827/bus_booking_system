<?php
require_once('vendor\tecnickcom\tcpdf\tcpdf.php'); // Include the TCPDF library
include 'db_connection.php';

// Fetch data from the database
$sql = "SELECT schedules.id, schedules.bus_name, schedules.travel_date, schedules.departure_time, schedules.eta, schedules.availability, schedules.price, sartart_location.name AS start_location_name, end_location.name AS end_location_name
        FROM schedules
        JOIN sartart_location ON schedules.sartart_location_id = sartart_location.id
        JOIN end_location ON schedules.end_location_id = end_location.id";
$result = $conn->query($sql);

// Create new TCPDF object
$pdf = new TCPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Title
$pdf->Cell(0, 10, 'Bus Schedule Report', 0, 1, 'C');

// Column headers
// Column headers
$pdf->Ln(5); // Line break
$pdf->Cell(30, 5, 'Bus Name', 1);
$pdf->Cell(25, 5, 'Travel Date', 1);
$pdf->Cell(25, 5, 'Departure', 1);
$pdf->Cell(25, 5, 'ETA', 1);
$pdf->Cell(22, 5, 'Availability', 1);
$pdf->Cell(23, 5, 'Price', 1);
$pdf->Cell(25, 5, 'S_Location', 1);
$pdf->Cell(24, 5, 'E_Location', 1);
$pdf->Ln();

// Fetch and output data
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(30, 5, $row['bus_name'], 1);
    $pdf->Cell(25, 5, $row['travel_date'], 1);
    $pdf->Cell(25, 5, $row['departure_time'], 1);
    $pdf->Cell(25, 5, $row['eta'], 1);
    $pdf->Cell(22, 5, $row['availability'], 1);
    $pdf->Cell(23, 5, 'Rs. ' . $row['price'], 1);
    $pdf->Cell(25, 5, $row['start_location_name'], 1);
    $pdf->Cell(24, 5, $row['end_location_name'], 1);
    $pdf->Ln();
}


// Output the PDF (send it to browser as a download)
$pdf->Output('schedule_report.pdf', 'D');
?>
