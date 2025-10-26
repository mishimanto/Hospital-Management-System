<?php
require_once('include/config.php');
require('fpdf/fpdf.php');

class PDF extends FPDF {
    // Header
    function Header() {
        // Logo
        if (file_exists('assets/images/logo_no_bg.png')) {
            $this->Image('assets/images/logo_no_bg.png', 10, 10, 30);
        }

        // Title
        $this->SetY(15);
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(13, 110, 253);
        $this->Cell(0,10,'MEDIZEN',0,1,'C');

        // Contact info from database
        global $con;
        $contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
        $contactData = mysqli_fetch_assoc($contactQuery);

        $this->SetFont('Arial','',11);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0,6, $contactData['PageDescription'], 0, 1, 'C');
        $this->Cell(0,6,'Phone: '.$contactData['MobileNumber'].' | Email: '.$contactData['Email'],0,1,'C');

        // Divider line
        $this->SetDrawColor(13, 110, 253);
        $this->SetLineWidth(0.5);
        $this->Line(10, 40, 200, 40);
        $this->Ln(15);
    }

    // Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Generate Invoice
if(isset($_GET['id'])) {
    $assignment_id = intval($_GET['id']);

    $assign = $con->query("
        SELECT ba.*, p.*, b.bed_number, b.price_per_day, w.ward_name, dr.requested_by, dr.processed_at

        FROM bed_assignments ba
        JOIN patient p ON ba.patient_id = p.id
        JOIN beds b ON ba.bed_id = b.bed_id
        JOIN wards w ON b.ward_id = w.ward_id
        JOIN discharge_requests dr ON ba.id = dr.assignment_id
        WHERE ba.id = $assignment_id
    ")->fetch_assoc();

    if($assign) {
        $days = ceil((strtotime($assign['discharge_date']) - strtotime($assign['admission_date'])) / (60 * 60 * 24));

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        // Invoice Title
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'INVOICE',0,1,'C');
        $pdf->Ln(5);

        // Patient Details
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Patient Name:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,$assign['PatientName'],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Contact No:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,$assign['PatientContno'],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Address:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,7,$assign['PatientAdd'],0,1);
        $pdf->Ln(5);

        // Booking Info
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Ward:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,$assign['ward_name'],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Bed No:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,$assign['bed_number'],0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Admission Date:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,date('M j, Y H:i', strtotime($assign['admission_date'])),0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(40,7,'Discharge Date:',0,0);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,7,date('M j, Y H:i', strtotime($assign['discharge_date'])),0,1);
        $pdf->Ln(10);

        // Invoice Table
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(90,8,'Bed',1,0,'C');
        $pdf->Cell(30,8,'Days',1,0,'C');
        $pdf->Cell(40,8,'Charge/Per-day',1,0,'C');
        $pdf->Cell(30,8,'Amount',1,1,'C');

        $pdf->SetFont('Arial','',12);
        $pdf->Cell(90,8, $assign['ward_name'].' - '.$assign['bed_number'],1,0);
        $pdf->Cell(30,8, $days,1,0,'C');
        $pdf->Cell(40,8,'BDT '.number_format($assign['price_per_day'],2),1,0,'R');
        $pdf->Cell(30,8,'BDT '.number_format($assign['total_charge'],2),1,1,'R');

        // Total
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(160,8,'TOTAL',1,0,'R');
        $pdf->Cell(30,8,'BDT '.number_format($assign['total_charge'],2),1,1,'R');
        $pdf->Ln(15);

        // Payment status
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8,'Payment Status: '.$assign['payment_status'],0,1);

        if($assign['payment_status'] == 'Paid') {
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0,8,'Thank you for your payment!',0,1);
        } else {
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0,8,'Please make payment at the hospital counter.',0,1);
        }

       /*// Add this after the booking info section
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,7,'Checked By:',0,0);
$pdf->SetFont('Arial','',12);

// Safely display requested_by with fallback
$checkedBy = 'System'; // Default value
if (!empty($assign['requested_by'])) {
    $checkedBy = $assign['requested_by'];
} elseif (!empty($assign['processed_by_name'])) {
    $checkedBy = $assign['processed_by_name'];
}
$pdf->Cell(0,7,$checkedBy,0,1);

// Only show check date if processed_at exists
if (!empty($assign['processed_at'])) {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,7,'Check Date:',0,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,7,date('M j, Y H:i', strtotime($assign['processed_at'])),0,1);
} else {
    // Optional: Show admission date if no discharge date exists
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(40,7,'Admission Date:',0,0);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,7,date('M j, Y H:i', strtotime($assign['admission_date'])),0,1);
}*/

// Footer bar
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Rect(0, 260, 210, 30, 'F');

    // Left side: generated time
    $pdf->SetY(265);
    $pdf->SetFont('Arial','I',7);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0,5,'Generated on: '.date('F j, Y, g:i a'),0,0,'L');

    // Right side: Checked by + doctor + date
    $pdf->SetY(262);
    $pdf->SetFont('Arial','',9);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(140); // Right position
    $pdf->Cell(60,5,'Checked by',0,1,'R');

    $pdf->SetFont('Arial','B',11);
    $pdf->SetX(140);
    $pdf->Cell(60,5, $assign['requested_by'] ,0,1,'R');

    $pdf->Ln(5);
        

        // Output PDF
        $pdf->Output('I', 'Invoice_'.$assignment_id.'.pdf');
        exit();
    }
}

// If no id found
header("Location: my_bed_bookings.php");
exit();
?>
