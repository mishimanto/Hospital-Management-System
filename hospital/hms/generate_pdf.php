<?php
// generate_pdf.php
require_once('fpdf/fpdf.php');

function generateAppointmentPDF($appointmentId, $con) {
    $query = mysqli_query($con, "
        SELECT 
            a.id, a.appointmentDate, a.appointmentTime, a.serialNumber, a.consultancyFees, 
            a.doctorSpecialization, a.appointment_number,
            d.doctorName, d.address AS docAddress, d.contactno AS docPhone, 
            u.fullName AS patientName, u.address AS patientAddress, u.email as patientEmail
        FROM appointment a
        JOIN doctors d ON d.id = a.doctorId
        JOIN users u ON u.id = a.userId
        WHERE a.id = '$appointmentId'
    ");

    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }

    $appointment = mysqli_fetch_assoc($query);
    if(!$appointment) {
        return false;
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Set document properties
    $pdf->SetTitle('Appointment Slip - MEDIZEN');
    $pdf->SetAuthor('MEDIZEN');
    $pdf->SetCreator('MEDIZEN');

    // Add logo (replace 'logo.png' with your actual logo file path)
    if (file_exists('assets/images/logo_no_bg.png')) {
        $pdf->Image('assets/images/logo_no_bg.png', 10, 10, 30);
    }
    
    // Header
    $pdf->SetY(15);
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(13, 110, 253); // Medical blue color
    $pdf->Cell(0,10,'MEDIZEN',0,1,'C');

    // contactus page info
    $contactQuery = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
    $contactData = mysqli_fetch_assoc($contactQuery);

        
    $pdf->SetFont('Arial','',11);
    $pdf->SetTextColor(0, 0, 0); // Black color
    $pdf->Cell(0,6,$contactData['PageDescription'],0,1,'C');
    $pdf->Cell(0,6,'Phone: ' .$contactData['MobileNumber']. ' | Email: '.$contactData['Email'],0,1,'C');
    
    // Divider line
    $pdf->SetDrawColor(13, 110, 253);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, 40, 200, 40);
    $pdf->Ln(15);

    // Appointment slip title
    $pdf->SetFont('Arial','B',16);
    $pdf->SetTextColor(13, 110, 253);
    $pdf->Cell(0,10,'APPOINTMENT SLIP',0,1,'C');
    $pdf->Ln(8);

    // Appointment details box
    $pdf->SetFillColor(233, 242, 255); // Light blue background
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->SetFont('Arial','B',12);
    $pdf->SetTextColor(0, 0, 0); // Black color
    $pdf->Cell(0,10,'APPOINTMENT DETAILS',0,1,'L');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Appointment Number:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['appointment_number'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Patient Name:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['patientName'],0,1);    

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Appointment Date:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['appointmentDate'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Appointment Time:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['appointmentTime'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Serial Number:',0,0);
    $pdf->SetFont('Arial','B',16);
    $pdf->SetTextColor(220, 53, 69); // Red color for serial number
    $pdf->Cell(0,8,$appointment['serialNumber'],0,1);
    $pdf->SetTextColor(0, 0, 0); // Reset to black

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Consultancy Fee:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,number_format($appointment['consultancyFees'], 2).' BDT',0,1);
    $pdf->Ln(12);

    // Doctor information box
    $pdf->SetFillColor(233, 242, 255); // Light blue background
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'DOCTOR INFORMATION',0,1,'L');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Doctor Name:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,'Dr. '.$appointment['doctorName'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Specialization:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['doctorSpecialization'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'From:',0,0);
    $pdf->SetFont('Arial','',11);
    $pdf->MultiCell(0,8,$appointment['docAddress'],0,1);

    $pdf->SetFont('Arial','',11);
    $pdf->Cell(50,8,'Contact:',0,0);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(0,8,$appointment['docPhone'],0,1);
    $pdf->Ln(12);

    // Important notes box
    $pdf->SetFillColor(255, 243, 205); // Light yellow background
    $pdf->Rect(10, $pdf->GetY(), 190, 10, 'F');
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'IMPORTANT NOTES',0,1,'L');
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',12);
    /*$pdf->Cell(0,10,'IMPORTANT NOTES:',0,1);*/
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(0,7,'1. Please arrive at least 15 minutes before your scheduled appointment time.',0,1);
    $pdf->MultiCell(0,7,'2. Bring this slip and any relevant medical reports with you.',0,1);
    /*$pdf->MultiCell(0,7,'3. Cancellations must be made at least 24 hours in advance.',0,1);*/
    $pdf->MultiCell(0,7,'3. Late arrivals may result in rescheduling.',0,1);
    $pdf->Ln(6);

    // Footer
    $pdf->SetY(270);
    $pdf->SetFont('Arial','I',8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0,5,'Generated on: '.date('F j, Y, g:i a'),0,0,'L');
    $pdf->Cell(0,5,''.$pdf->PageNo());
    $pdf->Ln(5);
    

    // Save the PDF
    $filename = 'Appointment_'.$appointment['id'].'_'.date('Ymd_His').'.pdf';
    if (!file_exists('appointment_slips')) {
        mkdir('appointment_slips', 0777, true);
    }
    $filepath = 'appointment_slips/'.$filename;
    $pdf->Output('F', $filepath);

    return $filepath;
}